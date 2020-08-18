<?php
Route::group(['middleware' => ['web', 'auth'], 'as' => 'exam::', 'namespace' => 'Exam\Http\Controllers', 'prefix' => 'app'], function () {
    // Route::get('questions/create', 'QuestionController@create')->name('questions.words.create');
    Route::get('questions/select2-ajax', 'QuestionController@select2Ajax')->name('questions.select2Ajax');

    Route::resource('questions', 'QuestionController');

    Route::resource('feedbacks', 'FeedbackController', ['only' => ['store', 'update']]);
    Route::post('exams/{exam}/question-add', 'ExamController@questionAdd')->name('exams.questionAdd');

    Route::group(['prefix' => 'exams'], function () {
        Route::get('{exam_user}/result', [
            'as' => 'exams.result',
            'uses' => 'ExamUserController@result',
        ]);

        Route::get('{exam}/start', [
            'as' => 'exams.start',
            'uses' => 'ExamUserController@start',
        ]);
        //
        Route::get('exams/reviews', [
            'as' => 'exams.reviews.all',
            'uses' => 'ExamReviewController@index',
        ]);
        //
        Route::get('{exam}/reviews', [
            'as' => 'exams.reviews.index',
            'uses' => 'ExamReviewController@index',
        ]);
        //
        Route::get('{exam}/reviews/{answer}', [
            'as' => 'exams.reviews.show',
            'uses' => 'ExamReviewController@show',
        ]);
        Route::get('{exam}/submit/reviews/{answer}', [
            'as' => 'exams.reviews.update',
            'uses' => 'ExamReviewController@update',
        ]);
        Route::get('{exam}/questions/{question}', [
            'as' => 'exams.question',
            'uses' => 'ExamUserController@question',
        ]);

        Route::post('{exam}/answer/{question}', [
            'as' => 'exams.answer',
            'uses' => 'ExamUserController@answer',
        ]);

        Route::get('{exam_user}/visibility/{visibility}', [
            'as' => 'exams.result.visibility',
            'uses' => 'ExamUserController@visibility',
        ]);
    });
    Route::resource('exams', 'ExamController');
    Route::get('exams/{exam}/invitations/{invitation}/response', 'InvitationController@response')->name('exams.invitations.response');
    Route::resource('exams.invitations', 'InvitationController');

});
