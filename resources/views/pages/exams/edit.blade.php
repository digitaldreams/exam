@extends('permit::layouts.app')
@section('styles')
    <link rel="stylesheet" href="{{asset('prototype/css/select2.min.css')}}"/>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('exam::exams.index')}}">exams</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{route('exam::exams.show',$model->slug)}}">{{$model->title}}</a>
    </li>
    <li class="breadcrumb-item">
        Edit
    </li>
@endsection

@section('tools')
    <a href="{{route('exam::exams.create')}}">
        <span class="fa fa-plus"></span> exams
    </a>
@endsection

@section('content')
    <div class="row">
        <div class='col-md-12'>
            <div class='card'>
                <div class="card-body">
                    @include('exam::forms.exam',[
                    'route'=>route('exam::exams.update',$model->slug),
                    'method'=>'PUT'
                    ])
                </div>
            </div>
        </div>
    </div>
@endSection
@section('scripts')
    <script type="text/javascript" src="{{asset('prototype/js/select2.full.min.js')}}"></script>
    <script type="text/javascript">
        $('#exam_questions').select2();
        $('#must_completed').select2();
        $('#tag_id').select2();

    </script>
@endsection