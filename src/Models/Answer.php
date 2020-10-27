<?php

namespace Exam\Models;

use Exam\Enums\AnswerStatus;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int            $exam_user_id exam user id
 * @property int            $question_id  question id
 * @property string         $answer       answer
 * @property int            $status       status
 * @property \Carbon\Carbon $created_at   created at
 * @property \Carbon\Carbon $updated_at   updated at
 * @property Question       $question     belongsTo
 * @property ExamUser       $examUser     belongsTo
 */
class Answer extends Model
{

    /**
     * Database table name.
     */
    protected $table = 'exam_user_answer';

    /**
     * Mass assignable columns.
     */
    protected $fillable = [
        'exam_user_id',
        'question_id',
        'answer',
        'status',
        'obtain_mark',
        'feedback',
    ];

    protected $casts = [
        'answer' => 'array',
    ];

    /**
     * question.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    /**
     * examUser.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function examUser()
    {
        return $this->belongsTo(ExamUser::class, 'exam_user_id');
    }

    /**
     * @return bool
     */
    public function isCorrect()
    {
        return AnswerStatus::CORRECT == $this->status;
    }

    /**
     * @return mixed|null
     */
    public function getAnswer()
    {
        return is_array($this->answer) && !empty($this->answer) ? $this->answer[0] : $this->answer;
    }

    /**
     * @return array|string
     */
    public function getAnswers()
    {
        return is_array($this->answer) && !empty($this->answer) ? $this->answer : [$this->answer];
    }
}
