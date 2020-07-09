<div class="form-group">
    <?php $options = $model->getOptions(); ?>
    @if(is_array($options))
        @foreach($options as $option)
            <figure class="figure" style="width: 10%;padding-left: 5px">
                <img src="{{$option}}" class="figure-img img-fluid rounded"
                     alt="{{$option}}">
                <figcaption class="figure-caption">{{pathinfo($option,PATHINFO_BASENAME)}}</figcaption>
            </figure>
        @endforeach
    @endif
    <label>Select Images</label>
    <select name="options[]" class="form-control worToImageOptions" multiple id="worToImageOptions">
        @if(is_array($options))
            @foreach($options as $item)
                <option value="{{$item}}" selected>{{pathinfo($item,PATHINFO_BASENAME)}}</option>
            @endforeach
        @endif
    </select>
</div>
<div class="form-row">
    <div class="col-1">
        @if($model->answer)
            <figure class="figure" style="width: 100%;padding-left: 5px">
                <img src="{{$model->answer}}" class="figure-img img-fluid rounded"
                     alt="{{$model->answer}}">
            </figure>
        @endif
    </div>
    <div class="form-group col-11">
        <label>Answer</label>
        <select name="answer" class="form-control worToImageOptions" id="worToImageAnswer" required>
            <option value="{{$model->answer}}" selected>{{pathinfo($model->answer,PATHINFO_BASENAME)}}</option>
        </select>
    </div>
</div>