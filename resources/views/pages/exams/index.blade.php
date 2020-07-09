@extends(config('exam.layouts.app'))
@section('breadcrumb')
    <li class="breadcrumb-item">
        exams
    </li>
@endsection
@section('header')
    <i class="fa fa-lightbulb text-themecolor"></i>
    Recommended exam for you
@endsection
@section('tools')
    @can('create',\Exam\Models\Exam::class)
        <a class="btn btn-info  m-l-15" href="{{route('exam::exams.create')}}">
            <span class="fa fa-plus"></span> Exam
        </a>
    @endcan
@endsection

@section('content')
    <div class="row exam-carde">
        <div class="col-sm-8">
            <h4 class="header">Recommend Exams</h4>
            <div class="row">
                @foreach($records as $record)
                    <div class="col-sm-6">
                        @include('exam::cards.exam')
                    </div>
                @endforeach
            </div>
        </div>
        <div class="col-sm-4">
            @if(count($pendingExams)>0)
                <h4>Your pending exams</h4>
                @foreach($pendingExams as $examUser)
                    @include('exam::cards.exam_user',['showExamTitle'=>true])
                @endforeach
            @endif
            @if(count($completedExams)>0)
                <h4>Your completed exams</h4>
                @foreach($completedExams as $examUser)
                    @include('exam::cards.exam_user',['showExamTitle'=>true])
                @endforeach
            @endif
        </div>

    </div>
    {!! $records->render() !!}
@endSection
