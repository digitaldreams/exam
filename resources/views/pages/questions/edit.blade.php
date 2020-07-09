@extends(config('exam.layouts.app'))
@section('styles')
    <link rel="stylesheet" href="{{asset('prototype/css/select2.min.css')}}"/>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{route('exam::questions.index')}}">questions</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{route('exam::questions.show',$model->id)}}">{{$model->title}}</a>
    </li>
    <li class="breadcrumb-item">
        Edit
    </li>
@endsection

@section('content')
    <div class="row">
        <div class='col-md-12'>
            <div class='card'>
                <div class="card-body">
                    @include('exam::forms.question',[
                    'route'=>route('exam::questions.update',$model->id),
                    'method'=>'PUT'
                    ])
                </div>
            </div>
        </div>
    </div>
@endSection
@section('scripts')
    <script type="text/javascript" src="{{asset('prototype/js/select2.full.min.js')}}"></script>
    <script type="text/javascript">
        $('#question_options').select2({
            tags: true
        });
        $('.question_answer').select2({
            tags: true
        });
        function formatState(state) {
            if (!state.id) {
                return state.text;
            }
            var $state = $(
                '<span><img width="120px" src="' + state.id + '" class="img-thumnail" /> ' + state.text + '</span>'
            );
            return $state;
        };
        $(".worToImageOptions").select2({
            tags: true,
            multiple: true,
            ajax: {
                url: "{{route(config('exam.image.url'))}}",
                dataType: "json",
                type: "GET",
                data: function (params) {

                    var queryParameters = {
                        q: params.term
                    }
                    return queryParameters;
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.caption,
                                id: item.thumbnail
                            }
                        })
                    };
                }
            },
            templateResult: formatState,
            formatSelection: formatState
        })
    </script>
@endsection
