<?php

return [
    /*
   |--------------------------------------------------------------------------
   | Flash Message Language Lines
   |--------------------------------------------------------------------------
   |
   | The following language lines are used during CRUD Operation for various
   | messages that we need to display to the user. You are free to modify
   | these language lines according to your application's requirements.
   |
   */

    'created' => 'New :model created successfully.',
    'saved' => ':model saved successfully.',
    'underReview' => ':model saved successfully and one of our moderator will review it soon',
    'updated' => ':model updated successfully',
    'deleted' => ':model deleted successfully',
    'deletePrompt' => 'Are you sure you want to delete :model ?',
    'notExists' => ':model does not exits any more.',
    'oops' => 'Oops something went wrong while :action',
    'errorOccurred' => 'Error occurred while :action',
    'question' => [
        'attach' => 'Questions successfully added',
        'detach' => 'Questions removed successfully',
        'detachConfirmationAlert' => 'Are you sure you want to unlink this question from this exam?',
        'createdAndAttached' => 'Question created and attached to this exam.',
    ],
    'answer' => [
        'reviewed' => 'Answer successfully reviewed',
    ],
    'exam' => [
        'completeRequired' => 'Please complete all the required exams first',
        'timeOver' => 'Time over',
        'alreadyCompleted' => 'You completed all the questions already',
        'choose' => 'Please choose a exam first',
        'visibility' => 'Your exam result visibility set to :visibility',
    ],
    'signup' => 'Please sign up to view your result.',
    'invalidToken' => 'Your token does not seems to be valid',
    'invitation' => [
        'send' => 'Invitation send successfully',
        'statusChanged' => 'Invitation successfully :status',
    ],
];
