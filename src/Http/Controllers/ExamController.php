<?php

namespace Exam\Http\Controllers;

use Exam\Http\Requests\Exams\Create;
use Exam\Http\Requests\Exams\Destroy;
use Exam\Http\Requests\Exams\Edit;
use Exam\Http\Requests\Exams\Index;
use Exam\Http\Requests\Exams\Show;
use Exam\Http\Requests\Exams\Store;
use Exam\Http\Requests\Exams\Update;
use Exam\Models\Exam;
use Exam\Models\ExamUser;
use Exam\Models\Question as QuestionModel;
use App\Http\Controllers\Controller;

class ExamController extends Controller
{
    /**
     * @param Index $index
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Index $index)
    {
        $pendingIds = ExamUser::where('user_id', auth()->id())
            ->pluck('exam_id')->toArray();
        $exams = Exam::query();
        if (!auth()->user()->isAdmin()) {
            $exams = $exams->forUser()->where('status', Exam::STATUS_ACTIVE);
        }
        $exams = $exams->whereNotIn('id', $pendingIds)->paginate(6);
        return view('exam::pages.exams.index', [
            'records' => $exams,
            'pendingExams' => ExamUser::where('user_id', auth()->id())
                ->where('status', Exam::STATUS_PENDING)
                ->orderBy('started_at', 'desc')->take(2)->get(),
            'completedExams' => ExamUser::where('user_id', auth()->id())
                ->where('status', Exam::STATUS_COMPLETED)
                ->orderBy('completed_at', 'desc')->take(2)->get(),
        ]);
    }

    /**
     * @param Show $show
     * @param Exam $exam
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Show $show, Exam $exam)
    {
        $exam->load(['questions', 'users', 'examUser']);
        return view('exam::pages.exams.show', [
            'record' => $exam
        ]);
    }

    public function store(Store $store)
    {
        $exam = new Exam();
        $exam->fill($store->all());
        if ($exam->save()) {
            $exam->questions()->sync($store->get('questions'));
            $exam->tags()->sync($store->get('tags'));
            session()->flash('permit_message', 'Exam successfully saved');
            return redirect()->route('exam::exams.index');
        } else {
            session()->flash('permit_error', 'Something is wrong while saving Exam');
        }
        return redirect()->back();
    }

    public function create(Create $create)
    {
        return view('exam::pages.exams.create', [
            'model' => new Exam(),
            'tags' =>[],
            'questions' => QuestionModel::onlyParent()->get(['id', 'title']),
            'exams' => Exam::where('status', Exam::STATUS_ACTIVE)->select(['id', 'title'])->get()
        ]);
    }

    public function update(Update $update, Exam $exam)
    {
        $exam->fill($update->all());
        if ($exam->save()) {
            $exam->questions()->sync($update->get('questions'));
            $exam->tags()->sync($update->get('tags'));
            session()->flash('permit_message', 'Exam successfully updated');
            return redirect()->route('exam::exams.index');
        } else {
            session()->flash('permit_error', 'Something is wrong while updating Exam');
        }
        return redirect()->back();
    }

    public function edit(Edit $edit, Exam $exam)
    {
        return view('exam::pages.exams.edit', [
            'model' => $exam,
            'tags' => Tag::all(),
            'questions' => QuestionModel::onlyParent()->get(['id', 'title']),
            'enableVoice' => true,
            'exams' => Exam::where('status', Exam::STATUS_ACTIVE)->where('id', '!=', $exam->id)->select(['id', 'title'])->get()
        ]);
    }

    public function destroy(Destroy $destroy, Exam $exam)
    {
        if ($exam->delete()) {
            session()->flash('permit_message', 'Exam successfully deleted');
        } else {
            session()->flash('permit_error', 'Error occurred while deleting Exam');
        }
        return redirect()->back();
    }
}
