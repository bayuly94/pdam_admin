@props([
    'item' => null,
    'routeName' => '',
    'routeParams' => [],
])

<div class="flex items-center space-x-2">
    <button type="button" 
            class="p-1 text-gray-500 hover:text-gray-700 focus:outline-none"
            onclick="moveItem('{{ route($routeName, array_merge($routeParams, ['direction' => 'up'])) }}', this)"
            {{ $attributes->get('isFirst') ? 'disabled' : '' }}
            {{ $attributes->get('isFirst') ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100 rounded' }}>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
        </svg>
    </button>
    <button type="button" 
            class="p-1 text-gray-500 hover:text-gray-700 focus:outline-none"
            onclick="moveItem('{{ route($routeName, array_merge($routeParams, ['direction' => 'down'])) }}', this)"
            {{ $attributes->get('isLast') ? 'disabled' : '' }}
            {{ $attributes->get('isLast') ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100 rounded' }}>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
    </button>
</div>

@once
@push('scripts')
<script>
    function moveItem(url, button) {
        if (button.disabled) return;
        
        // Disable button during request
        button.disabled = true;
        button.classList.add('opacity-50');
        
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                button.disabled = false;
                button.classList.remove('opacity-50');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            button.disabled = false;
            button.classList.remove('opacity-50');        
        });
    }
</script>
@endpush
@endonce
