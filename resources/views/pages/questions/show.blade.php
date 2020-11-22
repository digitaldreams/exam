@extends(config('exam.layouts.app'))
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('exam::questions.index')}}">questions</a>
    </li>
    <li class="breadcrumb-item">
        {{$record->title}}
    </li>
@endsection
@section('header')
    {{$record->title}}
@endsection
@section('tools')
    @can('create',\Exam\Models\Question::class)
        <a class="btn btn-outline-secondary" href="{{route('exam::questions.create')}}">
            <span class="fa fa-plus"></span> <span class="d-none d-sm-inline">Create New Question</span>
        </a>
    @endcan
    @can('update',$record)
        <a class="btn btn-outline-secondary" href="{{route('exam::questions.edit',$record->id)}}">
            <span class="fa fa-pencil"></span>
        </a>
    @endcan
    @can('delete',$record)
        <form onsubmit="return confirm('Are you sure you want to delete?')"
              action="{{route('exam::questions.destroy',$record->id)}}"
              method="post"
              style="display: inline">
            {{csrf_field()}}
            {{method_field('DELETE')}}
            <button type="submit" class="btn btn-outline-danger cursor-pointer">
                <i class="text-danger fa fa-remove"></i>
            </button>
        </form>
    @endcan
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-6">
            @include('exam::cards.question')
        </div>
        <div class="col-sm-6">
            @if($record->answer_type == \Exam\Enums\QuestionAnswerType::FILL_IN_THE_BLANK)
                <h4>Fill In the Blank</h4>
                <p>
                    {!! $record->getData('fill_in_the_blank.summary') !!}
                </p>
            @endif
            @if($record->exams()->count()>0)
                <ul class="list-group">
                    <li class="list-group-item-light"><h3>Used in Exams</h3></li>
                    @foreach($record->exams as $exam)
                        <li class="list-group-item">
                            @can('view',$exam)
                                <a href="{{route('exam::exams.show',$exam->slug)}}">
                                    #{{$exam->id}} {{$exam->title}}</a>
                            @else
                                {{$exam->title}}
                            @endcan
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="alert alert-success">Its a fresh question. Not used in any exam</p>
            @endif
            @if($record->children->count() >0)
                <ul class="list-group">
                    <li class="list-group-item list-group-item-light">
                        <h4>Child Questions</h4>
                    </li>
                    @foreach($record->children as $child)
                        <li class="list-group-item">
                            <a href="{{route('exam::questions.show',$child->id)}}">
                                #{{$child->id}} {{$child->title}}
                                <span class="badge bg-secondary">{{$child->type}}</span> <span
                                    class="badge bg-secondary">{{$child->answer_type}}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @elseif($record->parent)
                <ul class="list-group">
                    <li class="list-group-item list-group-item-light">
                        <h4>Parent Question</h4>
                    </li>
                    <li class="list-group-item">
                        <a href="{{route('exam::questions.show',$record->parent_id)}}">
                            #{{$record->parent->id}} {{$record->parent->title}}
                            <span class="badge bg-secondary">{{$record->parent->type}}</span> <span
                                class="badge bg-secondary">{{$record->parent->answer_type}}</span>
                        </a>
                    </li>
                </ul>
            @endif
            @include('exam::cards.question_preview')
        </div>
    </div>

@endSection
