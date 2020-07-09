<?php

namespace Exam\Notifications;

use Exam\Models\Exam;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;
use Permit\Notifications\Channels\Model as ModelChannel;

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
        return [ModelChannel::class];
    }

    public function toModel($notifiable)
    {
        return [
            'model' => $this->exam,
            'actor' => $this->user,
            'verb' => 'completed',
        ];
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
}
