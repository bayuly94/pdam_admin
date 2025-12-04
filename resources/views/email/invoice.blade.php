<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $transaction->code }}</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.5;
            color: #1f2937;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }

        .header {
            text-align: center;
            padding: 1.5rem 0;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 1.5rem;
        }

        .logo {
            max-width: 120px;
            margin-bottom: 1rem;
        }

        .card {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        .card-body {
            padding: 1.5rem;
        }

        .text-sm {
            font-size: 0.875rem;
        }

        .text-gray-600 {
            color: #4b5563;
        }

        .font-medium {
            font-weight: 500;
        }

        .border-b {
            border-bottom: 1px solid #e5e7eb;
        }

        .pb-4 {
            padding-bottom: 1rem;
        }

        .flex {
            display: flex;
        }

        .justify-between {
            justify-content: space-between;
        }

        .items-center {
            align-items: center;
        }

        .mt-4 {
            margin-top: 1rem;
        }

        .mb-4 {
            margin-bottom: 1rem;
        }

        .space-y-4>*+* {
            margin-top: 1rem;
        }

        .text-lg {
            font-size: 1.125rem;
        }

        .font-semibold {
            font-weight: 600;
        }

        .text-gray-800 {
            color: #1f2937;
        }

        .text-gray-900 {
            color: #111827;
        }

        .bg-yellow-100 {
            background-color: #fef9c3;
        }

        .bg-blue-100 {
            background-color: #dbeafe;
        }

        .bg-green-100 {
            background-color: #dcfce7;
        }

        .bg-red-100 {
            background-color: #fee2e2;
        }

        .text-yellow-800 {
            color: #92400e;
        }

        .text-blue-800 {
            color: #1e40af;
        }

        .text-green-800 {
            color: #166534;
        }

        .text-red-800 {
            color: #991b1b;
        }

        .px-3 {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        .py-1 {
            padding-top: 0.25rem;
            padding-bottom: 0.25rem;
        }

        .rounded-full {
            border-radius: 9999px;
        }

        .text-xs {
            font-size: 0.75rem;
        }

        .footer {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
            font-size: 0.875rem;
            color: #6b7280;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('logo.png') }}" alt="Logo" class="logo">
            <h1>Invoice #{{ $transaction->code }}</h1>
            <p>Tanggal: {{ $transaction->created_at->format('d M Y H:i:s') }}</p>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Transaksi</h3>

                    <div class="border-b pb-4 flex justify-between">
                        <p class="text-sm text-gray-600">Transaction ID</p>
                        <p class="font-medium text-gray-900">{{ $transaction->code ?? '-' }}</p>
                    </div>

                    <div class="border-b pb-4 flex justify-between">
                        <p class="text-sm text-gray-600">Tanggal</p>
                        <p class="font-medium text-gray-900">
                            {{ $transaction->created_at->format('d M Y H:i:s') ?? '-' }}</p>
                    </div>

                    <div class="border-b pb-4 flex justify-between items-center">
                        <p class="text-sm text-gray-600">Status</p>
                        @php
                            $statusClass =
                                [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'wait_payment' => 'bg-blue-100 text-blue-800',
                                    'paid' => 'bg-green-100 text-green-800',
                                    'canceled' => 'bg-red-100 text-red-800',
                                ][strtolower($transaction->status)] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="text-xs font-medium px-3 py-1 rounded-full {{ $statusClass }}">
                            {{ $transaction->status_name ?? '-' }}
                        </span>
                    </div>

                    <div class="border-b pb-4 flex justify-between">
                        <p class="text-sm text-gray-600">Item</p>
                        <p class="font-medium text-gray-900">{{ $transaction->product_name ?? '-' }}</p>
                    </div>

                    <div class="border-b pb-4 flex justify-between">
                        <p class="text-sm text-gray-600">Nomor Tujuan</p>
                        <p class="font-medium text-gray-900">{{ $transaction->customer_no ?? '-' }}</p>
                    </div>

                    <div class="border-b pb-4 flex justify-between">
                        <p class="text-sm text-gray-600">Metode Pembayaran</p>
                        <p class="font-medium text-gray-900">
                            {{ $transaction->payment_method_name ?? '-' }}
                        </p>
                    </div>

                    <div class="pt-4">
                        <div class="flex justify-between font-semibold">
                            <span>Total</span>
                            <span>Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                @if ($transaction->status === 'pending' || $transaction->status === 'wait_payment')
                    <div class="mt-6 p-4 bg-blue-50 border-l-4 border-blue-500 rounded">
                       
                        @if ($transaction->payment_va_number)
                            <p class="text-sm text-gray-700 mb-1">
                                Nomor Virtual Account: <span
                                    class="font-medium">{{ $transaction->payment_va_number }}</span>
                            </p>
                            <p class="text-sm text-gray-700 mb-1">
                                Jumlah: <span class="font-medium">Rp
                                    {{ number_format($transaction->total, 0, ',', '.') }}</span>
                            </p>
                            @if ($transaction->expired_at != null)
                            <p class="text-sm text-gray-700">
                                Batas waktu: <span
                                    class="font-medium">{{ $transaction->expired_at->format('d M Y H:i:s') }}</span>
                            </p>
                            @endif
                        @endif
                        @if ($transaction->payment_method_code === 'QRIS' && $transaction->payment_qr_string)
                            <div class="mt-3 text-center">
                                <p class="text-sm text-gray-700 mb-2">Scan kode QRIS berikut:</p>
                                <img src="{{ $transaction->payment_qr_string }}" alt="QRIS Code"
                                    style="max-width: 200px; margin: 0 auto;">
                            </div>
                        @endif
                    </div>
                @endif

                <div class="mt-6 text-center">
                    <a href="{{ $transactionDetailUrl ?? '#' }}"
                        style="display: inline-block; background-color: #2563eb; color: white; padding: 0.5rem 1.5rem; border-radius: 0.375rem; text-decoration: none; font-weight: 500;">
                        Lihat Detail Transaksi
                    </a>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>Jika Anda memiliki pertanyaan, silakan hubungi tim dukungan kami.</p>
            <p>Terima kasih telah bertransaksi dengan kami.</p>
            <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
