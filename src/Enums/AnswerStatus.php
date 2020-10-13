<?php


namespace Exam\Enums;

class AnswerStatus
{
    public const CORRECT = 1;
    public const WRONG = 0;
    public const PENDING = 2;
    public const PARTIALLY_CORRECT = 3;

    public static function toArray()
    {
        return [
            static::WRONG,
            static::CORRECT,
            static::PENDING,
            static::PARTIALLY_CORRECT,
        ];
    }
}
