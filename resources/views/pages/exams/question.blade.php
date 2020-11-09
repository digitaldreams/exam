@extends(auth()->check()?config('exam.layouts.app'):config('exam.layouts.frontend'))
@section('css')
    <style>
        body {
            -webkit-user-select: none;
            -webkit-touch-callout: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
    </style>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('exam::exams.index')}}">Exams</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{route('exam::exams.show',$exam->slug)}}">{{$exam->title}}</a>
    </li>
@endsection
@section('header')
    {{$exam->title}}
@endsection
@section('tools')

    <p class=" p-2">
        <label class="badge badge-primary">{{$question->total_mark}} </label> marks

        <span data-toggle="tooltip" title="Total Question remaining" class="badge badge-warning">{{$total ?? 0}} </span>
        &nbsp;&nbsp;&nbsp;
        @if($exam->hasTimeLimit())
            <label class="badge badge-secondary"><i class="fa fa-clock-o"></i> {{$takeExamService->timeLeft()}}</label>
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

@endSection
@section('scripts')
    <script type="text/javascript">
        var changeBrowerTabs = 0;
        document.addEventListener('contextmenu', event => event.preventDefault());
        document.addEventListener("visibilitychange", function () {
            if (document.visibilityState === 'visible') {
                if (changeBrowerTabs >= 1 && changeBrowerTabs < 2) {
                    alert('If you change your tab again your exam will be postponed');
                } else if (changeBrowerTabs >= 2) {
                    window.location.href = "{{route('exam::exams.result',['exam_user'=>$examUser->id])}}"
                }
                changeBrowerTabs += 1;
            } else {
                //     console.log('User go outside');
            }
        });
        var elem = document.documentElement;

    </script>
@endsection
