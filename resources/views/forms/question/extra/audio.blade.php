<div class="form-group row">

    <div class="col-sm-6">
        <label for="upload-audio-file">Upload Your Audio file.</label>
        <input type="file" name="file" id="upload-audio-file" class="form-control-file" accept="audio/*" required>
    </div>

</div>
<small>Either give a audio source url or Upload a audio file.</small>
<div class="">
    @if($mp3=$model->getData('media.url'))
        <audio controls class="">
            <source src="{{$mp3}}" type="audio/mpeg">
            Your browser does not support the audio element.
        </audio>
    @endif
</div>
