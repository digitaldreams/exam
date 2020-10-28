<?php

namespace Exam\Models;

use App\Models\User;
use Carbon\Carbon;
use Exam\Enums\AnswerStatus;
use Exam\Enums\ExamUserStatus;
use Exam\Services\CertificateService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int    $exam_id       exam id
 * @property int    $user_id       user id
 * @property string $status        status
 * @property string $completed     completed
 * @property float  $total_mark    total mark
 * @property bool   $reminder      Reminder
 * @property float  $achieved_mark achieved mark
 * @property Carbon $created_at    created at
 * @property Carbon $updated_at    updated at
 */
class ExamUser extends Model
{
    /**
     * Database table name.
     */
    protected $table = 'exam_user';

    /**
     * @var array
     */
    protected $casts = [
        'completed' => 'array',
    ];
    /**
     * Mass assignable columns.
     */
    protected $fillable = [
        'exam_id',
        'user_id',
        'status',
        'started_at',
        'completed_at',
        'token',
        'ip',
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function feedback(): HasOne
    {
        return $this->hasOne(Feedback::class, 'feedbackable_id')->where('feedbackable_type', static::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class, 'exam_user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pendingAnswers(): HasMany
    {
        return $this->hasMany(Answer::class, 'exam_user_id', 'id')
            ->where('status', AnswerStatus::PENDING);
    }

    /**
     * @param        $query
     * @param        $examId
     * @param string $userId
     *
     * @return mixed
     */
    public function scopeForUser($query, $examId, $userId = '')
    {
        $userId = empty($userId) && auth()->check() ? auth()->id() : $userId;

        return $query->where('exam_id', $examId)
            ->where('user_id', $userId);
    }

    /**
     * @return array
     */
    public function getCompleted(): array
    {
        return $this->answers()->pluck('question_id')->toArray();
    }

    /**
     * @return bool
     */
    public function isFinished(): bool
    {
        $total = $this->exam->questions()->count();
        $completed = $this->answers()->count();

        return $total == $completed;
    }

    /**
     * @return bool|int
     */
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
     * @return float|string
     */
    public function getCorrectionRate()
    {
        $total = $this->answers()->whereIn('status', [AnswerStatus::WRONG, AnswerStatus::CORRECT, AnswerStatus::PARTIALLY_CORRECT])->count();
        $correctAns = $this->answers()->where('status', AnswerStatus::CORRECT)->count();
        if ($total > 0) {
            return round(($correctAns / $total) * 100);
        }

        return 'n/a';
    }

    /**
     * @return mixed
     */
    public function getTotalObtainMark()
    {
        return $this->answers()->sum('obtain_mark');
    }

    /**
     * @return bool|string
     */
    public function getCertificate()
    {
        if (ExamUserStatus::COMPLETED == $this->status) {
            $certificate = new CertificateService($this);

            return $certificate->getFileName();
        }

        return false;
    }

    /**
     * @param string $default
     *
     * @return string
     */
    public function getDuration($default = '')
    {
        if (!empty($this->started_at) && !empty($this->completed_at)) {
            return Carbon::parse($this->completed_at)->diff(Carbon::parse($this->started_at))->format('%H:%I:%S');
        }

        return $default;
    }

}
