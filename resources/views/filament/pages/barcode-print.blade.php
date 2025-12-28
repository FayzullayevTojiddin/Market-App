<x-filament-panels::page>

<x-filament::card class="space-y-4">

<div style="display:flex;align-items:center;gap:8px;">
    <input
        type="text"
        placeholder="Поиск по ID, штрихкоду или названию"
        style="flex:1;padding:8px 12px;border:1px solid #ccc;border-radius:6px;"
        wire:model.live.debounce.300ms="search"
    />
    <x-filament::button wire:click="add" color="success">
        <span style="font-size:18px;">+</span> Добавить
    </x-filament::button>
</div>

@if(!empty($searchResults))
<div style="margin-top:8px;border:1px solid #ddd;border-radius:6px;max-height:240px;overflow:auto;background:#fff;">
    @foreach($searchResults as $id => $name)
        <div
            style="padding:10px;border-bottom:1px solid #eee;cursor:pointer; color: black;"
            wire:click="selectProduct({{ $id }})"
        >
            <strong>{{ $name }}</strong>
            <span style="color:#666;font-size:12px;">(ID: {{ $id }})</span>
        </div>
    @endforeach
</div>
@endif

@if(!empty($items))
<div style="margin-top:12px;padding:10px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:6px;">
    <strong style="color:#1e40af;">Всего: {{ count($items) }} товаров</strong>
</div>
@endif

</x-filament::card>

@if(!empty($items))
<x-filament::card style="margin-top:16px;">

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
    <h3 style="font-size:18px;font-weight:600;">Товары</h3>
    <x-filament::button wire:click="clearAll" color="danger" size="sm">
        Очистить
    </x-filament::button>
</div>

@php $barcodeGenerator = new \Milon\Barcode\DNS1D(); @endphp

<div class="products-grid">
@foreach ($items as $index => $item)

<div class="product-card">

<div style="display:flex;gap:10px;align-items:center;">

<div id="label-{{ $index }}" class="label-preview" wire:ignore>
    <div class="label-name">{{ $item['product']->name }}</div>
    <div class="label-barcode">
        {!! $barcodeGenerator->getBarcodeSVG($item['product']->barcode, 'C128', 2.2, 55, '#000') !!}
    </div>
    <div class="label-price">
        {{ number_format($item['product']->selling_price, 0, '', ' ') }} сум
    </div>
</div>

<div>
    <div class="product-title">{{ $item['product']->name }}</div>
    <div class="product-meta">Штрихкод: {{ $item['product']->barcode }}</div>
    <div class="product-meta">Цена: {{ number_format($item['product']->selling_price, 0, '', ' ') }} сум</div>
</div>

</div>

<div class="print-controls">
    <input
        type="number"
        min="1"
        value="1"
        id="copies-{{ $index }}"
        class="copies-input"
    >

    <button
        class="print-btn"
        onclick="
            let count = document.getElementById('copies-{{ $index }}').value;
            window.open('{{ url('/label') }}/{{ $item['product']->id }}/' + count, '_blank');
        "
    >
        Печать
    </button>
</div>

</div>

@endforeach
</div>

</x-filament::card>
@endif

<style>

.products-grid {
    display:flex;
    flex-wrap:wrap;
    gap:12px;
}

.product-card {
    width:260px;
    background:#f9fafb;
    border:1px solid #ddd;
    border-radius:8px;
    padding:10px;
    display:flex;
    flex-direction:column;
    gap:8px;
}

.label-preview {
    width:40mm;
    height:30mm;
    border:1px solid #000;
    padding:2mm;
    background:#fff;
    box-sizing:border-box;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    align-items:center;
    text-align:center;
}

.label-name { font-size:11pt;font-weight:600;max-height:7mm;overflow:hidden; color: black;}
.label-price { font-size:11pt;font-weight:bold; color: black;}

.label-barcode { width:100%;height:14mm;display:flex;align-items:center;justify-content:center;margin:1mm 0; }
.label-barcode svg { width:95%;height:100%; }
.label-barcode text { display:none; }

.product-title { font-weight:600; color: black;}
.product-meta { font-size:13px;color:black; }

.print-controls {
    display:flex;
    gap:6px;
}

.copies-input {
    width:60px;
    padding:6px 8px;
    border:1px solid #ccc;
    border-radius:6px;
    font-size:14px;
    color: black;
}

.print-btn {
    flex:1;
    background:#2563eb;
    color:white;
    border:none;
    padding:7px;
    border-radius:6px;
    font-size:14px;
    cursor:pointer;
}

.print-btn:hover {
    background:#1e40af;
}

</style>

</x-filament-panels::page>