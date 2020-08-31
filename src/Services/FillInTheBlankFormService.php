<?php

namespace Exam\Services;

use Exam\Models\Question;

class FillInTheBlankFormService
{
    /**
     * @var \Exam\Models\Question
     */
    protected $question;

    /**
     * FillInTheBlankFormService constructor.
     *
     * @param \Exam\Models\Question $question
     */
    public function __construct(Question $question)
    {
        $this->question = $question;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $summaryText = $this->question->getData('fill_in_the_blank.summary');
        return $this->textToInput($summaryText);
    }

    /**
     * @param $text
     *
     * @return string
     */
    private function textToInput($text): string
    {
        $text = str_replace('_', '', $text);
        $inputArr = [];
        foreach (array_keys($this->question->getAnswers()) as $key) {
            $inputArr[$key] = $key . '<input type="text" name="answer[' . $key . ']" class="from-control-inline" id="' . $key . '" datalist="availableOptions" placeholder="Write down the answer for ' . $key . ' ">';
        }

        return strtr($text, $inputArr);
    }
}
