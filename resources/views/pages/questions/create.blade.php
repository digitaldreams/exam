@extends(config('exam.layouts.app'))
@section('styles')
    <link rel="stylesheet" href="{{asset('prototype/css/select2.min.css')}}"/>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('exam::questions.index')}}">questions</a>
    </li>
    <li class="breadcrumb-item">
        Create
    </li>
@endsection
@section('header')
    Question <i class="fa fa-question-mark"></i>
@endsection
@section('content')
    <div class="row">
        <div class='col-md-12'>
            <div class='card'>
                <div class="card-body">
                    @if(request()->has('type'))
                        @include('exam::forms.question')
                    @else
                        @include('exam::forms.question_type')
                    @endif
                </div>
            </div>
        </div>
    </div>
@endSection
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function (e) {
            $('#question_options').select2({
                tags: true,
            });
        });

    </script>
@endsection
