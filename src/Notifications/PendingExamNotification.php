<?php

namespace Exam\Notifications;

use Exam\Models\ExamUser;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Channels\DatabaseChannel;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PendingExamNotification extends Notification
{
    use Queueable;
    /**
     * @var ExamUser
     */
    protected $examUser;

    /**
     * Create a new notification instance.
     *
     * @param ExamUser $examUser
     */
    public function __construct(ExamUser $examUser)
    {
        $this->examUser = $examUser;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [DatabaseChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Your exam ' . $this->examUser->exam->title . ' is pending over 24 hours.',
            'link' => route('exam::exams.start', $this->examUser->exam->slug)
        ];
    }

    public function toModel($notifiable)
    {
        return [
            'model' => $this->examUser,
            'actor' => $this->examUser->user,
            'verb' => 'pending '
        ];
    }
}
