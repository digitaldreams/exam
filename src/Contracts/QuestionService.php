<?php

namespace Exam\Contracts;

interface QuestionService
{
    /**
     * @return \Exam\Models\Question
     *
     * @param mixed $type
     */
    public function make($type = '');
}
