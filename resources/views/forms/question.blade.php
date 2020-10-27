<form action="{{$route ?? route('exam::questions.store')}}" method="POST" enctype="multipart/form-data">
    {{csrf_field()}}
    <input type="hidden" name="_method" value="{{$method ?? 'POST'}}"/>
    <input type="hidden" name="exam_id" value="{{request('exam_id')}}">

    <div class="row">
        <div class="col-sm-9">
            <div class="form-group">
                <label title="What you are going to ask" for="title">Question?</label>
                <textarea class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}" name="title"
                          id="title" placeholder="write your question here"
                          required>{{old('title',$model->title)}}</textarea>

                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('title') }}</strong>
                    </div>
                @endif
            </div>
            <div class="form-row">
                <div class="form-group col">
                    <label for="hints">Hints</label>
                    <input type="text" class="form-control {{ $errors->has('hints') ? ' is-invalid' : '' }}"
                           name="hints"
                           id="hints"
                           value="{{old('hints',$model->hints)}}"
                           placeholder="Help user by giving some hints about the possible answer." maxlength="191">
                    @if($errors->has('hints'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('hints') }}</strong>
                        </div>
                    @endif
                    <small class="text-muted">It will helps user to guess the correct answer.</small>
                </div>
                <div class="form-group col">
                    <label for="explanation">Explanation</label>
                    <input type="text" class="form-control {{ $errors->has('explanation') ? ' is-invalid' : '' }}"
                           name="explanation" id="explanation" value="{{old('explanation',$model->explanation)}}"
                           placeholder="Explain why answer is correct."
                           maxlength="191">
                    @if($errors->has('explanation'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('explanation') }}</strong>
                        </div>
                    @endif
                    <small class="text-muted">After submitted the answer it will shown to the user why the correct
                        answer is right.
                    </small>
                </div>
            </div>
            <?php $type = request('type', $model->type);?>
            @if(view()->exists('exam::forms.question.extra.'.$type))
                @include('exam::forms.question.extra.'.$type)
            @endif

            @if(view()->exists('exam::forms.question.'.$type))
                @include('exam::forms.question.'.$type)
            @else
                @include('exam::forms.question.default')
            @endif

            @if($errors->has('options'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('options') }}</strong>
                </div>
            @endif


        </div>
        <div class="col-sm-3">
            <div class="bg-light p-1">
                <div class="form-group">
                    <label for="parent_id">Total Mark</label>
                    <input type="number" class="form-control" name="total_mark"
                           value="{{old('total_mark',$model->total_mark)}}"
                           placeholder="Total mark of this question e.g. 5"
                           required
                           step="1" min="1"
                           max="99">
                </div>
                <div class="form-group">
                    <label for="parent_id">Parent Question</label>
                    <select class="form-control" name="parent_id" id="parentQuestionSearch">
                        @if($model->parent)
                            <option value="{{$model->parent->id}}" selected>{{$model->parent->title}}</option>
                        @endif

                    </select>
                </div>

                <div class="form-group">
                    <label>Type of Question you like to create</label>
                    <div class="input-group">
                        <select name="answer_type" id="answer_type" required>
                            @foreach(\Exam\Enums\QuestionAnswerType::toArray() as $value => $title)
                                <option value="{{$value}}"
                                    {{request('answer_type',$model->answer_type)==$value?'selected':''}}>
                                    {{$title}}
                                </option>
                            @endforeach
                        </select>

                        <select class="form-control" id="type" name="type" required>
                            @foreach(\Exam\Enums\QuestionType::toArray() as $key=>$name)
                                <option
                                    value="{{$key}}" {{request('type',$model->type)==$key?'selected':''}}>{{$name}}</option>
                            @endforeach
                        </select>

                    </div>

                </div>
                <div class="form-group">
                    <label>Question Review Type

                    </label> <br/>
                    <div class="form-check-inline">
                        <label>
                            <input type="radio" name="review_type" required
                                   value="{{\Exam\Enums\QuestionReview::AUTO}}"
                                {{old('review_type',$model->review_type)==\Exam\Enums\QuestionReview::AUTO?'checked':''}}
                            >
                            Auto
                        </label>
                    </div>
                    <div class="form-check-inline">
                        <label>
                            <input type="radio" name="review_type" required
                                   value="{{\Exam\Enums\QuestionReview::MANUAL}}"
                                {{old('review_type',$model->review_type)==\Exam\Enums\QuestionReview::MANUAL?'checked':''}}
                            >
                            Manual
                        </label>
                    </div>
                    <br/>
                    <small class="text-muted"> Manual review type will be reviewed by an teacher</small>
                </div>
                <div class="form-group">
                    <label for="category_id">Category</label>
                    <select class="form-control {{ $errors->has('category_id') ? ' is-invalid' : '' }}"
                            name="category_id" id="category_id" required>
                        @if($model->category)
                            <option value="{{$model->category_id}}" selected>{{$model->category->title}}</option>
                        @endif
                    </select>
                    @if($errors->has('category_id'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('category_id') }}</strong>
                        </div>
                    @endif
                    <a href="{{route('blog::categories.create')}}" target="_blank">
                        <small class="text-muted">Create a new category</small>
                    </a>
                </div>
                <div class="form-group">
                    <label for="tags">Tags</label>
                    <select class="form-control {{ $errors->has('tag_id') ? ' is-invalid' : '' }}" name="tags[]"
                            id="tags"
                            multiple>
                        @foreach ($model->tags as $data)
                            <option value="{{$data->name}}" selected>{{$data->name}}</option>
                        @endforeach
                    </select>
                    @if($errors->has('tags.*'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('tags') }}</strong>
                        </div>
                    @endif
                    <small>To create new tag. Just type tag name and add comma(,) at the end of your new tag name or
                        select from dropdown.
                    </small>
                </div>
            </div>
        </div>
    </div>


    <div class="form-group text-right ">
        <input type="reset" class="btn btn-default" value="Clear"/>
        <input type="submit" class="btn btn-primary" value="Save"/>
    </div>
</form>
