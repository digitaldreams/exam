<?php

namespace Exam\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

/**
 * @property varchar   $feedbackable_type feedbackable type
 * @property int       $feedbackable_id   feedbackable id
 * @property float     $rating            rating
 * @property varchar   $feedback          feedback
 * @property timestamp $created_at        created at
 * @property timestamp $updated_at        updated at
 */
class Feedback extends Model
{

    /**
     * Database table name.
     */
    protected $table = 'exam_feedback';

    /**
     * Mass assignable columns.
     */
    protected $fillable = [
        'feedbackable_type',
        'feedbackable_id',
        'rating',
        'feedback',
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

            return true;
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function feedbackable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /**
     * @return string
     */
    public function stars()
    {
        $rating = $this->rating;

        $yellow = '';
        $black = '';
        for ($r = 0; $r < $rating; ++$r) {
            $yellow .= ' <i class="fa fa-star text-warning"></i>';
        }
        $remaining = 5 - $rating;
        for ($r = 0; $r < $remaining; ++$r) {
            $black .= ' <i class="fa fa-star text-muted"></i>';
        }

        return $yellow . $black;
    }

}
