<?php

namespace Exam\Http\Controllers;

use App\Http\Controllers\Controller;
use Exam\Http\Requests\Questions\Create;
use Exam\Http\Requests\Questions\Destroy;
use Exam\Http\Requests\Questions\Edit;
use Exam\Http\Requests\Questions\Index;
use Exam\Http\Requests\Questions\Show;
use Exam\Http\Requests\Questions\Store;
use Exam\Http\Requests\Questions\Update;
use Exam\Models\Question;
use Illuminate\Http\Request;

/**
 * Description of QuestionController.
 *
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 */
class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Index $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Index $request)
    {
        $question = new Question();
        if (!empty($request->get('search'))) {
            $question = $question->search($request->get('search'));
        }

        return view('exam::pages.questions.index', ['records' => $question->paginate(8)]);
    }

    /**
     * Display the specified resource.
     *
     * @param Show     $request
     * @param Question $question
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Show $request, Question $question)
    {
        return view('exam::pages.questions.show', [
            'record' => $question,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Create $request
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Create $request)
    {
        $model = new Question();
        $questionableType = null;
        $questionableId = null;

        if ($request->has('q_type') && $request->has('q_id') && !empty($request->get('type'))) {
            $questionableType = isset(Question::$mapModel[$request->get('q_type')]) ? Question::$mapModel[$request->get('q_type')] : $request->get('q_type');
            $questionableId = $request->get('q_id');
            $model = Question::firstOrNew([
                'questionable_type' => $questionableType,
                'questionable_id' => $questionableId,
                'type' => $request->get('type'),
            ]);
            $model->answer_type = $request->get('answer_type');
            $model = $model->callService($request->get('q_type'), $request->get('q_id'));
        }
        if (Question::TYPE_FREEHAND_WRITING == $request->get('type') || Question::ANSWER_TYPE_WRITE == $request->get('answer_type')) {
            $model->review_type = Question::REVIEW_TYPE_MANUAL;
        }

        return view('exam::pages.questions.create', [
            'model' => $model,
            'parents' => Question::onlyParent()->get(['id', 'title']),
            'enableVoice' => true,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Store $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request)
    {
        $model = new Question();
        $model->fill($request->all());
        if (is_array($model->answer)) {
            if (Question::TYPE_REARRANGE == $model->type) {
                $model->answer = array_intersect_key($request->get('answer'), $request->get('options'));
            }
            $model->answer = json_encode($model->answer);
        }
        if ($model->save()) {
            session()->flash('message', 'Question saved successfully');

            return redirect()->route('exam::questions.index');
        } else {
            session()->flash('error', 'Something is wrong while saving Question');
        }

        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request  $request
     * @param Question $question
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Exception
     */
    public function edit(Edit $request, Question $question)
    {
        return view('exam::pages.questions.edit', [
            'model' => $question,
            'parents' => Question::onlyParent()->get(['id', 'title']),
            'enableVoice' => true,
        ]);
    }

    /**
     * Update a existing resource in storage.
     *
     * @param Update   $request
     * @param Question $question
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, Question $question)
    {
        $question->fill($request->all());
        if (is_array($question->answer)) {
            if (Question::TYPE_REARRANGE == $question->type) {
                $question->answer = array_intersect_key($request->get('answer'), $request->get('options'));
            }
            $question->answer = json_encode($question->answer);
        }
        if ($question->save()) {
            session()->flash('message', 'Question successfully updated');

            return redirect()->route('exam::questions.index');
        } else {
            session()->flash('error', 'Something is wrong while updating Question');
        }

        return redirect()->back();
    }

    /**
     * Delete a  resource from  storage.
     *
     * @param Destroy  $request
     * @param Question $question
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Exception
     */
    public function destroy(Destroy $request, Question $question)
    {
        if ($question->delete()) {
            session()->flash('message', 'Question successfully deleted');
        } else {
            session()->flash('error', 'Error occurred while deleting Question');
        }

        return redirect()->back();
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function select2Ajax(Request $request)
    {
        $questions = Question::query()->search($request->get('term'))->take(10)->get();
        $data = $questions->map(function ($question) {
            return [
                'id' => $question->id,
                'text' => $question->title,
            ];
        })->all();

        return response()->json([
            'results' => $data,
        ]);
    }

}
