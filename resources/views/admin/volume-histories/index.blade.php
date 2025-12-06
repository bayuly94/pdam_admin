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

            <!-- Search Form -->
            <div class="mb-6">
                <form action="{{ route('admin.volume-histories.index') }}" method="GET" class="flex gap-2">
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search Scan Meter..."
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </div>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Search
                    </button>
                    @if (request('search'))
                        <a href="{{ route('admin.volume-histories.index') }}"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            Clear
                        </a>
                    @endif
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
