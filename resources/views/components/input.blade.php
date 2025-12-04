@props([
    'type' => 'text',
    'name' => '',
    'id' => null,
    'value' => '',
    'required' => false,
    'autofocus' => false,
    'disabled' => false,
])

<input
    type="{{ $type }}"
    name="{{ $name }}"
    id="{{ $id ?? $name }}"
    value="{{ $value }}"
    {{ $attributes->merge([
        'class' => 'rounded-md shadow-sm border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 block w-full',
    ]) }}
    @if($required) required @endif
    @if($autofocus) autofocus @endif
    @if($disabled) disabled @endif
>
