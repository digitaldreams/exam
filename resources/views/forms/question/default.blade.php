@if(in_array(request('answer_type',$model->answer_type),[\Exam\Enums\QuestionAnswerType::SINGLE_CHOICE,\Exam\Enums\QuestionAnswerType::MULTIPLE_CHOICE]))
    <table class="table table-striped table-hover table-bordered" id="tblOptions">
        <thead>
        <tr>
            <th>Option</th>
            <th>Is Correct Answer?</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        @foreach($model->getOptions() as $index => $value)
            <tr>
                <td>
                    <input type="hidden" name="optionNumber" value="{{$index}}">
                    <input type="text" class="form-control option" name="options[option][{{$index}}]" value="{{$value}}"
                           placeholder="Type your option here">
                </td>
                <td>
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input isCorrect" name="options[isCorrect][{{$index}}]"
                                   type="checkbox"
                                   value="yes" {{$model->isCorrectAnswer($value)?'checked':''}}>
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
    <div class="form-group text-center">
        <a href="javascript:void(0)"
           onclick="addOption()"
           class="btn btn-primary btn-sm btn-large">
            <i class="fa fa-plus"></i> Add New Option
        </a>
    </div>
@elseif(request('answer_type',$model->answer_type)==\Exam\Enums\QuestionAnswerType::FILL_IN_THE_BLANK)
    <h4>Fill In the Blank</h4>
    <textarea name="data[fill_in_the_blank][summary]" class="form-control" id="fill_in_the_blank_summary" rows="8">{{$model->getData('fill_in_the_blank.summary')}}</textarea>
    <small class="text-muted">Once upon a time there was a (1)..... She was 12 years (2).....</small>
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
                    <input type="text" class="form-control questionKey" name="answers[{{$key}}][key]" value="{{$key}}" required
                           placeholder="e.g. (1) or (a)">
                    <small class="text-muted">Question Number e.g. <b>(1)</b> or <b>(a)</b></small>
                </td>
                <td>
                    <input type="text" class="form-control option" name="answers[{{$key}}][value]" value="{{$value}}" required
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
                <input type="text" class="form-control questionKey" name="" value=""
                       placeholder="e.g. (1) or (a)">
                <small class="text-muted">Question Number e.g. <b>(1)</b> or <b>(a)</b></small>
            </td>
            <td>
                <input type="text" class="form-control option" name="" value=""
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


@elseif(request('type')!==\Exam\Enums\QuestionType::FREEHAND_WRITING)
    <div class="form-group">
        <label>Answer</label>
        <input type="text" name="answer[]" value="{{implode(",",$model->answer)}}" class="form-control"
               placeholder="Please write the correct answer.">
    </div>
@endif

