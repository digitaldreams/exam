<?php

namespace Exam\Repositories;

use App\Models\User;
use Blog\Models\Activity;
use Blog\Models\Category;
use Blog\Models\Tag;
use Blog\Repositories\TagRepository;
use Blog\Services\UniqueSlugGeneratorService;
use Exam\Enums\ExamStatus;
use Exam\Enums\ExamUserStatus;
use Exam\Enums\ExamVisibility;
use Exam\Models\Exam;
use Exam\Models\ExamUser;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ExamRepository extends Repository
{
    /**
     * @var \Blog\Repositories\TagRepository
     */
    protected $tagRepository;

    /**
     * ExamRepository constructor.
     *
     * @param \Exam\Models\Exam                $exam
     * @param \Blog\Repositories\TagRepository $tagRepository
     */
    public function __construct(Exam $exam, TagRepository $tagRepository)
    {
        $this->model = $exam;
        $this->tagRepository = $tagRepository;
    }

    /**
     * Exam pagination for user.
     *
     * @param \App\Models\User $user
     * @param int              $perPage
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateForUser(User $user, ?string $search = null, int $perPage = 6)
    {
        $builder = $this->model->newQuery();

        if (!$user->isAdmin()) {
            $builder = $builder->forUser($user->id)->where('status', ExamStatus::ACTIVE);
        }
        if ($search) {
            $builder = $builder->search($search);
        }

        return $builder->paginate($perPage);
    }

    /**
     * User must completed these exam before attempt.
     *
     * @param \Exam\Models\Exam $exam
     *
     * @return array|mixed
     */
    public function getMustCompletedIds(Exam $exam): array
    {
        return !empty($exam->must_completed) ? $exam->must_completed : [];
    }

    /**
     * These exam must be completed before this exam.
     *
     * @param \Exam\Models\Exam $exam
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function mustCompletedExams(Exam $exam): Collection
    {
        return $this->model->newQuery()->whereIn('id', $this->getMustCompletedIds($exam))->get();
    }

    /**
     * Does user complete all the exams they have to complete before starting this exam.
     *
     * @param \Exam\Models\Exam $exam
     * @param int               $user_id
     *
     * @return bool
     */
    public function isRequiredExamCompleted(Exam $exam, int $user_id): bool
    {
        $mustCompleted = $this->getMustCompletedIds($exam);

        $count = ExamUser::query()->whereIn('exam_id', $mustCompleted)
            ->where('user_id', $user_id)
            ->where('status', ExamUserStatus::COMPLETED)
            ->count();

        return count($mustCompleted) == $count;
    }

    /**
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data): Model
    {
        $this->model->fill($data);
        $model = (new UniqueSlugGeneratorService())->createSlug($this->model, $this->model->title);
        $model->user_id = auth()->id();
        $model->save();

        if ($tags = $data['tags'] ?? []) {
            $model->tags()->sync($this->tagRepository->saveTags($tags));
        }

        return $this->model;
    }

    /**
     * @param array                               $data
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(array $data, Model $model): Model
    {
        $model->fill($data)->save();
        if ($tags = $data['tags'] ?? []) {
            $model->tags()->sync($this->tagRepository->saveTags($tags));
        }

        return $model;
    }

    /**
     * @param \App\Models\User $user
     * @param                  $type
     *
     * @return int
     */
    public function countActivity(User $user, $type)
    {
        return Activity::query()
            ->where('user_id', $user->id)
            ->where('activityable_type', Exam::class)
            ->where('type', $type)
            ->count();
    }

    /**
     * @return array|\Illuminate\Cache\CacheManager|mixed
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Exception
     */
    public function keywords()
    {
        $key = 'exams_category_tags_keywords';
        if (cache()->has($key)) {
            return cache($key);
        }

        $categories = Category::query()
            ->selectRaw('title as name,(select count(*) from exams where exams.category_id=blog_categories.id ) as total')
            ->havingRaw('total > 0 ')
            ->orderByRaw('total desc')->get()->toArray();

        $tags = Tag::query()
            ->selectRaw('name, (select count(*) from exam_tag where exam_tag.tag_id=blog_tags.id) as total')
            ->havingRaw('total > 0 ')
            ->orderByRaw('total desc')
            ->get()->toArray();

        $data = array_merge($categories, $tags);

        cache()->put($key, $data, now()->addDay());

        return $data;
    }

    /**
     * @param string $start
     * @param string $end
     *
     * @return \Illuminate\Database\Eloquent\Collection
     * @throws \Exception
     */
    public function createdBetween(string $start = '-24 hours', string $end = 'now'): Collection
    {
        return $this->model->newQuery()
            ->where('status', ExamStatus::ACTIVE)
            ->where('visibility', ExamVisibility::PUBLIC)
            ->whereBetween('created_at', [
                (new \DateTime($start))->format('Y-m-d H:i:s'),
                (new \DateTime($end))->format('Y-m-d H:i:s'),
            ])->get();
    }


    /**
     * @param \Exam\Models\Exam $exam
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findPreferredUsersForExam(Exam $exam): Collection
    {
        $category = $exam->category_id;
        $tags = $exam->tags()->allRelatedIds();

        return User::query()->where(function ($q) use ($category, $tags) {
            $q->orWhereHas('preferredCategories', function ($sq) use ($category) {
                $sq->where('category_id', $category);
            })->orWhereHas('preferredTags', function ($tg) use ($tags) {
                $tg->whereIn('tag_id', $tags);
            });
        })->get();
    }
}
