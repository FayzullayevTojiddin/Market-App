<div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6">Yangi buyurtma yaratish</h2>
    
    <form id="orderForm" action="{{ route('orders.store') }}" method="POST">
        @csrf
        
        <!-- Mijoz tanlash -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Mijoz <span class="text-red-500">*</span>
            </label>
            <select name="customer_id" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required>
                <option value="">Mijozni tanlang</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
            @error('customer_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Holat -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Holati <span class="text-red-500">*</span>
            </label>
            <select name="status" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required>
                <option value="new" selected>Yangi</option>
                <option value="paid">To'langan</option>
                <option value="cancelled">Bekor qilingan</option>
            </select>
        </div>

        <!-- Mahsulotlar -->
        <div class="mb-6">
            <div class="flex justify-between items-center mb-4">
                <label class="block text-sm font-medium text-gray-700">
                    Mahsulotlar <span class="text-red-500">*</span>
                </label>
                <button type="button" 
                        onclick="addProduct()"
                        class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                    + Mahsulot qo'shish
                </button>
            </div>

            <div id="productsContainer" class="space-y-4">
                <!-- Birinchi mahsulot qatori -->
                <div class="product-item border border-gray-200 rounded-lg p-4 bg-gray-50">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Mahsulot -->
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Mahsulot</label>
                            <select name="products[0][product_id]" 
                                    class="product-select w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500"
                                    data-index="0"
                                    onchange="updatePrice(0)"
                                    required>
                                <option value="">Tanlang</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}">
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Soni -->
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Soni</label>
                            <input type="number" 
                                   name="products[0][count]"
                                   class="product-count w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                                   value="1"
                                   min="1"
                                   oninput="updatePrice(0)"
                                   required>
                        </div>

                        <!-- Chegirma -->
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Chegirma (%)</label>
                            <input type="number" 
                                   name="products[0][discount]"
                                   class="product-discount w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                                   value="0"
                                   min="0"
                                   max="100"
                                   step="0.01"
                                   oninput="updatePrice(0)">
                        </div>

                        <!-- Jami summa -->
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Jami summa</label>
                            <div class="flex items-center gap-2">
                                <input type="number" 
                                       name="products[0][price_summ]"
                                       class="product-total flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm bg-gray-100"
                                       readonly
                                       step="0.01"
                                       value="0">
                                <button type="button" 
                                        onclick="removeProduct(this)"
                                        class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm"
                                        title="O'chirish">
                                    ✕
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Umumiy summa -->
        <div class="mb-6 p-4 bg-blue-50 rounded-lg">
            <div class="flex justify-between items-center">
                <span class="text-lg font-semibold text-gray-700">Umumiy summa:</span>
                <span id="grandTotal" class="text-2xl font-bold text-blue-600">0 so'm</span>
            </div>
        </div>

        <!-- Tugmalar -->
        <div class="flex gap-4">
            <button type="submit" 
                    class="flex-1 px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition font-medium">
                Saqlash
            </button>
            <a href="{{ route('orders.index') }}" 
               class="flex-1 px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition font-medium text-center">
                Bekor qilish
            </a>
        </div>
    </form>
</div>

<script>
let productIndex = 1;

// Mahsulot ma'lumotlari (backend'dan)
const products = @json($products->keyBy('id'));

function addProduct() {
    const container = document.getElementById('productsContainer');
    const newProduct = `
        <div class="product-item border border-gray-200 rounded-lg p-4 bg-gray-50">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Mahsulot</label>
                    <select name="products[${productIndex}][product_id]" 
                            class="product-select w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                            data-index="${productIndex}"
                            onchange="updatePrice(${productIndex})"
                            required>
                        <option value="">Tanlang</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}">
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Soni</label>
                    <input type="number" 
                           name="products[${productIndex}][count]"
                           class="product-count w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                           value="1"
                           min="1"
                           oninput="updatePrice(${productIndex})"
                           required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Chegirma (%)</label>
                    <input type="number" 
                           name="products[${productIndex}][discount]"
                           class="product-discount w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                           value="0"
                           min="0"
                           max="100"
                           step="0.01"
                           oninput="updatePrice(${productIndex})">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Jami summa</label>
                    <div class="flex items-center gap-2">
                        <input type="number" 
                               name="products[${productIndex}][price_summ]"
                               class="product-total flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm bg-gray-100"
                               readonly
                               step="0.01"
                               value="0">
                        <button type="button" 
                                onclick="removeProduct(this)"
                                class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm">
                            ✕
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', newProduct);
    productIndex++;
}

function removeProduct(button) {
    const productItems = document.querySelectorAll('.product-item');
    if (productItems.length > 1) {
        button.closest('.product-item').remove();
        updateGrandTotal();
    } else {
        alert('Kamida bitta mahsulot bo\'lishi kerak!');
    }
}

function updatePrice(index) {
    const item = document.querySelector(`select[data-index="${index}"]`).closest('.product-item');
    const select = item.querySelector('.product-select');
    const countInput = item.querySelector('.product-count');
    const discountInput = item.querySelector('.product-discount');
    const totalInput = item.querySelector('.product-total');

    const productId = select.value;
    const count = parseFloat(countInput.value) || 1;
    const discount = parseFloat(discountInput.value) || 0;

    if (!productId || !products[productId]) {
        totalInput.value = 0;
        updateGrandTotal();
        return;
    }

    const product = products[productId];
    let price = product.selling_price * count;
    price -= (price * discount / 100);

    totalInput.value = price.toFixed(2);
    updateGrandTotal();
}

function updateGrandTotal() {
    const totals = document.querySelectorAll('.product-total');
    let grandTotal = 0;
    
    totals.forEach(input => {
        grandTotal += parseFloat(input.value) || 0;
    });

    document.getElementById('grandTotal').textContent = 
        grandTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, " ") + ' so\'m';
}
</script>