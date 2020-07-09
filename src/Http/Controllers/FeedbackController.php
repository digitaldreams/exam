<?php

namespace Exam\Http\Controllers;

use App\Http\Controllers\Controller;
use Exam\Models\Feedback;
use Illuminate\Http\Request;

/**
 * Description of FeedbackController.
 *
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 */
class FeedbackController extends Controller
{
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
            session()->flash('permit_message', 'Feedback saved successfully');
        } else {
            session()->flash('permit_error', 'Something is wrong while saving Feedback');
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
            session()->flash('permit_message', 'Feedback successfully updated');
        } else {
            session()->flash('permit_error', 'Something is wrong while updating Feedback');
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
            session()->flash('app_message', 'Feedback successfully deleted');
        } else {
            session()->flash('app_error', 'Error occurred while deleting Feedback');
        }

        return redirect()->back();
    }
}
