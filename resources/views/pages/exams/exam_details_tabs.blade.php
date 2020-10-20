<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link {{Route::currentRouteName()=='exam::exams.show'?'active':''}}"
           href="{{route('exam::exams.show',$exam->slug)}}">
           <i class="fa fa-home"></i> Home
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{Route::currentRouteName()=='exam::exams.feedback.index'?'active':''}}"
           href="{{route('exam::exams.feedback.index',$exam->slug)}}">
         <i class="fa fa-star text-warning"></i>  Feedback
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{Route::currentRouteName()=='exam::exams.invitations.index'?'active':''}}"
           href="{{route('exam::exams.invitations.index',$exam->slug)}}">
            <i class="fa fa-send"> </i> Invitations
        </a>
    </li>
    <li class="nav-item ">
        <a class="nav-link {{Route::currentRouteName()=='exam::exams.reviews.index'?'active':''}} "
           href="{{route('exam::exams.reviews.index',$exam->slug)}}">
           <i class="fa fa-recycle"></i> Pending Answer Check
        </a>
    </li>
    <li class="nav-item ">
        <a class="nav-link {{Route::currentRouteName()=='exam::exams.completed'?'active':''}}"
           href="{{route('exam::exams.completed',$exam->slug)}}">
           <i class="fa fa-check-circle"></i> Completed
        </a>
    </li>
</ul>
