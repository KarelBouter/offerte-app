<?php

namespace App\Livewire\Verkoper\Quotes;

use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductDependency;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Services\QuotePdfService;
use Livewire\Component;

class Create extends Component
{
    // ── Wizard step ────────────────────────────────────────────────────────
    public int $step = 1;
    public ?int $existingQuoteId = null;

    // ── Step 1: Customer ───────────────────────────────────────────────────
    public string $customerSearch = '';
    public array  $customerSuggestions = [];
    public ?int   $existingCustomerId = null;
    public string $companyName = '';
    public string $address = '';
    public string $kvkNumber = '';
    public string $contactName = '';
    public string $contactEmail = '';
    public string $contactPhone = '';
    public bool   $differentInstallAddress = false;
    public string $installationAddress = '';

    // ── Step 2: Configurator selections ───────────────────────────────────
    public string $hwChoice = '';    // product_id of selected hardware base option
    public string $svcChoice = '';   // product_id of selected service contract
    public array  $qtyInputs = [];   // [productId => qty] for all other products

    // Dependency engine output
    public array $autoItems = [];
    // [productId => ['quantity'=>int,'auto_added_reason'=>string,'is_recommended'=>bool,'is_optional_declined'=>bool]]
    public array $excludeMessages = [];
    public array $declinedRecommended = [];  // [productId => true]

    public string $notes = '';

    // ── Step 3: Review ─────────────────────────────────────────────────────
    public string $validUntil = '';
    public string $discountType  = '';
    public string $discountValue = '';

    // ── Products that are auto-add only (not shown in manual configurator) ─
    private const AUTO_ONLY_PRODUCTS = [
        'Firewall',
        'Switch standaard',
        'NUC node',
        '2.5" SSD',
        'PoE Switch 8-poorts',
        'PoE Switch 16-poorts',
        'NVR (Network Video Recorder)',
        'Installatie halve dag (±4 uur)',
        'Installatie hele dag (±8 uur)',
    ];

    // ── Lifecycle ──────────────────────────────────────────────────────────

    public function mount(?Quote $quote = null): void
    {
        $days = (int) (\App\Models\Setting::where('key', 'quote_validity_days')->value('value') ?? 30);
        $this->validUntil = now()->addDays($days)->format('Y-m-d');

        if ($quote?->exists) {
            $this->existingQuoteId = $quote->id;
            $customer = $quote->customer;
            $this->existingCustomerId = $customer->id;
            $this->companyName = $customer->company_name;
            $this->address = $customer->address;
            $this->kvkNumber = $customer->kvk_number;
            $this->contactName = $customer->contact_name;
            $this->contactEmail = $customer->contact_email;
            $this->contactPhone = $customer->contact_phone ?? '';
            $this->installationAddress = $quote->installation_address ?? '';
            $this->differentInstallAddress = !empty($quote->installation_address);
            $this->notes = $quote->notes ?? '';
            $this->validUntil    = $quote->valid_until->format('Y-m-d');
            $this->discountType  = $quote->discount_type ?? '';
            $this->discountValue = $quote->discount_value ? (string) $quote->discount_value : '';

            foreach ($quote->items()->where('is_auto_added', false)->with('product')->get() as $item) {
                $product = $item->product;
                if ($product->category === 'Hardware' && str_starts_with($product->name, 'Optie')) {
                    $this->hwChoice = (string) $item->product_id;
                } elseif ($product->category === 'Service') {
                    $this->svcChoice = (string) $item->product_id;
                } else {
                    $this->qtyInputs[(string) $item->product_id] = $item->quantity;
                }
            }

            $this->syncAndEvaluate();
        }
    }

    // ── Step 1: Customer search ────────────────────────────────────────────

    public function updatedCustomerSearch(): void
    {
        if (strlen($this->customerSearch) < 2) {
            $this->customerSuggestions = [];
            return;
        }

        $this->customerSuggestions = Customer::where('company_name', 'like', '%'.$this->customerSearch.'%')
            ->orWhere('kvk_number', 'like', '%'.$this->customerSearch.'%')
            ->limit(6)
            ->get(['id', 'company_name', 'address', 'kvk_number', 'contact_name', 'contact_email', 'contact_phone'])
            ->toArray();
    }

    public function selectCustomer(int $id): void
    {
        $c = Customer::findOrFail($id);
        $this->existingCustomerId = $c->id;
        $this->companyName   = $c->company_name;
        $this->address       = $c->address;
        $this->kvkNumber     = $c->kvk_number;
        $this->contactName   = $c->contact_name;
        $this->contactEmail  = $c->contact_email;
        $this->contactPhone  = $c->contact_phone ?? '';
        $this->customerSearch = $c->company_name;
        $this->customerSuggestions = [];
    }

    public function clearCustomer(): void
    {
        $this->existingCustomerId = null;
        $this->customerSearch = '';
        $this->companyName = $this->address = $this->kvkNumber = '';
        $this->contactName = $this->contactEmail = $this->contactPhone = '';
        $this->customerSuggestions = [];
    }

    // ── Step 2: Configurator Livewire hooks ────────────────────────────────

    public function updatedHwChoice(): void
    {
        $this->syncAndEvaluate();
    }

    public function updatedSvcChoice(): void
    {
        $this->syncAndEvaluate();
    }

    public function updatedQtyInputs($value, $key): void
    {
        $this->syncAndEvaluate();
    }

    public function updatedDiscountType(): void
    {
        if (!$this->discountType) {
            $this->discountValue = '';
        }
    }

    public function declineRecommended(string $productId): void
    {
        $this->declinedRecommended[$productId] = true;
        unset($this->autoItems[$productId]);
    }

    public function acceptRecommended(string $productId): void
    {
        unset($this->declinedRecommended[$productId]);
        $this->syncAndEvaluate();
    }

    // ── Step navigation ────────────────────────────────────────────────────

    public function nextStep(): void
    {
        if ($this->step === 1) {
            if (!$this->existingCustomerId) {
                $this->validate([
                    'companyName'          => 'required|string|max:255',
                    'address'              => 'required|string|max:500',
                    'kvkNumber'            => 'required|string|max:50',
                    'contactName'          => 'required|string|max:255',
                    'contactEmail'         => 'required|email|max:255',
                    'contactPhone'         => 'nullable|string|max:50',
                    'installationAddress'  => $this->differentInstallAddress ? 'required|string|max:500' : 'nullable',
                ], [
                    'companyName.required'         => 'Bedrijfsnaam is verplicht.',
                    'address.required'             => 'Adres is verplicht.',
                    'kvkNumber.required'           => 'KvK-nummer is verplicht.',
                    'contactName.required'         => 'Naam contactpersoon is verplicht.',
                    'contactEmail.required'        => 'E-mailadres contactpersoon is verplicht.',
                    'contactEmail.email'           => 'Voer een geldig e-mailadres in.',
                    'installationAddress.required' => 'Installatieadres is verplicht als dit afwijkt.',
                ]);
            }
            $this->step = 2;
            return;
        }

        if ($this->step === 2) {
            if (!$this->hwChoice) {
                $this->addError('hwChoice', 'Kies een hardware-configuratie om door te gaan.');
                return;
            }
            if (!$this->svcChoice) {
                $this->addError('svcChoice', 'Kies een servicecontract om door te gaan.');
                return;
            }
            $this->step = 3;
        }
    }

    public function prevStep(): void
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    // ── Save ───────────────────────────────────────────────────────────────

    public function save(bool $generatePdf = false): void
    {
        // Get or create customer
        if ($this->existingCustomerId) {
            $customer = Customer::findOrFail($this->existingCustomerId);
        } else {
            $customer = Customer::create([
                'company_name'  => $this->companyName,
                'address'       => $this->address,
                'kvk_number'    => $this->kvkNumber,
                'contact_name'  => $this->contactName,
                'contact_email' => $this->contactEmail,
                'contact_phone' => $this->contactPhone ?: null,
            ]);
        }

        $prices = $this->calculatePrices();
        $installAddr = $this->differentInstallAddress ? $this->installationAddress : null;

        $discountData = [
            'discount_type'             => $this->discountType ?: null,
            'discount_value'            => ($this->discountType && $this->discountValue !== '') ? (float) $this->discountValue : null,
            'onetime_subtotal_excl_vat' => $prices['onetimeSubtotal'],
            'total_onetime_excl_vat'    => $prices['onetimeExclVat'],
            'total_yearly_excl_vat'     => $prices['yearlyExclVat'],
        ];

        if ($this->existingQuoteId) {
            $quote = Quote::findOrFail($this->existingQuoteId);
            $quote->update(array_merge([
                'customer_id'          => $customer->id,
                'installation_address' => $installAddr,
                'valid_until'          => $this->validUntil,
                'notes'                => $this->notes ?: null,
            ], $discountData));
            $quote->items()->delete();
        } else {
            $quote = Quote::create(array_merge([
                'user_id'              => auth()->id(),
                'customer_id'          => $customer->id,
                'installation_address' => $installAddr,
                'status'               => 'concept',
                'valid_until'          => $this->validUntil,
                'notes'                => $this->notes ?: null,
            ], $discountData));
        }

        $sortOrder = 0;
        foreach ($this->getAllItems() as $productId => $item) {
            $product = Product::find((int) $productId);
            if (!$product) continue;

            QuoteItem::create([
                'quote_id'            => $quote->id,
                'product_id'          => (int) $productId,
                'quantity'            => $item['quantity'],
                'unit_price_snapshot' => $product->unit_price,
                'is_auto_added'       => $item['is_auto_added'],
                'auto_added_reason'   => $item['auto_added_reason'] ?? null,
                'is_optional_declined'=> false,
                'sort_order'          => $sortOrder++,
            ]);
        }

        $quote->load('items.product');
        $quote->createVersion();

        if ($generatePdf && auth()->user()->canGeneratePdf()) {
            app(QuotePdfService::class)->generate($quote);
            session()->flash('success', 'Offerte opgeslagen. PDF wordt gedownload.');
            session()->flash('auto_download_pdf', route('verkoper.offertes.pdf', $quote));
        } else {
            session()->flash('success', 'Offerte opgeslagen als concept.');
        }

        $this->redirect(route('verkoper.offertes.show', $quote));
    }

    // ── Dependency engine ──────────────────────────────────────────────────

    private function syncAndEvaluate(): void
    {
        $this->excludeMessages = [];

        // Start from manual items only
        $currentItems = $this->buildManualItems();

        $productNames = Product::pluck('name', 'id')->all();

        $changed = true;
        $iterations = 0;

        while ($changed && $iterations < 10) {
            $changed = false;
            $iterations++;

            $activeIds = array_keys(array_filter(
                $currentItems,
                fn ($i) => !($i['is_optional_declined'] ?? false)
            ));

            if (empty($activeIds)) {
                break;
            }

            $deps = ProductDependency::with(['dependsOnProduct:id,name', 'replacesProduct:id,name'])
                ->whereIn('product_id', $activeIds)
                ->get();

            foreach ($deps as $dep) {
                $triggerId  = (string) $dep->product_id;
                $targetId   = (string) $dep->depends_on_product_id;
                $triggerQty = $currentItems[$triggerId]['quantity'] ?? 1;

                switch ($dep->rule_type) {
                    case 'REQUIRED':
                        if (!array_key_exists($targetId, $currentItems)) {
                            $qty = $dep->resulting_quantity ?? 1;
                            $currentItems[$targetId] = $this->makeAutoItem(
                                $qty,
                                'Vereist bij: '.($productNames[$dep->product_id] ?? ''),
                                false
                            );
                            $changed = true;
                        }
                        break;

                    case 'REQUIRED_CALCULATED':
                        $min = $dep->trigger_quantity_min;
                        $max = $dep->trigger_quantity_max;
                        if (($min === null || $triggerQty >= $min) && ($max === null || $triggerQty <= $max)) {
                            $qty = $this->applyFormula($dep->resulting_quantity_formula, $triggerQty)
                                ?? ($dep->resulting_quantity ?? 1);
                            $prev = $currentItems[$targetId]['quantity'] ?? null;
                            if (!array_key_exists($targetId, $currentItems) || $prev !== $qty) {
                                $currentItems[$targetId] = $this->makeAutoItem(
                                    $qty,
                                    'Berekend vereist bij: '.($productNames[$dep->product_id] ?? ''),
                                    false
                                );
                                $changed = true;
                            }
                        }
                        break;

                    case 'THRESHOLD_SWITCH':
                        $min    = $dep->trigger_quantity_min;
                        $max    = $dep->trigger_quantity_max;
                        $inRange = ($min === null || $triggerQty >= $min)
                            && ($max === null || $triggerQty <= $max);

                        if ($inRange) {
                            if (!array_key_exists($targetId, $currentItems)) {
                                $currentItems[$targetId] = $this->makeAutoItem(
                                    $dep->resulting_quantity ?? 1,
                                    'Drempelschakelaar bij: '.($productNames[$dep->product_id] ?? ''),
                                    false
                                );
                                $changed = true;
                            }
                            $replacesId = $dep->replaces_product_id
                                ? (string) $dep->replaces_product_id
                                : null;
                            if ($replacesId
                                && array_key_exists($replacesId, $currentItems)
                                && ($currentItems[$replacesId]['is_auto_added'] ?? false)
                            ) {
                                unset($currentItems[$replacesId]);
                                $changed = true;
                            }
                        }
                        break;

                    case 'RECOMMENDED':
                        $isDeclined = $this->declinedRecommended[$targetId] ?? false;
                        if (!array_key_exists($targetId, $currentItems) && !$isDeclined) {
                            $currentItems[$targetId] = $this->makeAutoItem(
                                $dep->resulting_quantity ?? 1,
                                'Aanbevolen bij: '.($productNames[$dep->product_id] ?? ''),
                                true
                            );
                            $changed = true;
                        }
                        break;

                    case 'EXCLUDES':
                        if (array_key_exists($targetId, $currentItems)
                            && ($currentItems[$targetId]['is_auto_added'] ?? false)
                        ) {
                            $targetName = $productNames[$dep->depends_on_product_id] ?? 'onbekend';
                            $sourceName = $productNames[$dep->product_id] ?? 'onbekend';
                            $this->excludeMessages[] = "\"$targetName\" is verwijderd omdat het niet combineerbaar is met \"$sourceName\".";
                            unset($currentItems[$targetId]);
                            $changed = true;
                        }
                        break;
                }
            }
        }

        // Extract auto items from final state
        $this->autoItems = array_filter($currentItems, fn ($i) => $i['is_auto_added'] ?? false);
    }

    private function buildManualItems(): array
    {
        $items = [];

        if ($this->hwChoice) {
            $items[$this->hwChoice] = ['quantity' => 1, 'is_auto_added' => false,
                'auto_added_reason' => null, 'is_recommended' => false, 'is_optional_declined' => false];
        }

        if ($this->svcChoice) {
            $items[$this->svcChoice] = ['quantity' => 1, 'is_auto_added' => false,
                'auto_added_reason' => null, 'is_recommended' => false, 'is_optional_declined' => false];
        }

        $autoAddedIds = array_keys($this->autoItems);

        foreach ($this->qtyInputs as $productId => $qty) {
            $qty = (int) $qty;
            if ($qty > 0 && !in_array((int) $productId, $autoAddedIds)) {
                $items[(string) $productId] = ['quantity' => $qty, 'is_auto_added' => false,
                    'auto_added_reason' => null, 'is_recommended' => false, 'is_optional_declined' => false];
            }
        }

        return $items;
    }

    private function makeAutoItem(int $qty, string $reason, bool $isRecommended): array
    {
        return [
            'quantity'            => $qty,
            'is_auto_added'       => true,
            'auto_added_reason'   => $reason,
            'is_recommended'      => $isRecommended,
            'is_optional_declined'=> false,
        ];
    }

    private function applyFormula(?string $formula, int $triggerQty): ?int
    {
        if (!$formula) {
            return null;
        }
        if (preg_match('/^CEIL\(trigger\/(\d+)\)$/i', $formula, $m)) {
            return (int) ceil($triggerQty / (int) $m[1]);
        }
        return null;
    }

    // ── Price calculation ──────────────────────────────────────────────────

    public function getAutoAddedProductIds(): array
    {
        return array_keys($this->autoItems);
    }

    public function getAllItems(): array
    {
        $manual = $this->buildManualItems();
        $all    = $manual;

        foreach ($this->autoItems as $productId => $item) {
            if (!($item['is_optional_declined'] ?? false)) {
                $all[$productId] = $item;
            }
        }

        return $all;
    }

    private function calculatePrices(): array
    {
        $products = Product::whereIn('id', array_keys($this->getAllItems()))->get()->keyBy('id');

        $onetimeTotal = 0.0;
        $yearlyTotal  = 0.0;
        $lineItems    = [];
        $quoteItems   = [];

        foreach ($this->getAllItems() as $productId => $item) {
            $product = $products[(int) $productId] ?? null;
            if (!$product) {
                continue;
            }

            $qty = $item['quantity'];

            if ($product->is_price_on_quote) {
                $quoteItems[] = compact('product', 'qty', 'item');
                $lineItems[]  = ['product' => $product, 'qty' => $qty,
                    'unit_price' => null, 'total' => null, 'item' => $item];
                continue;
            }

            $total = $product->unit_price * $qty;

            if ($product->unit === 'jaar') {
                $yearlyTotal += $total;
            } else {
                $onetimeTotal += $total;
            }

            $lineItems[] = ['product' => $product, 'qty' => $qty,
                'unit_price' => $product->unit_price, 'total' => $total, 'item' => $item];
        }

        $discountAmount = 0.0;
        if ($this->discountType && $this->discountValue !== '') {
            $val = (float) $this->discountValue;
            if ($val > 0) {
                if ($this->discountType === 'percentage') {
                    $discountAmount = round($onetimeTotal * ($val / 100), 2);
                } else {
                    $discountAmount = min($val, $onetimeTotal);
                }
            }
        }
        $onetimeAfterDiscount = $onetimeTotal - $discountAmount;

        return [
            'lineItems'       => $lineItems,
            'onetimeSubtotal' => $onetimeTotal,
            'discountAmount'  => $discountAmount,
            'onetimeExclVat'  => $onetimeAfterDiscount,
            'onetimeVat'      => $onetimeAfterDiscount * 0.21,
            'onetimeInclVat'  => $onetimeAfterDiscount * 1.21,
            'yearlyExclVat'   => $yearlyTotal,
            'yearlyVat'       => $yearlyTotal * 0.21,
            'yearlyInclVat'   => $yearlyTotal * 1.21,
        ];
    }

    // ── Render ─────────────────────────────────────────────────────────────

    public function render()
    {
        $allProducts = Product::where('is_active', true)
            ->orderBy('sort_order')->orderBy('name')
            ->get();

        $productsByCategory = $allProducts->groupBy('category');
        $autoOnlyNames      = self::AUTO_ONLY_PRODUCTS;

        $previewNumber = sprintf(
            'PI-%d-%04d',
            now()->year,
            Quote::whereYear('created_at', now()->year)->count() + ($this->existingQuoteId ? 0 : 1)
        );

        $title  = $this->existingQuoteId ? 'Offerte bewerken' : 'Nieuwe offerte';
        $layout = auth()->user()->role === 'admin' ? 'layouts.app-admin' : 'layouts.app-verkoper';

        return view('livewire.verkoper.quotes.create', [
            'productsByCategory' => $productsByCategory,
            'autoOnlyNames'      => $autoOnlyNames,
            'prices'             => $this->calculatePrices(),
            'previewNumber'      => $previewNumber,
            'autoAddedIds'       => $this->getAutoAddedProductIds(),
        ])->layout($layout, ['title' => $title]);
    }
}
