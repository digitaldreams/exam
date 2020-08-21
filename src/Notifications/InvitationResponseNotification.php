<?php

namespace Exam\Notifications;

use Exam\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

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
            'message' => $this->invitation->user->name . ' accepted invitation of  ' . $this->invitation->exam->title,
            'link' => route('exam::exams.show', $this->invitation->exam->slug),
        ];
    }

}
