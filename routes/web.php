<?php
$this->router->group(['middleware' => ['web', 'auth', 'verified'], 'as' => 'exam::', 'namespace' => 'Exam\Http\Controllers', 'prefix' => 'app'], function () {
    $this->router->get('questions/select2-ajax', 'QuestionController@select2Ajax')->name('questions.select2Ajax');

    $this->router->resource('questions', 'QuestionController');
    $this->router->get('feedbacks/{exam}', 'FeedbackController@index')->name('exams.feedback.index');
    $this->router->resource('feedbacks', 'FeedbackController', ['only' => ['store', 'update']]);
    $this->router->get('exams/select2', 'ExamController@select2Ajax')->name('exams.select2');
    $this->router->post('exams/{exam}/questions-add', 'ExamQuestionController@add')->name('exams.questionAdd');
    $this->router->post('exams/{exam}/questions-remove', 'ExamQuestionController@remove')->name('exams.questionRemove');
    $this->router->get('exams/{exam}/completed', 'ExamUserController@completed')->name('exams.completed');

    $this->router->group(['prefix' => 'exams'], function () {
        $this->router->get('{exam_user}/result', 'ExamUserController@result')->name('exams.result');
        $this->router->get('{exam}/start', 'ExamUserController@start')->name('exams.start');

        $this->router->get('exams/reviews', 'ExamReviewController@index')->name('exams.reviews.all');
        $this->router->get('{exam}/reviews', 'ExamReviewController@index')->name('exams.reviews.index');        //
        $this->router->get('{exam}/reviews/{answer}', 'ExamReviewController@show')->name('exams.reviews.show');
        $this->router->put('{exam}/submit/reviews/{answer}', 'ExamReviewController@update')->name('exams.reviews.update');

        $this->router->get('{exam}/questions/{question}', 'ExamUserController@question')->name('exams.question');
        $this->router->post('{exam}/answer/{question}', 'ExamUserController@answer')->name('exams.answer');

        $this->router->get('{exam_user}/visibility/{visibility}', 'ExamUserController@visibility')->name('exams.result.visibility');
    });

    $this->router->resource('exams', 'ExamController');
    $this->router->get('exams/{exam}/invitations/{invitation}/response', 'InvitationController@response')->name('exams.invitations.response');
    $this->router->resource('exams.invitations', 'InvitationController');

});
