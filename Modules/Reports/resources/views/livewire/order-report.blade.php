<div
    x-data="{
        activeTab: 'overview',
        showDaily: false
    }"
    class="space-y-6"
>
    {{-- Header --}}
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Order Reports</h1>
            <p class="mt-1 text-sm text-gray-500">Live stats powered by Livewire 4 + Alpine.js</p>
        </div>
        {{-- Filters — wire:model causes instant re-render (Livewire reactive binding) --}}
        <div class="flex items-center gap-3">
            <select
                wire:model.live="period"
                class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-amber-400 focus:ring-2 focus:ring-amber-200 focus:outline-none"
            >
                @foreach ($periods as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>

            <select
                wire:model.live="status"
                class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-amber-400 focus:ring-2 focus:ring-amber-200 focus:outline-none"
            >
                @foreach ($statuses as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Stat cards (filtered) — re-render on filter change via Livewire --}}
    <div class="grid grid-cols-2 gap-4">
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Orders in period</p>
            <p class="mt-2 text-4xl font-bold text-gray-900" wire:loading.class="opacity-40">
                {{ number_format($this->stats['count']) }}
            </p>
        </div>
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Revenue in period</p>
            <p class="mt-2 text-4xl font-bold text-emerald-600" wire:loading.class="opacity-40">
                ${{ number_format($this->stats['revenue'], 2) }}
            </p>
        </div>
    </div>

    {{-- Alpine.js tabs — purely client-side, no server round-trip --}}
    <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-100 overflow-hidden">
        <div class="flex border-b border-gray-100">
            <button
                @click="activeTab = 'overview'"
                :class="activeTab === 'overview' ? 'border-b-2 border-amber-400 text-amber-600' : 'text-gray-500 hover:text-gray-700'"
                class="px-5 py-3 text-sm font-medium transition-colors"
            >Overview</button>
            <button
                @click="activeTab = 'daily'"
                :class="activeTab === 'daily' ? 'border-b-2 border-amber-400 text-amber-600' : 'text-gray-500 hover:text-gray-700'"
                class="px-5 py-3 text-sm font-medium transition-colors"
            >Daily Breakdown</button>
            <button
                @click="activeTab = 'api'"
                :class="activeTab === 'api' ? 'border-b-2 border-amber-400 text-amber-600' : 'text-gray-500 hover:text-gray-700'"
                class="px-5 py-3 text-sm font-medium transition-colors"
            >REST API</button>
        </div>

        {{-- Overview tab --}}
        <div x-show="activeTab === 'overview'" class="p-5 space-y-4">
            <h3 class="text-sm font-semibold text-gray-700">All-time breakdown by status</h3>
            <div class="space-y-2">
                @php
                    $statusColors = [
                        'new' => 'bg-blue-100 text-blue-700',
                        'processing' => 'bg-amber-100 text-amber-700',
                        'shipped' => 'bg-purple-100 text-purple-700',
                        'delivered' => 'bg-emerald-100 text-emerald-700',
                        'cancelled' => 'bg-red-100 text-red-700',
                    ];
                    $total = array_sum($this->summary['by_status']);
                @endphp
                @foreach ($this->summary['by_status'] as $status => $count)
                    @php $pct = $total > 0 ? round($count / $total * 100) : 0; @endphp
                    <div class="flex items-center gap-3">
                        <span class="w-24 text-right text-xs font-medium capitalize {{ $statusColors[$status] ?? 'bg-gray-100 text-gray-600' }} rounded-full px-2 py-0.5">
                            {{ $status }}
                        </span>
                        <div class="flex-1 h-2 rounded-full bg-gray-100">
                            <div class="h-2 rounded-full bg-amber-400 transition-all duration-500" style="width: {{ $pct }}%"></div>
                        </div>
                        <span class="w-12 text-right text-sm text-gray-600">{{ $count }}</span>
                        <span class="w-8 text-right text-xs text-gray-400">{{ $pct }}%</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Daily breakdown tab --}}
        <div x-show="activeTab === 'daily'" class="p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Daily order counts</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs uppercase tracking-wide text-gray-400 border-b border-gray-100">
                            <th class="pb-2 pr-4">Date</th>
                            <th class="pb-2 text-right">Orders</th>
                            <th class="pb-2 pl-4">Bar</th>
                        </tr>
                    </thead>
                    <tbody wire:loading.class="opacity-40">
                        @php $maxDaily = max(array_values($this->stats['daily']) ?: [1]); @endphp
                        @foreach (array_slice($this->stats['daily'], -14, 14, true) as $date => $count)
                            <tr class="border-b border-gray-50">
                                <td class="py-1.5 pr-4 text-gray-500">{{ \Carbon\Carbon::parse($date)->format('M j') }}</td>
                                <td class="py-1.5 text-right font-medium text-gray-800">{{ $count }}</td>
                                <td class="py-1.5 pl-4 w-48">
                                    <div class="h-1.5 rounded-full bg-amber-400 transition-all duration-300"
                                         style="width: {{ $maxDaily > 0 ? round($count / $maxDaily * 100) : 0 }}%">
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- REST API tab --}}
        <div x-show="activeTab === 'api'" class="p-5 space-y-3">
            <p class="text-sm text-gray-600">This module exposes a REST API endpoint. Try it:</p>
            <code class="block rounded-lg bg-gray-900 text-green-400 text-xs font-mono p-4">
                GET /api/v1/reports/orders/summary?days={{ $period }}&status={{ $status }}
            </code>
            <div class="text-xs text-gray-500 space-y-1">
                <p><strong class="text-gray-700">days</strong> — integer 1–365 (default: 30)</p>
                <p><strong class="text-gray-700">status</strong> — all | new | processing | shipped | delivered | cancelled</p>
            </div>
            <a
                href="/api/v1/reports/orders/summary?days={{ $period }}&status={{ $status }}"
                target="_blank"
                class="inline-flex items-center gap-1.5 rounded-lg bg-amber-400 px-4 py-2 text-sm font-medium text-white hover:bg-amber-500 transition-colors"
            >
                Open in browser ↗
            </a>
        </div>
    </div>

    {{-- Livewire loading indicator --}}
    <div wire:loading class="fixed bottom-4 right-4 rounded-lg bg-gray-900 text-white text-xs px-3 py-2 shadow-lg">
        Updating…
    </div>
</div>
