{{-- <script src="https://cdn.jsdelivr.net/npm/dom-to-image-more@3.3.6/dist/dom-to-image-more.min.js"></script> --}}
<x-filament-panels::page>

    <x-filament::card class="space-y-4">
        <div style="display:flex;align-items:center;gap:8px;">
            <input
                type="text"
                placeholder="ID, Barcode yoki Name orqali qidirish"
                style="flex:1;padding:6px 10px;border:1px solid #ccc;border-radius:4px;"
                wire:model.live.debounce.300ms="search"
            />
            <x-filament::button wire:click="add" color="success">
                <span style="font-size:18px;">+</span> Qo'shish
            </x-filament::button>
        </div>

        @if(!empty($searchResults))
            <div style="margin-top:8px;border:1px solid #ddd;border-radius:6px;max-height:240px;overflow:auto;background:#fff;">
                @foreach($searchResults as $id => $name)
                    <div
                        style="padding:8px;border-bottom:1px solid #eee;cursor:pointer;"
                        wire:click="selectProduct({{ $id }})"
                    >
                        <strong>{{ $name }}</strong> <span style="color:#666;font-size:12px;">(ID: {{ $id }})</span>
                    </div>
                @endforeach
            </div>
        @endif

        @if(!empty($items))
            <div style="margin-top:12px;padding:8px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:6px;">
                <strong style="color:#1e40af;">Jami: {{ count($items) }} xil mahsulot</strong>
            </div>
        @endif
    </x-filament::card>

    @if(!empty($items))
        <x-filament::card style="margin-top:16px;">

            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
                <h3 style="font-size:18px;font-weight:600;">Mahsulotlar</h3>
                <x-filament::button wire:click="clearAll" color="danger" size="sm">
                    Tozalash
                </x-filament::button>
            </div>

            @php $barcodeGenerator = new \Milon\Barcode\DNS1D(); @endphp

            <div class="products-grid">
                @foreach ($items as $index => $item)
                    <div class="product-card">

                        <div style="display:flex;gap:10px;align-items:center;">

                            <div id="label-{{ $index }}" class="label-preview capture-area" wire:ignore>
                                <div class="label-name">{{ $item['product']->name }}</div>

                                <div class="label-barcode">
                                    {!! $barcodeGenerator->getBarcodeSVG($item['product']->barcode, 'C128', 2.2, 55, '#000') !!}
                                </div>

                                <div class="label-price">
                                    {{ number_format($item['product']->selling_price, 0, '', ' ') }} so'm
                                </div>
                            </div>

                            <div>
                                <div style="font-weight:600;">{{ $item['product']->name }}</div>
                                <div style="font-size:13px;color:#555;">Barcode: {{ $item['product']->barcode }}</div>
                                <div style="font-size:13px;color:#555;">Narx: {{ number_format($item['product']->selling_price, 0, '', ' ') }} so'm</div>
                            </div>

                        </div>
                        <a target="_blank" href="{{ route('label.show', $item['product']->id) }}">
                            <button class="download-btn">Yuklab olish</button>
                        </a>

                    </div>
                @endforeach
            </div>

        </x-filament::card>
    @endif

    <script>
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.download-btn');
            if (!btn) return;

            const index = btn.getAttribute('data-index');
            const node = document.getElementById('label-' + index);

            domtoimage.toPng(node, {
                bgcolor: '#ffffff',
                quality: 1
            }).then(function(dataUrl) {
                const link = document.createElement('a');
                link.download = 'barcode-' + index + '.png';
                link.href = dataUrl;
                link.click();
            }).catch(function(error) {
                console.error('Capture failed:', error);
            });
        });
        </script>

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
            border-radius:6px;
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
            background:#fff !important;
            box-sizing:border-box;
            display:flex;
            flex-direction:column;
            justify-content:space-between;
            align-items:center;
            text-align:center;
        }

        .label-name { font-size:11pt;font-weight:600;max-height:7mm;overflow:hidden;color:#000; }
        .label-price { font-size:11pt;font-weight:bold;color:#000; }

        .label-barcode { width:100%;height:14mm;display:flex;align-items:center;justify-content:center;margin:1mm 0; }
        .label-barcode svg { width:95%;height:100%; }
        .label-barcode text { display:none !important; }

        .download-btn {
            background:#2563eb;
            color:#fff;
            border:none;
            padding:6px;
            border-radius:6px;
            cursor:pointer;
            font-size:14px;
        }

        .download-btn:hover { background:#1e40af; }

        .capture-area,
        .capture-area * {
            color: #000 !important;
            background: #fff !important;
            box-shadow: none !important;
            filter: none !important;
        }
    </style>
</x-filament-panels::page>