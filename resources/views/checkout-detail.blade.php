@extends('layouts.app')

@push('styles')
    <style>
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .spinner-container {
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            text-align: center;
        }
    </style>
@endpush

@section('content')

    <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 lg:flex lg:gap-3">

        <div class="bg-white rounded-lg shadow-lg shadow-gray-300 w-full mt-6 mb-8">
            <div class="p-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Konfirmasi Pesanan</h3>


                <div class="flex flex-col items-center justify-center mb-6">
                    <div class="flex justify-center w-full">
                        <img src="{{ $product['icon_brand'] }}" class="w-24 h-24 object-contain"
                            onerror="this.onerror=null; this.src='{{ asset('logo.png') }}'" />
                    </div>
                    <p class="text-sm text-gray-600 my-2">Nominal Tagihan</p>
                    <div class="w-full flex justify-center">
                        <span class="font-bold text-black total text-2xl bg-yellow-400 p-2 px-5 rounded-3xl text-center">
                            Rp {{ number_format($transaction->total, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <!-- Selected Product -->
                <div class="space-y-4">
                    <div class="border-b pb-4 flex justify-between">
                        <p class="text-sm text-gray-600">Item</p>
                        <p class="font-medium text-gray-900 product-name ">{{ $transaction->product_name ?? '-' }}</p>
                    </div>


                    <div class="border-b pb-4 flex justify-between">

                        <p class="text-sm text-gray-600 mt-1">Nomor Tujuan</p>
                        <p class="font-medium text-gray-900 customer-no">{{ $transaction->customer_no ?? '-' }}</p>
                    </div>

                    @if ($transaction->customer_name)
                        <div class="border-b pb-4 flex justify-between">
                            <p class="text-sm text-gray-600 mt-1">Nama Tujuan</p>
                            <p class="font-medium text-gray-900 customer-name">{{ $transaction->customer_name ?? '-' }}</p>
                        </div>
                    @endif

                    <!-- Payment Method -->
                    <div class="border-b pb-4 flex justify-between">
                        <p class="text-sm text-gray-600">Metode Pembayaran</p>
                        <p class="font-medium text-gray-900 payment-method text-end">
                          {{ $transaction->payment_method_category ?? '-' }} {{ $transaction->payment_method_name ?? '-' }}<br>

                            <button type="button" onclick="showPaymentMethodModal()"
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Ubah
                            </button>
                        </p>
                    </div>


                    <form action="{{ route('checkout.create-invoice', $transaction->code) }}" method="POST">
                        @csrf

                        {{-- show error --}}
                        @if ($errors->any())
                            <div class="w-full bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                                <div class="font-bold">Error!</div>
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif


                        {{-- OVO --}}
                        @if ($transaction->payment_method_code == 'OVO')
                            <div class="border-b pb-4">
                                <p class="text-sm text-gray-600 mb-2">Nomor OVO</p>
                                <input type="number" id="ovoPhone" name="ovo_phone"
                                    class="w-full p-3 border-b border-gray-200 focus:border-gray-400 focus:outline-none rounded-lg"
                                    placeholder="Nomor OVO" required />
                            </div>
                        @endif

                        <div class="border-b pb-4">
                            <p class="text-sm text-gray-600 mb-2">Alamat Email</p>
                            <input type="email" id="emailInput" name="email"
                                class="w-full p-3 border-b border-gray-200 focus:border-gray-400 focus:outline-none rounded-lg"
                                placeholder="Alamat" required />
                            <span class="text-xs text-gray-600 mt-1">Status dan bukti pembelian akan dikirimkan ke email
                                ini</span>

                        </div>


                        <!-- Submit Button -->
                        <button type="submit" id="submitBtn"
                            class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-full font-medium mt-4 transition-colors duration-200 flex items-center justify-center gap-2"
                            onclick="this.querySelector('#spinner').classList.remove('hidden'); this.querySelector('#buttonText').textContent = 'Memproses...'; this.disabled = true; this.form.submit();">
                            <span id="buttonText">Lanjutkan Pembayaran</span>
                            <div id="spinner" class="hidden">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </div>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Method Modal -->
    <div id="paymentMethodModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Pilih Metode Pembayaran</h3>
                <div class="mt-4 space-y-3 max-h-96 overflow-y-auto">
                    @foreach ($payment_methods as $method)
                        <form action="{{ route('checkout.detail.update-payment-method', $transaction->code) }}"
                            method="POST">
                            @csrf
                            <input type="hidden" name="payment_method_id" value="{{ $method->id }}">
                            <button type="submit"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                {{ $method->category }} {{ $method->payment_channel }}
                            </button>
                        </form>
                    @endforeach
                </div>
                <div class="mt-4">
                    <button type="button" onclick="hidePaymentMethodModal()"
                        class="w-full px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .payment-method-option {
            transition: all 0.2s;
        }

        .payment-method-option:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>
@endsection

@push('scripts')
    <script>
        var ovoPhoneRequired = {{ $transaction->payment_method_code == 'OVO' ? 'true' : 'false' }};
        // Show payment method modal
        function showPaymentMethodModal() {
            document.getElementById('paymentMethodModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
        }

        // Hide payment method modal
        function hidePaymentMethodModal() {
            document.getElementById('paymentMethodModal').classList.add('hidden');
            document.body.style.overflow = 'auto'; // Re-enable scrolling
        }
    </script>
@endpush
