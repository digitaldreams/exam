<?php

namespace Exam\Http\Controllers;

use App\Http\Controllers\Controller;
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
use App\Models\User;

/**
 * Description of InvitationController.
 *
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 */
class InvitationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Index $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Index $request, Exam $exam)
    {
        return view('exam::pages.invitations.index', [
            'records' => Invitation::paginate(10),
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
            'users' => User::select(['id', 'first_name', 'last_name', 'email'])->get(),
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
        if ($model->save()) {
            if ($user = $model->user) {
                $user->notify(new InvitationNotification($model, auth()->user()));
            }
            session()->flash('message', 'Invitation send successfully');

            return redirect()->route('exam::exams.show', $exam->slug);
        } else {
            session()->flash('message', 'Something is wrong while sending Invitation');
        }

        return redirect()->back();
    }

    /**
     * Update a existing resource in storage.
     *
     * @param Request    $request
     * @param Exam       $exam
     * @param Invitation $invitation
     *
     * @return \Illuminate\Http\Response
     */
    public function response(Request $request, Exam $exam, Invitation $invitation)
    {
        $invitation->status = Invitation::STATUS_REJECTED == $request->get('status') ? Invitation::STATUS_REJECTED : Invitation::STATUS_ACCEPTED;

        if ($invitation->save()) {
            $admins = User::superAdmin()->get();
            Notification::send($admins, new InvitationResponseNotification($invitation));
            session()->flash('message', 'Invitation successfully ' . $invitation->status);

            return redirect()->route('exam::exams.show', $exam->slug);
        } else {
            session()->flash('error', 'Something is wrong while updating Invitation');
        }

        return redirect()->back();
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
        if ($invitation->delete()) {
            session()->flash('message', 'Invitation successfully deleted');
        } else {
            session()->flash('error', 'Error occurred while deleting Invitation');
        }

        return redirect()->back();
    }
}
