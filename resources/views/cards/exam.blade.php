<div class="card mb-3">
<?php
if (request('status') == 'completed') {
    $examUser = $record->examUsers()->where('user_id', auth()->id())->first();
}
?>
<!-- thi is card header -->

    <div class="card-header">
        <div class="row">
            <div class="col-sm-10 col-11 left-header">
                <div class="title">
                    @if($record->visibility===\Exam\Enums\ExamVisibility::PRIVATE)
                        <i title="this is a private exam." data-toggle="tooltip" class="fa fa-lock text-danger"></i>
                    @endif
                    @can('update',$record)
                        <a class="link-secondary text-decoration-none" href="{{route('exam::exams.show',$record->slug)}}">
                            #{{$record->id}}  {{$record->title}} {!! $record->stars() !!}
                        </a>
                    @else
                        @if(isset($examUser))
                            <a href="{{route('exam::exams.result',$examUser->id)}}">
                                #{{$record->id}}  {{$record->title}} {!! $record->stars() !!}    </a>
                        @else
                            #{{$record->id}} {{$record->title}} {!! $record->stars() !!}
                        @endif
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
            <div class="col-sm-2 col-1 text-right right-header">
                <div class="btn-group">
                    <div class="dropdown dropleft" id="dropdown-{{$record->id}}">
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
        <p class="text-right m-0 p-0">
        <a class="badge bg-info link-light text-decoration-none" title="Exam Category" href="?search={{$record->category->title}}">
            {{$record->category->title ??''}}
        </a>

        @foreach($record->tags as $tag)
            <a  class="badge bg-secondary text-decoration-none" href="?search={{$tag->name}}">
                {{$tag->name ?? ''}}
            </a>
        @endforeach
        </p>

    </div>

    <!-- this is card footer -->

    <div class="card-footer text-right ">
        @if($record->hasTimeLimit())
            <label title="Exam duration. Must be completed within this time frame. Once exam started can't be paused" data-toggle="tooltip" class="badge bg-secondary">
                <i class="fa fa-clock-o"></i> {{$record->duration}} min
            </label>
        @endif
        &nbsp;<form title="Click here to like this exam"  action="{{route('blog::activities.store')}}" method="post" class="d-inline">
            {{csrf_field()}}
            <input type="hidden" name="activityable_type" value="{{get_class($record)}}">
            <input type="hidden" name="activityable_id" value="{{$record->id }}">
            <input type="hidden" name="type" value="like">
            <button class="btn btn-outline-secondary btn-sm">
                <i class="fa fa-thumbs-up"></i> Like {{$record->likes()->count()}}
            </button>
        </form>

        <form title="click here to mark this as favourite" action="{{route('blog::activities.store')}}" method="post" class="d-inline">
            {{csrf_field()}}
            <input type="hidden" name="activityable_type" value="{{get_class($record)}}">
            <input type="hidden" name="activityable_id" value="{{$record->id }}">
            <input type="hidden" name="type" value="favourite">
            <button  class="btn btn-outline-secondary btn-sm">
                <i class="fa fa-star"></i> Favourite {{$record->favourites()->count()}}
            </button>
        </form>&nbsp;



        @can('update',$record)

            <a title="Invite your friends to take this exam." data-toggle="tooltip" class="btn btn-outline-secondary btn-sm"
               href="{{route('exam::exams.invitations.create',$record->slug)}}">
                <span class="fa fa-envelope"></span> Invite
            </a>
        @endcan
        @can('start',$record)
            <a title="Ready to give this exam?. Lets do it" data-toggle="tooltip" class="btn btn-outline-primary btn-sm"
               href="{{route('exam::exams.start',$record->slug)}}">Take</a>
        @endcan

    </div>
</div>
