<?php

namespace Exam\Repositories;

use App\Models\User;
use Exam\Enums\ExamStatus;
use Exam\Models\Exam;
use Exam\Models\ExamUser;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ExamRepository extends Repository
{
    /**
     * ExamRepository constructor.
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
    public function paginateForUser(User $user, int $perPage = 6)
    {
        $builder = $this->model->newQuery();

        if (!$user->isAdmin()) {
            $builder = $builder->forUser($user->id)->where('status', ExamStatus::ACTIVE);
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
     * @param int|null          $user_id
     *
     * @return bool
     */
    public function doesCompleteExams(Exam $exam, ?int $user_id = null): bool
    {
        $user_id = empty($user_id) && auth()->check() ? auth()->id() : $user_id;

        $mustCompleted = $this->getMustCompletedIds($exam);

        $count = ExamUser::query()->whereIn('exam_id', $mustCompleted)
            ->where('user_id', $user_id)
            ->where('status', ExamUser::STATUS_COMPLETED)
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
        $exam = $this->model->fill($data)->save();
        $exam->tags()->sync($data['tags'] ?? []);
    }
}
