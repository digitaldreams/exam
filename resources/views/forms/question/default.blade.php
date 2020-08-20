@if(request('answer_type')!==\Exam\Models\Question::ANSWER_TYPE_WRITE)
    <table class="table table-striped table-hover table-bordered" id="tblOptions">
        <thead>
        <tr>
            <th>Option</th>
            <th>Is Correct Answer?</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <input type="hidden" name="optionNumber" value="1">
                <input type="text" class="form-control option" name="options[option][1]"
                       placeholder="Type your option here">
            </td>
            <td>
                <div class="form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input isCorrect" name="options[isCorrect][1]" type="checkbox"
                               value="yes">
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
                <a title="Add new row" data-toggle="tooltip" href="javascript:void(0)"
                   onclick="addOption()"
                   class="btn btn-outline-primary btn-sm">
                    <i class="fa fa-plus"></i>
                </a>
            </td>
        </tr>
        </tbody>
    </table>
@elseif(request('type')!==\Exam\Models\Question::TYPE_FREEHAND_WRITING)
    <div class="form-group">
        <label>Answer</label>
        <input type="text" name="answer" value="{{$model->answer}}" class="form-control"
               placeholder="Please write the correct answer.">
    </div>
@endif

