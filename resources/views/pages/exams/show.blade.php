@extends(config('exam.layouts.app'))
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('exam::exams.index')}}">exams</a>
    </li>
    <li class="breadcrumb-item active">
        {{$exam->title}}
    </li>
@endsection
@section('header')
    {{$exam->title}}
    <small>{!! $exam->stars() !!}</small>
    @foreach($exam->tags as $tag)
        <small><label class="badge badge-secondary">{{$tag->name}}</label></small>
    @endforeach
@endsection

@section('tools')
    @can('start',$exam)
        <a class="btn btn-outline-primary" href="{{route('exam::exams.start',$exam->slug)}}">Take</a>
    @endcan
    @can('create',\Exam\Models\Exam::class)
        <a class="btn btn-light" href="{{route('exam::exams.create')}}">
            Create <span class="fa fa-plus"></span>
        </a>
    @endcan
    @can('update',$exam)
        <a class="btn btn-light" href="{{route('exam::exams.edit',$exam->slug)}}">
            Edit <span class="fa fa-pencil"></span>
        </a>
    @endcan
    @can('delete',$exam)
        <form onsubmit="return confirm('Are you sure you want to delete?')"
              action="{{route('exam::exams.destroy',$exam->slug)}}"
              method="post"
              style="display: inline">
            {{csrf_field()}}
            {{method_field('DELETE')}}
            <button type="submit" class="btn btn-light cursor-pointer">
                Delete <i class="text-danger fa fa-remove"></i>
            </button>
        </form>
    @endcan

@endsection

@section('content')
    @include('exam::pages.exams.exam_details_tabs')
    <div class="row">
        <div class="col-sm-6">
            @can('update',$exam)
                <form action="{{route('exam::exams.questionAdd',$exam->slug)}}" method="post">
                    {{csrf_field()}}
                    <div class="form-group form-row">
                        <select name="questions[]" list="questionKeywords" class="form-control col-11"
                                id="questionSearch"
                                placeholder="Search question" multiple>
                        </select>
                        <button class="btn btn-secondary input-group-append col-1">Add</button>
                        <small class="text-muted">Add question to this exam.</small>
                    </div>
                </form>

                <ol class="list-group">
                    <li class="list-group-item bg-light">Questions</li>
                    @foreach($exam->questions as $question)
                        <li class="list-group-item">
                            <a href="{{route('exam::questions.show',$question->id)}}">
                                #{{$question->id}} => {{$question->title}}
                            </a>
                            <label class="badge badge-secondary badge-pill"><b>{{$question->type}}</b></label>
                            <label class="badge badge-light badge-pill">{{$question->answer_type}}</label>

                            <form
                                onsubmit="return confirm('Are you sure you want to unlink this question from this exam?')"
                                action="{{route('exam::exams.questionRemove',$exam->slug)}}" method="post"
                                class="d-inline">
                                {{csrf_field()}}
                                <input type="hidden" name="questions[]" value="{{$question->id}}">
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fa fa-unlink"></i>
                                </button>

                            </form>
                        </li>
                    @endforeach
                </ol>
            @endcan
        </div>
        <div class="col-sm-6">
            {{$exam->description}}

            @if($exam->getMustCompletedIds())
                <ul class="list-group text-sm my-2">
                    <li class="list-group-item list-group-item-secondary">Must have to completed before taking this
                        exam
                    </li>
                    @foreach($exam->mustCompletedExams() as $parentExam)
                        <li class="list-group-item p-2">
                            <a href="{{route('exam::exams.show',$parentExam->slug)}}">{{$parentExam->title}}</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

@endSection

@section('scripts')
    <script>
        $("#questionSearch").select2({
            placeholder: 'Search questions',
            minimumInputLength: 2,
            ajax: {
                url: '{{route('exam::questions.select2Ajax')}}',
                dataType: 'json'
            }
        });
    </script>
@endsection
