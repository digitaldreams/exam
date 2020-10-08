@extends(config('exam.layouts.app'))
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('exam::exams.index')}}">Exams</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{route('exam::exams.show',$exam->slug)}}">{{$exam->title}}</a>
    </li>
    <li class="breadcrumb-item active">
        Completed
    </li>
@endsection
@section('header')
    {{$exam->title}}
    <small>{!! $exam->stars() !!}</small>
    @foreach($exam->tags as $tag)
        <small><label class="badge badge-secondary">{{$tag->name}}</label></small>
    @endforeach
@endsection

@section('tools')
    @can('start',$exam)
        <a class="btn btn-outline-primary" href="{{route('exam::exams.start',$exam->slug)}}">Take</a>
    @endcan
    @can('create',\Exam\Models\Exam::class)
        <a class="btn btn-light" href="{{route('exam::exams.create')}}">
            Create <span class="fa fa-plus"></span>
        </a>
    @endcan
    @can('update',$exam)
        <a class="btn btn-light" href="{{route('exam::exams.edit',$exam->slug)}}">
            Edit <span class="fa fa-pencil"></span>
        </a>
    @endcan
    @can('delete',$exam)
        <form onsubmit="return confirm('Are you sure you want to delete?')"
              action="{{route('exam::exams.destroy',$exam->slug)}}"
              method="post"
              style="display: inline">
            {{csrf_field()}}
            {{method_field('DELETE')}}
            <button type="submit" class="btn btn-light cursor-pointer">
                Delete <i class="text-danger fa fa-remove"></i>
            </button>
        </form>
    @endcan

@endsection

@section('content')
    @include('exam::pages.exams.exam_details_tabs')
    @if(count($exam->examUser)>0)
        <h4>Successfully completed</h4>
        @foreach($exam->examUser as $examUser)
            @include('exam::cards.exam_user')
        @endforeach
    @else
        <div class="alert alert-warning">No one taken this exam yet. Be the first</div>
    @endif
@endSection

