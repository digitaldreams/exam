<?php

namespace Exam\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exam\Http\Requests\Invitations\Create;
use Exam\Http\Requests\Invitations\Destroy;
use Exam\Http\Requests\Invitations\Index;
use Exam\Http\Requests\Invitations\Show;
use Exam\Http\Requests\Invitations\Store;
use Exam\Models\Exam;
use Exam\Models\Invitation;
use Exam\Notifications\InvitationNotification;
use Exam\Notifications\InvitationResponseNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Translation\Translator;

/**
 * Description of InvitationController.
 *
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 */
class InvitationController extends Controller
{
    /**
     * @var \Illuminate\Translation\Translator
     */
    protected $translator;

    /**
     * InvitationController constructor.
     *
     * @param \Illuminate\Translation\Translator $translator
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Index             $request
     * @param \Exam\Models\Exam $exam
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Index $request, Exam $exam)
    {
        return view('exam::pages.invitations.index', [
            'records' => $exam->invitations()->latest()->paginate(10),
            'exam' => $exam,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Show       $request
     * @param Exam       $exam
     * @param Invitation $invitation
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Show $request, Exam $exam, Invitation $invitation)
    {
        return view('exam::pages.invitations.show', [
            'invitation' => $invitation,
            'exam' => $exam,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Create $request
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Create $request, Exam $exam)
    {
        return view('exam::pages.invitations.create', [
            'model' => new Invitation(),
            'exam' => $exam,
            'users' => User::get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Store $request
     * @param Exam  $exam
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request, Exam $exam)
    {
        $model = new Invitation();
        $model->fill($request->all());
        $model->exam_id = $exam->id;
        $model->save();
        if ($user = $model->user) {
            $user->notify(new InvitationNotification($model, auth()->user()));
        }

        return redirect()
            ->route('exam::exams.show', $exam->slug)
            ->with('message', $this->translator->get('exam::flash.invitation.send'));
    }

    /**
     * Update a existing resource in storage.
     *
     * @param Request    $request
     * @param Exam       $exam
     * @param Invitation $invitation
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function response(Request $request, Exam $exam, Invitation $invitation)
    {
        $this->authorize('update', $invitation);

        $invitation->status = Invitation::STATUS_REJECTED == $request->get('status') ? Invitation::STATUS_REJECTED : Invitation::STATUS_ACCEPTED;
        $invitation->save();
        Notification::send(User::getAdmins(), new InvitationResponseNotification($invitation));

        return redirect()
            ->route('exam::exams.show', $exam->slug)
            ->with('message', $this->translator->get('exam::flash.invitation.statusChanged'));
    }

    /**
     * Delete a  resource from  storage.
     *
     * @param Destroy    $request
     * @param Exam       $exam
     * @param Invitation $invitation
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Exception
     */
    public function destroy(Destroy $request, Exam $exam, Invitation $invitation)
    {
        $invitation->delete();

        return redirect()->back()
            ->with('message', $this->translator->get('exam::flash.deleted', ['model' => 'Invitation']));
    }
}
