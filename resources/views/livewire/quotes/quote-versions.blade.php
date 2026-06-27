<div>
    <h3 class="text-lg font-semibold text-gray-800 mb-4">
        Versiegeschiedenis
        <span class="text-sm font-normal text-gray-500 ml-2">
            Huidige revisie: <b>v{{ $quote->revision }}</b>
        </span>
    </h3>

    @if($versions->isEmpty())
        <p class="text-sm text-gray-500">Nog geen versies opgeslagen.</p>
    @else
        <div class="space-y-2">
            @foreach($versions as $version)
                <div class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">
                    <div>
                        <span class="text-sm font-medium text-gray-800">
                            v{{ $version->revision }} — {{ $version->label }}
                        </span>
                        <div class="text-xs text-gray-500 mt-0.5">
                            {{ $version->created_at->format('d-m-Y H:i') }}
                            @if($version->creator)
                                &nbsp;·&nbsp; {{ $version->creator->name }}
                            @endif
                        </div>
                        <div class="text-xs text-gray-500 mt-1">
                            Eenmalig: €{{ number_format($version->quote_snapshot['total_onetime_excl_vat'] ?? 0, 2, ',', '.') }}
                            &nbsp;·&nbsp;
                            Jaarlijks: €{{ number_format($version->quote_snapshot['total_yearly_excl_vat'] ?? 0, 2, ',', '.') }}
                            &nbsp;·&nbsp;
                            {{ count($version->items_snapshot) }} regel(s)
                        </div>
                    </div>
                    <button
                        wire:click="restoreVersion({{ $version->id }})"
                        class="text-xs text-blue-600 hover:text-blue-800 font-medium ml-4 whitespace-nowrap"
                    >
                        Terugzetten
                    </button>
                </div>
            @endforeach
        </div>
    @endif

    @if($confirmRestore)
        <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl shadow-xl p-6 max-w-md w-full mx-4">
                <h4 class="text-lg font-semibold text-gray-800 mb-2">Versie terugzetten?</h4>
                <p class="text-sm text-gray-600 mb-4">
                    De inhoud wordt teruggezet naar die versie. Het revisienummer loopt
                    gewoon door — de klant ziet altijd een nieuwer versienummer op de PDF.
                    De huidige staat wordt eerst automatisch opgeslagen.
                </p>
                <div class="flex gap-3 justify-end">
                    <button
                        wire:click="cancelRestore"
                        class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50"
                    >
                        Annuleren
                    </button>
                    <button
                        wire:click="confirmRestoreVersion"
                        class="px-4 py-2 text-sm text-white bg-blue-600 rounded-lg hover:bg-blue-700"
                    >
                        Ja, terugzetten
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
