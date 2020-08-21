<?php

namespace Exam\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\User;

/**
 * @property int       $exam_id    exam id
 * @property varchar   $email      email
 * @property varchar   $status     status
 * @property varchar   $token      token
 * @property timestamp $created_at created at
 * @property timestamp $updated_at updated at
 */
class Invitation extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';
    /**
     * Database table name.
     */
    protected $table = 'exam_invitations';

    /**
     * Mass assignable columns.
     */
    protected $fillable = [
        'exam_id',
        'user_id',
        'status',
        'token',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->token)) {
                $model->token = Str::random(64);
            }

            return true;
        });
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getRouteKeyName()
    {
        return 'token';
    }
}
