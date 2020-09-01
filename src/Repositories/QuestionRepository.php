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

    /**
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data): Model
    {
        return $this->save($data, new Question());
    }

    /**
     * @param array                               $data
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(array $data, Model $model): Model
    {
        return $this->save($data, $model);
    }

    /**
     * @param array                 $data
     * @param \Exam\Models\Question $question
     *
     * @return \Exam\Models\Question
     */
    protected function save(array $data, Question $question): Question
    {
        $question->fill($data);

        if ($options = $data['options'] ?? []) {
            $question->options = $options['option'];
            $answerIndex = $options['isCorrect'];
            $answers = [];
            foreach ($answerIndex as $index => $value) {
                $answers[] = $question->options[$index];
            }
            $question->answer = $answers;
        } elseif ($answers = $data['answers'] ?? []) {
            $ansArr = [];
            foreach ($answers as $key => $answer) {
                $ansArr[$answer['key']] = $answer['value'];
            }
            $question->answer = $ansArr;
            $question->options = $data['option'] ?? [];
        }
        $question->save();

        return $question;
    }
}
