<?php

namespace Exam\Http\Controllers;

use App\Http\Controllers\Controller;
use Exam\Models\Exam;
use Illuminate\Http\Request;

class ExamQuestionController extends controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \Exam\Models\Exam        $exam
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function add(Request $request, Exam $exam)
    {
        $this->authorize('update', $exam);
        $exam->questions()->syncWithoutDetaching($request->get('questions', []));

        return redirect()->back()->with('message', 'Questions successfully added');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Exam\Models\Exam        $exam
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function remove(Request $request, Exam $exam)
    {
        $this->authorize('update', $exam);
        $exam->questions()->detach($request->get('questions', []));

        return redirect()->back()->with('message', 'Questions removed successfully');
    }
}
