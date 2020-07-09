<div class="form-row">
    <div class="form-group col-6">
        <label for="options">Options</label>
        <p class="text-muted">Does not need to an Option. Have to write what you listen</p>
    </div>

    <div class="form-group col-6">
        <label for="answer">Answer</label>
        <input type="text" class="form-control {{ $errors->has('answer') ? ' is-invalid' : '' }}" name="answer"
               id="answer" value="{{old('answer',$model->answer)}}" placeholder="Anything written here will be audio"
               maxlength="191"

        >
        <small class="text-muted"></small>
        @if($errors->has('answer'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('answer') }}</strong>
            </div>
        @endif
    </div>
</div>