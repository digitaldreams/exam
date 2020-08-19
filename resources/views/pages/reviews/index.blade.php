@extends(config('exam.layouts.app'))
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('exam::exams.index')}}">Exams</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{route('exam::exams.show',$exam->slug)}}">{{$exam->title}}</a>
    </li>
    <li class="breadcrumb-item active">Reviews</li>
@endsection
@section('tools')
@endsection

@section('content')

    @foreach($records as $record)
        @if(count($record->pendingAnswers)>1)

            <div class="row bg-light m-3">
                <div class="col-sm-12">
                    <h5>{{$record->user->name}} takes {{$record->exam->title}}</h5>
                </div>

                @foreach($record->pendingAnswers as $answer)
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-header">
                                {{$answer->question->title}}
                            </div>
                            <div class="card-body">
                                {!! $answer->answer !!}
                            </div>
                            <div class="card-footer text-center">
                                <a class="btn btn-outline-danger btn-sm"
                                   href="{{route('exam::exams.reviews.update',['exam'=>$exam->slug,'answer'=>$answer->id,'status'=>\Exam\Models\Answer::STATUS_WRONG])}}">
                                    <i class="fa fa-remove"></i>Wrong
                                </a>
                                <a class="btn btn-outline-primary btn-sm"
                                   href="{{route('exam::exams.reviews.update',['exam'=>$exam->slug,'answer'=>$answer->id,'status'=>\Exam\Models\Answer::STATUS_CORRECT])}}">
                                    <i class="fa fa-check-circle-o"></i> Right
                                </a>
                            </div>
                        </div>
                    </div>

                @endforeach
            </div>
        @endif
    @endforeach

    {!! $records->render() !!}
@endSection
