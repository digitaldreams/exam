<form class="row">
    <input type="hidden" name="exam_id" value="{{request('exam_id')}}">
    <div class="mb-3 col-md-5">
        <label for="type">Question Type</label>
        <select class="form-control" id="type" name="type">
            @foreach(\Exam\Enums\QuestionType::toArray() as $key=>$name)
                <option value="{{$key}}">{{$name}}</option>
            @endforeach
        </select>
        <small class="form-text">{{trans('exam::info.question.question_type')}}</small>
    </div>
    <div class="mb-3 col-md-5">
        <label for="answer_type">Answer Type</label>
        <select class="form-control" name="answer_type" id="answer_type">
            @foreach(\Exam\Enums\QuestionAnswerType::toArray() as $value=>$title)
                <option value="{{$value}}">{{$title}}</option>
            @endforeach
        </select>
        <small class="form-text">{{trans('exam::info.question.answer_type')}}</small>
    </div>
    <div class="mb-3 col-md-2">
        <label class="form-label">&nbsp;&nbsp;

        </label>
        <input class="btn btn-primary btn-block" type="submit" value="Go">
    </div>

</form>
