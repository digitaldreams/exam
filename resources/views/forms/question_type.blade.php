<form>
    <input type="hidden" name="q_type" value="{{request('q_type')}}">
    <input type="hidden" name="q_id" value="{{request('q_id')}}">
    <div class="form-group">
        <label>Type of Question you like to create</label>
        <div class="input-group">
            <select name="answer_type" id="answer_type">
                <option value="{{\Exam\Enums\QuestionAnswerType::SINGLE_CHOICE}}">Single Choice</option>
                <option value="{{\Exam\Enums\QuestionAnswerType::MULTIPLE_CHOICE}}">Multiple Choice</option>
                <option value="{{\Exam\Enums\QuestionAnswerType::WRITE}}">Write(User Input)</option>
            </select>

            <select class="form-control" id="type" name="type">
                @foreach(\Exam\Enums\QuestionType::toArray() as $key=>$name)
                    <option value="{{$key}}">{{$name}}</option>
                @endforeach
            </select>
            <div class="input-group-btn"><input class="btn btn-primary" type="submit" value="Go"></div>
        </div>

    </div>

</form>
