@extends(config('exam.layouts.app'))
@section('breadcrumb')
    <li class="breadcrumb-item">
        exams
    </li>
@endsection
@section('header')
    <i class="fa fa-lightbulb text-themecolor"></i>
    Exams
@endsection
@section('tools')
    @can('create',\Exam\Models\Exam::class)
        <a class="btn btn-info  m-l-15" href="{{route('exam::exams.create')}}">
            <span class="fa fa-plus"></span> Exam
        </a>
    @endcan
@endsection

@section('content')
    <div class="row exam-card">
        @foreach($records as $record)
            <div class="col-sm-6 col-md-4">
                @include('exam::cards.exam')
            </div>
        @endforeach
    </div>
    {!! $records->render() !!}
@endSection
