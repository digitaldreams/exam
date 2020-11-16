<div class="card card-default mb-2">
    <div class="card-header">
        <div class="row">
            <div class="col-11">

                <h4>

                    <a href="{{route('exam::questions.show',$record->id)}}">#{{$record->id}} {{$record->title}}   </a>
                </h4>

            </div>
            <div class="col-1">
                <div class="btn-group">
                    <div class="dropdown" id="dropdown-{{$record->id}}">
                        <a href="#" class="fa fa-ellipsis-v" data-toggle="dropdown" role="button" aria-expanded="false">
                        </a>
                        <ul class="dropdown-menu">
                            @can('update',$record)
                                <li>
                                    <a class="btn btn-light btn-block"
                                       href="{{route('exam::questions.edit',$record->id)}}">
                                        <i class="fa fa-pencil"></i> Edit
                                    </a>
                                </li>
                            @endcan
                            @can('delete',$record)
                                <li>
                                    <form class="card-link"
                                          onsubmit="return confirm('Are you sure you want to delete?')"
                                          action="{{route('exam::questions.destroy',$record->id)}}" method="post">
                                        {{csrf_field()}}
                                        {{method_field('DELETE')}}
                                        <button class="btn btn-light text-danger btn-block" type="submit">
                                            <i class="fa fa-remove"></i>
                                            Delete
                                        </button>
                                    </form>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <div class="body">
        @if($record->type==\Exam\Enums\QuestionType::IMAGE)
            <img src="{{asset($record->getData('media.url'))}}" class="card-img-top" style="max-height: 200px">
        @elseif($record->type==\Exam\Enums\QuestionType::AUDIO)
            @if($mp3=$record->getData('media.url'))
                <audio controls class="form-control">
                    <source src="{{$mp3}}" type="audio/mpeg">
                    Your browser does not support the audio element.
                </audio>
            @endif
        @elseif($record->type==\Exam\Enums\QuestionType::VIDEO)
            @if($video = $record->getVideoLink())
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" src="{{$video}}" allowfullscreen></iframe>
                </div>
            @endif
        @endif
    </div>
    <div class="card-block">
        <table class="table table-bordered table-striped">
            <tbody>
            @if($record->answer_type!==\Exam\Enums\QuestionAnswerType::WRITE)
                <tr>
                    <th>Options</th>
                    <td>
                        @if($record->answer_type==\Exam\Enums\QuestionAnswerType::IMAGE)
                            @foreach($record->getOptions() as $img)
                                <img src="{{$img}}" width="100px" class="img-thumbnail img-bordered d-inline"/>
                            @endforeach
                        @else
                            @foreach($record->getOptions() as $key=>$option)
                                <label class="badge badge-secondary p-1">{{$option[0]??''}}</label>
                            @endforeach
                        @endif
                    </td>
                </tr>
            @endif
            @if($record->review_type!==\Exam\Enums\QuestionReview::MANUAL)
                <tr>
                    <th>Answer</th>
                    <td>
                        @if($record->answer_type==\Exam\Enums\QuestionAnswerType::IMAGE)
                            @foreach($record->getAnswers() as $img)
                                <img src="{{$img}}" width="100px" class="img-thumbnail img-bordered d-inline"/>
                            @endforeach

                        @else
                            {{implode(",",$record->getAnswers())}}
                        @endif
                    </td>
                </tr>
            @endif

            </tbody>
        </table>
    </div>
    <div class="card-footer text-right">
        <label class="badge badge-info" title="Answer Type" data-toggle="tooltip">
            {{$record->answer_type}}
        </label>
        <label class="badge badge-info" title="Review Type" data-toggle="tooltip">
            {{$record->review_type}}
        </label>
        @if($record->category)
            <a class="text-muted" href="?search={{$record->category->title}}">
                <span class="badge badge-secondary">{{$record->category->title}}</span>
            </a>
        @endif
        @foreach($record->tags as $tag)
            <a href="?search={{$tag->name}}"><span class="badge badge-light">{{$tag->name}}</span></a>
        @endforeach
        <label class="badge badge-secondary" title="Question Type" data-toggle="tooltip">
            {{$record->type}}
        </label>
        <label class="badge badge-secondary" title="Total marks" data-toggle="tooltip">
            {{$record->total_mark}} marks
        </label>

    </div>
</div>
