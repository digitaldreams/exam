<form action="{{$route ?? route('exam::exams.store')}}" method="POST">
    {{csrf_field()}}
    <input type="hidden" name="_method" value="{{$method ?? 'POST'}}"/>
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" id="title"
               value="{{old('title',$model->title)}}" placeholder="" maxlength="191" required="required">
        @if($errors->has('title'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('title') }}</strong>
            </div>
        @endif
    </div>

    <div class="form-group">
        <label for="description">Description</label>
        <input type="text" class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}"
               name="description" id="description" value="{{old('description',$model->description)}}" placeholder=""
               maxlength="191">
        @if($errors->has('description'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('description') }}</strong>
            </div>
        @endif
    </div>

    <div class="form-group">
        <label for="tag_id">Questions</label>
        <select class="form-control {{ $errors->has('questions') ? ' is-invalid' : '' }}" name="questions[]"
                id="exam_questions" multiple>
            @foreach ($questions as $data)
                <option value="{{$data->id}}" {{in_array($data->id,$model->questionIds())?'selected':''}}>{{$data->title}}</option>
            @endforeach
        </select>
        @if($errors->has('questions'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('questions') }}</strong>
            </div>
        @endif
    </div>
    <div class="form-group">
        <label>Show Answer </label>
        <br/>
        <label class="form-check-inline">
            <input type="radio" name="show_answer" value="{{\Exam\Models\Exam::SHOW_ANSWER_INSTANTLY}}"
                    {{$model->show_answer==\Exam\Models\Exam::SHOW_ANSWER_INSTANTLY?'checked':''}}
            >
            Instantly
        </label>
        <label class="form-check-inline">
            <input type="radio" name="show_answer" value="{{\Exam\Models\Exam::SHOW_ANSWER_COMPLETED}}"
                    {{$model->show_answer==\Exam\Models\Exam::SHOW_ANSWER_COMPLETED?'checked':''}}
            >
            When Exam Completed
        </label>
        <p>
            <small class="text-muted">When instantly selected then answer will be shown on top of next question</small>
        </p>
    </div>
    <div class="form-row">
        <div class="form-group col">
            <label for="tag_id">Tags</label>
            <select class="form-control {{ $errors->has('tag_id') ? ' is-invalid' : '' }}" name="tags[]" id="tag_id" multiple>
                @if(isset($tags))
                    @foreach ($tags as $data)
                        <option value="{{$data->id}}" {{in_array($data->id,$model->tagIds())?'selected':''}}>{{$data->name}}</option>
                    @endforeach
                @endif

            </select>
            @if($errors->has('tags.*'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('tags') }}</strong>
                </div>
            @endif
        </div>
        <div class="form-group col">
            <label>Duration</label>
            <div class="input-group">
                <input type="number" name="duration" class="form-control" value="{{$model->duration}}" min="1" max="180"
                       step="1" placeholder="e.g 60">
                <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i> Min
                </div>
            </div>
            <small class="text-muted">Empty duraton will make exam unlimited</small>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col">
            <label>Visibility</label> <br/>
            <div class="form-check-inline">
                <label>
                    <input type="radio" name="visibility" value="{{\Exam\Models\Exam::VISIBILITY_PUBLIC}}"
                            {{old('visibility',$model->visibility)==\Exam\Models\Exam::VISIBILITY_PUBLIC?'checked':''}}>
                    Public
                    <small class="text-muted"> (Open for everyone)</small>
                </label>
                <label>
                    <input type="radio" name="visibility" value="{{\Exam\Models\Exam::VISIBILITY_PRIVATE}}"
                            {{old('visibility',$model->visibility)==\Exam\Models\Exam::VISIBILITY_PRIVATE?'checked':''}}>
                    Private
                    <small class="text-muted"> Protected and Invitation only</small>
                </label>
            </div>
        </div>
        <div class="form-group col">
            <label>Status</label> <br/>
            <div class="form-check-inline">
                <label>
                    <input type="radio" name="status" value="{{\Exam\Models\Exam::STATUS_ACTIVE}}"
                            {{old('status',$model->status)==\Exam\Models\Exam::STATUS_ACTIVE?'checked':''}}>
                    Active
                    <small class="text-muted"> (Ready to take exam)</small>
                </label>
                <label>
                    <input type="radio" name="status" value="{{\Exam\Models\Exam::STATUS_INACTIVE}}"
                            {{old('status',$model->status)==\Exam\Models\Exam::STATUS_INACTIVE?'checked':''}}>
                    Inactive
                    <small class="text-muted"> Only you can see this</small>
                </label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="must_completed">Must Complete this exams</label>
        <select name="must_completed[]" class="form-control" id="must_completed" multiple>
            @foreach($exams as $exam)
                <option value="{{$exam->id}}" {{in_array($exam->id,$model->getMustCompletedIds())?'selected':''}}>{{$exam->title}}</option>
            @endforeach
        </select>
        <small class="text-muted">User must completed this exams. Otherwise he is not allowed to participate in this
            exam
        </small>
    </div>


    <div class="form-group text-right ">
        <input type="reset" class="btn btn-default" value="Clear"/>
        <input type="submit" class="btn btn-primary" value="Save"/>

    </div>
</form>
