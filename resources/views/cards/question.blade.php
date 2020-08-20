<div class="card card-default mb-2">
    <div class="card-header">
        <div class="row">
            <div class="col-11">
                <a href="{{route('exam::questions.show',$record->id)}}">
                    <h4> {{$record->title}}</h4>
                </a>
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
        @if($record->type==\Exam\Models\Question::TYPE_IMG_TO_WORD)
            <img src="{{asset($record->getData('media.url'))}}" class="card-img-top" style="max-height: 200px">
        @elseif($record->type==\Exam\Models\Question::TYPE_AUDIO_TO_WORD)
            @if($mp3=$record->getData('media.url'))
                <audio controls class="form-control">
                    <source src="{{$mp3}}" type="audio/mpeg">
                    Your browser does not support the audio element.
                </audio>
            @endif
        @elseif($record->type==\Exam\Models\Question::TYPE_VIDEO_TO_WORD)
            @if($video=$record->getVideoLink())
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" src="{{$video}}" allowfullscreen></iframe>
                </div>
            @endif
        @endif
    </div>
    <div class="card-block">
        <table class="table table-bordered table-striped">
            <tbody>
            @if($record->answer_type!==\Exam\Models\Question::ANSWER_TYPE_WRITE)
                <tr>
                    <th>Options</th>
                    <td>
                        @foreach($record->getOptions() as $key=>$option)
                            @if($record->type==\Exam\Models\Question::TYPE_WORD_TO_IMG)
                                <img src="{{$option}}" width="100px" class="img-thumbnail img-bordered d-inline"/>
                            @elseif($record->type==\Exam\Models\Question::TYPE_REARRANGE)
                                <div>{{$key}} <i class="fa fa-arrow-right text-warning"></i> {{$option}}</div>
                            @else
                                <label class="badge badge-secondary p-1">{{$option}}</label>
                            @endif
                        @endforeach
                    </td>
                </tr>
            @endif
            @if($record->type!==\Exam\Models\Question::TYPE_FREEHAND_WRITING)
                <tr>
                    <th>Answer</th>

                    <td>
                        @if($record->type==\Exam\Models\Question::TYPE_WORD_TO_IMG)
                            <img src="{{$record->answer}}" width="100px" class="img-thumbnail img-bordered d-inline"/>
                        @elseif($record->type==\Exam\Models\Question::TYPE_REARRANGE)
                            @foreach($record->getAnswers() as $key=>$option)
                                <div>{{$key}} <i class="fa fa-arrow-right text-success"></i> {{$option}}</div>
                            @endforeach
                        @else
                            {{$record->answer}}
                        @endif
                    </td>
                </tr>
            @endif
            <tr>
                <th>Explanation</th>
                <td>{{$record->explanation}}</td>
            </tr>


            </tbody>
        </table>
    </div>
    <div class="card-footer text-right">
        <label class="badge badge-secondary" title="Question Type" data-toggle="tooltip">
            {{$record->type}}
        </label>
        <label class="badge badge-secondary" title="Total marks" data-toggle="tooltip">
            {{$record->total_mark}} marks
        </label>

    </div>
</div>
