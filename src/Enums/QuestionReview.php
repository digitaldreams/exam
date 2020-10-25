<?php


namespace Exam\Enums;


class QuestionReview
{
    public const PENDING = 2;
    public const AUTO = 'auto';
    public const MANUAL = 'manual';

    /**
     * @return array
     */
    public static function types()
    {
        return [
            static::AUTO,
            static::MANUAL,
        ];
    }
}
