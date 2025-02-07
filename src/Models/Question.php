<?php

namespace Exam\Models;

use App\Models\User;
use Blog\Models\Category;
use Blog\Models\Tag;
use Blog\Services\FullTextSearch;
use Exam\Services\FillInTheBlankFormService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Arr;

/**
 * @property string    $type        type
 * @property string    $title       title
 * @property string    $options     options
 * @property array     $answer      answer
 * @property string    $explanation explanation
 * @property array     $data        data
 * @property int       $total_mark  total mark
 * @property \DateTime $created_at  created at
 * @property \DateTime $updated_at  updated at
 */
class Question extends Model
{
    use FullTextSearch;

    /**
     * Database table name.
     */
    protected $table = 'questions';

    /**
     * Mass assignable columns.
     */
    protected $fillable = [
        'type',
        'parent_id',
        'category_id',
        'title',
        'hints',
        'options',
        'answer',
        'answer_type',
        'review_type',
        'explanation',
        'data',
        'total_mark',
    ];

    /**
     * @var array
     */
    protected $searchable = ['title'];

    /**
     * Date time columns.
     */
    protected $dates = [];

    /**
     * @var array
     */
    protected $casts = [
        'data' => 'array',
        'options' => 'array',
        'answer' => 'array',
    ];

    /**
     * The Creator of the Question.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function exams(): BelongsToMany
    {
        return $this->belongsToMany(Exam::class, 'exam_question');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function answer(): HasOne
    {
        return $this->hasOne(Answer::class, 'question_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'question_tag');
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int                                   $examId
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForExam(Builder $query, int $examId): Builder
    {
        return $query->whereHas('exams', function ($q) use ($examId) {
            $q->where('id', $examId);
        });
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnlyParent(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        if (empty($this->id)) {
            return [
                1 => '',
                2 => '',
                3 => '',
                4 => '',
            ];
        }

        return is_array($this->options) ? $this->options : [];
    }

    /**
     * @param string|array $value
     *
     * @return bool
     */
    public function isCorrectAnswer($value): bool
    {
        if (empty($this->id)) {
            return false;
        }
        $answers = $this->getAnswers();

        return is_array($answers) ? in_array($value, $answers) : $value == $this->answer;
    }

    /**
     * @return array|string
     */
    public function getAnswers()
    {
        return is_array($this->answer) ? $this->answer : [];
    }

    /**
     * @param      $word
     * @param bool $key
     *
     * @return bool|void
     */
    public function inOptions($word, $key = true): bool
    {
        $options = $this->getOptions();
        if (is_array($options)) {
            return !empty($key) ? array_key_exists($word, $options) : in_array($word, $options);
        }

        return $options == $word;
    }

    /**
     * @param string $key
     *
     * @return bool|mixed
     */
    public function getData($key = '')
    {
        if (empty($key)) {
            return $this->data;
        }

        return is_array($this->data) ? Arr::get($this->data, $key) : false;
    }

    /**
     * Parse Youtube Video link and make it embedded url.
     *
     * @return bool|mixed|string
     */
    public function getVideoLink()
    {
        $url = $this->getData('media.url');
        if (!empty($url)) {
            $parts = parse_url($url);
            if (isset($parts['host']) && isset($parts['query']) && 'www.youtube.com' == $parts['host']) {
                $id = str_replace('v=', '', $parts['query']);

                return 'https://www.youtube.com/embed/' . $id;
            } elseif ('youtu.be' == $parts['host']) {
                $path = $parts['path'];

                return 'https://www.youtube.com/embed/' . trim($path, '/');
            } elseif ('www.vimeo.com' == $parts['host'] || 'vimeo.com' == $parts['host']) {
                return 'https://player.vimeo.com/video/' . trim($parts['path'], '/');
            }
        }

        return $url;
    }

    /**
     * @return string
     */
    public function renderSummaryForm(): string
    {
        $summaryFormService = new FillInTheBlankFormService($this);

        return $summaryFormService->render();
    }
}
