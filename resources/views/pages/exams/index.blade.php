@extends(config('exam.layouts.app'))
@section('breadcrumb')
    <li class="breadcrumb-item">
        exams
    </li>
@endsection
@section('header')
    <i class="fa fa-lightbulb text-themecolor"></i>
    Exams
@endsection
@section('tools')
    @if($pendingExams  >0)
        <a  class="btn btn-warning" href="?status=pending">Pending <span class="badge badge-danger badge-pill">{{$pendingExams}}</span></a>
    @endif
    @if($completedExams  >0)
        <a class="btn btn-light" href="?status=completed">Completed <span class="badge badge-primary badge-pill">{{$completedExams}}</span></a>
    @endif
    @can('create',\Exam\Models\Exam::class)
        <a class="btn btn-info  m-l-15" href="{{route('exam::exams.create')}}">
            <span class="fa fa-plus"></span> Exam
        </a>
    @endcan

@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <form>
                <div class="input-group mb-3">
                    <input type="search" name="search" value="{{request('search')}}" class="form-control" placeholder="Search Exams"
                           aria-label="Search Post title" aria-describedby="button-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-6">
            {!! $records->render() !!}
        </div>
    </div>
    <div class="row exam-card">
        @foreach($records as $record)
            <div class="col-sm-6 col-md-4">
                @include('exam::cards.exam')
            </div>
        @endforeach
    </div>
    {!! $records->render() !!}
@endSection
