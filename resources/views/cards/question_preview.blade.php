<div class="card">
    <div class="card-body">
        <h3 class="text-center">Preview</h3>
        @if(!empty($record->parent))
            @include('exam::forms.partials.answer',['question'=>$record->parent])
            @if($record->parent->children)
                @foreach($record->parent->children as $child)
                    @include('exam::forms.partials.answer',[
                       'question'=>$child
                    ])
                @endforeach
            @endif
        @else
            @include('exam::forms.partials.answer',['question'=>$record])
            @if($record->children)
                @foreach($record->children as $child)
                    @include('exam::forms.partials.answer',[
                       'question'=>$child
                    ])
                @endforeach
            @endif
        @endif

        <div class="form-group text-right">

            <a class="btn btn-outline-secondary"
               href="#">Previous</a>
            <input type="button" class="btn btn-outline-primary" value="Save and Continue">
            <a class="btn btn-outline-secondary"
               href="#">Skip</a>
        </div>
    </div>
</div>
