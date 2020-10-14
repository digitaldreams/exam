<?php

namespace Exam\Http\Requests\Answers;

use Exam\Enums\AnswerStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class Update extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('update', $this->route('exam'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status' => [
                'required',
                Rule::in(array_keys(AnswerStatus::toArray())),
            ],
            'obtain_mark' => [
                Rule::requiredIf(AnswerStatus::PARTIALLY_CORRECT == $this->get('status')),
                'min:1',
                'max:' . $this->route('answer')->question->total_mark ?? 10,
            ],
        ];
    }

}
