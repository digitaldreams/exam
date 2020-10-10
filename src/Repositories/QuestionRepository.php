<?php

namespace Exam\Repositories;

use Blog\Repositories\TagRepository;
use Exam\Models\Question;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class QuestionRepository extends Repository
{
    /**
     * @var \Blog\Repositories\TagRepository
     */
    protected $tagRepository;

    /**
     * ExamRepository constructor.
     *
     * @param \Exam\Models\Question            $question
     * @param \Blog\Repositories\TagRepository $tagRepository
     */
    public function __construct(Question $question, TagRepository $tagRepository)
    {
        $this->model = $question;
        $this->tagRepository = $tagRepository;
    }

    /**
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data): Model
    {
        $question = $this->save($data, new Question());
        if ($tags = $data['tags'] ?? []) {
            $question->tags()->sync($this->tagRepository->saveTags($tags));
        }

        return $question;
    }

    /**
     * @param array                               $data
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(array $data, Model $model): Model
    {
        $question = $this->save($data, $model);

        if ($tags = $data['tags'] ?? []) {
            $question->tags()->sync($this->tagRepository->saveTags($tags));
        }

        return $question;
    }

    /**
     * @param array                 $data
     * @param \Exam\Models\Question $question
     *
     * @return \Exam\Models\Question
     */
    protected function save(array $data, Question $question): Question
    {
        $question->fill($data);

        if ($options = $data['options'] ?? []) {
            $question->options = $options['option'];
            $answerIndex = $options['isCorrect'];
            $answers = [];
            foreach ($answerIndex as $index => $value) {
                $answers[] = $question->options[$index];
            }
            $question->answer = $answers;
        } elseif ($answers = $data['answers'] ?? []) {
            $ansArr = [];
            foreach ($answers as $key => $answer) {
                $ansArr[$answer['key']] = $answer['value'];
            }
            $question->answer = $ansArr;
            $question->options = $data['option'] ?? [];
        }
        $question->save();

        return $question;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param                                       $search
     * @param mixed                                 $perPage
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function search($search, $perPage = 8): LengthAwarePaginator
    {
        return Question::query()->selectRaw('questions.*,
             match(questions.title) against ("' . $search . '" IN NATURAL LANGUAGE MODE) as qscore')->distinct()
            ->leftJoin('blog_categories', 'questions.category_id', '=', 'blog_categories.id')
            ->leftJoin('question_tag', 'questions.id', '=', 'question_tag.question_id')
            ->leftJoin('blog_tags', 'question_tag.tag_id', '=', 'blog_tags.id')
            ->where(function ($q) use ($search) {
                $q->orWhereRaw('match(questions.title) against (? IN NATURAL LANGUAGE MODE)', $search)
                    ->orWhereRaw('match(blog_categories.title) against (? IN NATURAL LANGUAGE MODE)', $search)
                    ->orWhereRaw('match(blog_tags.name) against (? IN NATURAL LANGUAGE MODE)', $search);
            })
            ->orderByRaw('qscore desc')
            ->paginate(8);
    }
}
