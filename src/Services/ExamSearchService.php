<?php

namespace Exam\Services;

use App\Models\User;
use Blog\Enums\ActivityType;
use Exam\Enums\ExamStatus;
use Exam\Enums\ExamUserStatus;
use Exam\Enums\ExamVisibility;
use Exam\Models\Exam;
use Exam\Models\Invitation;
use Illuminate\Database\Eloquent\Builder;

class ExamSearchService
{
    /**
     * @var \Exam\Models\Exam
     */
    protected $model;

    /**
     * ExamSearchService constructor.
     *
     * @param \Exam\Models\Exam $exam
     */
    public function __construct(Exam $exam)
    {
        $this->model = $exam;
    }

    /**
     * Exam pagination for user.
     *
     * @param \App\Models\User $user
     * @param string|null      $search
     * @param string|null      $status
     * @param int              $perPage
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * @throws \ReflectionException
     */
    public function paginateForUser(User $user, ?string $search = null, ?string $status = null, ?string $activity = null, int $perPage = 6)
    {
        $builder = $this->model->newQuery();

        if (!$user->isAdmin()) {
            $builder = $this->forUser($builder, $user->id);
        }
        if (!empty($status) && false !== array_search($status, ExamUserStatus::toArray())) {
            $builder = $this->filterByStatus($builder, $status, $user->id);
        } elseif (!empty($activity) && false !== array_search($activity, ActivityType::getValues())) {
            $builder = $this->filterByActivity($builder, $activity, $user->id);
        } else {
            $builder = !empty($search) ? $this->search($builder, $search) : $this->preferences($builder, $user->id);
        }

        return $builder->paginate($perPage);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|null                              $user_id
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function forUser(Builder $query, int $user_id): Builder
    {
        return $query->where(function ($q) use ($user_id) {
            $q->orWhere('user_id', $user_id)
                ->orWhere(function ($sq) {
                    $sq->where('visibility', ExamVisibility::PUBLIC)
                        ->where('status', ExamStatus::ACTIVE);
                })
                ->orWhereHas('invitations', function ($i) use ($user_id) {
                    $i->where('user_id', $user_id)->where('status', Invitation::STATUS_ACCEPTED);
                });
        });
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param                                       $search
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function search(Builder $builder, $search): Builder
    {
        return $builder->selectRaw('exams.*,
             match(exams.title,exams.description) against ("' . $search . '" IN NATURAL LANGUAGE MODE) as bscore')->distinct()
            ->leftJoin('blog_categories', 'exams.category_id', '=', 'blog_categories.id')
            ->leftJoin('exam_tag', 'exams.id', '=', 'exam_tag.exam_id')
            ->leftJoin('blog_tags', 'exam_tag.tag_id', '=', 'blog_tags.id')
            ->where(function ($q) use ($search) {
                $q->orWhereRaw('match(exams.title,exams.description) against (? IN NATURAL LANGUAGE MODE)', $search)
                    ->orWhereRaw('match(blog_categories.title) against (? IN NATURAL LANGUAGE MODE)', $search)
                    ->orWhereRaw('match(blog_tags.name) against (? IN NATURAL LANGUAGE MODE)', $search);
            })
            ->orderByRaw('bscore desc');
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function preferences(Builder $builder, int $userId): Builder
    {
        return $builder->selectRaw("*, 
        (select count(*) from preferences where category_id=exams.category_id and user_id='{$userId}') as cscore,
        (select count(*) from preferences where tag_id IN (select tag_id from exam_tag where exam_id=exams.id) and user_id={$userId}) as tscore
        ")->whereDoesntHave('examUser', function ($q) use ($userId) {
            $q->where('user_id', $userId)
                ->where('status', ExamUserStatus::COMPLETED);
        })->orderByRaw('cscore*2+tscore desc');
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string                                $status
     * @param                                       $userId
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function filterByStatus(Builder $builder, string $status, $userId)
    {
        return $builder->whereHas('examUser', function ($q) use ($status, $userId) {
            $statuses = ExamUserStatus::toArray();
            $q->where('status', $statuses[array_search($status, ExamUserStatus::toArray())])
                ->where('user_id', $userId);
        });
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string                                $activity
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function filterByActivity(Builder $builder, string $activity, int $userId)
    {
        return $builder->whereHas('activities', function ($q) use ($activity, $userId) {
            $q->where('type', $activity)
                ->where('user_id', $userId);
        });
    }
}
