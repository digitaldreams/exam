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
    <div class="btn-group">

        @can('create',\Exam\Models\Exam::class)
            <a class="btn btn-outline-secondary  m-l-15" href="{{route('exam::exams.create')}}">
                <span class="fa fa-plus"></span> Exam
            </a>
        @endcan
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <form>
                <div class="input-group">
                    <div class="dropdown">
                        <a class="btn btn-outline-secondary dropdown-toggle" href="#" role="button"
                           id="dropdownMenuLink"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Topics
                        </a>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            @foreach($keywords as $keyword)
                                <li class="dropdown-item">
                                    <a class="btn btn-outline-secondary" href="?search={{$keyword['name']}}">
                                        {{$keyword['name']}} <span
                                            class="badge badge-secondary">{{$keyword['total']}}</span>
                                    </a>
                                </li>
                            @endforeach
                        </div>
                    </div>
                    <input type="search" name="search" value="{{request('search')}}" class="form-control"
                           placeholder="Search Exams" list="keywords" autocomplete="off"
                           aria-label="Search Post title" aria-describedby="button-addon2">
                    @if(request('search'))
                        <div class="input-group-append">
                            <a href="?" class="btn btn-outline-secondary" type="submit" id="button-addon2">Clear</a>
                        </div>
                    @endif
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button>
                    </div>
                </div>
                <datalist id="keywords">
                    @foreach($keywords as $keyword)
                        <option value="{{$keyword['name']}}"/>
                    @endforeach
                </datalist>
                <p class="text-muted m-0 p-0 text-center">{{trans('exam::info.exam.search')}}</p>
            </form>
        </div>
        <div class="col-md-6 d-none d-sm-block">
            {!! $records->render() !!}
        </div>
    </div>
    <ul class="nav nav-tabs">
        @if(request('search'))
            <li class="nav-item ">
                <a class="nav-link active"
                   data-toggle="tooltip" href="?">
                    Search Result
                    <span class="badge badge-secondary badge-pill"><b>{{$records->count()}}</b></span>
                </a>
            </li>
        @else
            <li class="nav-item">
                <a class="nav-link {{empty(request('status')) || empty(request('activity'))?'active':''}}"
                   data-toggle="tooltip" href="?" title="{{trans('exam::info.exam.recommendation')}}">
                    Recommendations
                    @if(empty(request('activity')) && empty(request('status')))
                        <span class="badge badge-secondary badge-pill"><b>{{$records->count()}}</b>
                        </span>
                    @endif
                </a>
            </li>
        @endif

        <li class="nav-item" data-toggle="tooltip" title="{{trans('exam::info.exam.completed')}}">
            <a class="nav-link {{request('status')=='completed'?'active':''}}" href="?status=completed">Completed
                <span class="badge badge-light badge-pill">{{$completedExams}}</span>
            </a>
        </li>
        <li class="nav-item" data-toggle="tooltip" title="{{trans('exam::info.exam.pending')}}">
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
