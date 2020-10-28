@extends(config('exam.layouts.frontend'))
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8 col-sm-12">
            @include('exam::cards.exam',['record'=>$exam])
        </div>
    </div>
@endsection

