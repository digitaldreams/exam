<div class="card">

  <!-- thi is card header -->

  <div class="card-header">
    <div class="row">
      <div class="col-sm-9 col-6 left-header">
        <div class="title">
          <a href="{{route('exam::exams.show',$record->slug)}}">
            {{$record->title}}
          </a>
        </div>        
        @if($record->visibility==\Exam\Models\Exam::VISIBILITY_PRIVATE)
          <div class="status" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="private">
              <i class="ti-world"></i>
          </div>
        @else
          <div class="status" data-toggle="tooltip" title="" data-original-title="public">
              <i class="ti-world"></i>
          </div>
        @endif
      </div>
      <div class="col-sm-3 col-6 text-right right-header">
        <div class="btn-group">          
          <div class="dropdown" id="dropdown-{{$record->id}}">
            <a href="#" class="fa fa-ellipsis-v" data-toggle="dropdown" role="button" aria-expanded="false">
            </a>
            <ul class="dropdown-menu">
              <form action="{{route('blog::activities.store')}}" method="post">
                {{csrf_field()}}
                <input type="hidden" name="activityable_type" value="{{get_class($record)}}">
                <input type="hidden" name="activityable_id" value="{{$record->id }}">
                @foreach(\Blog\Models\Activity::actions() as $key=>$activity)
                  <li>
                    <input class="btn btn-block btn-light" name="type" type="submit" value="{{$key}}" value="{{$activity}}">
                  </li>
                @endforeach
              </form>
              <li class="list-group-item">
                @can('update',$record)
                <a class="" href="{{route('exam::exams.edit',$record->slug)}}">
                  Edit
                </a>
                @endcan
              </li>
              <li class="list-group-item">
                @can('delete',$record)
                <form onsubmit="return confirm('Are you sure you want to delete?')"
                action="{{route('exam::exams.destroy',$record->slug)}}"
                method="post"
                style="display: inline">
                {{csrf_field()}}
                {{method_field('DELETE')}}
                    <button type="submit" class="btn btn-default cursor-pointer  btn-sm">
                      Remove
                    </button>
                  </form>
                @endcan
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- this is card body --> 

  <div class="card-body">
    <p class="card-text">
      {{$record->description}}
    </p>
  </div>

  <!-- this is card footer -->

  <div class="card-footer ">
    <div class="left-footer">
      <div class="left-top">
        <label class="badge badge-secondary">{{$record->users_count}} User</label>
      </div>
      <div class="left-bottom">
        <label class="badge badge-secondary">{{$record->questions_count}} Questions</label>
        <label class="badge badge-secondary">

           <!-- {{$record->feedbacks()->avg('rating')}}         -->
          <i class="fa fa-star text-yellow"></i>
        </label>
        <label class="badge badge-secondary">{{$record->tag->name ?? ''}}</label>
        @if($record->hasTimeLimit())
          <label class="badge badge-secondary"><i class="fa fa-clock-o"></i> {{$record->duration}} min </label>
        @endif
      </div>
    </div>
    <div class="right-footer">
      @can('start',$record)
        <a class="btn btn-outline-primary btn-sm" href="{{route('exam::exams.start',$record->slug)}}">Take</a>
      @endcan
    </div>
  </div>
</div>
