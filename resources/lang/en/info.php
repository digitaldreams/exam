<?php

return [
    'exam' => [
        /*
         *  File: exam::pages.exam.index.blade.php
         */
        'search' => 'Search Examinations by Category, Tag name or words within exam title or description.',
        'completed' => 'Examinations you have successfully completed.',
        'pending' => 'Examinations you have started but have not finished yet.',
        'recommendation' => 'Suggested Examinations that you may like to take based on your preferences settings.',

        /*
         *  File: exam::form.exam.bade.php
         */
        'description' => 'Describe what is exam all about. Give a syllabus what topics will be covered. And any relevant link to study before taking this exam',
        'duration' => 'User must have to complete all of the question before this time.Empty duration will make exam time unlimited',
        'category' => 'Organize your exam by category. E.g. General Knowledge, Math, English Grammar',
        'tags' => 'Add tags to this exam along with category. However, while a category may cover a broad range of topics, tags are smaller in scope and focused to specific topics.',
        'tagCreate' => 'To create new tag. Just type tag name and add comma(,) at the end of your new tag name or select from dropdown.',
        'mustCompleteExams' => 'User must completed these exams. Otherwise they is not allowed to participate',
        'visibility' => 'public examination will be open for everyone. Anyone can take this exam even a guest. Private exam is protected only invited examine will have opportunity to take it.',
        'status' => 'When your exam is ready and you assign all of the questions then make it Active. That will allow others who have access to it will see. Otherwise make it inactive. This will make it hidden from others',
        'showAnswer' => 'When instantly selected then answer will be shown on top of next question otherwise answers will be shown at the end of the exam.',
    ],
    'question' => [
        'question_type' => 'This is the question body. The type of information will be visible to user to help choose/write correct answer.',
        'answer_type' => 'This is how user will answer to the question that they have asked.',
        'hints' => 'Help user by giving some clue about the possible answer.It will helps user to choose the correct answer.',
        'explanation' => 'After submitted the answer it will shown to the user why the correct answer is right.',
        'explainPlaceholder' => 'Explain here why answer(s) is correct.',
        'totalMark' => 'Total mark user will be awarded when answer is correct. If answers are multiple then total mark will be divided equally.',
        'parentQuestion' => 'Group your questions into one section. For example, Parent question may have an audio or video. You may want to create more than one question based on that audio/video.A child question always be a part of its parent. Never be able to use alone.',
        'reviewType' => 'Manual review type will be reviewed by an teacher.',
    ],
];
