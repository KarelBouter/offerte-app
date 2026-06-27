<x-layouts.public title="Offerte {{ $quote->quote_number }}">
<div class="space-y-8">

    {{-- Header --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Offerte {{ $quote->quote_number }}</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Opgesteld op {{ $quote->created_at->format('d-m-Y') }}
                    @if($quote->valid_until)
                        &middot; Geldig t/m {{ \Carbon\Carbon::parse($quote->valid_until)->format('d-m-Y') }}
                    @endif
                </p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                {{ $quote->status === 'ondertekend' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                {{ ucfirst($quote->status) }}
            </span>
        </div>
    </div>

    {{-- Klantgegevens --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Klantgegevens</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-2 gap-x-8 text-sm">
            <div>
                <span class="text-gray-500">Bedrijfsnaam</span>
                <p class="font-medium text-gray-800">{{ $quote->customer->company_name }}</p>
            </div>
            <div>
                <span class="text-gray-500">Contactpersoon</span>
                <p class="font-medium text-gray-800">{{ $quote->customer->contact_name }}</p>
            </div>
            <div>
                <span class="text-gray-500">E-mail</span>
                <p class="font-medium text-gray-800">{{ $quote->customer->contact_email }}</p>
            </div>
            @if($quote->customer->phone)
            <div>
                <span class="text-gray-500">Telefoon</span>
                <p class="font-medium text-gray-800">{{ $quote->customer->phone }}</p>
            </div>
            @endif
            @if($quote->customer->address)
            <div class="sm:col-span-2">
                <span class="text-gray-500">Adres</span>
                <p class="font-medium text-gray-800">
                    {{ $quote->customer->address }}
                    @if($quote->customer->postal_code || $quote->customer->city)
                        &mdash; {{ $quote->customer->postal_code }} {{ $quote->customer->city }}
                    @endif
                </p>
            </div>
            @endif
        </div>
    </div>

    {{-- Eenmalige kosten --}}
    @if($onetimeItems->count())
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-700">Eenmalige kosten</h2>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Product</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Aantal</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Stuksprijs</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Totaal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($onetimeItems as $item)
                <tr>
                    <td class="px-6 py-3 text-gray-800">
                        {{ $item->product->name }}
                        @if($item->product->description)
                            <p class="text-xs text-gray-400 mt-0.5">{{ $item->product->description }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-3 text-right text-gray-700">{{ $item->quantity }}</td>
                    <td class="px-6 py-3 text-right text-gray-700">€ {{ number_format($item->unit_price, 2, ',', '.') }}</td>
                    <td class="px-6 py-3 text-right font-medium text-gray-900">€ {{ number_format($item->quantity * $item->unit_price, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50 border-t border-gray-200">
                <tr>
                    <td colspan="3" class="px-6 py-3 text-right text-sm font-semibold text-gray-700">Subtotaal eenmalig</td>
                    <td class="px-6 py-3 text-right font-bold text-gray-900">
                        € {{ number_format($onetimeItems->sum(fn($i) => $i->quantity * $i->unit_price), 2, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif

    {{-- Jaarlijkse kosten --}}
    @if($yearlyItems->count())
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-700">Jaarlijkse kosten</h2>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Product</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Aantal</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Stuksprijs/jaar</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Totaal/jaar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($yearlyItems as $item)
                <tr>
                    <td class="px-6 py-3 text-gray-800">
                        {{ $item->product->name }}
                        @if($item->product->description)
                            <p class="text-xs text-gray-400 mt-0.5">{{ $item->product->description }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-3 text-right text-gray-700">{{ $item->quantity }}</td>
                    <td class="px-6 py-3 text-right text-gray-700">€ {{ number_format($item->unit_price, 2, ',', '.') }}</td>
                    <td class="px-6 py-3 text-right font-medium text-gray-900">€ {{ number_format($item->quantity * $item->unit_price, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50 border-t border-gray-200">
                <tr>
                    <td colspan="3" class="px-6 py-3 text-right text-sm font-semibold text-gray-700">Subtotaal per jaar</td>
                    <td class="px-6 py-3 text-right font-bold text-gray-900">
                        € {{ number_format($yearlyItems->sum(fn($i) => $i->quantity * $i->unit_price), 2, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif

    {{-- Prijs op offerte --}}
    @if($onQuoteItems->count())
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-700">Overige diensten (prijs op aanvraag)</h2>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Product</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Aantal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($onQuoteItems as $item)
                <tr>
                    <td class="px-6 py-3 text-gray-800">{{ $item->product->name }}</td>
                    <td class="px-6 py-3 text-right text-gray-700">{{ $item->quantity }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Opmerkingen --}}
    @if($quote->notes)
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Opmerkingen</h2>
        <p class="text-sm text-gray-700 whitespace-pre-line">{{ $quote->notes }}</p>
    </div>
    @endif

    {{-- Ondertekening --}}
    @if($quote->status !== 'ondertekend')
    <div class="bg-white border border-gray-200 rounded-xl p-6" id="ondertekening">
        <h2 class="text-lg font-semibold text-gray-800 mb-1">Offerte ondertekenen</h2>
        <p class="text-sm text-gray-500 mb-5">
            Bent u akkoord met deze offerte? Vul uw naam in en plaats uw handtekening.
        </p>

        <form method="POST" action="{{ route('quote.sign', $quote->sign_token) }}" id="signForm">
            @csrf

            @if(session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="mb-4">
                <label for="signer_name" class="block text-sm font-medium text-gray-700 mb-1">
                    Naam ondertekenaar *
                </label>
                <input
                    type="text"
                    id="signer_name"
                    name="signer_name"
                    value="{{ old('signer_name', $quote->customer->contact_name) }}"
                    required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Volledige naam"
                >
                @error('signer_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Handtekening * <span class="text-gray-400 font-normal">(teken hieronder met muis of vinger)</span>
                </label>
                <div class="border-2 border-gray-300 rounded-lg bg-gray-50 relative" style="touch-action: none;">
                    <canvas
                        id="signatureCanvas"
                        class="w-full rounded-lg cursor-crosshair"
                        style="display: block; height: 180px;"
                    ></canvas>
                    <button
                        type="button"
                        id="clearSignature"
                        class="absolute top-2 right-2 text-xs text-gray-400 hover:text-gray-600 bg-white border border-gray-200 rounded px-2 py-1"
                    >
                        Wissen
                    </button>
                </div>
                @error('signature')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <input type="hidden" name="signature" id="signatureInput">
            </div>

            <div class="mb-5 flex items-start gap-3">
                <input type="checkbox" id="akkoord" required
                    class="mt-0.5 h-4 w-4 rounded border-gray-300 text-blue-600">
                <label for="akkoord" class="text-sm text-gray-600">
                    Ik ga akkoord met de inhoud van deze offerte en geef Proud Innovations B.V.
                    opdracht de beschreven werkzaamheden uit te voeren onder de genoemde voorwaarden.
                </label>
            </div>

            <button
                type="submit"
                id="submitBtn"
                class="w-full bg-blue-700 hover:bg-blue-800 text-white font-semibold text-sm px-6 py-3 rounded-lg transition-colors"
            >
                Offerte ondertekenen
            </button>
        </form>
    </div>

    <script>
    (function () {
        const canvas  = document.getElementById('signatureCanvas');
        const ctx     = canvas.getContext('2d');
        const input   = document.getElementById('signatureInput');
        const clearBtn = document.getElementById('clearSignature');
        const form    = document.getElementById('signForm');

        let drawing = false;
        let hasDrawn = false;

        function setupCanvas() {
            const rect = canvas.getBoundingClientRect();
            const dpr  = window.devicePixelRatio || 1;
            canvas.width  = rect.width  * dpr;
            canvas.height = rect.height * dpr;
            ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
            ctx.strokeStyle = '#1a1a1a';
            ctx.lineWidth   = 2;
            ctx.lineCap     = 'round';
            ctx.lineJoin    = 'round';
        }

        setupCanvas();
        window.addEventListener('resize', () => { setupCanvas(); hasDrawn = false; input.value = ''; });

        function getPos(e) {
            const rect = canvas.getBoundingClientRect();
            const src  = e.touches ? e.touches[0] : e;
            return { x: src.clientX - rect.left, y: src.clientY - rect.top };
        }

        function startDraw(e) {
            e.preventDefault();
            drawing = true;
            const p = getPos(e);
            ctx.beginPath();
            ctx.moveTo(p.x, p.y);
        }

        function draw(e) {
            e.preventDefault();
            if (!drawing) return;
            const p = getPos(e);
            ctx.lineTo(p.x, p.y);
            ctx.stroke();
            hasDrawn = true;
        }

        function endDraw(e) {
            e.preventDefault();
            drawing = false;
        }

        canvas.addEventListener('mousedown',  startDraw);
        canvas.addEventListener('mousemove',  draw);
        canvas.addEventListener('mouseup',    endDraw);
        canvas.addEventListener('mouseleave', endDraw);
        canvas.addEventListener('touchstart', startDraw, { passive: false });
        canvas.addEventListener('touchmove',  draw,      { passive: false });
        canvas.addEventListener('touchend',   endDraw,   { passive: false });

        clearBtn.addEventListener('click', function () {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            hasDrawn  = false;
            input.value = '';
        });

        form.addEventListener('submit', function (e) {
            if (!hasDrawn) {
                e.preventDefault();
                alert('Plaats eerst uw handtekening in het vak.');
                return;
            }
            input.value = canvas.toDataURL('image/png');
        });
    })();
    </script>

    @else
    <div class="bg-green-50 border border-green-200 rounded-xl p-6 text-center">
        <p class="text-green-800 font-semibold text-lg mb-1">Ondertekend</p>
        <p class="text-green-700 text-sm">
            Ondertekend door <strong>{{ $quote->signed_by_name }}</strong>
            op {{ $quote->signed_at->format('d-m-Y \o\m H:i') }}.
        </p>
    </div>
    @endif

</div>
</x-layouts.public>
