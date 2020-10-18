<?php

namespace Exam\Notifications;

use Exam\Models\ExamUser;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;

class ReviewRequestToTeacher extends Notification
{
    use Queueable;
    /**
     * @var ExamUser
     */
    protected $examUser;
    protected $subject;
    protected $link;

    /**
     * Create a new notification instance.
     *
     * @param ExamUser $examUser
     */
    public function __construct(ExamUser $examUser)
    {
        $this->examUser = $examUser;
        $this->subject = $this->examUser->user->name . ' completed ' . $this->examUser->exam->title . ' has some question that need manual checking';
        $this->link = route('exam::exams.reviews.index', $this->examUser->exam->slug);
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
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
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
            'message' => $this->subject,
            'link' => $this->link,
        ];
    }

    /**
     * @return \NotificationChannels\WebPush\WebPushMessage
     */
    public function toWebPush()
    {
        return (new WebPushMessage())
            ->title('Manual checking needed')
            ->body($this->examUser->exam->title)
            ->requireInteraction()
            ->data(['url' => $this->link]);
    }
}
