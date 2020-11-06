<?php

namespace Exam\Notifications;

use App\Models\User;
use Exam\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class InvitationNotification extends Notification implements ShouldQueue
{
    use Queueable;
    /**
     * @var Invitation
     */
    protected $invitation;
    /**
     * @var User
     */
    protected $actor;

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
     * @param Invitation $invitation
     * @param User       $actor
     */
    public function __construct(Invitation $invitation, User $actor)
    {
        $this->invitation = $invitation;
        $this->actor = $actor;
        $this->subject = sprintf('You are invited to take %s exam', $this->invitation->exam->title);
        $this->link = route('exam::exams.invitations.response', [
            'exam' => $this->invitation->exam->slug,
            'invitation' => $this->invitation->token,
        ]);
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
            ->subject($this->subject)
            ->line($this->actor->name . ' invites you to take exam ' . $this->invitation->exam->title)
            ->action('Accept', $this->link)
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
            ->title('Invited to take Exam')
            ->body($this->invitation->exam->title)
            ->requireInteraction()
            ->data(['url' => $this->link]);
    }
}
