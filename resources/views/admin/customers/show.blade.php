<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Pelanggan: ') . $customer->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-buttons.back-button :href="route('admin.customers.index')" text="Kembali" />

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-4">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Informasi Pelanggan</h3>
                            <dl class="mt-2 divide-y divide-gray-200">
                                <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Kode Pelanggan</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $customer->code }}</dd>
                                </div>
                                <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Nama</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $customer->name }}</dd>
                                </div>
                                <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Alamat</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $customer->address }}</dd>
                                </div>
                                <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Dibuat</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $customer->created_at->format('d M Y H:i') }}</dd>
                                </div>
                                <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Terakhir Diperbarui</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $customer->updated_at->format('d M Y H:i') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('admin.customers.edit', $customer) }}" 
                               class="inline-flex items-center px-4 py-2 bg-yellow-100 border border-transparent rounded-md font-semibold text-xs text-yellow-800 uppercase tracking-widest hover:bg-yellow-200 focus:bg-yellow-200 active:bg-yellow-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Edit
                            </a>
                            <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" 
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 bg-red-100 border border-transparent rounded-md font-semibold text-xs text-red-800 uppercase tracking-widest hover:bg-red-200 focus:bg-red-200 active:bg-red-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>