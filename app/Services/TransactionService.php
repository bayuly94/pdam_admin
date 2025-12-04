<?php

namespace App\Services;

use App\Mail\InvoiceEmail;
use App\Models\Brand;
use Exception;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class TransactionService
{
    public function generateCode()
    {
        return strtoupper(substr(str_shuffle(str_repeat(strtoupper('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 5)), 1, 5) . uniqid());
    }


    public function create($sku, $customer_no, $type, $payment_method_code, $payment_method_name, $payment_method_category)
    {
        try{
            DB::beginTransaction();

            $digiflazz = new DigiflazzService();
            $product = $digiflazz->getProductByBuyerSku($sku);

            if (!$product) {
                throw new Exception('Product not found');
            }


            // validation
            $brand_customer_no_prefix = $product['brand_customer_no_prefix'] ?? null;

            if ($brand_customer_no_prefix != null && is_array($brand_customer_no_prefix)) {
                $isValid = false;
                foreach ($brand_customer_no_prefix as $prefix) {
                    if (str_starts_with($customer_no, $prefix)) {
                        $isValid = true;
                        break;
                    }
                }
                if (!$isValid) {
                    throw new Exception('Masukan nomor yang benar');
                }
            }

            $customer_name = null;

            $brand = Brand::where('brand_slug', $product['brand_slug'])->first();
   
            if ($brand && $brand->customer_no_check_sku != null) {
                $customer_name = $digiflazz->checkName($brand->customer_no_check_sku, $customer_no);
            }

            $type = $product['type'];

            if ($type == 'pasca') {
                // get billing
                $billing = $digiflazz->checkBilling($sku, $customer_no);

                if (isset($billing['data']) && $billing['data']['rc'] == '00') {
                    $billing_data = $billing['data'];

                    $price = $billing_data['selling_price'];
                    $product['price'] = $price;
                }
            }


            $price = $product['price'];
            $fee = 0;
            $total = $price + $fee;

            $insert = [
                'code' => $this->generateCode(),
                'type' => $type,
                'customer_no' => $customer_no,
                'customer_name' => $customer_name,
                'brand' => $product['brand'] ?? "-",
                'buyer_sku_code' => $sku,
                'product_name'  => $product['name'] ?? "-",
                'status' => 'pending',
                'price' => $price,
                'fee' => $fee,
                'total' => $total,
                'payment_method_code' => $payment_method_code,
                'payment_method_name' => $payment_method_name,
                'payment_method_category' => $payment_method_category,
            ];

            $transaction = Transaction::create($insert);

            DB::commit();
            return $transaction;
        }catch(Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    public function createInvoice($transaction_id)
    {
        try{
            DB::beginTransaction();

            $transaction = Transaction::find($transaction_id);

            if (!$transaction) {
                throw new Exception('Transaction not found');
            }

            $dewipay = new DewipayService();
            $dewipay->create($transaction->id);


            Mail::to($transaction->invoice_to_email)->send(new InvoiceEmail($transaction->id));

            DB::commit();
            return $transaction;
        }catch(Exception $e){
            DB::rollBack();
            throw $e;
        }
    }


    public function checkStatus($id)
    {
        try{
            DB::beginTransaction();

            $transaction = Transaction::find($id);

            if (!$transaction) {
                throw new Exception('Transaction not found');
            }

            if ($transaction->status == Transaction::STATUS_WAIT_PAYMENT) {
                $dewipay = new DewipayService();
                $dewipay->checkStatus($transaction->id);
                
                $transaction = Transaction::find($id);


                if ($transaction->status == Transaction::STATUS_PAID) {
                    $this->setProcessing($transaction->id);
                }

            }

            DB::commit();
            return $transaction;
        }catch(Exception $e){
            DB::rollBack();
            throw $e;
        }
    }


    public function setProcessing($id)
    {
        try{
            DB::beginTransaction();

            $transaction = Transaction::find($id);

            if (!$transaction) {
                throw new Exception('Transaction not found');
            }

            $transaction->update([
                'status' => Transaction::STATUS_PROCESSING,
            ]);

            $digiflazz = new DigiflazzService();
            $digiflazz->payBilling($transaction->id);

            DB::commit();
            return $transaction;
        }catch(Exception $e){
            DB::rollBack();
            throw $e;
        }
    }


    public function setSuccess($code)
    {
        try{
            DB::beginTransaction();

            $transaction = Transaction::where('code', $code)->first();

            if (!$transaction) {
                throw new Exception('Transaction not found');
            }

            $transaction->update([
                'status' => Transaction::STATUS_SUCCESS,
            ]);

            DB::commit();
            return $transaction;
        }catch(Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

}
