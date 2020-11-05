<?php


namespace Exam\Enums;


class QuestionAnswerType
{
    public const CHOICE = 'choice';
    public const IMAGE = 'image';
    public const WRITE = 'write';
    public const FILL_IN_THE_BLANK = 'fill_in_the_blank';

    /**
     * @return array
     */
    public static function toArray(): array
    {
        return [
            static::CHOICE => 'Single/Multiple Choice',
            static::IMAGE => 'Image',
            static::WRITE => 'Freehand Writing',
            static::FILL_IN_THE_BLANK => 'Fill in the Blank',
        ];
    }
}
