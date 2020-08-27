<?php

namespace Exam\Http\Controllers;

use App\Http\Controllers\Controller;
use Exam\Enums\QuestionAnswerType;
use Exam\Enums\QuestionReview;
use Exam\Enums\QuestionType;
use Exam\Http\Requests\Questions\Create;
use Exam\Http\Requests\Questions\Destroy;
use Exam\Http\Requests\Questions\Edit;
use Exam\Http\Requests\Questions\Index;
use Exam\Http\Requests\Questions\Show;
use Exam\Http\Requests\Questions\Store;
use Exam\Http\Requests\Questions\Update;
use Exam\Models\Question;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
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

        if (QuestionType::FREEHAND_WRITING == $request->get('type') || QuestionAnswerType::WRITE == $request->get('answer_type')) {
            $model->review_type = QuestionReview::MANUAL;
        } else {
            $model->review_type = QuestionReview::AUTO;
        }

        return view('exam::pages.questions.create', [
            'model' => $model,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Store $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Store $request): RedirectResponse
    {
        $model = new Question();
        $model->fill($request->except(['options']));
        $options = $request->get('options', []);
        $model->options = $options['option'];
        $answerIndex = $options['isCorrect'];
        $answers = [];
        foreach ($answerIndex as $index => $value) {
            $answers[] = $model->options[$index];
        }
        $model->answer = implode(',', $answers);

        if (is_array($model->answer)) {
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
     * @param Edit     $request
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Update $request, Question $question): RedirectResponse
    {
        $question->fill($request->except(['options']));
        $options = $request->get('options', []);
        $question->options = $options['option'];
        $answerIndex = $options['isCorrect'];
        $answers = [];
        foreach ($answerIndex as $index => $value) {
            $answers[] = $question->options[$index];
        }
        $question->answer = implode(',', $answers);

        if (is_array($question->answer)) {
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
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Exception
     */
    public function destroy(Destroy $request, Question $question): RedirectResponse
    {
        if ($question->delete()) {
            session()->flash('message', 'Question successfully deleted');
        } else {
            session()->flash('error', 'Error occurred while deleting Question');
        }

        return redirect()->route('exam::questions.index');
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function select2Ajax(Request $request): JsonResponse
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
