<div class="form-row">
    <div class="form-group col-sm-10">
        <label for="">Image source </label>
        <input type="hidden" name="data[media][type]" value="image">
        <input id="mediaImageUrl" type="url" name="data[media][url]" class="form-control"
               value="{{old('data.media.url',$model->getData('media.url'))}}"
               placeholder="e.g. https://example.com/logo.jpg">
    </div>
    <div class="col-sm-2">
        @if($src=$model->getData('media.url'))
            <img src="{{$src}}" class="img-thumbnail img-fluid d-inline" width="120px">
        @endif
    </div>
</div>