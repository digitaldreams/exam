<?php

namespace Exam\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExamFeedback extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'feedbackable_type' => 'nullable|max:191',
            'feedbackable_id' => 'nullable|numeric',
            'rating' => 'required|numeric',
            'feedback' => 'nullable|max:191',
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
