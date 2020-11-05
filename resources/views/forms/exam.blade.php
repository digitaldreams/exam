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
                <label for="description">Description
                    <i data-toggle="tooltip" class="fa fa-info-circle"
                       title="{{trans('exam::info.exam.description')}}"></i>
                </label>
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
                <label>Duration in Minutes
                    <i class="fa fa-info-circle" data-toggle="tooltip"
                       title="{{trans('exam::info.exam.duration')}}">
                    </i>
                </label>
                <div class="input-group">
                    <input type="number" name="duration" class="form-control" value="{{$model->duration}}" min="1"
                           max="180"
                           step="1" placeholder="e.g 60">
                    <div class="input-group-text">
                        <i class="fa fa-clock-o"></i> Min
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-sm">
                    <label for="category_id">Category
                        <i class="fa fa-info-circle" data-toggle="tooltip"
                           title="{{trans('exam::info.exam.category')}}"></i>
                    </label>
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
                    <a href="{{route('blog::categories.create')}}" target="_blank">
                        <small class="text-muted">Create a new category</small>
                    </a>
                </div>
                <div class="form-group col-sm">
                    <label for="tags">Tags
                        <i class="fa fa-info-circle" data-toggle="tooltip"
                           title="{{trans('exam::info.exam.tags')}}"></i>
                    </label>
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
                    <small> {{trans('exam::info.exam.tagCreate')}}</small>
                </div>
            </div>

            <div class="form-group">
                <label for="must_completed">Must Complete this exams</label>
                <select name="must_completed[]" class="form-control" id="must_completed" multiple>
                    @foreach($model->mustCompletedExams() as $parentExam)
                        <option value="{{$parentExam->id}}" selected>{{$parentExam->title}}</option>
                    @endforeach
                </select>
                <small class="text-muted">{{trans('exam::info.exam.mustCompleteExams')}} </small>
            </div>
        </div>
        <div class="col-sm-3 bg-light pt-3">
            <h3>Settings</h3>
            <div class="form-group">
                <label>Visibility
                    <i class="fa fa-info-circle" data-toggle="tooltip"
                       title="{{trans('exam::info.exam.visibility')}}"></i>
                </label> <br/>
                <label class="form-check-inline">
                    <input type="radio" name="visibility" value="{{\Exam\Enums\ExamVisibility::PUBLIC}}"
                        {{old('visibility',$model->visibility)==\Exam\Enums\ExamVisibility::PUBLIC?'checked':''}}>
                    Public
                </label>
                <label class="form-check-inline">
                    <input type="radio" name="visibility" value="{{\Exam\Enums\ExamVisibility::PRIVATE}}"
                        {{old('visibility',$model->visibility)==\Exam\Enums\ExamVisibility::PRIVATE?'checked':''}}>
                    Private
                </label>
            </div>
            <div class="form-group">
                <label>Status <i class="fa fa-info-circle" title="{{trans('exam::info.exam.status')}}"
                                 data-toggle="tooltip"></i></label> <br/>
                <div>
                    <label class="form-check-inline">
                        <input type="radio" name="status" value="{{\Exam\Enums\ExamStatus::ACTIVE}}"
                            {{old('status',$model->status)==\Exam\Enums\ExamStatus::ACTIVE?'checked':''}}>
                        Active
                    </label>
                    <label class="form-check-inline">
                        <input type="radio" name="status" value="{{\Exam\Enums\ExamStatus::INACTIVE}}"
                            {{old('status',$model->status)==\Exam\Enums\ExamStatus::INACTIVE?'checked':''}}>
                        Inactive
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label>Show Answer
                    <i class="fa fa-info-circle" data-toggle="tooltip"
                       title="{{trans('exam::info.exam.showAnswer')}}"></i>
                </label>
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
                    <small class="text-muted">
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
