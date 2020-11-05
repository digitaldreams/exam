<?php

namespace Exam\Enums;

class QuestionType
{
    public const  IMAGE = 'img';
    public const  AUDIO = 'audio';
    public const  VIDEO = 'video';
    public const TEXT = 'text';

    /**
     * @return array
     */
    public static function toArray(): array
    {
        return [
            static::TEXT => 'Text',
            static::IMAGE => 'Image',
            static::AUDIO => 'Audio',
            static::VIDEO => 'Video',
        ];
    }

    /**
     * @return array
     */
    public static function generic()
    {
        return [
            static::TEXT,
            static::IMAGE,
            static::AUDIO,
            static::VIDEO,
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
            static::IMAGE,
            static::AUDIO,
            static::VIDEO,
        ]);
    }
}
