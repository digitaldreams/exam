<?php

namespace Exam\Http\Controllers;

use App\Http\Controllers\Controller;
use Exam\Enums\ExamShowAnswer;
use Exam\Enums\ExamStatus;
use Exam\Enums\ExamVisibility;
use Exam\Http\Requests\Exams\Store;
use Exam\Http\Requests\Exams\Update;
use Exam\Models\Exam;
use Exam\Repositories\ExamRepository;
use Exam\Services\ExamSearchService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    /**
     * @var \Exam\Services\ExamSearchService
     */
    protected ExamSearchService $examSearchService;
    /**
     * @var \Exam\Repositories\ExamRepository
     */
    protected $examRepository;

    /**
     * ExamController constructor.
     *
     * @param \Exam\Repositories\ExamRepository $examRepository
     * @param \Exam\Services\ExamSearchService  $examSearchService
     */
    public function __construct(ExamRepository $examRepository, ExamSearchService $examSearchService)
    {
        $this->examSearchService = $examSearchService;
        $this->examRepository = $examRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Exam::class);

        return view('exam::pages.exams.index', [
            'records' => $this->examSearchService->paginateForUser(auth()->user(), $request->get('search'), 6),
        ]);
    }

    /**
     * @param Exam $exam
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Exam $exam)
    {
        $this->authorize('view', $exam);

        return view('exam::pages.exams.show', [
            'exam' => $exam,
        ]);
    }

    /**
     * Create a new Exam.
     *
     * @param \Exam\Http\Requests\Exams\Store $store
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Store $store): RedirectResponse
    {
        $exam = $this->examRepository->create($store->all());

        return redirect()->route('exam::exams.show', $exam->slug)->with('message', 'Exam successfully created');
    }

    /**
     * Show new exam form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Exam::class);
        $model = new Exam();
        $model->status = ExamStatus::ACTIVE;
        $model->visibility = ExamVisibility::PRIVATE;
        $model->show_answer = ExamShowAnswer::COMPLETED;

        return view('exam::pages.exams.create', [
            'model' => $model,
        ]);
    }

    /**
     * Update an Exam.
     *
     * @param \Exam\Http\Requests\Exams\Update $update
     * @param \Exam\Models\Exam                $exam
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Update $update, Exam $exam): RedirectResponse
    {
        $exam = $this->examRepository->update($update->all(), $exam);

        return redirect()->route('exam::exams.show', $exam->slug)->with('message', 'Exam updated successfully');
    }

    /**
     * @param \Exam\Models\Exam $exam
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Exam $exam)
    {
        $this->authorize('update', $exam);

        return view('exam::pages.exams.edit', [
            'model' => $exam,
        ]);
    }

    /**
     * @param \Exam\Models\Exam $exam
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Exception
     */
    public function destroy(Exam $exam): RedirectResponse
    {
        $this->authorize('delete', $exam);

        $this->examRepository->delete($exam);

        return redirect()->route('exam::exams.index')->with('message', 'Exam successfully deleted');
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function select2Ajax(Request $request)
    {
        $exams = Exam::query()->search($request->get('term'))->take(10)->get();
        $data = $exams->map(function ($exam) {
            return [
                'id' => $exam->id,
                'text' => $exam->title,
            ];
        })->all();

        return response()->json([
            'results' => $data,
        ]);
    }
}
