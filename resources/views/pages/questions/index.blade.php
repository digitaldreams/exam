@extends(config('exam.layouts.app'))
@section('page-id')
    id="question"
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item">
        questions
    </li>
@endsection
@section('header')
    Question <i class="fa fa-question-mark"></i>
@endsection
@section('tools')
    @can('create',\Exam\Models\Question::class)
        <a class="btn btn-outline-secondary" href="{{route('exam::questions.create')}}"> <i class="fa fa-plus"></i>
            Create New Question</a>
    @endcan
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <form>
                <div class="input-group mb-3">
                    <select name="type" class="form-control-inline">
                        <option value="">All</option>
                        @foreach(\Exam\Enums\QuestionType::toArray() as $key=>$name)
                            <option value="{{$key}}" {{request('type')==$key?'selected':''}}>{{$name}}</option>
                        @endforeach
                    </select>
                    <input type="search" name="search" value="{{request('search')}}" class="form-control"
                           placeholder="Search Questions"
                           autocomplete="off"
                           list="keywords"
                           aria-label="Search Question" aria-describedby="button-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button>
                    </div>
                </div>
                <datalist id="keywords">
                    @foreach($keywords as $keyword)
                        <option value="{{$keyword['name']}}">&nbsp; {{$keyword['name']}}
                            &nbsp; {{$keyword['total']}}</option>
                    @endforeach
                </datalist>
            </form>
        </div>
        <div class="col-md-6">
            {!! $records->appends(['search'=> request('search'),'type'=>request('type')])->render() !!}
        </div>
    </div>
    <div class="row">
        @foreach($records->chunk(2) as $chunkRecords)
            <div class="col-sm-6 col-md-6 col-12">
                <div class="row">
                    @foreach($chunkRecords as $record)
                        <div class="col-md-6" style="height: 350px;overflow: scroll">
                            @include('exam::cards.question')
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
    {!! $records->appends(['search'=> request('search'),'type'=>request('type')])->render() !!}
@endSection
