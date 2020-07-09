<div class="form-row">
    <div class="form-group col-9">
        <label for="options">Options</label>
        <select id="question_options" name="options[]" multiple
                class="form-control {{ $errors->has('options') ? ' is-invalid' : '' }}">
            @foreach($model->getOptions() as $key=>$value)
                <option value="{{$value}}" selected>{{$value}}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-3">
        <label for="answer">Answer</label>
        <input type="text" class="form-control {{ $errors->has('answer') ? ' is-invalid' : '' }}" name="answer"
               id="answer" value="{{old('answer',$model->answer)}}" placeholder="" maxlength="191">
        @if($errors->has('answer'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('answer') }}</strong>
            </div>
        @endif
    </div>

</div>