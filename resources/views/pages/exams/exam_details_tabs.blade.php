<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link {{Route::currentRouteName()=='exam::exams.show'?'active':''}}" href="{{route('exam::exams.show',$exam->slug)}}">Details</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{Route::currentRouteName()=='exam::exams.feedback.index'?'active':''}}" href="{{route('exam::exams.feedback.index',$exam->slug)}}">Feedback</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">Invitations</a>
    </li>
    <li class="nav-item">
        <a class="nav-link disabled" href="#">Pending</a>
    </li>
    <li class="nav-item">
        <a class="nav-link disabled" href="#">Completed</a>
    </li>
</ul>
