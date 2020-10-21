<form action="{{$route ?? route('exam::exams.invitations.store',$exam->slug)}}" method="POST">
    {{csrf_field()}}
    <input type="hidden" name="_method" value="{{$method ?? 'POST'}}"/>


    <div class="form-group">
        <label for="user_id">Email</label>

        <select name="user_id" id="user_id" class="form-control" placeholder="Search">

        </select>
        <small class="text-muted">Search from existing users or type a new Email</small>
        @if($errors->has('user_id'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('user_id') }}</strong>
            </div>
        @endif
    </div>


    <div class="form-group text-right ">
        <input type="submit" class="btn btn-primary" value="Send Invitation"/>

    </div>
</form>
