@extends(config('exam.layouts.app'))
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('exam::exams.index')}}">Exams</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{route('exam::exams.show',$exam->slug)}}">{{$exam->title}}</a>
    </li>
    <li class="breadcrumb-item active">
        Feedback
    </li>
@endsection
@section('header')

    <small class="d-none d-sm-inline">{!! $exam->stars() !!}</small>
    {{$exam->title}}
@endsection

@section('tools')
    @can('start',$exam)
        <a class="btn btn-primary " href="{{route('exam::exams.start',$exam->slug)}}">Take</a>
    @endcan
    @can('create',\Exam\Models\Exam::class)
        <a class="btn btn-outline-secondary" href="{{route('exam::exams.create')}}">
            <span class="fa fa-plus"></span> <span class="d-none d-sm-inline">Create</span>
        </a>
    @endcan
    @can('update',$exam)
        <a class="btn btn-outline-secondary" href="{{route('exam::exams.edit',$exam->slug)}}">
            <span class="fa fa-pencil"></span> <span class="d-none d-sm-inline">Edit</span>
        </a>
    @endcan
    @can('delete',$exam)
        <form onsubmit="return confirm('Are you sure you want to delete?')"
              action="{{route('exam::exams.destroy',$exam->slug)}}"
              method="post"
              style="display: inline">
            {{csrf_field()}}
            {{method_field('DELETE')}}
            <button type="submit" class="btn btn-outline-danger cursor-pointer">
                <span class="d-none d-sm-inline"> Delete</span> <i class="text-danger fa fa-remove"></i>
            </button>
        </form>
    @endcan

@endsection

@section('content')
    @include('exam::pages.exams.exam_details_tabs')

    @foreach($records as $record)


        @foreach($record->pendingAnswers as $answer)
            <form action="{{route('exam::exams.reviews.update',['exam'=>$exam->slug,'answer'=>$answer->id])}}"
                  method="post">
                {{csrf_field()}}
                {{method_field('PUT')}}
                <div class="card">
                    <div class="card-header">
                        @if($answer->question->type==\Exam\Enums\QuestionType::IMAGE)
                            <img src="{{asset($answer->question->getData('media.url'))}}" class="img-thumbnail img-fluid"/><br/>
                        @elseif($answer->question->type==\Exam\Enums\QuestionType::AUDIO)
                            @if($mp3=$answer->question->getData('media.url'))
                                <audio controls class="form-control">
                                    <source src="{{$mp3}}" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                            @endif
                        @elseif($answer->question->type==\Exam\Enums\QuestionType::VIDEO)
                            @if($video=$answer->question->getData('media.url'))
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe class="embed-responsive-item" src="{{$answer->question->getVideoLink()}}" allowfullscreen></iframe>
                                </div>
                            @endif
                        @endif
                        {{$answer->question->title}}
                    </div>
                    <div class="card-body">
                        <h4>User Answer</h4>
                        <p class="alert alert-secondary">{!! $answer->getAnswer() !!}</p>

                        <div class="mb-3">
                            <label>This answer is </label> <br/>
                            <label class="form-check-inline">
                                <input type="radio" name="status"  required value="{{\Exam\Enums\AnswerStatus::WRONG}}">
                                Wrong
                            </label>
                            <label class="form-check-inline">
                                <input type="radio" name="status" required
                                       value="{{\Exam\Enums\AnswerStatus::PARTIALLY_CORRECT}}">
                                Partially Correct
                            </label>
                            <label class="form-check-inline">
                                <input type="radio" name="status" required value="{{\Exam\Enums\AnswerStatus::CORRECT}}">
                                Correct
                            </label>
                        </div>
                        <div class="mb-3">
                            <label>Total mark you want to give.</label>
                            <input class="form-control" type="number" min="0" value="{{$answer->question->total_mark}}"
                                   max="{{$answer->question->total_mark}}" name="obtain_mark" required>
                            <small>Please give a mark between 0 to {{$answer->question->total_mark}} when answer is
                                partially correct. Correct answer will get full mark and wrong answer will get nothing.
                            </small>
                        </div>

                        <div class="mb-3">
                            <label>Your Feedback</label>
                            <textarea name="feedback" class="form-control"
                                      placeholder="Write your feedback here."></textarea>
                            <small>Your feedback will help user to understand what's went wrong and how could have been
                                improved.
                            </small>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <input type="submit" class="btn btn-primary" value="Submit">

                    </div>
                </div>
            </form>
        @endforeach
    @endforeach

    {!! $records->render() !!}
@endSection
