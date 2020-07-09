<form action="{{$route ?? route('exam::questions.store')}}" method="POST">
    {{csrf_field()}}
    <input type="hidden" name="_method" value="{{$method ?? 'POST'}}"/>
    <input type="hidden" name="questionable_type" value="{{$model->questionable_type}}"/>
    <input type="hidden" name="questionable_id" value="{{$model->questionable_id}}"/>
    <div class="form-group">
        <label title="What you are going to ask" for="title">Title</label>
        <textarea class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}" name="title"
                  id="title">{{old('title',$model->title)}}</textarea>

        @if($errors->has('title'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('title') }}</strong>
            </div>
        @endif
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
    <div class="form-row">
        <div class="form-group col">
            <label for="hints">Hints</label>
            <input type="text" class="form-control {{ $errors->has('hints') ? ' is-invalid' : '' }}" name="hints"
                   id="hints"
                   value="{{old('hints',$model->hints)}}" placeholder="" maxlength="191">
            @if($errors->has('hints'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('hints') }}</strong>
                </div>
            @endif
        </div>
        <div class="form-group col">
            <label for="explanation">Explanation</label>
            <input type="text" class="form-control {{ $errors->has('explanation') ? ' is-invalid' : '' }}"
                   name="explanation" id="explanation" value="{{old('explanation',$model->explanation)}}" placeholder=""
                   maxlength="191">
            @if($errors->has('explanation'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('explanation') }}</strong>
                </div>
            @endif
        </div>
    </div>

    <div class="form-group">
        <label for="parent_id">Parent Question</label>
        <select class="form-control" name="parent_id" id="parent_id">
            <option value="">None</option>
            @foreach($parents as $parent)
                <option value="{{$parent->id}}" {{$model->parent_id==$parent->id?'selected':''}}>{{$parent->title}}</option>
            @endforeach
        </select>
    </div>
    <div class="bg-light p-1">
        <div class="form-group">
            <label>Type of Question you like to create</label>
            <div class="input-group">
                <select name="answer_type" id="answer_type">
                    <option value="{{\Exam\Models\Question::ANSWER_SINGLE}}"
                            {{request('answer_type',$model->answer_type)==\Exam\Models\Question::ANSWER_SINGLE?'selected':''}}>
                        Single
                        Choice
                    </option>
                    <option value="{{\Exam\Models\Question::ANSWER_TYPE_MULTIPLE}}" {{request('answer_type',$model->answer_type)==\Exam\Models\Question::ANSWER_TYPE_MULTIPLE?'selected':''}}
                    >Multiple Choice
                    </option>
                    <option value="{{\Exam\Models\Question::ANSWER_TYPE_WRITE}}"
                            {{request('answer_type',$model->answer_type)==\Exam\Models\Question::ANSWER_TYPE_WRITE?'selected':''}}
                    >Write(User Input)
                    </option>
                </select>

                <select class="form-control" id="type" name="type">
                    @foreach(\Exam\Models\Question::types() as $key=>$name)
                        <option value="{{$key}}" {{request('type',$model->type)==$key?'selected':''}}>{{$name}}</option>
                    @endforeach
                </select>

            </div>

        </div>
        <div class="form-group">
            <label>Question Review Type
                <small class="text-muted"> Manual review type will be reviewed by an teacher</small>
            </label> <br/>
            <div class="form-check-inline">
                <label>
                    <input type="radio" name="review_type"
                           value="{{\Exam\Models\Question::REVIEW_TYPE_AUTO}}"
                            {{old('review_type',$model->review_type)==\Exam\Models\Question::REVIEW_TYPE_AUTO?'checked':''}}
                    >
                    Auto
                </label>
            </div>
            <div class="form-check-inline">
                <label>
                    <input type="radio" name="review_type"
                           value="{{\Exam\Models\Question::REVIEW_TYPE_MANUAL}}"
                            {{old('review_type',$model->review_type)==\Exam\Models\Question::REVIEW_TYPE_MANUAL?'checked':''}}
                    >
                    Manual
                </label>
            </div>
        </div>
    </div>
    <div class="form-group text-right ">
        <input type="reset" class="btn btn-default" value="Clear"/>
        <input type="submit" class="btn btn-primary" value="Save"/>
    </div>
</form>
