<?php

namespace Exam\Services;

use Exam\Enums\QuestionAnswerType;
use Exam\Enums\QuestionReview;
use Exam\Models\Answer;
use Exam\Models\ExamUser;
use Exam\Models\Question;
use Exam\Models\Question as QuestionModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class AnswerService
{
    /**
     * @var Request
     */
    protected $answer;

    /**
     * @var ExamUser
     */
    protected $examUser;

    /**
     * @var array
     */
    protected $questionAnswers = [];

    /**
     * AnswerService constructor.
     *
     * @param          $answer
     * @param Question $question
     * @param          $examUser
     */
    public function __construct($answer, ExamUser $examUser)
    {
        $this->answer = $answer;
        $this->examUser = $examUser;
    }

    /**
     *
     */
    public function check()
    {
        if (is_array($this->answer)) {
            foreach ($this->answer as $key => $value) {
                $model = QuestionModel::query()->find($key);
                if (QuestionAnswerType::FILL_IN_THE_BLANK == $model->answer_type) {
                    $correctAns = $this->checkFillInTheBlankAnswers($value, $model);
                } else {
                    $correctAns = QuestionReview::MANUAL === $model->review_type ? Answer::STATUS_PENDING : $this->checkSingle($value, $model);
                }
                $this->questionAnswers[] = $this->save($key, $value, $correctAns);
            }
        }
    }

    /**
     * @param $qid
     * @param $answer
     * @param $correctAns
     *
     * @return mixed
     */
    protected function save($qid, $answer, $correctAns)
    {
        $questionAnswer = Answer::firstOrNew([
            'exam_user_id' => $this->examUser->id,
            'question_id' => $qid,
        ]);
        $questionAnswer->answer = $answer;
        $questionAnswer->question_id = $qid;
        $questionAnswer->status = $correctAns;
        $questionAnswer->save();

        return $questionAnswer;
    }

    /**
     * @param          $userAnswer
     * @param Question $question
     *
     * @return bool
     */
    private function checkSingle($userAnswer, $question)
    {
        $correctAns = false;
        if (is_array($userAnswer)) {
            $correctAnswers = array_intersect($question->getAnswers(), $userAnswer);
            if (count($correctAnswers) == count($question->getAnswers())) {
                $correctAns = true;
            }
        } else {
            $correctAnswer = $question->getAnswers()[0] ?? false;
            if (0 == strcasecmp($userAnswer, $correctAnswer)) {
                $correctAns = true;
            }
        }

        return $correctAns;
    }

    /**
     * @param array                 $userAnswer
     * @param \Exam\Models\Question $question
     *
     * @return bool
     */
    private function checkFillInTheBlankAnswers(array $userAnswer, Question $question)
    {
        $correctAns = false;
        $correctAnswers = array_intersect($question->getAnswers(), $userAnswer);
        if (count($correctAnswers) == count($question->getAnswers())) {
            $correctAns = true;
        }

        return $correctAns;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return new Collection($this->questionAnswers);
    }
}
