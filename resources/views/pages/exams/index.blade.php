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
                    <input type="search" name="search" value="{{request('search')}}" class="form-control"
                           placeholder="Search Exams" list="keywords" autocomplete="off"
                           aria-label="Search Post title" aria-describedby="button-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button>
                    </div>
                </div>
                <datalist id="keywords">
                    @foreach($keywords as $keyword)
                        <option value="{{$keyword['name']}}">&nbsp; &nbsp; {{$keyword['total']}}</option>
                    @endforeach
                </datalist>
            </form>
        </div>
        <div class="col-md-6">
            {!! $records->render() !!}
        </div>
    </div>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link {{empty(request('status'))?'active':''}}" href="?">
                {{request('search')?'Search Result':'Recommendation'}}
                @if(empty(request('activity')) && empty(request('status')))
                    <span class="badge badge-secondary badge-pill"><b>{{$records->count()}}</b>
                </span>
                @endif
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{request('status')=='completed'?'active':''}}" href="?status=completed">Completed
                <span class="badge badge-light badge-pill">{{$completedExams}}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{request('status')=='pending'?'active':''}}" href="?status=pending">Pending
                <span class="badge badge-light badge-pill">{{$pendingExams}}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{request('activity')=='like'?'active':''}}" href="?activity=like">Liked
                <span class="badge badge-light badge-pill">{{$likedExams}}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{request('activity')=='favourite'?'active':''}}" href="?activity=favourite">Favourite
                <span class="badge badge-light badge-pill">{{$favouriteExams}}</span>
            </a>
        </li>

    </ul>
    <div class="row exam-card">

        @foreach($records as $record)
            <div class="col-sm-6 col-md-4">
                @include('exam::cards.exam')
            </div>
        @endforeach
    </div>
    {!! $records->render() !!}
@endSection
