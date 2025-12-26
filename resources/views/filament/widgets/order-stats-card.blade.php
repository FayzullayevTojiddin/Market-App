<x-filament-widgets::widget>
    <x-filament::section>
        {{ $this->form }}

        <div class="grid grid-cols-5 gap-4 mt-4">
            <div class="p-2 bg-primary text-white text-center">Orderlar: {{ $this->getStats()['totalOrders'] }}</div>
            <div class="p-2 bg-success text-white text-center">Savdo: {{ number_format($this->getStats()['totalAmount']) }} so'm</div>
            <div class="p-2 bg-success text-white text-center">Naqd: {{ number_format($this->getStats()['totalCash']) }} so'm</div>
            <div class="p-2 bg-info text-white text-center">Karta: {{ number_format($this->getStats()['totalCard']) }} so'm</div>
            <div class="p-2 bg-danger text-white text-center">Qarz: {{ number_format($this->getStats()['totalDebt']) }} so'm</div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
