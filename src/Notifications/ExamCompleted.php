<?php

namespace Exam\Notifications;

use Exam\Models\Exam;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;

class ExamCompleted extends Notification
{
    use Queueable;

    /**
     * @var Exam
     */
    protected $exam;
    /**
     * @var Model
     */
    protected $user;

    /**
     * Create a new notification instance.
     *
     * @param Exam  $exam
     * @param Model $user
     */
    public function __construct(Exam $exam, Model $user)
    {
        $this->exam = $exam;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    public function toDatabase()
    {
        return [
            'message' => $this->user->name . ' completed ' . $this->exam->title,
            'link' => route('exam::exams.show', $this->exam->slug),
        ];
    }
}
