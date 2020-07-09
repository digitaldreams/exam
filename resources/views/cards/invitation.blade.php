<div class="card card-default">

    <div class="card-body">
        <img src="{{$record->user->getAvatarThumb()}}" class="img-thumbnail" width="80px"/>
        <b>{{$record->user->name or ''}}</b>
        {{$record->user->email}}
        {{$record->created_at->diffForHumans()}}
        <br/>

        <span class="badge badge-secondary">{{$record->status}}</span>
        <form onsubmit="return confirm('Are you sure you want to delete?')"
              action="{{route('exam::exams.invitations.destroy',['exam'=>$exam->slug,'invitation'=>$record->token])}}"
              method="post"
              style="display: inline">
            {{csrf_field()}}
            {{method_field('DELETE')}}
            <button type="submit" class="btn btn-default cursor-pointer  btn-sm">
                <i class="text-danger fa fa-remove"></i>
            </button>
        </form>

    </div>
</div>
