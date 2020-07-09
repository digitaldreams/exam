<?php

namespace Exam\Notifications;

use Exam\Models\ExamUser;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ReviewRequestToTeacher extends Notification
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
        return ['database'];
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
     * @return array
     */
    public function toDatabase()
    {
        return [
            'message' => $this->examUser->user->name . ' completed ' . $this->examUser->exam->title . ' has some question that need manual checking',
            'link' => route('exam::exams.reviews.index', $this->examUser->exam->slug)
        ];
    }
}
