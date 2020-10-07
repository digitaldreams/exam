<?php


namespace Exam\Enums;


class ExamUserStatus
{
    const PENDING = 1;
    const COMPLETED = 2;
    const CANCELED = 3;

    /**
     * @return array
     */
    public static function toArray()
    {
        return [
            self::PENDING => 'pending',
            self::COMPLETED => 'completed',
            self::CANCELED => 'canceled',
        ];
    }
}
