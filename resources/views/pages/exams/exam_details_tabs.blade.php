<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link {{Route::currentRouteName()=='exam::exams.show'?'active':''}}"
           href="{{route('exam::exams.show',$exam->slug)}}">
            Details
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{Route::currentRouteName()=='exam::exams.feedback.index'?'active':''}}"
           href="{{route('exam::exams.feedback.index',$exam->slug)}}">
            Feedback
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{Route::currentRouteName()=='exam::exams.invitations.index'?'active':''}}"
           href="{{route('exam::exams.invitations.index',$exam->slug)}}">Invitations</a>
    </li>
    <li class="nav-item ">
        <a class="nav-link {{Route::currentRouteName()=='exam::exams.reviews.index'?'active':''}} "
           href="{{route('exam::exams.reviews.index',$exam->slug)}}">Pending Answer Check</a>
    </li>
    <li class="nav-item ">
        <a class="nav-link {{Route::currentRouteName()=='exam::exams.completed'?'active':''}}"
           href="{{route('exam::exams.completed',$exam->slug)}}">Completed</a>
    </li>
</ul>
