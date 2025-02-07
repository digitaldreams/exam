<?php

namespace Exam\Notifications;

use Exam\Models\Exam;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;

class ExamCompleted extends Notification implements ShouldQueue
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
     * @param Exam                                $exam
     * @param \Illuminate\Database\Eloquent\Model $user
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
            'message' => sprintf('%s  completed  %s', $this->user->name, $this->exam->title),
            'link' => route('exam::exams.completed', $this->exam->slug),
        ];
    }
}
