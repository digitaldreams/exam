<?php

namespace Exam\Http\Requests\Questions;

use Exam\Enums\QuestionAnswerType;
use Exam\Enums\QuestionReview;
use Exam\Enums\QuestionType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class Store extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'title' => ['required', 'max:191'],
            'type' => ['required', 'max:191', Rule::in(QuestionType::generic())],
            'answer_type' => ['required', Rule::in(array_keys(QuestionAnswerType::toArray()))],
            'total_mark' => ['required', 'digits_between:1,99'],
            'parent_id' => ['nullable', Rule::exists('questions', 'id')],
            'review_type' => ['required', Rule::in(QuestionReview::types())],
            'category_id' => ['required', Rule::exists('blog_categories', 'id')],
        ];

        if (QuestionType::AUDIO == $this->get('type')) {
            $rules['file'] = ['required', 'file', 'max:8384', 'mimes:mp3,ogg,wav,m4a,m4b'];
        } elseif (QuestionType::IMG_TO_QUESTION == $this->get('type')) {
            $rules['file'] = ['required', 'file', 'max:8384', 'image'];
        }

        if (in_array($this->get('answer_type'), [QuestionAnswerType::SINGLE_CHOICE, QuestionAnswerType::MULTIPLE_CHOICE])) {
            $rules['options'] = ['required', 'array'];
            $rules['options.option'] = ['required', 'array'];
            $rules['options.isCorrect'] = ['required', 'array'];
        }

        if (QuestionAnswerType::FILL_IN_THE_BLANK == $this->get('answer_type')) {
            $rules['data.fill_in_the_blank.summary'] = ['required'];
            $rules['answers'] = ['required', 'array'];
            $rules['answers.*.key'] = ['required', 'max:150'];
            $rules['answers.*.value'] = ['required', 'max:150'];
        }

        if (QuestionAnswerType::WRITE == $this->get('answer_type') && QuestionReview::AUTO == $this->get('review_type')) {
            $rules['answer.*'] = ['required', 'max:190'];
        }

        $rules['hints'] = ['nullable', 'max:191'];
        $rules['explanation'] = ['nullable', 'max:191'];

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
        ];
    }
}
