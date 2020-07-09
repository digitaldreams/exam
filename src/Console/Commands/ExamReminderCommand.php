<?php

namespace Exam\Console\Commands;

use Carbon\Carbon;
use Exam\Models\Exam;
use Exam\Models\ExamUser;
use Exam\Notifications\PendingExamNotification;
use Illuminate\Console\Command;

class ExamReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exam:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pending exam reminder';

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
     * @return mixed
     */
    public function handle()
    {
        $pendingExams = ExamUser::where('status', Exam::STATUS_PENDING)
            ->where('started_at', '<=', Carbon::now()->subHours(24)->toDateTimeString())
            ->whereNull('reminder')->get();
        foreach ($pendingExams as $pendingExam) {
            $pendingExam->user->notify(new PendingExamNotification($pendingExam));
            $pendingExam->reminder = 1;
            $pendingExam->save();
        }
        $this->info(count($pendingExams) . ' notification sent');

    }
}
