@extends('permit::layouts.app')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('exam::exams.show',$exam->slug)}}">{{$exam->title}}</a>
    </li>
    @can('index',\Exam\Models\Invitation::class)
        <li class="breadcrumb-item">
            <a href="{{route('exam::exams.invitations.index',$exam->slug)}}"> Invitations</a>
        </li>
    @endcan

@endsection


@section('content')
    <div class="row">
        <div class="col-sm-4 offset-md-2 text-right">
            <a class="btn btn-primary btn-block" href="{{route('exam::exams.invitations.response', [
                'exam' => $exam->slug, 'invitation' => $invitation->token
            ])}}">Accept</a>
            <small class="text-center text-muted">You can able to take this exam</small>
        </div>
        <div class="col-sm-4 text-left">
            <a class="btn btn-warning btn-block" href="{{route('exam::exams.invitations.response', [
                'exam' => $exam->slug,
                'invitation' => $invitation->token,
                'status' => Exam\Models\Invitation::STATUS_REJECTED
            ])}}">Reject</a>
            <small class="text-muted">You no longer able to take this exam</small>
        </div>
    </div>

@endSection