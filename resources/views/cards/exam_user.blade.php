<div class="card card-default">
    <div class="card-header">
        <div class="row">
            <div class="col-sm-9">
                @if(isset($showExamTitle))
                    <a href="{{route('exam::exams.show',$examUser->exam->slug)}}">
                        {{$examUser->exam->title or ''}}
                    </a>
                @else
                    <a href="{{route('users.show',$examUser->user_id)}}">
                        {{$examUser->user->name or ''}}
                    </a>
                @endif
            </div>
            <div class="col-sm-3 text-right">
                <div class="btn-group">
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        @if($path=$examUser->getCertificate())
            <img title="Certificate of exam" src="{{asset($path)}}" class="img-fluid">
        @else
            <p>{{$examUser->exam->description or ''}}</p>
        @endif
    </div>
    <div class="card-footer">
        <label class="badge badge-success">{{$examUser->getCorrectionRate()}}% Correct</label>
        <?php $left = $examUser->remaining(); ?>
        @if($left && $examUser->status!==\Exam\Models\Exam::STATUS_COMPLETED)
            <label class="badge badge-warning">{{abs($left)}} left</label>
        @else
            <label class="badge badge-success"><i class="fa fa-check-circle-o"></i> Completed</label>
            @if($duration=$examUser->getDuration())
                <label class="badge badge-secondary">
                    <i class="fa fa-clock-o"> {{$duration}}</i>
                </label>
            @endif
            @can('result',$examUser)
                <a href="{{route('exam::exams.result',$examUser->id)}}">Result</a>
            @endcan
        @endif

        @can('start',$examUser->exam)
            <a class="btn btn-outline-primary btn-sm" href="{{route('exam::exams.start',$examUser->exam->slug)}}">Take</a>
        @endcan
    </div>
</div>
