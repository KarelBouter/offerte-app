{{--
    Onderhoudscontract toggle.
    Verwachte variabelen:
      $groep           — Onderhoudsgroep model (met basisproduct + perStukProduct eager loaded)
      $aantalStuks     — int: huidig aantal producten uit deze groep in de configurator
      $onderhoudscontracten — de Livewire property array (voor wire:model binding)
--}}
@if($aantalStuks > 0)
<div class="mt-3 pt-3 border-t border-gray-100">
    <div class="flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 p-3">
        <div class="flex-1">
            <p class="text-sm font-medium text-amber-900">Onderhoudscontract {{ $groep->naam }}</p>
            <p class="text-xs text-amber-700 mt-0.5">
                Basis:
                @if($groep->basisproduct)
                    &euro; {{ number_format($groep->basisproduct->unit_price, 2, ',', '.') }}/jaar
                @else
                    —
                @endif
                &nbsp;+&nbsp;
                {{ $aantalStuks }}&nbsp;&times;&nbsp;
                @if($groep->perStukProduct)
                    &euro; {{ number_format($groep->perStukProduct->unit_price, 2, ',', '.') }}/jaar per stuk
                @else
                    —
                @endif
                @if($groep->basisproduct && $groep->perStukProduct)
                    &nbsp;=&nbsp;&euro;&nbsp;{{ number_format(
                        $groep->basisproduct->unit_price + ($aantalStuks * $groep->perStukProduct->unit_price),
                        2, ',', '.'
                    ) }}/jaar
                @endif
            </p>
        </div>
        <label class="flex items-center gap-2 cursor-pointer flex-shrink-0 mt-0.5">
            <input type="checkbox"
                   wire:model.live="onderhoudscontracten.{{ $groep->id }}"
                   class="rounded border-amber-400 text-amber-600 shadow-sm focus:ring-amber-500"/>
            <span class="text-sm font-medium text-amber-900">Toevoegen</span>
        </label>
    </div>
</div>
@endif
