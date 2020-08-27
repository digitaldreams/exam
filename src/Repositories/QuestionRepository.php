<?php

namespace Exam\Repositories;

use Exam\Models\Question;
use Illuminate\Database\Eloquent\Model;

class QuestionRepository extends Repository
{
    /**
     * ExamRepository constructor.
     *
     * @param \Exam\Models\Question $question
     */
    public function __construct(Question $question)
    {
        $this->model = $question;
    }

    public function create(array $data): Model
    {
        return parent::create($data);
    }
}
