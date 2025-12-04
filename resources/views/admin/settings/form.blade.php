<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Settings') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            <!-- add message success -->
            @if (session('success'))
                <div class="bg-green-50 border border-green-500 text-green-500 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')


                        <!-- Contact Information -->
                        <div class="mb-8">
                            <h3 class="mb-4 text-lg font-medium text-gray-900">Contact Information</h3>
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                              
                                <!-- Contact Address -->
                                <div class="md:col-span-2">
                                    <x-label for="{{ App\Models\Setting::CODE_ABOUT }}" value="{{ __('Alamat') }}" />
                                    <textarea id="{{ App\Models\Setting::CODE_ABOUT }}" 
                                              name="{{ App\Models\Setting::CODE_ABOUT }}" 
                                              class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                              rows="2">{{ old(App\Models\Setting::CODE_ABOUT, setting(App\Models\Setting::CODE_ABOUT)) }}</textarea>
                                    <x-input-error for="{{ App\Models\Setting::CODE_ABOUT }}" class="mt-2" />
                                </div>
                            </div>
                        </div>

                       

                        <div class="flex items-center justify-end mt-6">
                            <x-button type="submit" class="ml-4">
                                {{ __('Save Settings') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Initialize any JavaScript plugins here if needed
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize any rich text editor for the about field if needed
            // Example: CKEditor or TinyMCE
        });
    </script>
    @endpush
</x-admin-layout>