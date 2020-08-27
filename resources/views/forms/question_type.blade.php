<form>
    <input type="hidden" name="q_type" value="{{request('q_type')}}">
    <input type="hidden" name="q_id" value="{{request('q_id')}}">
    <div class="form-group">
        <label>Type of Question you like to create</label>
        <div class="input-group">
            <select name="answer_type" id="answer_type">
                @foreach(\Exam\Enums\QuestionAnswerType::toArray() as $value=>$title)
                    <option value="{{$value}}">{{$title}}</option>
                @endforeach
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
