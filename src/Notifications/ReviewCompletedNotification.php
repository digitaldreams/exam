<?php

namespace Exam\Notifications;

use Exam\Models\Answer;
use Exam\Models\Exam;
use Exam\Models\ExamUser;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReviewCompletedNotification extends Notification
{
    use Queueable;

    /**
     * @var ExamUser
     */
    protected $answer;

    /**
     * @var Exam
     */
    protected $exam;

    /**
     * Create a new notification instance.
     *
     * @param Answer $answer
     * @param Exam   $exam
     */
    public function __construct(Answer $answer, Exam $exam)
    {
        $this->answer = $answer;
        $this->exam = $exam;
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

    /**
     * @return array
     */
    public function toDatabase()
    {
        return [
            'message' => sprintf('Your exam question %s  answer reviewed.', $this->answer->question->title),
            'link' => route('exam::exams.reviews.show', ['exam' => $this->exam->slug, 'answer' => $this->answer->id]),
        ];
    }
}
