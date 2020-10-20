<?php

namespace Exam\Http\Controllers;

use App\Http\Controllers\Controller;
use Exam\Enums\QuestionAnswerType;
use Exam\Enums\QuestionReview;
use Exam\Enums\QuestionType;
use Exam\Http\Requests\Questions\Store;
use Exam\Http\Requests\Questions\Update;
use Exam\Models\Exam;
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
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Question::class);

        if (!empty($request->get('search'))) {
            $questions = $this->questionRepository->search($request->get('search'), $request->get('type'));
        } else {
            $builder = Question::query();
            if (!empty($request->get('type'))) {
                $builder = $builder->where('type', $request->get('type'));
            }
            $questions = $builder->latest()->paginate(8);
        }

        return view('exam::pages.questions.index', [
            'records' => $questions,
            'keywords' => $this->questionRepository->keywords(),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Question $question
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Question $question)
    {
        $this->authorize('view', $question);

        return view('exam::pages.questions.show', [
            'record' => $question,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(Request $request)
    {
        $this->authorize('create', Question::class);

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
        $question = $this->questionRepository->create($request->all(), $request->file('file'));
        $examId = $request->get('exam_id');

        if (!empty($examId) && $exam = Exam::query()->find($examId)) {
            $question->exams()->sync([$examId]);
            return redirect()->route('exam::exams.show', $exam->slug)->with('message', 'Question created and attached to this exam.');
        }

        return redirect()->route('exam::questions.show', $question->id)->with('message', 'Question saved successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Question $question
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Exception
     */
    public function edit(Question $question)
    {
        $this->authorize('update', Question::class);

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
     * @param Question $question
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Question $question): RedirectResponse
    {
        $this->authorize('delete', $question);

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
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function select2Ajax(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Question::class);

        $questions = $this->questionRepository->search($request->get('term'), null, 10);

        $data = $questions->map(function ($question) {
            return [
                'id' => $question->id,
                'text' => '#' . $question->id . ' ' . $question->title . '(' . $question->type . '-> ' . $question->answer_type . ')',
            ];
        })->all();

        return response()->json([
            'results' => $data,
        ]);
    }
}
