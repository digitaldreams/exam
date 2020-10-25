<?php

namespace Exam\Enums;

class QuestionType
{
    public const  IMG_TO_QUESTION = 'img';
    public const  AUDIO = 'audio';
    public const  VIDEO = 'video';
    public const  QUESTION_TO_IMG = 'question_to_img';
    public const MCQ = 'mcq';
    public const FREEHAND_WRITING = 'freehand_writing';

    /**
     * @return array
     */
    public static function toArray(): array
    {
        return [
            static::MCQ => 'MCQ',
            static::IMG_TO_QUESTION => 'Image To Question',
            static::QUESTION_TO_IMG => 'Question To Image',
            static::AUDIO => 'Audio',
            static::VIDEO => 'Video',
            static::FREEHAND_WRITING => 'Freehand Writing',
        ];
    }

    /**
     * @return array
     */
    public static function generic()
    {
        return [
            static::MCQ,
            static::IMG_TO_QUESTION,
            static::QUESTION_TO_IMG,
            static::AUDIO,
            static::VIDEO,
            static::FREEHAND_WRITING,
        ];
    }

    /**
     * @param mixed $type
     *
     * @return bool
     *
     */
    public function isMediaInTitle($type)
    {
        return in_array($type, [
            static::IMG_TO_QUESTION,
            static::AUDIO,
            static::VIDEO,
        ]);
    }
}
