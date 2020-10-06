<form action="{{$route ?? route('exam::exams.store')}}" method="POST">
    {{csrf_field()}}
    <input type="hidden" name="_method" value="{{$method ?? 'POST'}}"/>

    <div class="row">
        <div class="col-sm-9">
            <div class="form-group">
                <label for="title">Exam name</label>
                <input type="text" class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}" name="title"
                       id="title"
                       value="{{old('title',$model->title)}}" placeholder="e.g. Degree changing." maxlength="191"
                       required="required">
                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('title') }}</strong>
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}"
                          placeholder="Describe what topics will be covered in this exam."
                          name="description" id="description">{{old('description',$model->description)}}</textarea>

                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('description') }}</strong>
                    </div>
                @endif
            </div>
            <div class="form-group ">
                <label>Duration</label>
                <div class="input-group">
                    <input type="number" name="duration" class="form-control" value="{{$model->duration}}" min="1"
                           max="180"
                           step="1" placeholder="e.g 60">
                    <div class="input-group-text">
                        <i class="fa fa-clock-o"></i> Min
                    </div>
                </div>
                <small class="text-muted">Empty duration will make exam unlimited</small>
            </div>

            <div class="form-row">
                <div class="form-group col">
                    <label for="category_id">Category</label>
                    <select class="form-control {{ $errors->has('category_id') ? ' is-invalid' : '' }}"
                            name="category_id" id="category_id">
                        @if($model->category)
                            <option value="{{$model->category_id}}" selected>{{$model->category->title}}</option>
                        @endif
                    </select>
                    @if($errors->has('category_id'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('category_id') }}</strong>
                        </div>
                    @endif
                    <a href="{{route('blog::categories.create')}}" target="_blank"> <small class="text-muted">Create a new category</small></a>
                </div>
                <div class="form-group col">
                    <label for="tags">Tags</label>
                    <select class="form-control {{ $errors->has('tag_id') ? ' is-invalid' : '' }}" name="tags[]"
                            id="tags"
                            multiple>
                        @foreach ($model->tags as $data)
                            <option value="{{$data->id}}" selected>{{$data->name}}</option>
                        @endforeach
                    </select>
                    @if($errors->has('tags.*'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('tags') }}</strong>
                        </div>
                    @endif
                    <small>To create new tag. Just type tag name and add comma(,) at the end of your new tag name or select from dropdown.</small>
                </div>
            </div>

            <div class="form-group">
                <label for="must_completed">Must Complete this exams</label>
                <select name="must_completed[]" class="form-control" id="must_completed" multiple>
                    @foreach($model->mustCompletedExams() as $parentExam)
                        <option value="{{$parentExam->id}}" selected>{{$parentExam->title}}</option>
                    @endforeach
                </select>
                <small class="text-muted">User must completed this exams. Otherwise he is not allowed to participate in
                    this
                    exam
                </small>
            </div>
        </div>
        <div class="col-sm-3 bg-light pt-3">
            <h3>Settings</h3>
            <div class="form-group">
                <label>Visibility</label> <br/>
                <div class="form-check-inline">
                    <label>
                        <input type="radio" name="visibility" value="{{\Exam\Enums\ExamVisibility::PUBLIC}}"
                            {{old('visibility',$model->visibility)==\Exam\Enums\ExamVisibility::PUBLIC?'checked':''}}>
                        Public
                        <small class="text-muted"> (Open for everyone)</small>
                    </label>
                    <label>
                        <input type="radio" name="visibility" value="{{\Exam\Enums\ExamVisibility::PRIVATE}}"
                            {{old('visibility',$model->visibility)==\Exam\Enums\ExamVisibility::PRIVATE?'checked':''}}>
                        Private
                        <small class="text-muted"> Protected and Invitation only</small>
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label>Status</label> <br/>
                <div class="form-check-inline">
                    <label>
                        <input type="radio" name="status" value="{{\Exam\Enums\ExamStatus::ACTIVE}}"
                            {{old('status',$model->status)==\Exam\Enums\ExamStatus::ACTIVE?'checked':''}}>
                        Active
                        <small class="text-muted"> (Ready to take exam)</small>
                    </label>
                    <label>
                        <input type="radio" name="status" value="{{\Exam\Enums\ExamStatus::INACTIVE}}"
                            {{old('status',$model->status)==\Exam\Enums\ExamStatus::INACTIVE?'checked':''}}>
                        Inactive
                        <small class="text-muted"> Only you can see this</small>
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label>Show Answer </label>
                <br/>
                <label class="form-check-inline">
                    <input type="radio" name="show_answer" value="{{\Exam\Enums\ExamShowAnswer::INSTANTLY}}"
                        {{$model->show_answer==\Exam\Enums\ExamShowAnswer::INSTANTLY?'checked':''}}
                    >
                    Instantly
                </label>
                <label class="form-check-inline">
                    <input type="radio" name="show_answer" value="{{\Exam\Enums\ExamShowAnswer::COMPLETED}}"
                        {{$model->show_answer==\Exam\Enums\ExamShowAnswer::COMPLETED?'checked':''}}
                    >
                    When Exam Completed
                </label>
                <p>
                    <small class="text-muted">When instantly selected then answer will be shown on top of next question
                    </small>
                </p>
            </div>
        </div>
    </div>


    <div class="form-group text-right ">
        <input type="reset" class="btn btn-default" value="Clear"/>
        <input type="submit" class="btn btn-primary" value="Save"/>

    </div>
</form>
