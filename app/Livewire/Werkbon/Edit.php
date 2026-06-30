<?php

namespace App\Livewire\Werkbon;

use App\Models\Quote;
use App\Models\QuoteItem;
use App\Support\WerkbonAantekeningen;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Edit extends Component
{
    public Quote $quote;

    // Per product_id (string key):
    public array $installatieNotities  = [];
    public array $werkbonAantekeningen = [];
    public array $werkbonVerborgen     = [];
    // Per quote_item_id (string key), only for cable products:
    public array $cableRunNames        = [];

    public function mount(Quote $quote): void
    {
        abort_unless(Auth::user()->canEditWerkbon(), 403);

        $this->quote = $quote;
        $quote->load(['customer', 'items.product']);

        foreach ($quote->items as $item) {
            $key = (string) $item->product_id;

            $this->installatieNotities[$key]  = $item->installatie_notitie ?? '';
            $this->werkbonAantekeningen[$key]  = $item->werkbon_aantekening ?? '';
            // '' = null (product-instelling), '0' = geforceerd zichtbaar, '1' = geforceerd verborgen
            $this->werkbonVerborgen[$key] = $item->werkbon_verborgen === null
                ? ''
                : ($item->werkbon_verborgen ? '1' : '0');

            if ($item->cable_runs && is_array($item->cable_runs)) {
                $this->cableRunNames[(string) $item->id] = collect($item->cable_runs)
                    ->map(fn($run) => is_array($run) ? ($run['naam'] ?? '') : '')
                    ->toArray();
            }
        }
    }

    public function save(): void
    {
        abort_unless(Auth::user()->canEditWerkbon(), 403);

        $this->quote->load('items.product');

        foreach ($this->quote->items as $item) {
            $key     = (string) $item->product_id;
            $itemKey = (string) $item->id;

            $verborgenStr = $this->werkbonVerborgen[$key] ?? '';
            $updateData = [
                'installatie_notitie'  => $this->installatieNotities[$key] ?? null ?: null,
                'werkbon_aantekening'  => $this->werkbonAantekeningen[$key] ?? null ?: null,
                'werkbon_verborgen'    => $verborgenStr === '' ? null : (bool)(int) $verborgenStr,
            ];

            // Update cable run names if present
            if (isset($this->cableRunNames[$itemKey]) && $item->cable_runs) {
                $runs = $item->cable_runs;
                foreach ($this->cableRunNames[$itemKey] as $i => $naam) {
                    if (isset($runs[$i]) && is_array($runs[$i])) {
                        $runs[$i]['naam'] = $naam;
                    }
                }
                $updateData['cable_runs'] = $runs;
            }

            $item->update($updateData);
        }

        $this->quote->update([
            'werkbon_laatst_bewerkt_op'   => now(),
            'werkbon_laatst_bewerkt_door' => Auth::id(),
        ]);

        session()->flash('success', 'Werkbon opgeslagen.');
        $this->redirect(route('werkbon.edit', $this->quote), navigate: true);
    }

    public function render()
    {
        $layout = auth()->user()->role === 'admin' ? 'layouts.app-admin' : 'layouts.app-verkoper';

        $items = $this->quote->items->filter(fn($i) => $i->product !== null)->sortBy('sort_order');

        return view('livewire.werkbon.edit', [
            'items'      => $items,
            'aantekeningOpties' => WerkbonAantekeningen::OPTIES,
        ])->layout($layout, ['title' => 'Werkbon bewerken — ' . $this->quote->quote_number]);
    }
}
