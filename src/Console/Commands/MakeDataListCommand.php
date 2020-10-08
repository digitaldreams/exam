<?php

namespace App\Console\Commands;

use Blog\Models\Category;
use Exam\Models\Exam;
use Illuminate\Console\Command;

class MakeDataListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exam:datalist';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create autocomplete keyword with tags and categories';

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
     * @return int
     */
    public function handle()
    {
        $exam = new Exam();
        $categories= Exam::query()->selectRaw('category_id,count(*) as total');

        return Command::SUCCESS;
    }
}
