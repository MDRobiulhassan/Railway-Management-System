@props(['type' => 'error', 'dismissible' => true])

@php
    $classes = [
        'success' => 'bg-green-50 border-green-200 text-green-800',
        'error' => 'bg-red-50 border-red-200 text-red-800',
        'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
        'info' => 'bg-blue-50 border-blue-200 text-blue-800'
    ];
@endphp

<div class="mb-4 p-4 border rounded-md {{ $classes[$type] ?? $classes['error'] }} {{ $dismissible ? 'alert-dismissible' : '' }}" role="alert">
    <div class="flex justify-between items-start">
        <div class="flex-1">
            {{ $slot }}
        </div>
        @if($dismissible)
            <button type="button" class="ml-3 -mx-1.5 -my-1.5 rounded-lg p-1.5 hover:bg-gray-100 inline-flex h-8 w-8 items-center justify-center" onclick="this.parentElement.parentElement.remove()">
                <span class="sr-only">Dismiss</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
            </button>
        @endif
    </div>
</div>
