<x-filament-panels::page>
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($modules as $module)
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="text-base font-semibold text-gray-950 dark:text-white">{{ $module['name'] }}</h3>
                        <p class="mt-1 text-sm text-gray-500">Key: {{ $module['key'] }}</p>
                        @if (! empty($module['dependencies']))
                            <p class="mt-2 text-xs text-gray-400">Depends on: {{ implode(', ', $module['dependencies']) }}</p>
                        @endif
                    </div>
                    @if ($module['always_on'])
                        <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-800">Always on</span>
                    @else
                        <button
                            wire:click="toggle('{{ $module['key'] }}')"
                            type="button"
                            class="rounded-full px-3 py-1 text-xs font-semibold {{ $module['enabled'] ? 'bg-teal-600 text-white' : 'bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-200' }}"
                        >
                            {{ $module['enabled'] ? 'ON' : 'OFF' }}
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
