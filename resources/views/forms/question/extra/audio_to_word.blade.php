
    <div class="form-group">
        <label for="">Audio source </label>
        <input type="hidden" name="data[media][type]" value="audio">
        <input id="mediaAudioUrl" type="url" name="data[media][url]" class="form-control"
               value="{{old('data.media.url',$model->getData('media.url'))}}"
               placeholder="e.g. https://example.com/audio.mp3">
    </div>
    <div class="">
        @if($mp3=$model->getData('media.url'))
            <audio controls class="">
                <source src="{{$mp3}}" type="audio/mpeg">
                Your browser does not support the audio element.
            </audio>
        @endif
    </div>
