<?php

namespace Exam\Notifications;

use Exam\Models\ExamUser;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Channels\DatabaseChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;

class PendingExamNotification extends Notification
{
    use Queueable;
    /**
     * @var ExamUser
     */
    protected $examUser;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var string
     */
    protected $link;

    /**
     * Create a new notification instance.
     *
     * @param ExamUser $examUser
     */
    public function __construct(ExamUser $examUser)
    {
        $this->examUser = $examUser;
        $this->subject = 'Your exam ' . $this->examUser->exam->title . ' is pending over 24 hours.';
        $this->link = route('exam::exams.start', $this->examUser->exam->slug);
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
        return [DatabaseChannel::class];
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

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toDatabase($notifiable)
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
            ->title('Your exam is pending')
            ->body($this->examUser->exam->title)
            ->requireInteraction()
            ->data(['url' => $this->link]);
    }
}
