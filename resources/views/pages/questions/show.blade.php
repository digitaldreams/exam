@extends(config('exam.layouts.app'))
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('exam::questions.index')}}">questions</a>
    </li>
    <li class="breadcrumb-item">
        {{$record->title}}
    </li>
@endsection
@section('header')
    {{$record->title}}
@endsection
@section('tools')
    @can('create',\Exam\Models\Question::class)
        <a class="btn btn-outline-secondary" href="{{route('exam::questions.create')}}">
            <span class="fa fa-plus"></span> Create New Question
        </a>
    @endcan
    @can('update',$record)
        <a href="{{route('exam::questions.edit',$record->id)}}">
            <span class="fa fa-pencil"></span>
        </a>
    @endcan
    @can('delete',$record)
        <form onsubmit="return confirm('Are you sure you want to delete?')"
              action="{{route('exam::questions.destroy',$record->id)}}"
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
    <div class="row">
        <div class="col-sm-6">
            @include('exam::cards.question')
        </div>
    </div>
@endSection
