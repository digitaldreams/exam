<div class="row">
    <div class="mb-3 col-sm-4">
        <label class="form-label">Upload Image</label>
        <input type="file" name="file" id="question_image"
               onchange="checkSize(8388608,'question_image','image')"
               class="form-control @error('file') is-invalid  @enderror" accept="image/*" {{empty($model->id)?'required':''}}>
        @error('file')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="col-sm-2">
        @if($src=$model->getData('media.url'))
            <img src="{{$src}}" class="img-thumbnail img-fluid d-inline" id="question_image_preview" width="120px">
        @endif
    </div>
</div>
<small class="form-text">Either you have to give a image url or upload a image. Maximum Upload Size 8MB</small>
