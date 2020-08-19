@extends(config('exam.layouts.app'))
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('exam::exams.show',$exam->slug)}}">{{$exam->title}}</a>
    </li>
    <li class="breadcrumb-item active">
        Invitations
    </li>
@endsection
@section('header')
    Invitations
@endsection
@section('tools')
    <a href="{{route('exam::exams.invitations.create',$exam->slug)}}">
        <span class="fa fa-plus"></span> Invite
    </a>
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
    {!! $records->render() !!}
@endSection
