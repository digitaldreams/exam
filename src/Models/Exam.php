<?php

namespace Exam\Models;

use App\Models\User;
use Blog\Models\Activity;
use Blog\Models\Category;
use Blog\Models\Tag;
use Blog\Services\FullTextSearch;
use Exam\Enums\ExamShowAnswer;
use Exam\Enums\ExamUserStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property string                                   $title          title
 * @property string                                   $status         status
 * @property string                                   $description    description
 * @property int                                      $duration       Duration
 * @property string                                   $show_answer    Duration
 * @property string                                   $slug           Duration
 * @property array                                    $must_completed Duration
 * @property \Carbon\Carbon                           $created_at     created at
 * @property \Carbon\Carbon                           $updated_at     updated at
 * @property \Illuminate\Database\Eloquent\Collection $question       belongsToMany
 * @property \Illuminate\Database\Eloquent\Collection $tags           belongsToMany
 * @property \Illuminate\Database\Eloquent\Collection $examUser       hasMany
 */
class Exam extends Model
{
    use FullTextSearch;

    /**
     * Database table name.
     */
    protected $table = 'exams';

    /**
     * Mass assignable columns.
     */
    protected $fillable = [
        'title',
        'status',
        'visibility',
        'description',
        'slug',
        'duration',
        'show_answer',
        'must_completed',
        'category_id',
    ];

    /**
     * @var array
     */
    protected $searchable = [
        'title',
        'description',
    ];

    /**
     * @var array
     */
    protected $casts = ['must_completed' => 'array'];

    /**
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Relationship between exams and blog_tags via exam_tag table.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'exam_tag');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relationship between exams and questions via exam_questions table.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'exam_question');
    }

    /**
     * Relationship between exams and users via exam_user table.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'exam_user');
    }

    /**
     * Relationship between exams and invitations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    /**
     * Relationship between exams and exam_users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function examUser(): HasMany
    {
        return $this->hasMany(ExamUser::class);
    }

    /**
     * MorphMany Relationship between exams and feedback table.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function feedback(): MorphMany
    {
        return $this->morphMany(Feedback::class, 'feedbackable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function likes()
    {
        return $this->morphMany(Activity::class, 'activityable')
            ->where('type', \Blog\Enums\ActivityType::LIKE);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activities()
    {
        return $this->morphMany(Activity::class, 'activityable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function favourites()
    {
        return $this->morphMany(Activity::class, 'activityable')
            ->where('type', \Blog\Enums\ActivityType::FAVOURITE);
    }

    /**
     * Filter Exam by tag id.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int                                   $id
     *
     * @return Builder
     */
    public function scopeTagId(Builder $query, int $id): Builder
    {
        return $query->whereHas('tags', function ($q) use ($id) {
            $q->where('id', $id);
        });
    }

    /**
     * Filter exam that user is not taken yet.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|null                              $user_id
     *
     * @return mixed
     */
    public function scopeNotTaken(Builder $query, ?int $user_id = null): Builder
    {
        $user_id = empty($user_id) && auth()->check() ? auth()->id() : $user_id;

        return $query->whereHas('examUser', function ($q) use ($user_id) {
            $q->where('user_id', '!=', $user_id);
        });
    }

    /**
     * Whether this exam has a time limit or not?
     *
     * @return bool
     */
    public function hasTimeLimit(): bool
    {
        return !empty($this->duration);
    }

    /**
     * Does answer of the previous question will be shown on top of the next question?
     *
     * @return bool
     */
    public function showInstantly(): bool
    {
        return ExamShowAnswer::INSTANTLY == $this->show_answer;
    }

    /**
     * List of Question ids this exam has.
     *
     * @return array
     */
    public function questionIds(): array
    {
        return $this->questions()->allRelatedIds()->toArray();
    }

    /**
     * List of tag ids this exam has.
     *
     * @return array
     */
    public function tagIds(): array
    {
        return $this->tags()->allRelatedIds()->toArray();
    }

    /**
     * @return array
     */
    public function getMustCompletedIds()
    {
        return is_array($this->must_completed) ? $this->must_completed : [];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function mustCompletedExams()
    {
        return static::newQuery()->whereIn('id', $this->getMustCompletedIds())->get();
    }

    /**
     * @return string
     */
    public function stars()
    {
        $rating = $this->feedback()->avg('rating');

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

    /**
     * @param string $user_id
     *
     * @return bool
     */
    public function doesCompleteExams($user_id = '')
    {
        $user_id = empty($user_id) && auth()->check() ? auth()->id() : $user_id;
        $mustCompleted = $this->getMustCompletedIds();
        $count = ExamUser::whereIn('exam_id', $mustCompleted)->where('user_id', $user_id)
            ->where('status', ExamUserStatus::COMPLETED)
            ->count();

        return count($mustCompleted) == $count;
    }
}
