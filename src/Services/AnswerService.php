<?php
/**
 * Created by PhpStorm.
 * User: Tuhin
 * Date: 8/12/2018
 * Time: 2:32 PM
 */

namespace Exam\Services;


use Exam\Models\Answer;
use Exam\Models\ExamUser;
use Exam\Models\Question;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Exam\Models\Question as QuestionModel;

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
     * @param $answer
     * @param Question $question
     * @param $examUser
     */
    public function __construct($answer, ExamUser $examUser)
    {
        $this->answer = $answer;
        $this->examUser = $examUser;
    }

    public function check()
    {
        if (is_array($this->answer)) {
            foreach ($this->answer as $key => $value) {
                $model = QuestionModel::find($key);
                $correctAns = $model->review_type === Question::REVIEW_TYPE_MANUAL ? Answer::STATUS_PENDING : $this->checkSingle($value, $model);
                $this->questionAnswers[] = $this->save($key, $value, $correctAns);
            }
        }
    }

    /**
     * @param $qid
     * @param $answer
     * @param $correctAns
     * @return mixed
     */
    protected function save($qid, $answer, $correctAns)
    {
        $questionAnswer = Answer::firstOrNew([
            'exam_user_id' => $this->examUser->id,
            'question_id' => $qid
        ]);
        $questionAnswer->answer = $answer;
        $questionAnswer->question_id = $qid;
        $questionAnswer->status = $correctAns;
        $questionAnswer->save();
        return $questionAnswer;
    }

    /**
     * @param $userAnswer
     * @param Question $question
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
            if ($question->type == QuestionModel::TYPE_WRITE_SENTENCE) {
                if (stripos($userAnswer, $question->answer) !== false) {
                    $correctAns = true;
                }
            } elseif (strcasecmp($userAnswer, $question->answer) == 0) {
                $correctAns = true;
            }
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