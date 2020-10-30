<?php

namespace Exam\Console\Commands;

use Exam\Notifications\NewExamCreatedNotification;
use Exam\Repositories\ExamRepository;
use Illuminate\Console\Command;
use Illuminate\Notifications\ChannelManager;

class SendNewExamCreatedNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exam:preferred-exam-created';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify users who have shown interest on this category, tags.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param \Exam\Repositories\ExamRepository        $examRepository
     * @param \Illuminate\Notifications\ChannelManager $channelManager
     *
     * @return int
     * @throws \Exception
     */
    public function handle(ExamRepository $examRepository, ChannelManager $channelManager)
    {
        $exams = $examRepository->createdBetween();
        if ($exams->count() > 0) {
            foreach ($exams as $exam) {
                $users = $examRepository->findPreferredUsersForExam($exam);
                if ($users->count() > 0) {
                    $channelManager->send($users, new NewExamCreatedNotification($exam));
                }
                $this->info(sprintf('%s user send exam %s ', $users->count(), $exam->title));
            }
        }
        $this->info(sprintf('There are %s exam created in last 24 hours', $exams->count()));

        return 0;
    }
}
