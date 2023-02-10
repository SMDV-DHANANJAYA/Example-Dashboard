<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeleteLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Laravel Log Monthly';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try{
            exec("truncate -s 0 " . storage_path('/logs/laravel.log'));
            return Command::SUCCESS;
        }
        catch(\Throwable $e){
            Log::error($e->getMessage());
            return Command::FAILURE;
        }
    }
}
