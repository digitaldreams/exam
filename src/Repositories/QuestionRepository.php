<?php

namespace Exam\Repositories;

use Blog\Models\Category;
use Blog\Models\Tag;
use Blog\Repositories\TagRepository;
use Exam\Models\Question;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;

class QuestionRepository extends Repository
{

    /**
     * @var \Blog\Repositories\TagRepository
     */
    protected $tagRepository;
    /**
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected $filesystem;

    protected $onlyParent = false;

    /**
     * ExamRepository constructor.
     *
     * @param \Exam\Models\Question             $question
     * @param \Blog\Repositories\TagRepository  $tagRepository
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     */
    public function __construct(Question $question, TagRepository $tagRepository, Filesystem $filesystem)
    {
        $this->model = $question;
        $this->tagRepository = $tagRepository;
        $this->filesystem = $filesystem;
    }

    /**
     * @param array                              $data
     * @param \Illuminate\Http\UploadedFile|null $file
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data, ?UploadedFile $file = null): Model
    {
        if ($file && $file->isValid()) {
            $data['data']['media']['url'] = $this->uploadFile($file);
        }

        $question = $this->save($data, new Question());

        if ($tags = $data['tags'] ?? []) {
            $question->tags()->sync($this->tagRepository->saveTags($tags));
        }

        return $question;
    }

    /**
     * @param \Illuminate\Http\UploadedFile $file
     *
     * @return string
     */
    private function uploadFile(UploadedFile $file)
    {
        return $this->filesystem->url($this->filesystem->putFile('questions', $file, 'public'));
    }

    /**
     * @param array                              $data
     * @param \Exam\Models\Question              $model
     * @param \Illuminate\Http\UploadedFile|null $file
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(array $data, Model $model, ?UploadedFile $file = null): Model
    {
        if ($file && $file->isValid()) {
            $data['data']['media']['url'] = $this->uploadFile($file);
        }
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
     * @param             $search
     * @param string|null $type
     * @param mixed       $perPage
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function search($search, ?string $type, $perPage = 8): LengthAwarePaginator
    {
        $search = $this->fullTextWildcards($search);

        $builder = Question::query()->selectRaw('questions.*,
             match(questions.title) against ("' . $search . '" IN BOOLEAN MODE) as qscore')->distinct()
            ->leftJoin('blog_categories', 'questions.category_id', '=', 'blog_categories.id')
            ->leftJoin('question_tag', 'questions.id', '=', 'question_tag.question_id')
            ->leftJoin('blog_tags', 'question_tag.tag_id', '=', 'blog_tags.id')
            ->where(function ($q) use ($search) {
                $q->orWhereRaw('match(questions.title) against (? IN BOOLEAN MODE)', $search)
                    ->orWhereRaw('match(blog_categories.title) against (? IN BOOLEAN MODE)', $search)
                    ->orWhereRaw('match(blog_tags.name) against (? IN BOOLEAN MODE)', $search);
            });
        if (!empty($type)) {
            $builder = $builder->where('questions.type', $type);
        }

        if ($this->onlyParent) {
            $builder = $builder->whereNull('questions.parent_id');
        }

        return $builder->orderByRaw('qscore desc')
            ->paginate(8);
    }

    /**
     * @return array|\Illuminate\Cache\CacheManager|mixed
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Exception
     */
    public function keywords()
    {
        $key = 'questions_category_tags_keywords';
        if (cache()->has($key)) {
            return cache($key);
        }

        $categories = Category::query()
            ->selectRaw('title as name,(select count(*) from questions where blog_categories.id=category_id ) as total')
            ->havingRaw('total > 0 ')
            ->orderByRaw('total desc')->get()->toArray();

        $tags = Tag::query()
            ->selectRaw('name, (select count(*) from question_tag where question_tag.tag_id=blog_tags.id) as total')
            ->havingRaw('total > 0 ')
            ->orderByRaw('total desc')
            ->get()->toArray();

        $data = array_merge($categories, $tags);

        cache()->put($key, $data, now()->addDay());

        return $data;
    }

    /**
     * @return $this
     */
    public function onlyParent()
    {
        $this->onlyParent = true;

        return $this;
    }

    /**
     * Replaces spaces with full text search wildcards.
     *
     * @param string $term
     * @param string $start
     * @param string $end
     *
     * @return string
     */
    protected function fullTextWildcards($term, $start = '+', $end = '*')
    {
        // removing symbols used by MySQL
        $reservedSymbols = ['-', '+', '"', "'", '<', '>', '@', '(', ')', '~', '*'];
        $term = str_replace($reservedSymbols, '', $term);

        $words = explode(' ', $term);

        foreach ($words as $key => $word) {
            /*
             * applying + operator (required word) only big words
             * because smaller ones are not indexed by mysql
             */
            if (strlen($word) >= 3) {
                $words[$key] = $key + 1 == count($words) ? $word . $end : $start . $word . $end;

            }
        }

        $searchTerm = implode(' ', $words);

        return $searchTerm;
    }
}
