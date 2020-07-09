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
        <a class="btn btn-secondary" href="{{route('exam::questions.create')}}"> <i class="fa fa-plus"></i> Create</a>
    @endcan
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <form>
                <div class="input-group mb-3">
                    <input type="search" name="search" value="{{request('search')}}" class="form-control" placeholder="Search Questions"
                           aria-label="Search Question" aria-describedby="button-addon2">
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
    <div class="row">
        @foreach($records->chunk(4) as $chunkRecords)
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
    {!! $records->render() !!}
@endSection
