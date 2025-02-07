@extends(config('exam.layouts.app'))
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('exam::exams.index')}}">Exams</a>
    </li>
    <li class="breadcrumb-item active">
        Create
    </li>
@endsection
@section('header')
    Create New Exam
@endsection
@section('content')
    <div class="row">
        <div class='col-md-12'>
            <div class='card'>
                <div class="card-body">
                    @include('exam::forms.exam')
                </div>
            </div>
        </div>
    </div>
@endSection
@section('scripts')
    @include('exam::pages.exams.scripts')
@endsection
