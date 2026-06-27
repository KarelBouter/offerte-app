<div>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Klanten</h1>
        <input
            wire:model.live.debounce.300ms="search"
            type="text"
            placeholder="Zoek op naam, contactpersoon of KvK..."
            class="border border-gray-300 rounded-lg px-4 py-2 text-sm w-72 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-4 py-3 font-medium text-gray-600">Bedrijf</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-600">Contactpersoon</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-600">E-mail</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-600">Offertes</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($customers as $customer)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $customer->company_name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $customer->contact_name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $customer->contact_email }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $customer->quotes_count }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('verkoper.klanten.show', $customer) }}"
                               class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                Bekijken →
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                            Geen klanten gevonden.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $customers->links() }}
    </div>
</div>
