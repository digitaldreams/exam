@extends(config('exam.layouts.app'))
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('exam::exams.index')}}">Exams</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{route('exam::exams.show',$exam->slug)}}">{{$exam->title}}</a>
    </li>
    <li class="breadcrumb-item active">
        Invitations
    </li>
@endsection
@section('header')

    <small class="d-none d-sm-inline">{!! $exam->stars() !!}</small>
    {{$exam->title}}
@endsection

@section('tools')
    <a class="btn btn-outline-secondary" href="{{route('exam::exams.invitations.create',$exam->slug)}}">
        <span class="fa fa-plus"></span> Invite
    </a>
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

@section('tools')

@endsection

@section('content')
    @include('exam::pages.exams.exam_details_tabs')

    <div class="row">
        @foreach($records as $record)
            <div class="col-sm-6">
                @include('exam::cards.invitation')
            </div>
        @endforeach

    </div>
    @include('exam::forms.invitation')
    {!! $records->render() !!}
@endSection
