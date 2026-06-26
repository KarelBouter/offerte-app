<?php

namespace App\Livewire\Admin\Dependencies;

use App\Models\Product;
use App\Models\ProductDependency;
use Livewire\Component;

class Index extends Component
{
    public const RULE_LABELS = [
        'REQUIRED'            => 'Altijd vereist',
        'REQUIRED_CALCULATED' => 'Vereist (berekend aantal)',
        'THRESHOLD_SWITCH'    => 'Drempelschakelaar',
        'RECOMMENDED'         => 'Aanbevolen',
        'EXCLUDES'            => 'Sluit uit',
    ];

    // Product selector
    public ?int $selectedProductId = null;

    // Create/edit modal
    public bool $showModal = false;
    public ?int $editingDependencyId = null;

    // Form fields
    public string $rule_type = 'REQUIRED';
    public string $depends_on_product_id = '';
    public ?int $trigger_quantity_min = null;
    public ?int $trigger_quantity_max = null;
    public ?int $resulting_quantity = null;
    public string $resulting_quantity_formula = '';
    public string $replaces_product_id = '';

    // Test modal
    public bool $showTestModal = false;
    public int $testQuantity = 1;

    // ── Lifecycle ───────────────────────────────────────────────────────────

    public function mount(): void
    {
        $this->selectedProductId = null;
    }

    // ── Visibility helpers ──────────────────────────────────────────────────

    public function getShowTriggerMinProperty(): bool
    {
        return in_array($this->rule_type, ['REQUIRED_CALCULATED', 'THRESHOLD_SWITCH']);
    }

    public function getShowTriggerMaxProperty(): bool
    {
        return $this->rule_type === 'THRESHOLD_SWITCH';
    }

    public function getShowResultingQtyProperty(): bool
    {
        return in_array($this->rule_type, ['REQUIRED', 'REQUIRED_CALCULATED']);
    }

    public function getShowFormulaProperty(): bool
    {
        return $this->rule_type === 'REQUIRED_CALCULATED';
    }

    public function getShowReplacesProperty(): bool
    {
        return $this->rule_type === 'THRESHOLD_SWITCH';
    }

    // ── Test computed property ──────────────────────────────────────────────

    public function getTestResultsProperty(): array
    {
        if (! $this->showTestModal || ! $this->selectedProductId) {
            return [];
        }

        return ProductDependency::with(['dependsOnProduct', 'replacesProduct'])
            ->where('product_id', $this->selectedProductId)
            ->get()
            ->map(fn ($dep) => [
                'label'       => self::RULE_LABELS[$dep->rule_type] ?? $dep->rule_type,
                'description' => $this->simulateRule($dep, $this->testQuantity),
                'applies'     => $this->ruleApplies($dep, $this->testQuantity),
            ])
            ->all();
    }

    // ── Product selectie ────────────────────────────────────────────────────

    public function selectProduct(string $productId): void
    {
        $this->selectedProductId = $productId !== '' ? (int) $productId : null;
        $this->showModal         = false;
        $this->showTestModal     = false;
        $this->resetForm();
    }

    // ── Updated hooks (fallback) ─────────────────────────────────────────────

    public function updatedSelectedProductId(): void
    {
        $this->showModal     = false;
        $this->showTestModal = false;
        $this->resetForm();
    }

    // ── Modal open / close ──────────────────────────────────────────────────

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $dep = ProductDependency::findOrFail($id);
        $this->editingDependencyId       = $id;
        $this->rule_type                 = $dep->rule_type;
        $this->depends_on_product_id     = (string) $dep->depends_on_product_id;
        $this->trigger_quantity_min      = $dep->trigger_quantity_min;
        $this->trigger_quantity_max      = $dep->trigger_quantity_max;
        $this->resulting_quantity        = $dep->resulting_quantity;
        $this->resulting_quantity_formula = $dep->resulting_quantity_formula ?? '';
        $this->replaces_product_id       = (string) ($dep->replaces_product_id ?? '');
        $this->resetValidation();
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function openTestModal(): void
    {
        $this->testQuantity  = 1;
        $this->showTestModal = true;
    }

    public function closeTestModal(): void
    {
        $this->showTestModal = false;
        $this->testQuantity  = 1;
    }

    // ── Save ────────────────────────────────────────────────────────────────

    public function save(): void
    {
        $this->validate(
            [
                'rule_type'                  => 'required|in:REQUIRED,REQUIRED_CALCULATED,THRESHOLD_SWITCH,RECOMMENDED,EXCLUDES',
                'depends_on_product_id'      => 'required|exists:products,id',
                'trigger_quantity_min'       => 'nullable|integer|min:1',
                'trigger_quantity_max'       => 'nullable|integer|min:1',
                'resulting_quantity'         => 'nullable|integer|min:1',
                'resulting_quantity_formula' => 'nullable|string|max:255',
                'replaces_product_id'        => 'nullable|exists:products,id',
            ],
            [
                'rule_type.required'             => 'Regeltype is verplicht.',
                'rule_type.in'                   => 'Kies een geldig regeltype.',
                'depends_on_product_id.required' => 'Afhankelijk product is verplicht.',
                'depends_on_product_id.exists'   => 'Het gekozen product bestaat niet.',
                'trigger_quantity_min.integer'   => 'Minimaal aantal moet een geheel getal zijn.',
                'trigger_quantity_min.min'       => 'Minimaal aantal moet minimaal 1 zijn.',
                'trigger_quantity_max.integer'   => 'Maximaal aantal moet een geheel getal zijn.',
                'trigger_quantity_max.min'       => 'Maximaal aantal moet minimaal 1 zijn.',
                'resulting_quantity.integer'     => 'Resulterende hoeveelheid moet een geheel getal zijn.',
                'resulting_quantity.min'         => 'Resulterende hoeveelheid moet minimaal 1 zijn.',
                'replaces_product_id.exists'     => 'Het te vervangen product bestaat niet.',
            ]
        );

        $data = [
            'product_id'                 => $this->selectedProductId,
            'depends_on_product_id'      => (int) $this->depends_on_product_id,
            'rule_type'                  => $this->rule_type,
            'trigger_quantity_min'       => $this->showTriggerMin ? $this->trigger_quantity_min : null,
            'trigger_quantity_max'       => $this->showTriggerMax ? $this->trigger_quantity_max : null,
            'resulting_quantity'         => $this->showResultingQty ? $this->resulting_quantity : null,
            'resulting_quantity_formula' => $this->showFormula ? ($this->resulting_quantity_formula ?: null) : null,
            'replaces_product_id'        => $this->showReplaces && $this->replaces_product_id
                                                ? (int) $this->replaces_product_id
                                                : null,
        ];

        if ($this->editingDependencyId) {
            ProductDependency::findOrFail($this->editingDependencyId)->update($data);
            $message = 'Regel bijgewerkt.';
        } else {
            ProductDependency::create($data);
            $message = 'Regel toegevoegd.';
        }

        $this->closeModal();
        $this->dispatch('notify', message: $message);
    }

    // ── Delete ──────────────────────────────────────────────────────────────

    public function delete(int $id): void
    {
        ProductDependency::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Regel verwijderd.');
    }

    // ── Render ──────────────────────────────────────────────────────────────

    public function render()
    {
        $products = Product::orderBy('category')->orderBy('sort_order')->orderBy('name')
            ->get(['id', 'name', 'category']);

        $selectedProduct = $this->selectedProductId
            ? $products->firstWhere('id', $this->selectedProductId)
            : null;

        $dependencies = $this->selectedProductId
            ? ProductDependency::with(['dependsOnProduct', 'replacesProduct'])
                ->where('product_id', $this->selectedProductId)
                ->get()
            : collect();

        $otherProducts = $this->selectedProductId
            ? Product::where('is_active', true)
                ->where('id', '!=', $this->selectedProductId)
                ->orderBy('category')->orderBy('name')
                ->get(['id', 'name', 'category'])
            : collect();

        return view('livewire.admin.dependencies.index', [
            'products'            => $products,
            'selectedProductId'   => $this->selectedProductId,
            'selectedProductName' => $selectedProduct?->name ?? '',
            'dependencies'        => $dependencies,
            'otherProducts'       => $otherProducts,
            'ruleLabels'          => self::RULE_LABELS,
        ])->layout('layouts.app-admin', ['title' => 'Afhankelijkheden']);
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    private function resetForm(): void
    {
        $this->editingDependencyId        = null;
        $this->rule_type                  = 'REQUIRED';
        $this->depends_on_product_id      = '';
        $this->trigger_quantity_min       = null;
        $this->trigger_quantity_max       = null;
        $this->resulting_quantity         = null;
        $this->resulting_quantity_formula = '';
        $this->replaces_product_id        = '';
        $this->resetValidation();
    }

    private function evaluateFormula(string $formula, int $quantity): ?int
    {
        $expr = strtolower(str_replace(['trigger', ' '], [(string) $quantity, ''], $formula));

        if (preg_match('/^ceil\((.+?)\)$/', $expr, $m)) {
            $inner = $m[1];
            if (preg_match('/^([\d.]+)\/([\d.]+)$/', $inner, $p) && (float) $p[2] > 0) {
                return (int) ceil((float) $p[1] / (float) $p[2]);
            }
        }
        if (preg_match('/^floor\((.+?)\)$/', $expr, $m)) {
            $inner = $m[1];
            if (preg_match('/^([\d.]+)\/([\d.]+)$/', $inner, $p) && (float) $p[2] > 0) {
                return (int) floor((float) $p[1] / (float) $p[2]);
            }
        }
        if (preg_match('/^([\d.]+)\*([\d.]+)$/', $expr, $p)) {
            return (int) round((float) $p[1] * (float) $p[2]);
        }
        if (preg_match('/^([\d.]+)\/([\d.]+)$/', $expr, $p) && (float) $p[2] > 0) {
            return (int) round((float) $p[1] / (float) $p[2]);
        }

        return null;
    }

    private function simulateRule(ProductDependency $dep, int $qty): string
    {
        $product  = $dep->dependsOnProduct?->name ?? '?';
        $replaces = $dep->replacesProduct?->name;

        if ($dep->rule_type === 'REQUIRED') {
            return 'Voegt automatisch ' . ($dep->resulting_quantity ?? 1) . ' stuks ' . $product . ' toe';
        }

        if ($dep->rule_type === 'REQUIRED_CALCULATED') {
            if (! $dep->resulting_quantity_formula) {
                return 'Berekent ' . $product . ' (geen formule ingesteld)';
            }
            $n = $this->evaluateFormula($dep->resulting_quantity_formula, $qty);
            return $n !== null
                ? 'Voegt ' . $n . ' stuks ' . $product . ' toe (formule: ' . $dep->resulting_quantity_formula . ')'
                : 'Berekent ' . $product . ' via formule ' . $dep->resulting_quantity_formula . ' (kan niet automatisch worden berekend)';
        }

        if ($dep->rule_type === 'THRESHOLD_SWITCH') {
            $range = ($dep->trigger_quantity_min ?? '?') . '–' . ($dep->trigger_quantity_max ?? '∞');
            if ($this->ruleApplies($dep, $qty)) {
                $action = $replaces
                    ? 'Vervangt ' . $replaces . ' door ' . $product
                    : 'Voegt ' . $product . ' toe';
                return $action . ' (drempel actief: ' . $qty . ' valt in bereik ' . $range . ')';
            }
            return 'Drempel niet actief bij ' . $qty . ' stuks (bereik: ' . $range . ')';
        }

        if ($dep->rule_type === 'RECOMMENDED') {
            return 'Stelt ' . $product . ' voor aan de verkoper (kan worden afgevinkt)';
        }

        if ($dep->rule_type === 'EXCLUDES') {
            return 'Sluit ' . $product . ' uit — deze twee producten kunnen niet samen worden gekozen';
        }

        return '—';
    }

    private function ruleApplies(ProductDependency $dep, int $qty): bool
    {
        if ($dep->rule_type !== 'THRESHOLD_SWITCH') {
            return true;
        }

        return ($dep->trigger_quantity_min === null || $qty >= $dep->trigger_quantity_min)
            && ($dep->trigger_quantity_max === null || $qty <= $dep->trigger_quantity_max);
    }
}
