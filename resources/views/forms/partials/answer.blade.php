<div class="alert alert-secondary">
    @if($question->type==\Exam\Enums\QuestionType::IMG_TO_QUESTION)
        <img src="{{asset($question->getData('media.url'))}}" class="img-thumbnail img-fluid"/><br/>
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

<div class="form-group">
    @if(in_array($question->type,\Exam\Enums\QuestionType::generic()))

        @if(in_array($question->answer_type,[\Exam\Enums\QuestionAnswerType::SINGLE_CHOICE,\Exam\Enums\QuestionAnswerType::MULTIPLE_CHOICE]))

            @foreach(array_chunk($question->getOptions(),2) as $chucks)
                <div class="row">

                    @foreach($chucks as $key=>$value)
                        <div class="col-6">
                            <label>
                                @if($question->answer_type==\Exam\Enums\QuestionAnswerType::SINGLE_CHOICE)
                                    <input type="radio" value="{{$value}}" name="answer[{{$question->id}}]">
                                @else
                                    <input type="checkbox" value="{{$value}}" name="answer[{{$question->id}}][]">
                                @endif
                                {{$value}}
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
            <textarea name="answer[{{$question->id}}]" class="form-control" required></textarea>
        @endif
    @elseif($question->type==\Exam\Enums\QuestionType::QUESTION_TO_IMG)
        <div class="row">
            @foreach($question->getOptions() as $key=>$value)
                <div class="col-6 col-sm-3">
                    <label>
                        <img src="{{asset($value)}}" class="img-thumbnail img-fluid">
                        @if($question->answer_type==\Exam\Enums\QuestionAnswerType::SINGLE_CHOICE)
                            <input type="radio" name="answer[{{$question->id}}]" value="{{$value}}">
                        @else
                            <input type="checkbox" value="{{$value}}" name="answer[{{$question->id}}][]">
                        @endif
                    </label>
                </div>
            @endforeach
        </div>

    @elseif($question->type==\Exam\Enums\QuestionType::PRONOUNCE)
        <p>
            <span id="voiceCommandInstruction">Click mic icon to start</span>
            <b id="showSaidAnswer" class="bg-secondary"></b>
            <i id="startListening" class="fa fa-microphone"></i>
            &nbsp;&nbsp;
            <i id="cleanAnswer" class="fa fa-remove"></i>
        </p>
        <input type="hidden" id="hiddenAnswer" name="answer[{{$question->id}}]" value="{{old('answer')}}"
               class="form-control form-control-sm"
               placeholder="e.g. I do">
    @elseif($question->type==\Exam\Enums\QuestionType::VOICE_TO_SENTENCE)
        <p>
                    <span id="textToSpeakCommand"
                          onclick="speakWord('{{$question->answer}}')">Click here to listen</span>
            &nbsp;&nbsp;
        </p>
        <input type="text" id="speak2TextAnswer" name="answer[{{$question->id}}]" value="{{old('answer')}}"
               class="form-control form-control-sm"
               placeholder="e.g. I do">
    @endif
</div>
