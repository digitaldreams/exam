@extends(config('exam.layouts.app'))
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('exam::exams.index')}}">Exams</a>
    </li>
    <li class="breadcrumb-item">
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
    <script type="text/javascript">

        $('#tags').select2({
            ajax: {
                url: '{{route('blog::tags.select2')}}',
                dataType: 'json'
            }
        });

        $('#category_id').select2({
            ajax: {
                url: '{{route('blog::categories.select2')}}',
                dataType: 'json'
            }
        });

        $('#must_completed').select2({
            ajax: {
                url: '{{route('exam::exams.select2')}}',
                dataType: 'json'
            }
        });
    </script>
@endsection
