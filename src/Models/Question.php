<?php

namespace Exam\Models;

use Blog\Services\FullTextSearch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * @property varchar   $type              type
 * @property varchar   $questionable_type questionable type
 * @property int       $questionable_id   questionable id
 * @property varchar   $title             title
 * @property text      $options           options
 * @property varchar   $answer            answer
 * @property varchar   $explanation       explanation
 * @property text      $data              data
 * @property int       $total_mark        total mark
 * @property timestamp $created_at        created at
 * @property timestamp $updated_at        updated at
 */
class Question extends Model
{
    use FullTextSearch;
    const  TYPE_IMG_TO_WORD = 'img_to_word';
    const  TYPE_AUDIO_TO_WORD = 'audio_to_word';
    const  TYPE_VIDEO_TO_WORD = 'video_to_word';
    const  TYPE_WORD_TO_IMG = 'word_to_img';
    const TYPE_MCQ = 'mcq';
    const TYPE_PART_OF_SPEECH = 'part_of_speech';
    const TYPE_CONVERSION = 'conversion';
    const TYPE_WRITE_SENTENCE = 'write_sentence';
    const TYPE_VOICE_TO_WORD = 'voice_to_word';
    const TYPE_REARRANGE = 'rearrange';
    const TYPE_SPELLING = 'spelling';
    const TYPE_PRONOUNCE = 'pronounce';
    const TYPE_VOICE_TO_SENTENCE = 'voice_to_sentence';
    const TYPE_FREEHAND_WRITING = 'freehand_writing';

    const  ANSWER_SINGLE = 'single';
    const ANSWER_TYPE_MULTIPLE = 'multiple';
    const ANSWER_TYPE_WRITE = 'write';
    const REVIEW_PENDING = 2;
    const REVIEW_TYPE_AUTO = 'auto';
    const REVIEW_TYPE_MANUAL = 'manual';

    /**
     * Database table name.
     */
    protected $table = 'questions';

    /**
     * Mass assignable columns.
     */
    protected $fillable = [
        'type',
        'questionable_type',
        'questionable_id',
        'parent_id',
        'title',
        'hints',
        'options',
        'answer',
        'answer_type',
        'review_type',
        'explanation',
        'data',
    ];

    protected $searchable = ['title'];

    /**
     * Date time columns.
     */
    protected $dates = [];

    protected $casts = [
        'data' => 'array',
        'options' => 'array',
    ];

    public function questionable()
    {
        return $this->morphTo();
    }

    public function exam()
    {
        return $this->belongsToMany(Exam::class, 'exam_question');
    }

    public function answer()
    {
        return $this->hasOne(Answer::class, 'question_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    public function scopeForExam($query, $examId)
    {
        $query->whereHas('exam', function ($q) use ($examId) {
            $q->where('id', $examId);
        });
    }

    public function scopeOnlyParent($query)
    {
        return $query->whereNull('parent_id');
    }

    public static function types()
    {
        return [
            'mcq' => 'MCQ',
            'img_to_word' => 'Image To Question',
            'word_to_img' => 'Question To Image',
            'voice_to_sentence' => 'Voice',
            'pronounce' => 'Pronounce',
            'audio_to_word' => 'Audio',
            'video_to_word' => 'Video',
            'freehand_writing' => 'Freehand Writing',
        ];
    }

    /**
     * @return array
     */
    public static function wordTypes()
    {
        return [
            'part_of_speech' => [
                'title' => 'Parts of Speech',
                'type' => static::ANSWER_SINGLE,
            ],
            'conversion' => [
                'title' => 'PP conversion',
                'type' => static::ANSWER_SINGLE,
            ],
            'write_sentence' => [
                'title' => 'Make sentence',
                'type' => static::ANSWER_TYPE_WRITE,
            ],
            'spelling' => [
                'title' => 'Spelling',
                'type' => static::ANSWER_SINGLE,
            ],
            'pronounce' => [
                'title' => 'Pronounce',
                'type' => static::ANSWER_SINGLE,
            ],
            'word_to_img' => [
                'title' => 'Image',
                'type' => static::ANSWER_SINGLE,
            ],
        ];
    }

    /**
     * @return array|text
     */
    public function getOptions()
    {
        try {
            return is_array($this->options) ? $this->options : [];
        } catch (\Exception $ex) {
            return [];
        }
    }

    public function getAnswers()
    {
        if (in_array($this->type, static::generic()) && $this->answer_type == static::ANSWER_TYPE_MULTIPLE) {
            return explode(',', $this->answer);
        }

        return $this->answer;
    }

    /**
     * @return array
     */
    public static function generic()
    {
        return [
            static::TYPE_MCQ,
            static::TYPE_IMG_TO_WORD,
            static::TYPE_PART_OF_SPEECH,
            static::TYPE_SPELLING,
            static::TYPE_AUDIO_TO_WORD,
            static::TYPE_VIDEO_TO_WORD,
            static::TYPE_FREEHAND_WRITING,
        ];
    }

    /**
     * @param      $word
     * @param bool $key
     *
     * @return bool|void
     */
    public function inOptions($word, $key = true)
    {
        $options = $this->getOptions();
        if (is_array($options)) {
            return !empty($key) ? array_key_exists($word, $options) : in_array($word, $options);
        }

        return $options == $word;
    }

    /**
     * @param $type
     * @param $id
     *
     * @return Question
     */
    public function callService($type, $id)
    {
        $modelClass = config('exam.services.' . $type . '.model');
        if (empty($modelClass)) {
            return new static();
        }
        $serviceClass = config('exam.services.' . $type . '.service');
        if (empty($serviceClass)) {
            return new static();
        }
        $modelObj = $modelClass::find($id);
        $serviceObj = new $serviceClass($modelObj, $this);

        return $serviceObj->make();
    }

    /**
     * @return bool
     */
    public function isMediaInTitle()
    {
        return in_array($this->type, [
            static::TYPE_WORD_TO_IMG,
            static::TYPE_AUDIO_TO_WORD,
            static::TYPE_VIDEO_TO_WORD,
        ]);
    }

    /**
     * @param string $key
     *
     * @return bool|text|mixed
     */
    public function getData($key = '')
    {
        if (empty($key)) {
            return $this->data;
        }

        return is_array($this->data) ? Arr::get($this->data, $key) : false;
    }
}
