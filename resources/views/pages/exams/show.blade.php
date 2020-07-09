@extends(config('exam.layouts.app'))
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('exam::exams.index')}}">exams</a>
    </li>
    <li class="breadcrumb-item">
        {{$record->title}}
        @foreach($record->tags as $tag)
            <label class="badge badge-secondary">{{$tag->name}}</label>
        @endforeach
    </li>
@endsection

@section('tools')
    @can('create',\Exam\Models\Exam::class)
        <a href="{{'exam::exams.create'}}">
            <span class="fa fa-plus"></span>
        </a>
    @endcan
    &nbsp;&nbsp;
    @can('update',$record)
        <a href="{{route('exam::exams.edit',$record->slug)}}">
            <span class="fa fa-pencil"></span>
        </a>
    @endcan
    @can('delete',$record)
        <form onsubmit="return confirm('Are you sure you want to delete?')"
              action="{{route('exam::exams.destroy',$record->slug)}}"
              method="post"
              style="display: inline">
            {{csrf_field()}}
            {{method_field('DELETE')}}
            <button type="submit" class="btn btn-default cursor-pointer  btn-sm">
                <i class="text-danger fa fa-remove"></i>
            </button>
        </form>
    @endcan

@endsection

@section('content')
    <div class="alert alert-secondary">
        {{$record->description}}         <label class="badge badge-secondary">{{$record->feedbacks()->avg('rating')}} <i class="fa fa-star text-yellow"></i></label>

    @can('start',$record)
            <a class="btn btn-outline-primary btn-sm" href="{{route('exam::exams.start',$record->slug)}}">Take</a>
        @endcan
    </div>
    @if($record->getMustCompletedIds())
        <ul class="list-group text-sm my-2">
            <li class="list-group-item list-group-item-secondary">Must have to completed before taking this exam</li>
            @foreach($record->mustCompletedExams() as $exam)
                <li class="list-group-item p-2">
                    <a href="{{route('exam::exams.show',$exam->slug)}}">{{$exam->title}}</a>
                </li>
            @endforeach
        </ul>
    @endif
    <div class="row">
        <div class="col-sm-6">
            @can('update',$record)
                <ol class="list-group">
                    <li class="list-group-item bg-light">Questions</li>
                    @foreach($record->questions as $question)
                        <li class="list-group-item">{{$question->title}} <label
                                    class="badge badge-light badge-pill">{{$question->type}}</label></li>
                    @endforeach
                </ol>
            @endcan
            <ul class="list-unstyled mt-3">
                <li class="list-group-item mb-3">Feedbacks</li>
                @foreach($record->feedbacks as $feedback)
                    <li class="media bg-light mb-3">
                        <img class="mr-3" src="{{$feedback->user->getAvatarThumb()}}" width="64px"
                             alt="Generic placeholder image">
                        <div class="media-body">
                            <div class="media-body">
                                <h5 class="mt-0">{{$feedback->user->name or ''}} <label
                                            class="badge badge-secondary badge-pill">{{$feedback->rating}} <i class="fa fa-star text-yellow"></i></label></h5>
                                {{$feedback->feedback}}
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="col-sm-6">
            @if(count($record->examUser)>0)
                <h4>Successfully completed</h4>
                @foreach($record->examUser as $examUser)
                    @include('exam::cards.exam_user')
                @endforeach
            @else
                <div class="alert alert-warning">No one taken this exam yet. Be the first</div>
            @endif
        </div>
    </div>
@endSection
