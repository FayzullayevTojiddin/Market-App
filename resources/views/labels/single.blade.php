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

html, body {
    margin: 0;
    padding: 0;
}

/* Har bir label alohida sahifa bo‘lsin */
.page {
    width: 40mm;
    height: 30mm;
    page-break-after: always;

    display: flex;
    justify-content: center;
    align-items: center;
}

.label {
    width: 38mm;
    height: 28mm;
    border: 1px solid #000;
    padding: 1mm;
    box-sizing: border-box;

    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: center;

    font-family: Arial, sans-serif;
    text-align: center;
}

.name { font-size: 11pt; font-weight: 700; }
.price { font-size: 11pt; font-weight: 700; }

.barcode {
    width: 100%;
    height: 16mm;
    display: flex;
    justify-content: center;
    align-items: center;
}

.barcode svg { width: 100%; height: 100%; }
.barcode text { display: none; }
</style>
</head>

<body onload="window.print()">

@for($i = 0; $i < $count; $i++)
<div class="page">
    <div class="label">
        <div class="name">{{ $product->name }}</div>

        <div class="barcode">
            {!! DNS1D::getBarcodeSVG($product->barcode, 'C128', 2.8, 65, '#000') !!}
        </div>

        <div class="price">
            {{ number_format($product->selling_price, 0, '', ' ') }} so'm
        </div>
    </div>
</div>
@endfor

</body>
</html>