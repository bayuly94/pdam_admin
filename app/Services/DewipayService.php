<?php

namespace App\Services;

use App\Models\PaymentMethod;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class DewipayService
{
    private $token;

    public function __construct()
    {
        $this->token = env('DEWIPAY_TOKEN');
    }

    public function init()
    {
        $payment_channels = [
            [
                'payment_channel' => 'QRIS',
                'category' => 'EWallet',
                'is_active' => false,
                'min_amount' => 10000,
            ],
            [
                'payment_channel' => 'OVO',
                'category' => 'EWallet',
                'is_active' => false,
                'min_amount' => 10000,
            ],
            [
                'payment_channel' => 'DANA',
                'category' => 'EWallet',
                'is_active' => false,
                'min_amount' => 10000,
            ],
            [
                'payment_channel' => 'LINKAJA',
                'category' => 'EWallet',
                'is_active' => false,
                'min_amount' => 10000,
            ],
            [
                'payment_channel' => 'SHOPEEPAY',
                'category' => 'EWallet',
                'is_active' => false,
                'min_amount' => 10000,
            ],

            [
                'payment_channel' => 'BRI',
                'category' => 'Virtual Account',
                'is_active' => false,
                'min_amount' => 15000,
            ],
            [
                'payment_channel' => 'BNI',
                'category' => 'Virtual Account',
                'is_active' => false,
                'min_amount' => 15000,
            ],
            [
                'payment_channel' => 'MANDIRI',
                'category' => 'Virtual Account',
                'is_active' => false,
                'min_amount' => 15000,
            ],
            [
                'payment_channel' => 'PERMATA',
                'category' => 'Virtual Account',
                'is_active' => false,
                'min_amount' => 15000,
            ],
            [
                'payment_channel' => 'BSI',
                'category' => 'Virtual Account',
                'is_active' => false,
                'min_amount' => 15000,
            ],
            [
                'payment_channel' => 'CIMB',
                'category' => 'Virtual Account',
                'is_active' => false,
                'min_amount' => 15000,
            ],
            [
                'payment_channel' => 'BCA',
                'category' => 'Virtual Account',
                'is_active' => false,
                'min_amount' => 15000,
            ],
            [
                'payment_channel' => 'BNC',
                'category' => 'Virtual Account',
                'is_active' => false,
                'min_amount' => 15000,
            ],
            [
                'payment_channel' => 'BTPN',
                'category' => 'Virtual Account',
                'is_active' => false,
                'min_amount' => 15000,
            ],
            [
                'payment_channel' => 'DANAMON',
                'category' => 'Virtual Account',
                'is_active' => false,
                'min_amount' => 15000,
            ],

        ];

        foreach ($payment_channels as $payment_channel) {
            $payment_method = PaymentMethod::where('payment_channel', $payment_channel['payment_channel'])->first();
            if (! $payment_method) {
                PaymentMethod::create($payment_channel);
            }
        }
    }

    public function create($transaction_id)
    {
        try {
            $transaction = Transaction::find($transaction_id);

            // Log::info($transaction);

            if (! $transaction) {
                throw ValidationException::withMessages([
                    'message' => 'Transaction not found',
                ]);
            }

            if ($transaction->payment_method_category == 'Virtual Account') {
                $this->createVirtualAccount($transaction);
            } elseif ($transaction->payment_method_category == 'EWallet') {

                if ($transaction->payment_method_code == 'QRIS') {
                    $this->createQris($transaction);
                } else {
                    $this->createEwallet($transaction);
                }
            } else {
                throw ValidationException::withMessages([
                    'message' => 'Payment method category not found',
                ]);
            }

            $transaction->status = Transaction::STATUS_WAIT_PAYMENT;
            $transaction->save();

            return $transaction;
        } catch (Exception $e) {
            throw $e;
        }

    }

    private function createVirtualAccount(Transaction $transaction)
    {
        try {
            $token = $this->token;
            if (! $token) {
                throw ValidationException::withMessages([
                    'message' => 'Failed to obtain authentication token',
                ]);
            }

            $post = [
                'merchant_trx_id' => $transaction->code,
                'payment_channel' => $transaction->payment_method_code,
                'amount' => $transaction->total,
                'name' => $transaction->customer_no,
                'description' => $transaction->buyer_sku_code,
                'expired_time' => Carbon::now()->addDay()->toIso8601String(),
                'callback_url' => route('dewipay.callback'),
            ];

            // Log::info($post);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$token,
                'Accept' => 'application/json',
            ])
                ->timeout(30) // 30 seconds timeout
                ->retry(3, 100) // Retry up to 3 times, 100ms between attempts
                ->post('https://api.dewipay.id/v1/virtual_account', $post);

            $data = $response->json();

            if ($response->successful()) {
                $transaction->update([
                    'payment_trx_id' => $data['data']['uuid'] ?? null,
                    'payment_va_number' => $data['data']['va_number'] ?? null,
                    'payment_qr_string' => $data['data']['qr_string'] ?? null,
                    'payment_expired_time' => $data['data']['expired_time'] ?? null,
                    'payment_payment_method' => $data['data']['payment_method'] ?? null,
                    'payment_payment_channel' => $data['data']['payment_channel'] ?? null,
                    'dewipay_customer_phone' => $data['data']['customer_phone'] ?? null,
                ]);

            } else {
                Log::error($data);
                if (isset($data['status']) && $data['status'] == 422) {

                    $fields = $data['error']['fields'];

                    throw ValidationException::withMessages($fields);
                }
                throw ValidationException::withMessages([
                    'message' => 'Failed to create virtual account',
                ]);
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {
            // Handle HTTP request specific exceptions
            Log::error($e);
            throw ValidationException::withMessages([
                'message' => 'Failed to create virtual account',
            ]);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Connection error: '.$e->getMessage());
            throw ValidationException::withMessages([
                'message' => 'Unable to connect to payment service. Please try again later.',
            ]);
        } catch (\Exception $e) {
            Log::error('Unexpected error: '.$e->getMessage());
            throw ValidationException::withMessages([
                'message' => 'Failed to create virtual account',
            ]);
        }

    }

    private function createQris(Transaction $transaction)
    {
        try {
            $token = $this->token;
            if (! $token) {
                throw ValidationException::withMessages([
                    'message' => 'Failed to obtain authentication token',
                ]);
            }

            $post = [
                'merchant_trx_id' => $transaction->code,
                'amount' => $transaction->total,
                'callback_url' => route('dewipay.callback'),

            ];

            // Log::info($post);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$token,
                'Accept' => 'application/json',
            ])
                ->timeout(30) // 30 seconds timeout
                ->retry(3, 100) // Retry up to 3 times, 100ms between attempts
                ->post('https://api.dewipay.id/v1/qris', $post);

            $data = $response->json();

            if ($response->successful()) {
                $transaction->update([
                    'payment_trx_id' => $data['data']['uuid'] ?? null,
                    'payment_qr_string' => $data['data']['qr_string'] ?? null,
                    'payment_expired_time' => $data['data']['expired_time'] ?? null,
                    'payment_payment_method' => $data['data']['payment_method'] ?? null,
                    'payment_payment_channel' => $data['data']['payment_channel'] ?? null,
                ]);

            } else {
                // Log::error($data);
                if (isset($data['status']) && $data['status'] == 422) {

                    $fields = $data['error']['fields'];

                    throw ValidationException::withMessages($fields);
                }
                throw ValidationException::withMessages([
                    'message' => 'Failed to create qris',
                ]);
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {
            // Handle HTTP request specific exceptions
            Log::error($e);
            throw ValidationException::withMessages([
                'message' => 'Failed to create qris',
            ]);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Connection error: '.$e->getMessage());
            throw ValidationException::withMessages([
                'message' => 'Unable to connect to payment service. Please try again later.',
            ]);
        } catch (\Exception $e) {
            Log::error('Unexpected error: '.$e->getMessage());
            throw ValidationException::withMessages([
                'message' => 'Failed to create qris',
            ]);
        }

    }

    private function createEwallet(Transaction $transaction)
    {
        try {
            $token = $this->token;
            if (! $token) {
                throw ValidationException::withMessages([
                    'message' => 'Failed to obtain authentication token',
                ]);
            }

            // Validate required fields based on payment channel
            $post = [
                'merchant_trx_id' => $transaction->code,
                'payment_channel' => $transaction->payment_method_code,
                'amount' => $transaction->total,
                'customer_phone' => '',
                'return_url' => '',
                'callback_url' => route('dewipay.callback'),
            ];

            // Add customer_phone only if payment channel is OVO or if it's required
            if ($transaction->payment_method_code === 'OVO' && $post['customer_phone'] == '') {
                if (empty($transaction->customer_no)) {
                    throw ValidationException::withMessages([
                        'customer_no' => 'Nomor telepon pelanggan diperlukan untuk pembayaran OVO',
                    ]);
                }
                $post['customer_phone'] = $transaction->customer_no;
            }

            // return_url required for DANA, LinkAJA, SHOPEEPAY
            if (in_array($transaction->payment_method_code, ['DANA', 'LINKAJA', 'SHOPEEPAY']) && $post['return_url'] == '') {
                throw ValidationException::withMessages([
                    'return_url' => 'Return URL is required for DANA, LinkAJA, and SHOPEEPAY',
                ]);
            }

            // Log::info($post);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$token,
                'Accept' => 'application/json',
            ])
                ->timeout(30) // 30 seconds timeout
                ->retry(3, 100) // Retry up to 3 times, 100ms between attempts
                ->post('https://api.dewipay.id/v1/ewallet', $post);

            $data = $response->json();

            if ($response->successful()) {
                $transaction->update([
                    'payment_trx_id' => $data['data']['uuid'] ?? null,
                    'payment_va_number' => $data['data']['va_number'] ?? null,
                    'payment_qr_string' => $data['data']['qr_string'] ?? null,
                    'payment_expired_time' => $data['data']['expired_time'] ?? null,
                    'payment_payment_method' => $data['data']['payment_method'] ?? null,
                    'payment_payment_channel' => $data['data']['payment_channel'] ?? null,
                    'payment_customer_phone' => $data['data']['customer_phone'] ?? null,
                ]);

            } else {
                // Log::error($data);
                if (isset($data['status']) && $data['status'] == 422) {

                    $fields = $data['error']['fields'];

                    throw ValidationException::withMessages($fields);
                }
                throw ValidationException::withMessages([
                    'message' => 'Failed to create ewallet',
                ]);
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {
            // Handle HTTP request specific exceptions
            Log::error($e);
            throw ValidationException::withMessages([
                'message' => 'Failed to create ewallet',
            ]);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Connection error: '.$e->getMessage());
            throw ValidationException::withMessages([
                'message' => 'Unable to connect to payment service. Please try again later.',
            ]);
        } catch (\Exception $e) {
            Log::error('Unexpected error: '.$e->getMessage());
            throw ValidationException::withMessages([
                'message' => 'Failed to create ewallet',
            ]);
        }

    }

    public function checkStatus($transaction_id)
    {
        try{
            $transaction = Transaction::find($transaction_id);

            if (! $transaction) {
                throw ValidationException::withMessages([
                    'message' => 'Transaction not found',
                ]);
            }

            if ($transaction->payment_method_category == 'Virtual Account') {
                $url = 'https://api.dewipay.id/v1/virtual_account/inquiry/'.$transaction->payment_trx_id;
            } elseif ($transaction->payment_method_code == 'QRIS') {
                $url = 'https://api.dewipay.id/v1/qris/inquiry/'.$transaction->payment_trx_id;
            } elseif ($transaction->payment_method_category == 'EWallet') {
                $url = 'https://api.dewipay.id/v1/ewallet/inquiry/'.$transaction->payment_trx_id;
            } else {
                throw ValidationException::withMessages([
                    'message' => 'Payment method not found',
                ]);
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->token,
                'Accept' => 'application/json',
            ])->get($url);

            $data = $response->json();

            logger($data);

            if ($response->successful()) {

                if ($data['data']['status'] == 'PAID') {
                    $transaction->update([
                        'status' => Transaction::STATUS_PAID,
                    ]);

                } elseif ($data['data']['status'] == 'ACTIVE') {

                } else {
                    // anggap expired
                    $transaction->update([
                        'status' => Transaction::STATUS_CANCELED,
                    ]);
                }
            }

        } catch (\Illuminate\Http\Client\RequestException $e) {
            // Handle HTTP request specific exceptions
            Log::error($e);
            throw ValidationException::withMessages([
                'message' => 'Failed to check status',
            ]);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Connection error: ' . $e->getMessage());
            throw ValidationException::withMessages([
                'message' => 'Unable to connect to payment service. Please try again later.',
            ]);
        } catch (\Exception $e) {
            Log::error('Unexpected error: ' . $e->getMessage());
            throw ValidationException::withMessages([
                'message' => 'Failed to check status',
            ]);
        }

    }
}
