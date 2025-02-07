<div class="row">

    <div class="mb-3 col-sm-9">
        <label for="" class="form-label">Video source </label>
        <input type="hidden" name="data[media][type]" value="video">
        <input id="mediaVideoUrl" type="url" name="data[media][url]" class="form-control"
               value="{{old('data.media.url',$model->getData('media.url'))}}"
               placeholder="e.g. https://example.com/video.mp4">
        <small class="form-text">You can copy video url from <a target="_blank" href="https://www.youtube.com">Youtube</a> or
            <a target="_blank" href="https://www.vimeo.com"> Vimeo</a>
        </small>
    </div>
    <div class="col-sm-3">
        @if($video=$model->getData('media.url'))
            <div class="embed-responsive embed-responsive-16by9">
                <iframe class="embed-responsive-item" src="{{$model->getVideoLink()}}" allowfullscreen></iframe>
            </div>
        @endif
    </div>
</div>
