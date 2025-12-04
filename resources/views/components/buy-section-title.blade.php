@props([
    'step' => 1,
    'title' => 'MASUKAN NOMOR TELEPON',
    'bgColor' => 'bg-green-600',
    'textColor' => 'text-white',
    'stepBgColor' => 'bg-white',
    'stepTextColor' => 'text-green-600',
])

<div class="{{ $bgColor }} {{ $textColor }} rounded-l-lg shadow-md -ml-4 my-4">
    <div class="flex items-center gap-4 p-4">
        <div class="flex items-center justify-center w-8 h-8 {{ $stepBgColor }} {{ $stepTextColor }} font-bold rounded-full">
            {{ $step }}
        </div>
        <div class="font-medium">
            {{ $title }}
        </div>
    </div>
</div>
