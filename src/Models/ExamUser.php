<?php

namespace Exam\Models;

use Carbon\Carbon;
use Exam\Services\CertificateService;
use Illuminate\Database\Eloquent\Model;
use Permit\Models\User;

/**
 * @property int $exam_id exam id
 * @property int $user_id user id
 * @property varchar $status status
 * @property text $completed completed
 * @property double $total_mark total mark
 * @property bool $reminder  Reminder
 * @property double $achieved_mark achieved mark
 * @property timestamp $created_at created at
 * @property timestamp $updated_at updated at
 */
class ExamUser extends Model
{
    const STATUS_COMPLETED = 'completed';
    const STATUS_PENDING = 'pending';
    const STATUS_CREATED = 'created';
    const STATUS_CANCELED = 'canceled';

    const VISIBILITY_PUBLIC = 'public';
    const VISIBILITY_PRIVATE = 'private';

    /**
     * Database table name
     */
    protected $table = 'exam_user';

    /**
     * @var array
     */
    protected $casts = [
        'completed' => 'array'
    ];
    /**
     * Mass assignable columns
     */
    protected $fillable = [
        'exam_id',
        'user_id',
        'status',
    ];

    /**
     * Date time columns.
     */
    protected $dates = [];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->user_id) && auth()->check()) {
                $model->user_id = auth()->id();
            }
        });
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function feedback()
    {
        return $this->hasOne(Feedback::class, 'feedbackable_id')->where('feedbackable_type', static::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class, 'exam_user_id', 'id');
    }

    public function pendingAnswers()
    {
        return $this->hasMany(Answer::class, 'exam_user_id', 'id')->where('status', Answer::STATUS_PENDING);

    }


    /**
     * @param $query
     * @param $examId
     * @param string $userId
     * @return mixed
     */
    public function scopeForUser($query, $examId, $userId = '')
    {
        $userId = empty($userId) && auth()->check() ? auth()->id() : $userId;
        return $query->where('exam_id', $examId)
            ->where('user_id', $userId);
    }

    public function getCompleted()
    {
        return $this->answers()->pluck('question_id')->toArray();
    }

    /**
     * @return bool
     */
    public function isFinished()
    {
        $total = $this->exam->questions()->count();
        $completed = $this->answers()->count();
        return $total == $completed;
    }

    public function remaining()
    {
        if ($this->isFinished()) {
            return false;
        }
        $total = $this->exam->questions()->count();
        $completed = $this->answers()->count();
        $remaining = $total - $completed;
        return $remaining > 0 ? $remaining : false;
    }

    /**
     *
     */
    public function getCorrectionRate()
    {
        $total = $this->answers()->whereIn('status', [Answer::STATUS_WRONG, Answer::STATUS_CORRECT])->count();
        $correctAns = $this->answers()->where('status', Answer::STATUS_CORRECT)->count();
        if ($total > 0) {
            return round(($correctAns / $total) * 100);
        }
        return 'n/a';
    }

    /**
     * Return the title of the model. For example
     *  John Doe and 30 others likes {Tour to Silliong part one}
     * @return mixed
     */
    public function getNotificationMessage()
    {
        return $this->exam->title;
    }

    public function getCertificate()
    {
        if ($this->status == static::STATUS_COMPLETED) {
            $certificate = new CertificateService($this);
            return $certificate->getFileName();
        }
        return false;
    }

    /**
     * @param string $default
     * @return string
     */
    public function getDuration($default = '')
    {
        if (!empty($this->started_at) && !empty($this->completed_at)) {
            return Carbon::parse($this->completed_at)->diff(Carbon::parse($this->started_at))->format('%H:%I:%S');
        }
        return $default;
    }

    public function timeLeft()
    {
        if (!empty($this->started_at)) {
            $lastTime = Carbon::parse($this->started_at)->addMinutes($this->exam->duration);
            return $lastTime->diff(Carbon::now())->format('%H:%I:%S');
        }
        return '';
    }

    public function isTimeOver()
    {
        if ($this->exam->hasTimeLimit()) {
            $lastTime = Carbon::parse($this->started_at)->addMinutes($this->exam->duration);
            return $lastTime->lt(Carbon::now());
        }
        return false;
    }


}