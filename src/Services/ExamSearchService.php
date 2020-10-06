<?php

namespace Exam\Services;

use App\Models\User;
use Exam\Enums\ExamStatus;
use Exam\Models\Exam;

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
            $builder = $builder->selectRaw('exams.*,
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

        return $builder->paginate($perPage);
    }
}
