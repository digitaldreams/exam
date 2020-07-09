<?php

namespace Exam\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int       $exam_user_id exam user id
 * @property int       $question_id  question id
 * @property text      $answer       answer
 * @property tinyint   $status       status
 * @property timestamp $created_at   created at
 * @property timestamp $updated_at   updated at
 * @property Question  $question     belongsTo
 * @property ExamUser  $examUser     belongsTo
 */
class Answer extends Model
{
    const STATUS_CORRECT = 1;
    const STATUS_WRONG = 0;
    const STATUS_PENDING = 2;

    /**
     * Database table name.
     */
    protected $table = 'exam_user_answer';

    /**
     * Mass assignable columns.
     */
    protected $fillable = ['exam_user_id', 'question_id', 'answer', 'status', 'spent_time'];

    /**
     * Date time columns.
     */
    protected $dates = [];

    public static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            if (is_array($model->answer)) {
                $model->answer = json_encode($model->answer);
            }

            return true;
        });
    }

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

    public function isCorrect()
    {
        return $this->status == static::STATUS_CORRECT;
    }
}
