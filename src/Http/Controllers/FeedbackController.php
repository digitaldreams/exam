<?php

namespace Exam\Http\Controllers;

use App\Http\Controllers\Controller;
use Exam\Models\Exam;
use Exam\Models\Feedback;
use Illuminate\Http\Request;

/**
 * Description of FeedbackController.
 *
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 */
class FeedbackController extends Controller
{

    public function index(Exam $exam)
    {
        return view('exam::pages.exams.feedback', [
            'exam' => $exam,
            'feedbacks' => $exam->feedback()->latest()->paginate(10),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new Feedback();
        $model->fill($request->all());
        if ($model->save()) {
            session()->flash('message', 'Feedback saved successfully');
        } else {
            session()->flash('error', 'Something is wrong while saving Feedback');
        }

        return redirect()->back();
    }

    /**
     * Update a existing resource in storage.
     *
     * @param Request  $request
     * @param Feedback $feedback
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Feedback $feedback)
    {
        $feedback->fill($request->all());
        if ($feedback->save()) {
            session()->flash('message', 'Feedback successfully updated');
        } else {
            session()->flash('error', 'Something is wrong while updating Feedback');
        }

        return redirect()->back();
    }

    /**
     * Delete a  resource from  storage.
     *
     * @param Request  $request
     * @param Feedback $feedback
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Exception
     */
    public function destroy(Request $request, Feedback $feedback)
    {
        if ($feedback->delete()) {
            session()->flash('message', 'Feedback successfully deleted');
        } else {
            session()->flash('error', 'Error occurred while deleting Feedback');
        }

        return redirect()->back();
    }
}
