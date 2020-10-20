@extends(config('exam.layouts.app'))
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('exam::exams.index')}}">Exams</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{route('exam::exams.show',$exam->slug)}}">{{$exam->title}}</a>
    </li>
    <li class="breadcrumb-item active">
        Feedback
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
        <a class="btn btn-outline-secondary" href="{{route('exam::exams.create')}}">
            <span class="fa fa-plus"></span>  Create
        </a>
    @endcan
    @can('update',$exam)
        <a class="btn btn-outline-secondary" href="{{route('exam::exams.edit',$exam->slug)}}">
            <span class="fa fa-pencil"></span>  Edit
        </a>
    @endcan
    @can('delete',$exam)
        <form onsubmit="return confirm('Are you sure you want to delete?')"
              action="{{route('exam::exams.destroy',$exam->slug)}}"
              method="post"
              style="display: inline">
            {{csrf_field()}}
            {{method_field('DELETE')}}
            <button type="submit" class="btn btn-outline-danger cursor-pointer">
                Delete <i class="text-danger fa fa-remove"></i>
            </button>
        </form>
    @endcan

@endsection

@section('content')
    @include('exam::pages.exams.exam_details_tabs')

    @foreach($records as $record)


        @foreach($record->pendingAnswers as $answer)
            <form action="{{route('exam::exams.reviews.update',['exam'=>$exam->slug,'answer'=>$answer->id])}}"
                  method="post">
                {{csrf_field()}}
                {{method_field('PUT')}}
                <div class="card">
                    <div class="card-header">
                        {{$answer->question->title}}
                    </div>
                    <div class="card-body">
                        <h4>User Answer</h4>
                        <p class="alert alert-secondary">{!! $answer->getAnswer() !!}</p>

                        <div class="form-group">
                            <label>This answer is </label> <br/>
                            <label class="form-check-inline">
                                <input type="radio" name="status"  required value="{{\Exam\Enums\AnswerStatus::WRONG}}">
                                Wrong
                            </label>
                            <label class="form-check-inline">
                                <input type="radio" name="status" required
                                       value="{{\Exam\Enums\AnswerStatus::PARTIALLY_CORRECT}}">
                                Partially Correct
                            </label>
                            <label class="form-check-inline">
                                <input type="radio" name="status" required value="{{\Exam\Enums\AnswerStatus::CORRECT}}">
                                Correct
                            </label>
                        </div>
                        <div class="form-group">
                            <label>Total mark you want to give.</label>
                            <input class="form-control" type="number" min="0" value="{{$answer->question->total_mark}}"
                                   max="{{$answer->question->total_mark}}" name="obtain_mark" required>
                            <small>Please give a mark between 0 to {{$answer->question->total_mark}} when answer is
                                partially correct. Correct answer will get full mark and wrong answer will get nothing.
                            </small>
                        </div>

                        <div class="form-group">
                            <label>Your Feedback</label>
                            <textarea name="feedback" class="form-control"
                                      placeholder="Write your feedback here."></textarea>
                            <small>Your feedback will help user to understand what's went wrong and how could have been
                                improved.
                            </small>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <input type="submit" class="btn btn-primary" value="Submit">

                    </div>
                </div>
            </form>
        @endforeach
    @endforeach

    {!! $records->render() !!}
@endSection
