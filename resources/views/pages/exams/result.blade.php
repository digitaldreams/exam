@extends('permit::layouts.app')
@section('styles')
    <title>{{$exam_user->user->name .'- got '.$correctionRate.'% on '.$exam->title}}</title>
    <meta name="description" content="{{$exam->description}}">
    <meta property="og:title" content="{{$exam_user->user->name .'- got '.$correctionRate.'% on '.$exam->title}}"/>
    <meta property="og:image.url" content="{{asset($certificate->getFileName())}}"/>
    <meta property="og:image.alt" content="{{'Examination certification'}}"/>

@endsection
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('exam::exams.index')}}">Exams</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{route('exam::exams.show',$exam->slug)}}">{{$exam->title}}</a>
    </li>
    <li class="breadcrumb-item">
        Result
    </li>
@endsection

@section('tools')
    &nbsp;&nbsp;
    <div class="btn-group-sm">
        <span class="btn btn-info">{{$correctionRate}}% </span>
        @if($left)
            <span class="btn btn-warning">
        <a href="{{route('exam::exams.start',['exam'=>$exam->slug,'user'=>auth()->id()])}}">
            <i class="fa fa-warning"></i> {{abs($left)}} left</a>
    </span>
        @else
            <btn class="btn btn-success"><i class="fa fa-check-circle"></i> Done</btn>
        @endif
        @can('update',$exam_user)
            &nbsp;&nbsp;
            <btn title=" Your result is {{$exam_user->visibility}}"><label
                        class="btn btn-primary">{{$exam_user->visibility}}</label>
            </btn>
            <?php $setVisibility = $exam_user->visibility == \Exam\Models\ExamUser::VISIBILITY_PUBLIC ? \Exam\Models\ExamUser::VISIBILITY_PRIVATE : \Exam\Models\ExamUser::VISIBILITY_PUBLIC;?>
            <a class="btn btn-warning"
               href="{{route('exam::exams.result.visibility',['exam_user'=>$exam_user,'visibility'=>$setVisibility])}}">Make
                it {{$setVisibility}}</a>

        @endcan
    </div>
@endsection

@section('content')
    @if(auth()->guest())
        <div class="alert alert-secondary">
            <div class="row">
                <div class="col-sm-6">
                    {{$exam->title}}
                </div>
                <div class="col-sm-6">
                    <span class="badge badge-info">{{$correctionRate}}% correction rate</span>
                    @if($left)
                        <span class="badge badge-warning">
                                <a href="#">
                                    <i class="fa fa-warning"></i> {{abs($left)}} left
                                </a>
                        </span>
                    @else
                        <span class="badge badge-success"><i
                                    class="fa fa-check-circle-o"></i> Everything Completed</span>
                    @endif
                </div>
            </div>
        </div>

    @endif
    @if($answers && config('exam.showAnswer',false))
        @foreach($answers as $answer)
            @include('exam::partials.answer')
        @endforeach
    @endif
    @if(!$feedback)
        @can('update',$exam_user)
            <div class="alert alert-secondary">
                @include('exam::forms.feedback',[
            'model'=>new \Exam\Models\Feedback(),
            'feedbackable_type'=>get_class($exam),
            'feedbackable_id'=>$exam->id
            ])
            </div>
        @endcan
    @else
        @include('exam::cards.feedback')
    @endif

    <div class="row">
        @foreach($exam_user->answers as $answer)
            <div class="col-sm-6">
                <div class="card mb-4">
                    <div class="card-header">
                        @if($answer->status==\Exam\Models\Question::REVIEW_PENDING)
                            <i class="fa fa-spinner text-warning" data-toggle="tooltip"
                               title="Your answer are in review "></i>
                        @else
                            <i class="fa {{$answer->isCorrect()?'fa-check-circle-o text-success':'fa-remove text-danger'}}"></i>
                        @endif
                        {{$answer->question->title}}
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <?php $correctAns = $answer->question->getAnswers() ?>
                                @if(is_array($correctAns))
                                    <ol class="list-group" title="Correct answer">
                                        @foreach($correctAns as $key=>$value)
                                            <li class="list-group-item">{{$key}} <i
                                                        class="fa fa-arrow-right"></i> {{$value}}</li>
                                        @endforeach
                                    </ol>
                                @elseif($answer->question->type==\Exam\Models\Question::TYPE_WORD_TO_IMG)
                                    <img title="Correct answer" src="{{$answer->question->answer}}"
                                         class="img-fluid img-thumbnail"/>
                                @else
                                    <b title="Correct answer"> {{$answer->question->answer or ''}}</b>
                                @endif
                            </div>
                            <div class="col-sm-6">
                                @if(is_array($correctAns) && !empty($answer->answer))
                                    <ol class="list-group" title="Your answer">
                                        <?php $userAns = json_decode($answer->answer, true)?>
                                        @if(is_array($userAns))
                                            @foreach($userAns as $word=>$mean)
                                                <li class="list-group-item">{{$word}} <i
                                                            class="fa fa-arrow-right"></i> {{$mean}}</li>
                                            @endforeach
                                        @endif
                                    </ol>
                                @elseif($answer->question->type==\Exam\Models\Question::TYPE_WORD_TO_IMG)
                                    <img title="Your answer" src="{{$answer->answer}}"
                                         class="img-fluid img-thumbnail"/>

                                @else
                                    <b title="Your answer"> {{$answer->answer or ''}}</b>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @if(count($leftQuestions)>0)
        <div class="row">
            <div class="alert alert-warning">You have not answer following question</div>
            @foreach($leftQuestions as $record)
                <div class="col-sm-6">
                    @include('exam::cards.question')
                </div>
            @endforeach
        </div>
    @endif
@endSection
@section('scripts')
    <script>
        var slider = document.getElementById("rating");
        var output = document.getElementById("sliderOutput");
        output.innerHTML = slider.value; // Display the default slider value

        // Update the current slider value (each time you drag the slider handle)
        slider.oninput = function () {
            output.innerHTML = this.value;
        }
    </script>
@endsection