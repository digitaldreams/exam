<form action="{{$route ?? route('exam::feedbacks.store')}}" method="POST">
    {{csrf_field()}}
    <input type="hidden" name="_method" value="{{$method ?? 'POST'}}"/>
    <input type="hidden" name="feedbackable_type" value="{{$feedbackable_type ?? ''}}">
    <input type="hidden" name="feedbackable_id" value="{{$feedbackable_id ?? ''}}">
    <div class="mb-3">
        <label for="title" class="form-label">Feedback</label>
        <input type="text" class="form-control {{ $errors->has('feedback') ? ' is-invalid' : '' }}" name="feedback"
               id="feedback"
               value="{{old('feedback',$model->feedback)}}"
               placeholder="Tell us your experience"
               maxlength="191" required="required">
        @if($errors->has('feedback'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('feedback') }}</strong>
            </div>
        @endif
    </div>
    <div class="row">
        <div class="mb-3 col">
            <label for="rating" class="form-label">Rating</label>
            <div class="input-group">
                <input type="range" class="form-range {{ $errors->has('rating') ? ' is-invalid' : '' }}"
                       name="rating"
                       id="rating"
                       value="{{old('rating',$model->feedback)}}"
                       placeholder="Rate your experience" min="0" step="0.5"
                       max="5"
                       min="0"
                       step="0.5"
                       required="required">
                <div class="input-group-addon">
                    <small id="sliderOutput">0</small>
                </div>
            </div>


            @if($errors->has('rating'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('rating') }}</strong>
                </div>
            @endif
        </div>
        <div class="mb-3 col">
            <br/>
            <input type="reset" class="btn btn-default" value="Clear"/>
            <input type="submit" class="btn btn-primary" value="Save"/>
        </div>
    </div>
</form>
