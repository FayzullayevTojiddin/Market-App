<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Label</title>

<style>
/* Printer uchun sahifa o‘lchami */
@page {
    size: 40mm 30mm;   /* LABEL RAZMER — shu yer muhim */
    margin: 0;
}

html, body {
    width: 40mm;
    height: 30mm;
    margin: 0;
    padding: 0;
}

body {
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Label konteyneri */
.label {
    width: 40mm;
    height: 30mm;
    box-sizing: border-box;
    border: 1px solid black;
    padding: 2mm;

    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: center;

    font-family: Arial, sans-serif;
    text-align: center;
}

/* Ichki dizayn */
.name {
    font-size: 12pt;
    font-weight: bold;
}

.barcode {
    width: 100%;
    height: 16mm;
    display: flex;
    justify-content: center;
    align-items: center;
}

.barcode svg {
    width: 100%;
    height: 100%;
}

.barcode text { display: none; }

.price {
    font-size: 12pt;
    font-weight: bold;
}
</style>
</head>

<body onload="window.print()">

<div class="label">
    <div class="name">{{ $product->name }}</div>

    <div class="barcode">
        {!! DNS1D::getBarcodeSVG($product->barcode, 'C128', 2.8, 65, '#000') !!}
    </div>

    <div class="price">
        {{ number_format($product->selling_price, 0, '', ' ') }} so'm
    </div>
</div>

</body>
</html>