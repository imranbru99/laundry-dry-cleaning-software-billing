<x-filament-panels::page>
    <div class="grid gap-6 lg:grid-cols-2">
        <div class="space-y-4 rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-900">
            <h3 class="text-lg font-semibold">Add line</h3>
            <label class="block text-sm">Customer
                <select wire:model="customer_id" class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800">
                    <option value="">Walk-in</option>
                    @foreach ($this->customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="block text-sm">Item name
                <input wire:model="itemName" class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800" />
            </label>
            <div class="grid grid-cols-2 gap-3">
                <label class="block text-sm">Qty
                    <input type="number" min="1" wire:model="quantity" class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800" />
                </label>
                <label class="block text-sm">Unit price (minor)
                    <input type="number" min="0" wire:model="unitPrice" class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800" />
                </label>
            </div>
            <button type="button" wire:click="addToCart" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Add to cart</button>
        </div>
        <div class="space-y-4 rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-900">
            <h3 class="text-lg font-semibold">Cart</h3>
            <ul class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse ($cart as $line)
                    <li class="flex justify-between py-2 text-sm">
                        <span>{{ $line['quantity'] }} × {{ $line['name'] }}</span>
                        <span>{{ number_format(($line['quantity'] * $line['unit_price']) / 100, 2) }}</span>
                    </li>
                @empty
                    <li class="py-6 text-sm text-gray-500">Cart is empty. Scan or add items.</li>
                @endforelse
            </ul>
            <button type="button" wire:click="checkout" class="rounded-lg bg-teal-600 px-4 py-2 text-sm font-semibold text-white">Checkout & tag</button>
        </div>
    </div>
</x-filament-panels::page>
