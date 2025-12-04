@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 lg:flex-col lg:gap-3 mt-6 min-h-screen">
        <div class="w-full mb-4">
            <div class="bg-white w-full rounded-lg shadow-lg shadow-gray-200">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="card">

                            <div class="card-body p-6">

                                <div class="mb-4 space-y-4">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Cek Transaksi</h3>


                                    @if (session('error'))
                                        <div class="max-w-7xl mx-auto">
                                            <div class="w-full bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4"
                                                role="alert">
                                                <p>{{ session('error') }}</p>
                                            </div>
                                        </div>
                                    @endif


                                    <form action="{{ route('transaction.check') }}" method="GET">
                                        <div class="mb-4">
                                            <label for="transaction_code"
                                                class="block text-sm font-medium text-gray-700">Kode
                                                Transaksi</label>
                                            <input type="text" name="transaction_code" id="transaction_code"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm uppercase"
                                                oninput="this.value = this.value.toUpperCase()"
                                                placeholder="Masukkan Kode Transaksi">
                                        </div>
                                        <button type="submit"
                                            class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md w-full">Cek</button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
