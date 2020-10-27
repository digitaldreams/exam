<div class="form-group row">

    <div class="col-sm-6">
        <label for="upload-audio-file">Upload Your Audio file.</label>
        <input type="file" name="file" id="upload-audio-file"
               class="form-control-file @error('file') is-invalid  @enderror"
               onchange="checkSize(8388608,'upload-audio-file','audio')"
               accept="audio/*" {{empty($model->id)?'required':''}}>
        @error('file')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>
<small>Upload a audio file. Maximum Upload Size 8MB</small>
<div class="">
    @if($mp3=$model->getData('media.url'))
        <audio controls class="">
            <source src="{{$mp3}}" type="audio/mpeg">
            Your browser does not support the audio element.
        </audio>
    @endif
</div>
