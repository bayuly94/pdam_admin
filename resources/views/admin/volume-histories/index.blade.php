<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Scan Meter') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Search and Filter Form -->
            <div class="mb-6 bg-white p-4 rounded-lg shadow">
                <form action="{{ route('admin.volume-histories.index') }}" method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Search Input -->
                        <div class="sm:col-span-2 lg:col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search by name or code..."
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        </div>

                        <!-- Start Date -->
                        <div class="sm:col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        </div>

                        <!-- End Date -->
                        <div class="sm:col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        </div>


                    </div>

                    <!-- Action Buttons -->
                    <div
                        class="sm:col-span-2 lg:col-span-1 flex space-x-2">
                        <button type="submit"
                            class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Filter
                        </button>

                        <a href="{{ route('admin.volume-histories.export', request()->query()) }}"
                            class="w-full sm:w-auto px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 flex items-center justify-center sm:justify-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Export
                        </a>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="bg-white border-b border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <th class="px-6 py-3">Date</th>
                                    <th class="px-6 py-3">Customer</th>
                                    <th class="px-6 py-3">Before</th>
                                    <th class="px-6 py-3">Volume</th>
                                    <th class="px-6 py-3">After</th>
                                    <th class="px-6 py-3">Recorded By</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($histories as $history)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">{{ $history->date->format('M d, Y') }}</td>
                                        <td class="px-6 py-4">{{ $history->customer->name ?? 'N/A' }} -
                                            {{ $history->customer->code }}</td>
                                        <td class="px-6 py-4">{{ number_format($history->before, 0) }}</td>
                                        <td class="px-6 py-4">{{ number_format($history->volume, 0) }}</td>
                                        <td class="px-6 py-4">{{ number_format($history->after, 0) }}</td>
                                        <td class="px-6 py-4">{{ $history->employee->name ?? 'System' }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end space-x-2">

                                                <a href="{{ route('admin.volume-histories.show', $history) }}"
                                                    class="px-3 py-1 bg-yellow-100 text-yellow-800 text-sm rounded hover:bg-yellow-200">
                                                    Detail
                                                </a>


                                                <form action="{{ route('admin.volume-histories.destroy', $history) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="px-3 py-1 bg-red-100 text-red-800 text-sm rounded hover:bg-red-200"
                                                        onclick="return confirm('Are you sure you want to delete?')">
                                                        Delete
                                                    </button>
                                                </form>


                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            No volume history records found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                {{ $histories->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>
