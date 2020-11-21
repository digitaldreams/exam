<div class="mb-3 row">

    <div class="col-sm-6">
        <label for="upload-audio-file" class="form-label">Upload Your Audio file.</label>
        <input type="file" name="file" id="upload-audio-file"
               class="form-control @error('file') is-invalid  @enderror"
               onchange="checkSize(8388608,'upload-audio-file','audio')"
               accept="audio/*" {{empty($model->id)?'required':''}}>
        @error('file')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>
<small class="form-text">Upload a audio file. Maximum Upload Size 8MB</small>
<div class="">
    @if($mp3=$model->getData('media.url'))
        <audio controls class="">
            <source src="{{$mp3}}" type="audio/mpeg">
            Your browser does not support the audio element.
        </audio>
    @endif
</div>
