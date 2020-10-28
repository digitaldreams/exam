<?php


namespace Exam\Repositories;


use Exam\Models\Answer;

class AnswerRepository extends Repository
{
    /**
     * AnswerRepository constructor.
     *
     * @param \Exam\Models\Answer $answer
     */
    public function __construct(Answer $answer)
    {
        $this->model = $answer;
    }

    /**
     * @param array $ids
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getByIds(array $ids)
    {
        return $this->model->newQuery()->whereIn('id', $ids)->get();
    }

}
