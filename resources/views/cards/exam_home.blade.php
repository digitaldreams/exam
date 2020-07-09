<div class="word-details">
    <img src="{{asset('img/exam.png')}}" alt="img">
    <div class="time">
        <i class="fa fa-clock-o" aria-hidden="true"></i>
        <p>{{$exam->created_at->diffForHumans()}}</p>
    </div>
    <div class="bangla">
        <h3>{{$exam->title}}</h3>
    </div>
    <p>{{$exam->description}}</p>
    <p class="read-more"><a href="{{route('exam::exams.show',$exam->id)}}">read more</a></p>
</div>

