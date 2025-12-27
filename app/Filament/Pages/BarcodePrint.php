<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Product;
use BackedEnum;
use UnitEnum;

class BarcodePrint extends Page
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-printer';

    protected static ?string $slug = 'barcode-print';
    protected string $view = 'filament.pages.barcode-print';
    protected static ?string $navigationLabel = 'Barcode Print';
    protected static string|UnitEnum|null $navigationGroup = 'Tools';

    public $search = '';
    public $searchResults = [];
    public $items = [];
    public $qty = 1;

    public function updatedSearch()
    {
        if (strlen($this->search) < 2) {
            $this->searchResults = [];
            return;
        }

        $this->searchResults = Product::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('barcode', 'like', '%' . $this->search . '%')
            ->orWhere('id', $this->search)
            ->limit(10)
            ->pluck('name', 'id')
            ->toArray();
    }

    public function selectProduct($productId)
    {
        $product = Product::find($productId);
        
        if (!$product) {
            return;
        }

        $exists = false;
        foreach ($this->items as $key => $item) {
            if ($item['product']->id === $productId) {
                $this->items[$key]['quantity'] += $this->qty;
                $exists = true;
                break;
            }
        }

        if (!$exists) {
            $this->items[] = [
                'product' => $product,
                'quantity' => $this->qty
            ];
        }

        $this->search = '';
        $this->searchResults = [];
        $this->qty = 1;

        $this->dispatch('product-added');
    }

    public function add()
    {
        if (strlen($this->search) < 1) {
            return;
        }

        $product = Product::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('barcode', $this->search)
            ->orWhere('id', $this->search)
            ->first();

        if ($product) {
            $this->selectProduct($product->id);
        }
    }

    public function clearAll()
    {
        $this->items = [];
        $this->search = '';
        $this->searchResults = [];
        $this->qty = 1;
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }
}