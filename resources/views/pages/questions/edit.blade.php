@extends(config('exam.layouts.app'))
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
    @include('exam::pages.questions.scripts')
@endsection
