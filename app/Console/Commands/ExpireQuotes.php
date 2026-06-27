<?php

namespace App\Console\Commands;

use App\Models\Quote;
use App\Services\ActivityLogService;
use App\Services\AutoTaskService;
use Illuminate\Console\Command;

class ExpireQuotes extends Command
{
    protected $signature   = 'quotes:expire';
    protected $description = 'Zet verlopen offertes (valid_until verstreken) op status "verlopen"';

    public function handle(ActivityLogService $activityLog, AutoTaskService $autoTask): int
    {
        $quotes = Quote::whereIn('status', ['concept', 'verzonden'])
            ->whereNotNull('valid_until')
            ->whereDate('valid_until', '<', today())
            ->with('customer', 'user')
            ->get();

        if ($quotes->isEmpty()) {
            $this->info('Geen verlopen offertes gevonden.');
            return self::SUCCESS;
        }

        $count = $quotes->count();
        foreach ($quotes as $quote) {
            $quote->update(['status' => 'verlopen']);
            $activityLog->log(
                'quote.expired',
                $quote,
                'Offerte '.$quote->quote_number.' automatisch verlopen (geldig t/m '.$quote->valid_until.')'
            );
            $autoTask->triggerForStatusChange($quote, 'verlopen');
        }

        $this->info("{$count} offerte(s) op verlopen gezet.");
        return self::SUCCESS;
    }
}
