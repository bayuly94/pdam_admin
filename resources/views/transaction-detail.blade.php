@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 lg:flex-col lg:gap-3 mt-6 min-h-screen">
        <div class="w-full mb-4">
            <div class="bg-white w-full rounded-lg shadow-lg shadow-gray-200">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="card">

                            <div class="card-body p-6">
                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                @if ($transaction->status == 'refund')
                                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-3" role="alert">
                                        Silakan hubungi admin untuk mengajukan pengembalian dana.
                                                        <br><br>


                                        
                                                        <div class="flex justify-center gap-4">

                                                                 
                                                        <a target="_blank" href="mailto:{{ setting('contact_email') }}" class="flex"><svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg> {{ setting('contact_email') }}</a>
                                        

                                        <a target="_blank" href="https://wa.me/{{ setting('contact_phone') }}" class="flex">
                                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        {{ setting('contact_phone') }}
                                        </a>
                                                        </div>

                                    </div>
                                @endif

                                <div class="mb-4 space-y-4">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Transaksi</h3>


                                    {{-- transaction id --}}
                                    <div class="border-b pb-4 flex justify-between">
                                        <p class="text-sm text-gray-600">Transaction ID</p>
                                        <p class="font-medium text-gray-900 product-name ">
                                            {{ $transaction->code ?? '-' }}</p>
                                    </div>

                                    {{-- date --}}
                                    <div class="border-b pb-4 flex justify-between">
                                        <p class="text-sm text-gray-600">Tanggal</p>
                                        <p class="font-medium text-gray-900 product-name ">
                                            {{ $transaction->created_at->format('d M Y H:i:s') ?? '-' }}</p>
                                    </div>
                                    {{-- status --}}
                                    <div class="border-b pb-4 flex justify-between items-center">
                                        <p class="text-sm text-gray-600">Status</p>
                                        @php
                                            $statusClass =
                                                [
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'wait_payment' => 'bg-blue-100 text-blue-800',
                                                    'paid' => 'bg-green-100 text-green-800',
                                                    'canceled' => 'bg-red-100 text-red-800',
                                                    'refund' => 'bg-gray-100 text-gray-800',
                                                ][strtolower($transaction->status)] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                            {{ $transaction->status_name ?? '-' }}
                                        </span>
                                    </div>


                                    {{-- item --}}
                                    <div class="border-b pb-4 flex justify-between">
                                        <p class="text-sm text-gray-600">Item</p>
                                        <p class="font-medium text-gray-900 product-name ">
                                            {{ $transaction->product_name ?? '-' }}</p>
                                    </div>


                                    {{-- customer number --}}
                                    <div class="border-b pb-4 flex justify-between">
                                        <p class="text-sm text-gray-600">Nomor Tujuan</p>
                                        <p class="font-medium text-gray-900 product-name ">
                                            {{ $transaction->customer_no ?? '-' }}</p>
                                    </div>



                                </div>


                                {{-- @if ($transaction->status == 'pending')
                                    <div class="alert alert-warning">
                                        <i class="fas fa-info-circle"></i> This transaction is still pending. Please
                                        complete
                                        your payment.
                                    </div>
                                @endif

                                <div class="mt-4">
                                    <a href="{{ route('home') }}" class="btn btn-primary">
                                        <i class="fas fa-arrow-left"></i> Back to Home
                                    </a>
                                    @if ($transaction->status == 'pending')
                                        <button class="btn btn-success" id="check-status" data-id="{{ $transaction->id }}">
                                            <i class="fas fa-sync-alt"></i> Check Status
                                        </button>
                                    @endif
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="bg-white rounded-lg shadow-lg shadow-gray-200 w-full h-fit">

            <div class="row justify-content-center">
                <div class="col-12 p-4">

                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Pembayaran</h3>

                    <div class="space-y-4">
                        <div class="border-b pb-4 flex justify-between">
                            <p class="text-sm text-gray-600">Metode Bayar</p>
                            <p class="font-medium text-gray-900 product-name ">
                                {{ $transaction->payment_method_category ?? '-' }}
                                {{ $transaction->payment_method_name ?? '-' }}
                            </p>
                        </div>

                        {{-- total --}}

                        <div class="border-b pb-4 flex justify-between">
                            <p class="text-sm text-gray-600">Total</p>
                            <p class="font-medium text-gray-900 product-name ">Rp
                                {{ number_format($transaction->total, 0, ',', '.') ?? '-' }}</p>
                        </div>



                        <div class="flex justify-center mb-4">
                            <br>
                            <br>
                            <br>
                            <br>

                            <img src="{{ asset('assets/payment_methods/' . strtolower($transaction->payment_method_code) . '.svg') }}"
                                class="w-24 h-24 object-contain"
                                onerror="this.onerror=null; this.src='{{ asset('logo.png') }}'">
                        </div>

                        {{-- va number --}}

                        @if ($transaction->payment_va_number)
                            <p class="text-center"><strong>Nomor Virtual Account:</strong>
                                <br>
                                <span
                                    class="text-2xl font-medium text-gray-900 product-name">{{ $transaction->payment_va_number ?? '-' }}</span>
                                <br>

                            </p>
                        @endif

                        @if ($transaction->payment_method_code == 'QRIS')
                            <div id="qr-wrap" class="flex justify-center my-4">
                                <canvas id="qrcanvas" class="mx-auto"></canvas>
                            </div>
                        @endif




                        @if ($transaction->payment_method_code == 'QRIS')
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Cara Bayar</h3>
                            <hr />
                            <br>
                            1. Buka aplikasi Bank/Ewallet Anda<br>
                            2. Pilih bayar QRIS<br>
                            3. Scan kode QR atau klik kode QR ini untuk simpan kode QR ke device Anda lalu pilih “Upload
                            from
                            phone”
                        @endif
                    </div>

                </div>
            </div>
        </div>


        {{-- button back --}}
        <div class="flex justify-center mt-4 mb-4">
            <a href="{{ url()->current() }}"
                class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md text-center">Cek
                Status</a>
        </div>

    </div>
@endsection

@push('styles')
    <style>
        #qr-wrap {
            margin: 20px 0;
            text-align: center;
        }

        #qrcanvas {
            max-width: 100%;
            height: auto;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px;
            background: white;
        }
    </style>
@endpush

@push('scripts')

    <script>
        // Load QRCode library dynamically
        function loadScript(src, callback) {
            const script = document.createElement('script');
            script.src = src;
            script.onload = () => callback();
            script.onerror = () => console.error('Failed to load script:', src);
            document.head.appendChild(script);
        }
        $(document).ready(function() {
            $('#check-status').click(function() {
                const button = $(this);
                const transactionId = button.data('id');

                button.prop('disabled', true);
                button.html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Checking...'
                );

                $.ajax({
                    url: '/transaction/' + transactionId + '/check-status',
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            window.location.reload();
                        } else {
                            alert('Status is still pending. Please try again later.');
                        }
                    },
                    error: function() {
                        alert('Error checking status. Please try again.');
                    },
                    complete: function() {
                        button.prop('disabled', false);
                        button.html('<i class="fas fa-sync-alt"></i> Check Status');
                    }
                });
            });
        });
    </script>


    @if ($transaction->payment_qr_string)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const textEl = "{{ $transaction->payment_qr_string }}";
                const canvas = document.getElementById('qrcanvas');
                const size = 256;

                // Set initial canvas size
                canvas.width = size;
                canvas.height = size;

                // Show loading message
                const ctx = canvas.getContext('2d');
                ctx.clearRect(0, 0, size, size);
                ctx.font = "14px system-ui";
                ctx.fillText("Loading QR code...", 10, 24);

                // Load QR code library and generate QR code
                loadScript('https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js', function() {
                    try {
                        // Clear the loading message
                        ctx.clearRect(0, 0, size, size);

                        // Generate QR code
                        new QRCode(document.getElementById('qr-wrap'), {
                            text: textEl,
                            width: size,
                            height: size,
                            colorDark: "#000000",
                            colorLight: "#ffffff",
                            correctLevel: QRCode.CorrectLevel.L
                        });

                        // hide canvas
                        canvas.style.display = 'none';

                        // hide element attribute title qr-wrap
                        const el = document.getElementById('qr-wrap');
                        if (el) el.removeAttribute('title');
                    } catch (err) {
                        console.error('Error generating QR code:', err);
                        ctx.clearRect(0, 0, size, size);
                        ctx.fillText("Error generating QR code", 10, 24);
                    }
                });
            });
        </script>
    @endif
@endpush
