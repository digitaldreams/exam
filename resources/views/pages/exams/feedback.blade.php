@extends(config('exam.layouts.app'))
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('exam::exams.index')}}">Exams</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{route('exam::exams.show',$exam->slug)}}">{{$exam->title}}</a>
    </li>
    <li class="breadcrumb-item active">
        Feedback
    </li>
@endsection
@section('header')

    <small class="d-none d-sm-inline">{!! $exam->stars() !!}</small>
    {{$exam->title}}
@endsection

@section('tools')
    @can('start',$exam)
        <a class="btn btn-primary " href="{{route('exam::exams.start',$exam->slug)}}">Take</a>
    @endcan
    @can('create',\Exam\Models\Exam::class)
        <a class="btn btn-outline-secondary" href="{{route('exam::exams.create')}}">
            <span class="fa fa-plus"></span> <span class="d-none d-sm-inline">Create</span>
        </a>
    @endcan
    @can('update',$exam)
        <a class="btn btn-outline-secondary" href="{{route('exam::exams.edit',$exam->slug)}}">
            <span class="fa fa-pencil"></span> <span class="d-none d-sm-inline">Edit</span>
        </a>
    @endcan
    @can('delete',$exam)
        <form onsubmit="return confirm('Are you sure you want to delete?')"
              action="{{route('exam::exams.destroy',$exam->slug)}}"
              method="post"
              style="display: inline">
            {{csrf_field()}}
            {{method_field('DELETE')}}
            <button type="submit" class="btn btn-outline-danger cursor-pointer">
                <span class="d-none d-sm-inline"> Delete</span> <i class="text-danger fa fa-remove"></i>
            </button>
        </form>
    @endcan

@endsection


@section('content')
    @include('exam::pages.exams.exam_details_tabs')

    <ul class="list-unstyled mt-3">
        @foreach($feedbacks as $feedback)
            <li class="media bg-light mb-3">
                <img class="mr-3" src="{{$feedback->user->getAvatarThumb()}}" width="64px"
                     alt="Generic placeholder image">
                <div class="media-body">
                    <div class="media-body">
                        <h5 class="mt-0">{{$feedback->user->name ?? ''}}  <label
                                class="badge badge-secondary badge-pill">{{$feedback->rating}}   {!! $feedback->stars() !!} </label></h5>
                        {{$feedback->feedback}}
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
@endSection

