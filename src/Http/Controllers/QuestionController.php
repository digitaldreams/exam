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
use Exam\Repositories\QuestionRepository;
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
     * @var \Exam\Repositories\QuestionRepository
     */
    protected $questionRepository;

    /**
     * QuestionController constructor.
     *
     * @param \Exam\Repositories\QuestionRepository $questionRepository
     */
    public function __construct(QuestionRepository $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Index $request
     *
     * @return \Illuminate\Http\Response
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function index(Index $request)
    {
        if (!empty($request->get('search'))) {
            $questions = $this->questionRepository->search($request->get('search'));
        } else {
            $questions = Question::query()->latest()->paginate(8);
        }

        return view('exam::pages.questions.index', [
            'records' => $questions,
            'keywords' => $this->questionRepository->keywords(),
        ]);
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
        $question = $this->questionRepository->create($request->all());

        return redirect()->route('exam::questions.show', $question->id)->with('message', 'Question saved successfully');
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
        $this->questionRepository->update($request->all(), $question);

        return redirect()->route('exam::questions.show', $question->id)->with('message', 'Question successfully updated');

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
