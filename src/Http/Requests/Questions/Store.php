<?php

namespace Exam\Http\Requests\Questions;

use Illuminate\Foundation\Http\FormRequest;
use Exam\Models\Question;

class Store extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('create', Question::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'required|max:191',
            'title' => 'nullable|max:191',
            'options' => 'nullable|array',
            'answer' => 'nullable',
            'explanation' => 'nullable|max:191',
        ];
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
