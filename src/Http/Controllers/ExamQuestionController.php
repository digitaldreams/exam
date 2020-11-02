<?php

namespace Exam\Http\Controllers;

use App\Http\Controllers\Controller;
use Exam\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Translation\Translator;

class ExamQuestionController extends controller
{
    /**
     * @var \Illuminate\Translation\Translator
     */
    protected $translator;

    /**
     * ExamQuestionController constructor.
     *
     * @param \Illuminate\Translation\Translator $translator
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

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

        return redirect()->back()->with('message', $this->translator->get('exam::flash.question.attach'));
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

        return redirect()->back()->with('message', $this->translator->get('exam::flash.question.detach'));
    }
}
