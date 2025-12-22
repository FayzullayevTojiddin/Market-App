<x-filament::page>
    <div
        x-data="{ loading: true }"
        x-init="setTimeout(() => loading = false, 1200)"
        class="relative"
    >

        {{-- 🔄 LOADING --}}
        <div
            x-show="loading"
            class="fixed inset-0 z-50 flex flex-col items-center justify-center bg-white dark:bg-gray-900"
        >
            <img
                src="{{ asset('images/logo.svg') }}"
                class="w-24 mb-4 animate-pulse"
                alt="Logo"
            >

            <x-filament::loading-indicator class="h-6 w-6 text-primary-600" />

            <p class="mt-3 text-sm text-gray-500">Yuklanmoqda...</p>
        </div>

        {{-- 🔐 LOGIN FORM --}}
        <div x-show="!loading">
            {{ $this->form }}
        </div>

    </div>
</x-filament::page>