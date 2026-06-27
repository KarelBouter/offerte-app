<?php

namespace App\Livewire\Quotes;

use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\QuoteVersion;
use Livewire\Component;

class QuoteVersions extends Component
{
    public Quote $quote;
    public bool $confirmRestore = false;
    public ?int $restoreVersionId = null;

    public function restoreVersion(int $versionId): void
    {
        $this->restoreVersionId = $versionId;
        $this->confirmRestore = true;
    }

    public function confirmRestoreVersion(): void
    {
        $version = QuoteVersion::findOrFail($this->restoreVersionId);

        // Eerst snapshot van huidige staat opslaan (revisienummer loopt door)
        $this->quote->load('items.product');
        $this->quote->createVersion('Automatisch opgeslagen vóór terugzetten naar v' . $version->revision);

        // Quote-velden terugzetten zonder het revisienummer terug te draaien
        $snapshot = $version->quote_snapshot;
        unset($snapshot['revision']);
        $this->quote->update($snapshot);

        // Items vervangen
        $this->quote->items()->delete();
        foreach ($version->items_snapshot as $item) {
            QuoteItem::create([
                'quote_id'            => $this->quote->id,
                'product_id'          => $item['product_id'],
                'quantity'            => $item['quantity'],
                'unit_price_snapshot' => $item['unit_price_snapshot'],
                'is_auto_added'       => $item['is_auto_added'] ?? false,
                'sort_order'          => $item['sort_order'] ?? 0,
            ]);
        }

        $this->confirmRestore = false;
        $this->restoreVersionId = null;

        $newRevision = $this->quote->fresh()->revision;
        session()->flash('success', 'Offerte teruggezet naar inhoud van v' . $version->revision . '. Huidig revisienummer is v' . $newRevision . '.');
        $this->redirect(route('verkoper.offertes.show', $this->quote));
    }

    public function cancelRestore(): void
    {
        $this->confirmRestore = false;
        $this->restoreVersionId = null;
    }

    public function render()
    {
        return view('livewire.quotes.quote-versions', [
            'versions' => $this->quote->versions()->with('creator')->get(),
        ]);
    }
}
