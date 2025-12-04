@props(['href', 'active'])

@php
$classes = ($active ?? false)
            ? 'bg-gray-100 text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md'
            : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $icon ?? '' }}
    {{ $slot }}
</a>
