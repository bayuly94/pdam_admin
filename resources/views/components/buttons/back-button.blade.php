@props([
    'href' => '#',
    'text' => 'Back',
])

<a href="{{ $href }}"
   {{ $attributes->merge([
       'class' => 'inline-flex items-center justify-center rounded-md bg-gray-100 px-4 py-2.5 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2'
   ]) }}>
    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
    </svg>
    {{ $text }}
</a>
