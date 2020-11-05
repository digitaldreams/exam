@extends(config('exam.layouts.app'))
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('exam::exams.index')}}">Exams</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{route('exam::exams.show',$exam->slug)}}">{{$exam->title}}</a>
    </li>
    <li class="breadcrumb-item">
        questions
    </li>
    <li class="breadcrumb-item">
        {{$record->title}}
    </li>
    <li>Manual Review</li>
@endsection

@section('tools')

@endsection

@section('content')
    <h3> Your Answer</h3>
    <p class="alert alert-secondary">{{is_array($answer->answer)?$answer->answer[0]:$answer->answer}}</p>
    @if($answer->feedback)
        <h3>Teacher's Feedback</h3>
        {{$answer->feedback}}
    @endif
    <hr/>
    <p class="lead">Your answer is {{\Exam\Enums\AnswerStatus::toArray()[$answer->status]}}.
        @if($answer->status!==\Exam\Enums\AnswerStatus::WRONG)
            You got <span class="badge badge-primary">{{$answer->obtain_mark}}</span> out of {{$record->total_mark}}
        @endif
    </p>
@endSection
