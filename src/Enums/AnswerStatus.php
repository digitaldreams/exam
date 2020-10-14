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
            static::WRONG => 'Wrong',
            static::CORRECT => 'Correct',
            static::PENDING => 'Pending',
            static::PARTIALLY_CORRECT => 'Partially Correct',
        ];
    }
}
