<div class="card">

    <!-- thi is card header -->

    <div class="card-header">
        <div class="row">
            <div class="col-sm-9 col-6 left-header">
                <div class="title">
                    @can('update',$record)
                        <a href="{{route('exam::exams.show',$record->slug)}}">
                            {{$record->title}} {!! $record->stars() !!}
                        </a>
                    @else
                        {{$record->title}} {!! $record->stars() !!}
                    @endcan
                </div>
                @if($record->visibility==\Exam\Enums\ExamVisibility::PRIVATE)
                    <div class="status" data-toggle="tooltip" data-placement="bottom" title=""
                         data-original-title="private">
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
                        <ul class="dropdown-menu list-group-flush">
                            <form action="{{route('blog::activities.store')}}" method="post">
                                {{csrf_field()}}
                                <input type="hidden" name="activityable_type" value="{{get_class($record)}}">
                                <input type="hidden" name="activityable_id" value="{{$record->id }}">
                                @foreach(\Blog\Models\Activity::actions() as $key=>$activity)
                                    <li>
                                        <input class="btn btn-block btn-light" name="type" type="submit"
                                               value="{{$key}}" value="{{$activity}}">
                                    </li>
                                @endforeach
                            </form>
                            @can('update',$record)
                                <li class="list-group-item">

                                    <a class="btn btn-outline-secondary btn-block"
                                       href="{{route('exam::exams.edit',$record->slug)}}">
                                        <i class="fa fa-pencil"></i> Edit
                                    </a>

                                </li>
                            @endcan
                            @can('delete',$record)
                                <li class="list-group-item">
                                    <form onsubmit="return confirm('Are you sure you want to delete?')"
                                          action="{{route('exam::exams.destroy',$record->slug)}}"
                                          method="post"
                                          style="display: inline">
                                        {{csrf_field()}}
                                        {{method_field('DELETE')}}
                                        <button type="submit" class="btn btn-outline-danger btn-block btn-sm">
                                            <i class="fa fa-remove"></i> Remove
                                        </button>
                                    </form>
                                </li>
                            @endcan
                            @can('update',$record)
                            <li class="list-group-item">

                                    <a class="btn btn-outline-secondary btn-block btn-sm"
                                       href="{{route('exam::exams.invitations.create',$record->slug)}}">
                                        <span class="fa fa-envelope"></span> Invite
                                    </a>

                            </li>
                            @endif
                            <li class="list-group-item">
                                &nbsp;<form action="{{route('blog::activities.store')}}" method="post" class="d-inline">
                                    {{csrf_field()}}
                                    <input type="hidden" name="activityable_type" value="{{get_class($record)}}">
                                    <input type="hidden" name="activityable_id" value="{{$record->id }}">
                                    <input type="hidden" name="type" value="like">
                                    <button class="btn badge badge-light">
                                        <i class="fa fa-thumbs-up"></i> Like {{$record->likes()->count()}}
                                    </button>
                                </form>
                            </li>
                            <li class="list-group-item">

                                <form action="{{route('blog::activities.store')}}" method="post" class="d-inline">
                                    {{csrf_field()}}
                                    <input type="hidden" name="activityable_type" value="{{get_class($record)}}">
                                    <input type="hidden" name="activityable_id" value="{{$record->id }}">
                                    <input type="hidden" name="type" value="favourite">
                                    <button class="btn badge badge-light">
                                        <i class="fa fa-star"></i> Favourite {{$record->favourites()->count()}}
                                    </button>
                                </form>&nbsp;
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

    <div class="card-footer text-right ">
        @foreach($record->tags as $tag)
            <a href="?search={{$tag->name}}">
                <label class="badge badge-light">{{$tag->name ?? ''}}</label>
            </a>
        @endforeach
        <a href="?search={{$record->category->title}}">
            <label class="badge badge-secondary">{{$record->category->title ??''}} </label>
        </a>


        @if($record->hasTimeLimit())
            <label class="badge badge-secondary"><i class="fa fa-clock-o"></i> {{$record->duration}} min
            </label>
        @endif
        @can('start',$record)
            <a class="btn btn-outline-primary btn-sm" href="{{route('exam::exams.start',$record->slug)}}">Take</a>
        @endcan
    </div>
</div>
