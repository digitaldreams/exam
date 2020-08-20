@extends(config('exam.layouts.app'))
@section('styles')
    <link rel="stylesheet" href="{{asset('prototype/css/select2.min.css')}}"/>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('exam::questions.index')}}">questions</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{route('exam::questions.show',$model->id)}}">{{$model->title}}</a>
    </li>
    <li class="breadcrumb-item">
        Edit
    </li>
@endsection

@section('content')
    <div class="row">
        <div class='col-md-12'>
            <div class='card'>
                <div class="card-body">
                    @include('exam::forms.question',[
                    'route'=>route('exam::questions.update',$model->id),
                    'method'=>'PUT'
                    ])
                </div>
            </div>
        </div>
    </div>
@endSection
@section('scripts')
    <script type="text/javascript" src="{{asset('prototype/js/select2.full.min.js')}}"></script>
    @include('exam::pages.questions.scripts')
@endsection
