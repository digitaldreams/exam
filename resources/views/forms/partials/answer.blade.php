<div class="alert alert-secondary">
    @if($question->type==\Exam\Enums\QuestionType::IMAGE)
        <img src="{{secure_asset($question->getData('media.url'))}}" class="img-thumbnail img-fluid"/><br/>
    @elseif($question->type==\Exam\Enums\QuestionType::AUDIO)
        @if($mp3=$question->getData('media.url'))
            <audio controls class="form-control">
                <source src="{{$mp3}}" type="audio/mpeg">
                Your browser does not support the audio element.
            </audio>
        @endif
    @elseif($question->type==\Exam\Enums\QuestionType::VIDEO)
        @if($video=$question->getData('media.url'))
            <div class="embed-responsive embed-responsive-16by9">
                <iframe class="embed-responsive-item" src="{{$question->getVideoLink()}}" allowfullscreen></iframe>
            </div>
        @endif
    @endif
    {{$question->title}}
</div>

<div class="mb-3">
    @if(in_array($question->answer_type,[\Exam\Enums\QuestionAnswerType::CHOICE,\Exam\Enums\QuestionAnswerType::IMAGE]))

        @foreach(array_chunk($question->getOptions(),2) as $chucks)
            <div class="row">

                @foreach($chucks as $key=>$value)
                    <div class="col-6">
                        <label>
                            @if(count($question->getAnswers())>1)
                                <input type="checkbox" value="{{$value}}" name="answer[{{$question->id}}][]">
                            @else
                                <input type="radio" value="{{$value}}" name="answer[{{$question->id}}]">
                            @endif
                            @if($question->answer_type==\Exam\Enums\QuestionAnswerType::IMAGE)
                                <img src="{{$value}}" width="150" class="img-thumbnail img-fluid">
                            @else
                                {{$value}}
                            @endif
                        </label>
                    </div>
                @endforeach
            </div>
        @endforeach
    @elseif($question->answer_type == \Exam\Enums\QuestionAnswerType::FILL_IN_THE_BLANK)
        <p>
            {!! $question->renderSummaryForm() !!}
        </p>
    @else
        <textarea name="answer[{{$question->id}}]" placeholder="Write Your answer here." class="form-control" required></textarea>
    @endif
</div>
