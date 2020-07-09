@extends('permit::layouts.app')
@section('breadcrumb')
    <li class="breadcrumb-item">
        exam_invitations
    </li>
@endsection

@section('tools')
    <a href="{{route('exam::exams.invitations.create',$exam->slug)}}">
        <span class="fa fa-plus"></span> Invite
    </a>
@endsection

@section('content')
    <div class="row">
        @foreach($records as $record)
            <div class="col-sm-6">
                @include('exam::cards.invitation')
            </div>
        @endforeach
    </div>
    {!! $records->render() !!}
@endSection