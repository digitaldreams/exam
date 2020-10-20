<div class="form-row">
    <div class="form-group col-sm-4">
        <label>Upload Image</label>
        <input type="file" name="file" class="form-control-file" accept="image/*" required>
    </div>
    <div class="col-sm-2">
        @if($src=$model->getData('media.url'))
            <img src="{{$src}}" class="img-thumbnail img-fluid d-inline" width="120px">
        @endif
    </div>
</div>
<small>Either you have to give a image url or upload a image.</small>
