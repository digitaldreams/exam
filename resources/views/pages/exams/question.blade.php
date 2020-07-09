@extends('permit::layouts.app')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('exam::exams.index')}}">Exams</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{route('exam::exams.show',$exam->slug)}}">{{$exam->title}}</a>
    </li>
@endsection

@section('tools')
    <p class=" p-2"><span class="badge badge-info">{{$position or 0}}/{{$total or 0}}</span>
        &nbsp;&nbsp;&nbsp;
        @if($exam->hasTimeLimit())
            <label class="badge badge-secondary"><i class="fa fa-clock-o"></i> {{$examUser->timeLeft()}}</label>
        @endif
    </p>
@endsection

@section('content')
    @if($exam->showInstantly())
        @if($answers)
            @foreach($answers as $answer)
                @include('exam::partials.answer')
            @endforeach
        @endif
    @endif
    <form action="{{route('exam::exams.answer',['exam'=>$exam->slug,'question'=>$question->id])}}" method="post">
        {{csrf_field()}}
        <input type="hidden" name="nextId" value="{{$nextId}}"/>
        <input type="hidden" name="timestamp" value="{{$timestamp}}">

        @include('exam::forms.partials.answer')
        @if($question->children)
            @foreach($question->children as $child)
                @include('exam::forms.partials.answer',[
                   'question'=>$child
                ])
            @endforeach
        @endif

        <div class="form-group text-right">
            @if($previousId)
                <a class="btn btn-outline-secondary"
                   href="{{route('exam::exams.question',['exam'=>$exam->slug,'question'=>$previousId])}}">Previous</a>
            @endif
            <input type="submit" class="btn btn-outline-primary" value="Save and Continue">
            @if($nextId)
                <a class="btn btn-outline-secondary"
                   href="{{route('exam::exams.question',['exam'=>$exam->slug,'question'=>$nextId])}}">Skip</a>
            @endif
        </div>
    </form>
    <div class="row">
        <div class="col-sm-12">

        </div>
    </div>
@endSection
@section('scripts')
    <script type="text/javascript">
        function startDictation(btn, instruction, answer) {
            var lang = 'en-GB';
            var recognition = null;

            if (window.hasOwnProperty('webkitSpeechRecognition')) {
                if (!recognition) {
                    recognition = new webkitSpeechRecognition();
                } else {
                    recognition.stop();
                    recognition = null;
                    return false;
                }

                recognition.continuous = false;
                recognition.interimResults = false;

                recognition.lang = lang;
                recognition.start();

                recognition.onresult = function (e) {
                    var lastIndex = parseInt(e.results.length) - parseInt(1);
                    var lastScript = e.results[lastIndex];
                    var text = document.createTextNode(lastScript[0].transcript);
                    if (btn) {
                        answer.innerText = lastScript[0].transcript;
                        if (btn.nodeName == 'INPUT') {
                            btn.value = lastScript[0].transcript;
                        } else {
                            btn.innerText = lastScript[0].transcript;
                        }
                    }
                };
                recognition.onend = function () {
                    instruction.innerText = 'Completed';
                };
                recognition.onsoundstart = function (e) {
                    instruction.innerText = 'Listening...';
                }
                recognition.onerror = function (e) {
                    recognition.stop();
                }

            } else {
                console.log('Opps sorry your browser does not support Speech Recognition')
            }
        }

        $(document).ready(function (e) {

            $("#startListening").on('click', function (e) {
                var node = document.getElementById("hiddenAnswer");
                var instruction = document.getElementById("voiceCommandInstruction");
                var answer = document.getElementById("showSaidAnswer");
                startDictation(node, instruction, answer);
            });
            $("#cleanAnswer").on('click', function (e) {
                var node = document.getElementById("hiddenAnswer");
                var answer = document.getElementById("showSaidAnswer");
                node.value = '';
                answer.innerHTML = '';

            })
        })
    </script>
@endsection