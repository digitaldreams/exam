<?php

namespace Exam\Http\Controllers;

use App\Http\Controllers\Controller;
use Exam\Models\Exam;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     */
    public function index(Request $request)
    {

    }

    /**
     * @param \Exam\Models\Exam $exam
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Exam $exam)
    {
        $this->authorize('open', $exam);

        return view('exam::pages.exams.frontend.show', [
            'exam' => $exam,
        ]);
    }
}
