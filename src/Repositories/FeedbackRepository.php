<?php


namespace Exam\Repositories;


use Exam\Models\Exam;
use Exam\Models\Feedback;

class FeedbackRepository extends Repository
{
    /**
     * FeedbackRepository constructor.
     *
     * @param \Exam\Models\Feedback $feedback
     */
    public function __construct(Feedback $feedback)
    {
        $this->model = $feedback;
    }

    /**
     * @param int $examId
     * @param int $userId
     *
     * @return \Exam\Models\Feedback|null
     */
    public function userExamFeedback(int $examId, int $userId)
    {
        return $this->model->newQuery()
            ->where('feedbackable_type', Exam::class)
            ->where('feedbackable_id', $examId)
            ->where('user_id', $userId)
            ->first();
    }
}
