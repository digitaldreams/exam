<?php


namespace Exam\Enums;


class QuestionAnswerType
{
    public const SINGLE_CHOICE = 'single';
    public const MULTIPLE_CHOICE = 'multiple';
    public const WRITE = 'write';
    public const FILL_IN_THE_BLANK = 'fill_in_the_blank';

    /**
     * @return array
     */
    public static function toArray(): array
    {
        return [
            static::SINGLE_CHOICE => 'Single Choice',
            static::MULTIPLE_CHOICE => 'Multiple Choice',
            static::WRITE => 'Write (User Input)',
            static::FILL_IN_THE_BLANK => 'Fill in the Blank',
        ];
    }
}
