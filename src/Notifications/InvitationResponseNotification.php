<?php

namespace Exam\Notifications;

use Exam\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Permit\Notifications\Channels\Model as ModelChannel;

class InvitationResponseNotification extends Notification
{
    use Queueable;

    /**
     * @var Invitation
     */
    protected $invitation;

    /**
     * Create a new notification instance.
     *
     * @param Invitation $invitation
     */
    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
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
     * @return array
     */
    public function toModel()
    {
        return [
            'model' => $this->invitation->exam,
            'verb' => $this->invitation->status,
            'actor' => $this->invitation->user,
        ];
    }
}
