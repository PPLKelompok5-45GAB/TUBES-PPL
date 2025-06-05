<?php

namespace App\Console\Commands;

use App\Http\Controllers\BorrowController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckOverdueBooks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'library:check-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for overdue books and update their status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for overdue books...');
        
        try {
            $controller = new BorrowController();
            $count = $controller->checkOverdueBorrows();
            
            $this->info("Found and updated {$count} overdue books.");
            Log::info("Checked for overdue borrows. Found and updated {$count} records.");
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Error checking overdue books: {$e->getMessage()}");
            Log::error("Error in overdue books check: {$e->getMessage()}");
            
            return Command::FAILURE;
        }
    }
}
