<?php

namespace Exam\Services;

use Exam\Enums\AnswerStatus;
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
     * Check the User answer whether its right or wrong.
     */
    public function check()
    {
        if (is_array($this->answer)) {
            foreach ($this->answer as $key => $value) {
                $model = QuestionModel::query()->find($key);
                if (QuestionAnswerType::FILL_IN_THE_BLANK == $model->answer_type) {
                    $this->checkFillInTheBlankAnswers($value, $model);
                } else {
                    if (QuestionReview::MANUAL === $model->review_type) {
                        $this->save($model, [$value], AnswerStatus::PENDING, 0);
                    } else {
                        $this->checkSingle($value, $model);
                    }
                }
            }
        }
    }

    /**
     * @param \Exam\Models\Question $question
     * @param array                 $answer
     * @param                       $correctAns
     *
     * @param int                   $obtainMark
     *
     * @return mixed
     */
    protected function save(Question $question, $answer, $correctAns, int $obtainMark = 0)
    {
        $questionAnswer = Answer::query()->firstOrNew([
            'exam_user_id' => $this->examUser->id,
            'question_id' => $question->id,
        ]);
        $questionAnswer->answer = is_array($answer) ? $answer : [$answer];
        $questionAnswer->question_id = $question->id;
        $questionAnswer->status = $correctAns;
        $questionAnswer->obtain_mark = $obtainMark;

        $questionAnswer->save();

        return $questionAnswer;
    }

    /**
     * @param          $userAnswer
     * @param Question $question
     *
     * @return bool
     */
    private function checkSingle($userAnswer, Question $question)
    {
        $correctAns = false;
        if (is_array($userAnswer)) {
            $correctAnswers = array_intersect($question->getAnswers(), $userAnswer);
            if (count($correctAnswers) == count($question->getAnswers())) {
                $correctAns = true;
                $this->questionAnswers[] = $this->save($question, $userAnswer, $correctAns, $question->total_mark);
            } else {
                $obtainMark = ($question->total_mark / count($question->getAnswers())) * count($correctAnswers);
                if ($obtainMark > 0) {
                    $this->questionAnswers[] = $this->save($question, $userAnswer, AnswerStatus::PARTIALLY_CORRECT, $obtainMark);
                } else {
                    $this->questionAnswers[] = $this->save($question, $userAnswer, AnswerStatus::WRONG, 0);
                }
            }

        } else {
            $correctAnswer = $question->getAnswers()[0] ?? false;
            if (0 == strcasecmp($userAnswer, $correctAnswer)) {
                $correctAns = true;
                $this->questionAnswers[] = $this->save($question, $userAnswer, $correctAns, $question->total_mark);
            } else {
                $this->questionAnswers[] = $this->save($question, $userAnswer, AnswerStatus::WRONG, 0);
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

        $correctAnswers = array_intersect_assoc($question->getAnswers(), $userAnswer);
        if (count($correctAnswers) == count($question->getAnswers())) {
            $correctAns = true;
            $obtainMark = $question->total_mark;
            $this->questionAnswers[] = $this->save($question, $userAnswer, $correctAns, $obtainMark);
        } else {
            $obtainMark = ($question->total_mark / count($question->getAnswers())) * count($correctAnswers);
            if ($obtainMark > 0) {
                $this->questionAnswers[] = $this->save($question, $userAnswer, AnswerStatus::PARTIALLY_CORRECT, $obtainMark);
            } else {
                $this->questionAnswers[] = $this->save($question, $userAnswer, AnswerStatus::WRONG, 0);
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
