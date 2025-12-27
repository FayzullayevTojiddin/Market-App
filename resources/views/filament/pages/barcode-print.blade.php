<x-filament-panels::page>
    <x-filament::card class="space-y-4">
        <div style="display: flex; align-items: center; gap: 8px;">
            <input
                type="text"
                placeholder="ID, Barcode yoki Name orqali qidirish"
                style="flex: 1; padding: 6px 10px; border: 1px solid #ccc; border-radius: 4px;"
                wire:model.live.debounce.300ms="search"
            />
            <input
                type="number"
                wire:model="qty"
                min="1"
                value="1"
                placeholder="Soni"
                style="width: 80px; padding: 6px 10px; border: 1px solid #ccc; border-radius: 4px;"
            />
            <x-filament::button wire:click="add" color="success">
                <span style="font-size: 18px;">+</span> Qo'shish
            </x-filament::button>
        </div>

        @if(!empty($searchResults))
            <div class="mt-2 border rounded max-h-60 overflow-y-auto bg-white shadow-sm">
                @foreach($searchResults as $id => $name)
                    <div
                        class="px-3 py-2 hover:bg-gray-100 cursor-pointer border-b last:border-b-0 transition"
                        wire:click="selectProduct({{ $id }})"
                    >
                        <strong>{{ $name }}</strong> <span class="text-gray-500 text-sm">(ID: {{ $id }})</span>
                    </div>
                @endforeach
            </div>
        @endif

        @if(!empty($items))
            <div class="mt-4 p-3 bg-blue-50 rounded border border-blue-200">
                <strong class="text-blue-800">Jami: {{ count($items) }} xil mahsulot, 
                    {{ array_sum(array_column($items, 'quantity')) }} ta etiket</strong>
            </div>
        @endif
    </x-filament::card>

    @if(!empty($items))
        <x-filament::card class="mt-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Etiketlar ko'rinishi</h3>
                <div class="flex gap-2">
                    <x-filament::button wire:click="clearAll" color="danger" size="sm">
                        Tozalash
                    </x-filament::button>
                    <x-filament::button onclick="window.print()" color="success" icon="heroicon-o-printer">
                        Chop etish
                    </x-filament::button>
                </div>
            </div>

            @php
                $barcodeGenerator = new \Milon\Barcode\DNS1D();
            @endphp

            <div id="print-area">
                <div class="labels-grid">
                    @foreach ($items as $item)
                        @for ($i = 0; $i < $item['quantity']; $i++)
                            <div class="label-item">
                                <div class="label-content">
                                    <div class="product-name">{{ $item['product']->name }}</div>
                                    
                                    <div class="barcode-zone">
                                        @if(isset($item['product']->barcode) && $item['product']->barcode)
                                            <div class="barcode-svg">
                                                {!! $barcodeGenerator->getBarcodeSVG($item['product']->barcode, 'C128', 1.4, 22) !!}
                                            </div>
                                            <div class="barcode-text">{{ $item['product']->barcode }}</div>
                                        @endif
                                    </div>
                                    
                                    <div class="product-price">
                                        {{ isset($item['product']->selling_price)
                                            ? number_format($item['product']->selling_price, 0, '', ' ') . ' so`m' 
                                            : '—' 
                                        }}
                                    </div>
                                </div>
                            </div>
                        @endfor
                    @endforeach
                </div>
            </div>
        </x-filament::card>
    @endif

    <style>
        /* Umumiy stillar - ekran va print uchun */
        .labels-grid {
            display: grid;
            grid-template-columns: repeat(5, 40mm);
            gap: 2mm;
            justify-content: center;
            padding: 5mm;
            background: #f9fafb;
            border-radius: 8px;
        }

        .label-item {
            width: 40mm;
            height: 30mm;
            background: white;
            border: 1px dashed #d1d5db;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .label-content {
            width: 100%;
            height: 100%;
            padding: 2mm;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .product-name {
            font-size: 7pt;
            font-weight: bold;
            line-height: 1.2;
            text-align: center;
            max-height: 8mm;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            color: #1f2937;
        }

        .barcode-zone {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1mm 0;
        }

        .barcode-svg {
            width: 100%;
            max-height: 10mm;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .barcode-svg svg {
            max-width: 36mm;
            max-height: 10mm;
            height: auto;
        }

        .barcode-text {
            font-size: 5pt;
            margin-top: 0.5mm;
            color: #6b7280;
            text-align: center;
        }

        .product-price {
            font-size: 8pt;
            font-weight: bold;
            text-align: center;
            color: #000;
            border-top: 1px solid #e5e7eb;
            padding-top: 1mm;
            margin-top: 1mm;
        }

        /* Print stillari */
        @media print {
            body * {
                visibility: hidden;
            }

            #print-area,
            #print-area * {
                visibility: visible;
            }

            #print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .labels-grid {
                background: white;
                padding: 5mm;
                gap: 2mm;
            }

            .label-item {
                border: 1px solid #000;
                box-shadow: none;
            }

            @page {
                size: A4 portrait;
                margin: 5mm;
            }

            /* Har bir etiket alohida bo'linmasligini ta'minlash */
            .label-item {
                page-break-inside: avoid;
                break-inside: avoid;
            }
        }

        /* Ekran uchun qo'shimcha stillar */
        @media screen {
            .label-item {
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
                transition: transform 0.2s, box-shadow 0.2s;
            }

            .label-item:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15);
                border-color: #3b82f6;
            }
        }
    </style>
</x-filament-panels::page>