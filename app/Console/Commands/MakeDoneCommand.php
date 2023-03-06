<?php

namespace App\Console\Commands;

use App\Models\Todo;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MakeDoneCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'todo:done';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set done';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $todoToDone = Todo::query()
            ->where('done', false)
            ->where(Todo::CREATED_AT, '<', Carbon::now()->subMinutes(5)->toDateTimeString())
            ->get();

        foreach ($todoToDone as $todo) {
            $todo->forceFill(['done' => true])->save();
        }


        return Command::SUCCESS;
    }
}
