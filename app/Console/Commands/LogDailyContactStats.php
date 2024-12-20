<?php

namespace App\Console\Commands;

use App\Models\Contact;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class LogDailyContactStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contacts:log-daily-stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Log daily contact creation and update statistics';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $yesterday = Carbon::yesterday();
        $startOfDay = $yesterday->copy()->startOfDay();
        $endOfDay = $yesterday->copy()->endOfDay();

        // Count contacts created yesterday
        $createdCount = Contact::whereBetween('created_at', [$startOfDay, $endOfDay])
                               ->count();

        // Count contacts updated yesterday (excluding newly created ones)
        $updatedCount = Contact::whereBetween('updated_at', [$startOfDay, $endOfDay])
                               ->where('created_at', '<', $startOfDay)
                               ->count();

        // Note: this could also be implemented as a single query using SUM(IF()), i.e.
        // SUM(IF(created_at BETWEEN $startOfDay AND $endOfDay, 1, 0)) as numCreated
        // SUM(IF(created_at < $startOfDay AND updated_at BETWEEN $startOfDay AND $endOfDay, 1, 0)) as numUpdated

        // Create a log entry
        Log::build([
            'driver' => 'single',
            'path' => storage_path("logs/contacts/{$yesterday->toDateString()}.log"),
        ])->info('Daily Contact Statistics', [
            'created' => $createdCount,
            'updated' => $updatedCount,
            'total_changes' => $createdCount + $updatedCount
        ]);

        $this->info("Statistics logged successfully for {$yesterday->toDateString()}");
    }
}
