<?php

namespace Exam\Notifications;

use Exam\Models\Exam;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class NewExamCreatedNotification extends Notification
{
    use Queueable;
    /**
     * @var string
     */
    private $subject;
    /**
     * @var string
     */
    private $link;

    /**
     * Create a new notification instance.
     *
     * @param \Exam\Models\Exam $exam
     */
    public function __construct(Exam $exam)
    {
        $this->exam = $exam;
        $this->subject = 'New Exam <b>' . $exam->title . '</b> created';
        $this->link = route('exam::exams.show', $exam->slug);
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
        return ['mail', 'database', WebPushChannel::class];
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
            ->subject(strip_tags($this->subject))
            ->line('New exam created that you may be interested')
            ->line($this->subject)
            ->action('View', $this->link)
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
            'icon' => 'fa fa-doc',
        ];
    }

    /**
     * @return \NotificationChannels\WebPush\WebPushMessage
     */
    public function toWebPush()
    {
        return (new WebPushMessage())
            ->title('New Exam created that you are interested in.')
            ->body($this->subject)
            ->requireInteraction()
            ->data(['url' => $this->link]);
    }
}
