@extends(config('exam.layouts.app'))
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
    <li class="breadcrumb-item active">
        Edit
    </li>
@endsection
@section('header')
    <i class="fa fa-pencil"></i> {{$model->title}}
@endsection
@section('tools')
    <a class="btn btn-outline-secondary" href="{{route('exam::exams.create')}}">
        <span class="fa fa-plus"></span> Create New Exam
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
    @include('exam::pages.exams.scripts')
@endsection
