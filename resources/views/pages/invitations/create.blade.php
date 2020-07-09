@extends('permit::layouts.app')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('exam::exams.invitations.index',$exam->slug)}}">exam_invitations</a>
    </li>
    <li class="breadcrumb-item">
        Create
    </li>
@endsection
@section('content')
    <div class="row">
        <div class='col-md-12'>
            <div class='card bg-white'>
                <div class="card-body">
                    @include('exam::forms.invitation')
                </div>
            </div>
        </div>
    </div>
@endSection