<?php
$this->router->group(['middleware' => ['web'], 'as' => 'exam::', 'namespace' => 'Exam\Http\Controllers', 'prefix' => 'app'], function () {
    $this->router->get('questions/select2-ajax', 'QuestionController@select2Ajax')->name('questions.select2Ajax')->middleware(['auth']);

    $this->router->resource('questions', 'QuestionController')->middleware(['auth']);
    $this->router->get('feedbacks/{exam}', 'FeedbackController@index')->name('exams.feedback.index')->middleware(['auth']);
    $this->router->resource('feedbacks', 'FeedbackController', ['only' => ['store', 'update']])->middleware(['auth']);
    $this->router->get('exams/select2', 'ExamController@select2Ajax')->name('exams.select2')->middleware(['auth']);
    $this->router->post('exams/{exam}/questions-add', 'ExamQuestionController@add')->name('exams.questionAdd')->middleware(['auth']);
    $this->router->post('exams/{exam}/questions-remove', 'ExamQuestionController@remove')->name('exams.questionRemove')->middleware(['auth']);
    $this->router->get('exams/{exam}/completed', 'ExamUserController@completed')->name('exams.completed')->middleware(['auth']);

    $this->router->group(['prefix' => 'exams'], function () {
        $this->router->get('{examUser}/assign-user/{token}', 'ExamUserController@assignUser')->name('exams.assignUser')->middleware(['auth']);
        $this->router->get('{exam_user}/result', 'ExamUserController@result')->name('exams.result')->middleware(['auth']);
        $this->router->get('{exam}/start', 'ExamUserController@start')->name('exams.start');

        $this->router->get('exams/reviews', 'ExamReviewController@index')->name('exams.reviews.all')->middleware(['auth']);
        $this->router->get('{exam}/reviews', 'ExamReviewController@index')->name('exams.reviews.index')->middleware(['auth']);        //
        $this->router->get('{exam}/reviews/{answer}', 'ExamReviewController@show')->name('exams.reviews.show')->middleware(['auth']);
        $this->router->put('{exam}/submit/reviews/{answer}', 'ExamReviewController@update')->name('exams.reviews.update')->middleware(['auth']);

        $this->router->get('{exam}/questions/{question}', 'ExamUserController@question')->name('exams.question');
        $this->router->post('{exam}/answer/{question}', 'ExamUserController@answer')->name('exams.answer');

        $this->router->get('{exam_user}/visibility/{visibility}', 'ExamUserController@visibility')->name('exams.result.visibility')->middleware(['auth']);
    });

    $this->router->resource('exams', 'ExamController')->middleware(['auth']);
    $this->router->get('exams/{exam}/invitations/{invitation}/response', 'InvitationController@response')->name('exams.invitations.response')->middleware(['auth']);
    $this->router->resource('exams.invitations', 'InvitationController')->middleware(['auth']);

});

$this->router->group(['middleware' => ['web'], 'as' => 'exam::', 'namespace' => 'Exam\Http\Controllers', 'prefix' => 'exam'], function () {
    $this->router->get('exams/{exam}', 'FrontendController@show')->name('frontend.exams.show');
});

