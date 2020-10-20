<div class="form-row">

    <div class="form-group col-sm-9">
        <label for="">Video source </label>
        <input type="hidden" name="data[media][type]" value="video">
        <input id="mediaVideoUrl" type="url" name="data[media][url]" class="form-control"
               value="{{old('data.media.url',$model->getData('media.url'))}}"
               placeholder="e.g. https://example.com/video.mp4">
        <small>You can copy video url from <a target="_blank" href="https://www.youtube.com">Youtube</a> or
            <a target="_blank" href="https://www.vimeo.com"> Vimeo</a>
        </small>
    </div>
    <div class="col-sm-3">
        @if($video=$model->getData('media.url'))
            <div class="embed-responsive embed-responsive-16by9">
                <iframe class="embed-responsive-item" src="{{$video}}" allowfullscreen></iframe>
            </div>
        @endif
    </div>
</div>
