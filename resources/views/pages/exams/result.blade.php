@extends(config('exam.layouts.app'))
@section('styles')
    <title>{{$exam_user->user->name ?? ''.'- got '.$correctionRate.'% on '.$exam->title}}</title>
    <meta name="description" content="{{$exam->description}}">
    <meta property="og:title" content="{{$exam_user->user->name ??'' .'- got '.$correctionRate.'% on '.$exam->title}}"/>
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
@section('header')
    {{$exam->title}}
@endsection
@section('tools')
    &nbsp;&nbsp;
    <div class="btn-group-sm">
        <span class="btn btn-info">Mark: {{$obtainMark}}/{{$totalMark}} </span>
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
            <span class="btn btn-primary" title=" Your result is {{$exam_user->visibility}}">
                {{$exam_user->visibility}}
            </span>
            <?php $setVisibility = $exam_user->visibility == \Exam\Enums\ExamVisibility::PUBLIC ? \Exam\Enums\ExamVisibility::PRIVATE : \Exam\Enums\ExamVisibility::PUBLIC;?>
            <a class="btn btn-warning"
               href="{{route('exam::exams.result.visibility',['exam_user'=>$exam_user->id,'visibility'=>$setVisibility])}}">
                Make it {{$setVisibility}}
            </a>

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
                    <span class="btn btn-info">Mark: {{$obtainMark}}/{{$totalMark}} </span>
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
        @foreach($exam_user->answers->chunk(2) as $answers)
            <div class="col-md-4 col-12">
                <div class="row">
                    @foreach($answers as $answer)
                        <div class="col-sm-12">
                            <div class="card mb-4" id="{{$answer->id}}">
                                <div class="card-header">
                                    @if($answer->status==\Exam\Enums\QuestionReview::PENDING)
                                        <i class="fa fa-spinner text-warning" data-toggle="tooltip"
                                           title="Your answer are in review "></i> <b class="badge badge-warning">Under
                                            Review</b>
                                    @else
                                        @if($answer->isCorrect())
                                            <i class="fa fa-check-circle-o text-success"> Correct</i>

                                        @else
                                            <i class="fa fa-remove text-danger"> Wrong</i>

                                        @endif
                                    @endif
                                    @if($answer->question->type==\Exam\Enums\QuestionType::IMG_TO_QUESTION)
                                        <img src="{{asset($answer->question->getData('media.url'))}}"
                                             class="card-img-top"
                                             style="max-height: 200px">
                                    @elseif($answer->question->type==\Exam\Enums\QuestionType::AUDIO)
                                        @if($mp3=$answer->question->getData('media.url'))
                                            <audio controls class="form-control">
                                                <source src="{{$mp3}}" type="audio/mpeg">
                                                Your browser does not support the audio element.
                                            </audio>
                                        @endif
                                    @elseif($answer->question->type==\Exam\Enums\QuestionType::VIDEO)
                                        @if($video = $answer->question->getVideoLink())
                                            <div class="embed-responsive embed-responsive-16by9">
                                                <iframe class="embed-responsive-item" src="{{$video}}"
                                                        allowfullscreen></iframe>
                                            </div>
                                        @endif
                                    @endif
                                    #{{$answer->question->id}} {{$answer->question->title}}
                                    <span
                                        class="badge badge-primary badge-pill">{{$answer->obtain_mark}} / {{$answer->question->total_mark}}</span>
                                </div>
                                <div class="card-body">
                                    @if($answer->question->review_type==\Exam\Enums\QuestionReview::MANUAL)
                                        <h5>Your Answer</h5>
                                        <p>{{$answer->getAnswer()}}</p>
                                        @if(!empty($answer->feedback))
                                            <h5>Teacher Feedback</h5>
                                            <p class="alert alert-light">{{$answer->feedback}}</p>
                                        @elseif($answer->status==\Exam\Enums\AnswerStatus::PENDING)
                                            <span class="lead">Pending</span>
                                        @endif
                                    @else
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <h5>Correct Answer</h5>
                                                <?php $correctAns = $answer->question->getAnswers() ?>
                                                @if(is_array($correctAns) )
                                                    @if(count($correctAns)>1)
                                                        <ol class="list-group" title="Correct answer">
                                                            @foreach($correctAns as $key=>$value)
                                                                <li class="list-group-item">{{$key}} <i
                                                                        class="fa fa-arrow-right"></i>
                                                                    @if($answer->question->type==\Exam\Enums\QuestionType::QUESTION_TO_IMG)
                                                                        <img title="Correct answer" src="{{$value}}"
                                                                             class="img-fluid img-thumbnail"/>
                                                                    @else
                                                                        {{$value}}
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ol>
                                                    @else
                                                        @if($answer->question->type==\Exam\Enums\QuestionType::QUESTION_TO_IMG)
                                                            <img title="Correct answer"
                                                                 src="{{array_shift($correctAns)}}"
                                                                 class="img-fluid img-thumbnail"/>
                                                        @else
                                                            {{array_shift($correctAns)}}
                                                        @endif

                                                    @endif
                                                @elseif($answer->question->type==\Exam\Enums\QuestionType::QUESTION_TO_IMG)

                                                @else
                                                    <b title="Correct answer"> {{$answer->question->answer ?? ''}}</b>
                                                @endif
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <h5>Your Answer</h5>
                                                @if($answer->question->type==\Exam\Enums\QuestionType::QUESTION_TO_IMG)
                                                    <img title="Your answer"
                                                         src="{{str_replace(["[","]",'"'],"",$answer->getAnswers()[0]??'')}}"
                                                         class="img-fluid img-thumbnail"/>
                                                @else
                                                    <ol class="list-group" title="Your answer">
                                                        @if(count($answers=$answer->getAnswers())>1)
                                                            @foreach($answers as $key=>$answer)
                                                                <li class="list-group-item"> {{$key}} <i
                                                                        class="fa fa-arrow-right"></i> {{$answer}}</li>
                                                            @endforeach
                                                        @else
                                                            <?php $yourAnswer = $answer->getAnswers() ?>

                                                            {{array_shift($yourAnswer)}}
                                                        @endif
                                                    </ol>

                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                </div>

                            </div>
                        </div>
                    @endforeach
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
