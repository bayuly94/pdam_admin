@props([
    'active' => false,
    'model' => null,
    'url' => '#',
    'activeText' => 'Active',
    'inactiveText' => 'Inactive',
    'activeClass' => 'bg-green-500',
    'inactiveClass' => 'bg-gray-300',
])

@php
    $isActive = $active || ($model && $model->is_active);
@endphp

<label class="relative inline-flex items-center cursor-pointer">
    <input 
        type="checkbox" 
        class="sr-only peer" 
        @if($model) 
            x-data="{ isActive: {{ $isActive ? 'true' : 'false' }}}"
            @click="
                isActive = !isActive;
                fetch('{{ $url }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        is_active: isActive ? 1 : 0,
                        _method: 'PUT'
                    })
                }).catch(error => {
                    isActive = !isActive; // Revert on error
                    console.error('Error:', error);
                });
            "
            :checked="isActive"
            :aria-checked="isActive"
        @else
            {{ $attributes }}
        @endif
    >
    <div x-data="{ 
        isOn: {{ $isActive ? 'true' : 'false' }},
        toggle() {
            this.isOn = !this.isOn;
            fetch('{{ $url }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    is_active: this.isOn ? 1 : 0,
                    _method: 'PUT'
                })
            }).catch(error => {
                this.isOn = !this.isOn; // Revert on error
                console.error('Error:', error);
            });
        }
    }"
    @click="toggle()"
    :class="{ '{{ $activeClass }}': isOn, '{{ $inactiveClass }}': !isOn }"
    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200 cursor-pointer">
    <span :class="{ 'translate-x-6': isOn, 'translate-x-1': !isOn }" class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200"></span>
    </div>
  
</label>
