<?php

namespace Exam\Notifications;

use Exam\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class InvitationNotification extends Notification
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
     * Create a new notification instance.
     *
     * @param Invitation $invitation
     * @param User       $actor
     */
    public function __construct(Invitation $invitation, User $actor)
    {
        $this->invitation = $invitation;
        $this->actor = $actor;
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
        return ['mail', 'database'];
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
            ->line($this->actor->name . ' invites you to take exam ' . $this->invitation->exam->title)
            ->action('Accept', route('exam::exams.invitations.response', [
                'exam' => $this->invitation->exam->slug, 'invitation' => $this->invitation->token,
            ]))
            ->action('Reject', route('exam::exams.invitations.response', [
                'exam' => $this->invitation->exam->slug,
                'invitation' => $this->invitation->token,
                'status' => Invitation::STATUS_REJECTED,
            ]))
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
            'message' => $this->actor->name . ' invites you to take exam ' . $this->invitation->exam->title,
            'link' => route('exam::exams.invitations.show', [
                'exam' => $this->invitation->exam->slug,
                'invitation' => $this->invitation->token,
            ]),
        ];
    }
}
