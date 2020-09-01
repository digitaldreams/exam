@if($answer)
    @if($answer->status==\Exam\Enums\QuestionReview::PENDING)
        <div class="alert alert-info">Your answer are in review.</div>
    @elseif($answer->isCorrect())
        <div class="alert alert-success">
            <i class="fa fa-check-circle-o"></i>
            Awesome your answer is right
        </div>
    @else
        <div class="alert alert-warning">
            <i class="fa fa-remove"></i>
            Opps your answer is not right. Here is the correct one.<br/>
            @if($answer->question)
                <?php $correctAns = $answer->question->getAnswers() ?>

                @if(is_array($correctAns))
                    <ol class="list-group">
                        @foreach($correctAns as $key=>$value)
                            <li class="list-group-item">{{$key}} <i class="fa fa-arrow-right"></i> {{$value}}</li>
                        @endforeach
                    </ol>
                @else
                    <b> {{$answer->question->answer or ''}}</b>
                @endif
            @endif
        </div>
    @endif
@endif
