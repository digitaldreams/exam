<?php

namespace Exam\Contracts;
interface QuestionService
{
    /**
     * @return \Exam\Models\Question
     */
    public function make($type = '');
}