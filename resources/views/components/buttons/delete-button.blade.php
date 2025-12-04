@props([
    'action' => '#',
    'confirmTitle' => 'Hapus?',
    'confirmText' => 'Apakah anda yakin ingin menghapus ini?',
    'confirmButtonText' => 'Ya, Hapus!',
    'cancelButtonText' => 'Batal',
    'buttonText' => 'Hapus',
    'buttonClass' => 'inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500',
])

<form action="{{ $action }}" method="POST" class="inline" id="delete-form-{{ md5($action) }}">
    @csrf
    @method('DELETE')
    <button type="button"
            onclick="confirmDelete('{{ $confirmTitle }}', '{{ $confirmText }}', '{{ $confirmButtonText }}', '{{ $cancelButtonText }}', 'delete-form-{{ md5($action) }}')"
            {{ $attributes->merge(['class' => $buttonClass]) }}>
        {{ $buttonText }}
    </button>
</form>

@push('scripts')
<script>
    function confirmDelete(title, text, confirmButtonText, cancelButtonText, formId) {
        Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: confirmButtonText,
            cancelButtonText: cancelButtonText
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }
</script>
@endpush
