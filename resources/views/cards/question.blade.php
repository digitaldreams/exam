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
        @if($record->type==\Exam\Enums\QuestionType::IMG_TO_QUESTION)
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
                        @foreach($record->getOptions() as $key=>$option)
                            <label class="badge badge-secondary p-1">{{$option}}</label>
                        @endforeach
                    </td>
                </tr>
            @endif
            @if($record->type!==\Exam\Enums\QuestionType::FREEHAND_WRITING)
                <tr>
                    <th>Answer</th>
                    <td>
                        @if($record->type==\Exam\Enums\QuestionType::QUESTION_TO_IMG)
                            <img src="{{$record->answer}}" width="100px" class="img-thumbnail img-bordered d-inline"/>
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
