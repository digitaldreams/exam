@if(in_array(request('answer_type',$model->answer_type),[\Exam\Enums\QuestionAnswerType::CHOICE,\Exam\Enums\QuestionAnswerType::IMAGE]))
    <table class="table table-striped table-hover table-bordered" id="tblOptions">
        <thead>
        <tr>
            <th class="@error('options.option') border border-danger @enderror">Options<br/>
                <small class="text-muted"> All of the below are required.
                </small>
                @error('options.option')
                <div class="alert alert-danger m-0 p-1">One or more options must be required.</div>
                @enderror
            </th>
            <th class="@error('options.isCorrect') border border-danger @enderror">Is Correct Answer?<br/>
                <small class="text-muted">Must be check at least one</small>
                @error('options.isCorrect')
                <div class="alert alert-danger m-0 p-1">You must select one or more as correct answer</div>
                @enderror
            </th>
            <th>&nbsp; <i data-toggle="tooltip" class="fa fa-info-circle" title="Delete any options just clicking"></i>
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach(old("options.option",$model->getOptions()) as $index => $value)
            <tr>
                <td>
                    <input type="hidden" name="optionNumber" value="{{$index}}">
                    @if(request('answer_type',$model->answer_type)==\Exam\Enums\QuestionAnswerType::IMAGE)
                        <input type="url" class="form-control option" name="options[option][{{$index}}]"
                               value="{{$value}}"
                               required
                               placeholder="e.g. https://example.com/images/default.png">
                    @else
                        <input type="text" class="form-control option" name="options[option][{{$index}}]"
                               value="{{$value}}"
                               required
                               placeholder="Type your option here">
                    @endif

                </td>
                <td>
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input isCorrect" name="options[isCorrect][{{$index}}]"
                                   type="checkbox"
                                   value="yes" {{$model->isCorrectAnswer($value)?'checked':''}} >
                            Yes
                        </label>
                    </div>
                </td>
                <td>
                    <a title="Remove this row" data-toggle="tooltip" href="javascript:void(0)"
                       onclick="removeOption($(this))"

                       class="btn btn-outline-danger btn-sm">
                        <i class="fa fa-remove"></i>
                    </a>

                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @if(request('answer_type',$model->answer_type)==\Exam\Enums\QuestionAnswerType::IMAGE)
        <p>See <a href="{{route('photo::photos.index')}}" target="_blank"> list of photos here</a> or <a href="{{route('photo::photos.create')}}" target="_blank">Upload </a> </p>
    @endif

    <div class="form-group text-center">
        <a href="javascript:void(0)"
           onclick="addOption()"
           class="btn btn-primary btn-sm btn-large">
            <i class="fa fa-plus"></i> Add New Option
        </a>
    </div>

@elseif(request('answer_type',$model->answer_type)==\Exam\Enums\QuestionAnswerType::FILL_IN_THE_BLANK)
    <h4>Fill In the Blank</h4>
    <textarea name="data[fill_in_the_blank][summary]" class="form-control" id="fill_in_the_blank_summary"
              rows="8"
              required>{{old('data.fill_in_the_blank.summary',$model->getData('fill_in_the_blank.summary'))}}</textarea>
    <small class="text-muted">Once upon a time there was a (1)___ She was 12 years (2)___</small>
    @error('data.fill_in_the_blank.summary')
    <div class="alert alert-danger">{{$message}}</div>
    @enderror
    <h4>Answers</h4>
    <table class="table table-striped table-hover table-bordered" id="fillInTheBlankAnswerTable">
        <thead>
        <tr>
            <th>Question Number/Serial</th>
            <th>Correct Answer</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody id="fill-in-the-blank-body">
        @foreach($model->getAnswers() as $key=> $value)
            <tr class="answerTr" id="{{$key}}">
                <td>
                    <input type="text" class="form-control questionKey" name="answers[{{$key}}][key]" value="{{$key}}"
                           required
                           placeholder="e.g. (1) or (a)">
                    <small class="text-muted">Question Number e.g. <b>(1)</b> or <b>(a)</b></small>
                </td>
                <td>
                    <input type="text" class="form-control option" name="answers[{{$key}}][value]" value="{{$value}}"
                           required
                           placeholder="e.g. queen">
                    <small class="text-muted">Type the correct answer here.</small>
                </td>
                <td>
                    <a title="Remove this row" data-toggle="tooltip" href="javascript:void(0)"
                       onclick="removeOption($(this))"

                       class="btn btn-outline-danger btn-sm">
                        <i class="fa fa-remove"></i>
                    </a>
                </td>
            </tr>
        @endforeach
        <tr class="answerTr" id="">
            <td>
                <input type="text" class="form-control questionKey" name="" value="" required
                       placeholder="e.g. (1) or (a)">
                <small class="text-muted">Question Number e.g. <b>(1)</b> or <b>(a)</b></small>
            </td>
            <td>
                <input type="text" class="form-control option" name="" value="" required
                       placeholder="e.g. queen">
                <small class="text-muted">Type the correct answer here.</small>
            </td>
            <td>
                <a title="Remove this row" data-toggle="tooltip" href="javascript:void(0)"
                   onclick="removeOption($(this))"

                   class="btn btn-outline-danger btn-sm">
                    <i class="fa fa-remove"></i>
                </a>
            </td>
        </tr>
        </tbody>
    </table>
    @error('answers','answers.*.key','answers.*.value')
    <div class="alert alert-danger">{{$message}}</div>
    @enderror

@elseif(request('answer_type',$model->answer_type)==\Exam\Enums\QuestionAnswerType::WRITE)
    <div class="mb-3">
        <label>Answer</label>
        <input type="text" name="answer[]" value="{{$model->getAnswers()[0]??''}}"
               class="form-control @error('answer.0') is-invalid @enderror"
               placeholder="Please write the correct answer.">
        <small class="text-muted">
            Please provide correct answer if you Question Review Type is <b>Auto</b>. Leave it blank for <b>Manual</b>
        </small>
        @error('answer.0')
        <div class="invalid-feedback">{{$message}}</div>
        @enderror
    </div>
@endif

