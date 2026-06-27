<?php

namespace App\Services;

use App\Models\AutoTaskTemplate;
use App\Models\Quote;
use App\Models\Task;

class AutoTaskService
{
    public function triggerForStatusChange(Quote $quote, string $newStatus): void
    {
        $templates = AutoTaskTemplate::where('trigger_status', $newStatus)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        if ($templates->isEmpty()) {
            return;
        }

        $quote->loadMissing('customer', 'user');

        foreach ($templates as $template) {
            Task::create([
                'created_by_user_id'  => null,
                'assigned_to_user_id' => $template->assign_to_user_id,
                'quote_id'            => $quote->id,
                'title'               => $this->replaceVars($template->title_template, $quote),
                'description'         => $this->replaceVars($template->description_template ?? '', $quote),
                'status'              => 'open',
                'due_date'            => $template->due_days
                    ? now()->addDays($template->due_days)->toDateString()
                    : null,
            ]);
        }
    }

    private function replaceVars(string $text, Quote $quote): string
    {
        return str_replace(
            ['{{klant}}', '{{offerte_nr}}', '{{bedrag_eenmalig}}', '{{bedrag_jaarlijks}}', '{{verkoper}}'],
            [
                $quote->customer->company_name ?? '',
                $quote->quote_number,
                '€ ' . number_format((float) $quote->total_onetime_excl_vat, 2, ',', '.'),
                '€ ' . number_format((float) $quote->total_yearly_excl_vat, 2, ',', '.'),
                $quote->user->name ?? '',
            ],
            $text
        );
    }
}
