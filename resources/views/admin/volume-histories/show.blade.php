<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Volume History Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Volume Information -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900">Volume Information</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <dl class="space-y-4">
                                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                        <dt class="text-sm font-medium text-gray-500">Previous Volume</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">
                                            {{ number_format($volumeHistory->before, 2) }} m³
                                        </dd>
                                    </div>
                                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                        <dt class="text-sm font-medium text-gray-500">Volume Used</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">
                                            {{ number_format($volumeHistory->volume, 2) }} m³
                                        </dd>
                                    </div>
                                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                        <dt class="text-sm font-medium text-gray-500">Current Volume</dt>
                                        <dd class="mt-1 text-sm text-gray-900 font-semibold sm:col-span-2">
                                            {{ number_format($volumeHistory->after, 2) }} m³
                                        </dd>
                                    </div>
                                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                        <dt class="text-sm font-medium text-gray-500">Date</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">
                                            {{ $volumeHistory->date->format('F j, Y H:i') }}
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <!-- Location & Photo -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900">Location & Photo</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                @if($volumeHistory->photo)
                                    <div class="mb-4">
                                        <img src="{{ asset( $volumeHistory->photo) }}" 
                                             alt="Meter Reading" 
                                             class="max-w-full h-auto rounded-lg shadow-sm">
                                    </div>
                                @endif
                                
                                @if($volumeHistory->latitude && $volumeHistory->longitude)
                                    <div class="mt-4">
                                        <a href="https://www.google.com/maps?q={{ $volumeHistory->latitude }},{{ $volumeHistory->longitude }}" 
                                           target="_blank" 
                                           class="inline-flex items-center text-blue-600 hover:text-blue-800">
                                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            View on Map
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Customer & Employee Information -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Customer</h4>
                                    <p class="mt-1 text-sm text-gray-900">
                                        {{ $volumeHistory->customer->name ?? 'N/A' }}
                                    </p>
                                    <p class="text-xs text-gray-500">ID: {{ $volumeHistory->customer_id }}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Recorded By</h4>
                                    <p class="mt-1 text-sm text-gray-900">
                                        {{ $volumeHistory->employee->name ?? 'N/A' }}
                                    </p>
                                    <p class="text-xs text-gray-500">Employee ID: {{ $volumeHistory->employee_id }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-8 flex items-center justify-end space-x-4">
                        <a href="{{ route('admin.volume-histories.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Back to List
                        </a>
                        <a href="{{ route('admin.volume-histories.edit', $volumeHistory) }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Edit Record
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>