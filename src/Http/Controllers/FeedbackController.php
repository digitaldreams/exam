<?php

namespace Exam\Http\Controllers;

use App\Http\Controllers\Controller;
use Exam\Models\Exam;
use Exam\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Translation\Translator;

/**
 * Description of FeedbackController.
 *
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 */
class FeedbackController extends Controller
{
    /**
     * @var \Illuminate\Translation\Translator
     */
    protected $translator;

    /**
     * FeedbackController constructor.
     *
     * @param \Illuminate\Translation\Translator $translator
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

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
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', Feedback::class);

        $model = new Feedback();
        $model->fill($request->all())->save();

        return redirect()->back()->with('message', $this->translator->get('exam::flash.saved', ['model' => 'Feedback']));
    }

    /**
     * Update a existing resource in storage.
     *
     * @param Request  $request
     * @param Feedback $feedback
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Feedback $feedback)
    {
        $this->authorize('update', $feedback);

        $feedback->fill($request->all())->save();

        return redirect()->back()->with('message', $this->translator->get('exam::flash.updated', ['model' => 'Feedback']));
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
    public function destroy(Feedback $feedback)
    {
        $this->authorize('delete', $feedback);

        $feedback->delete();

        return redirect()->back()->with('message', $this->translator->get('exam::flash.deleted', ['model' => 'Feedback']));
    }
}
