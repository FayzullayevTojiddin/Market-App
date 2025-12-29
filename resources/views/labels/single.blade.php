<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Label</title>

<style>
@page {
    size: 40mm 30mm;
    margin: 0;
}

* {
    box-sizing: border-box;
}

html, body {
    width: 40mm;
    height: 30mm;
    margin: 0;
    padding: 0;
    background: #fff;
}

/* Sahifa konteyneri */
.page {
    width: 40mm;
    height: 30mm;
    display: flex;
    justify-content: center;
    align-items: center;
    page-break-after: always;
}

.label {
    width: 100%;
    height: 100%;
    /* padding: 2mm; */

    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: center;
}

/* Matnlar */
.name {
    font-size: 5pt;
    font-weight: 700;
}

.price {
    font-size: 11pt;
    font-weight: 700;
}

/* Barcode */
.barcode {
    width: 100%;
    height: 16mm;
    display: flex;
    justify-content: center;
    align-items: center;
}

.barcode svg {
    width: 90%;
    height: 90%;
}

.barcode text {
    display: none !important;
}

/* Brauzer print effektlarini o‘chiramiz */
@media print {
    body {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    * {
        box-shadow: none !important;
        outline: none !important;
        border-radius: 0 !important;
    }
}
</style>
</head>

<body onload="window.print()">

@for($i = 0; $i < $count; $i++)
<div class="page">
    <div class="label">

        <div class="name">{{ $product->name }}</div>

        @php
            $len = strlen($product->barcode);

            if ($len <= 8) {
                $scale = 2.4;
            } elseif ($len <= 12) {
                $scale = 1.3;
            } elseif ($len <= 16) {
                $scale = 1;
            }else {
                $scale = 0.5;
            }

            $scale = 1.4;
        @endphp
        <div class="barcode">
            {!! DNS1D::getBarcodeSVG($product->barcode, 'C128', $scale, 60, '#000', true) !!}
        </div>

        <div class="price">
            {{ number_format($product->selling_price, 0, '', ' ') }} so'm
        </div>

    </div>
</div>
@endfor

</body>
</html>