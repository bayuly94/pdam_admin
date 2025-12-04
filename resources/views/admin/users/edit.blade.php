<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Pengguna: ') . $user->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-buttons.back-button :href="route('admin.users.index')" text="Kembali" />

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-4">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Name -->
                            <div>
                                <x-label for="name" :value="__('Nama')" />
                                <x-input id="name" class="block mt-1 w-full" type="text" name="name" 
                                    :value="old('name', $user->name)" required autofocus />
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <x-label for="email" :value="__('Email')" />
                                <x-input id="email" class="block mt-1 w-full" type="email" name="email" 
                                    :value="old('email', $user->email)" required />
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div>
                                <x-label for="password" :value="__('Password Baru')" />
                                <x-input id="password" class="block mt-1 w-full" type="password" 
                                    name="password" autocomplete="new-password" />
                                <p class="mt-1 text-sm text-gray-500">Biarkan kosong jika tidak ingin mengubah password</p>
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <x-label for="password-confirm" :value="__('Konfirmasi Password Baru')" />
                                <x-input id="password-confirm" class="block mt-1 w-full" type="password" 
                                    name="password_confirmation" autocomplete="new-password" />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <x-button class="ml-4" type="submit">
                                    {{ __('Simpan Perubahan') }}
                                </x-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
