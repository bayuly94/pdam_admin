<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DigiflazzService
{
    private $username;

    private $secret;

    public function __construct()
    {
        $this->username = env('DIGIFLAZZ_USERNAME');
        $this->secret = env('DIGIFLAZZ_API_KEY');
    }

    public function isDev()
    {
        return strpos($this->secret, 'dev-') !== false;
    }

    public function init()
    {
        cache()->forget('digiflazz_prepaid_pricelist');

        cache()->forget('digiflazz_pasca_pricelist');

        $all = $this->getAllProducts(true);

        // brand
        foreach ($all as $item) {
            $brand = Brand::where('brand_slug', $item['brand_slug_pasca'] ?? $item['brand_slug'])->first();
            if (! $brand) {
                $customer_no_prefix = null;

                if (strtoupper($item['brand']) == 'TELKOMSEL') {
                    $customer_no_prefix = collect(['0811', '0812', '0813', '0821', '0822', '0852', '0853', '0811', '0812', '0813'])->join(',');
                } elseif (strtoupper($item['brand']) == 'INDOSAT') {
                    $customer_no_prefix = collect(['0814', '0815', '0816', '0855', '0856', '0857', '0858', '0814', '0815', '0816', '0855', '0856', '0857', '0858'])->join(',');
                } elseif (strtoupper($item['brand']) == 'XL') {
                    $customer_no_prefix = collect(['0817', '0818', '0819', '0859', '0877', '0878', '0817', '0818', '0819', '0859', '0877', '0878'])->join(',');
                } elseif (strtoupper($item['brand']) == 'TRI') {
                    $customer_no_prefix = collect(['0895', '0896', '0897', '0898', '0899', '0895', '0896', '0897'])->join(',');
                } elseif (strtoupper($item['brand']) == 'SMARTFREN') {
                    $customer_no_prefix = collect(['0881', '0882', '0883', '0884', '0885', '0886', '0887', '0888', '0889'])->join(',');
                }

                Brand::create([
                    'brand' => $item['brand'],
                    'brand_slug' => $item['brand_slug_pasca'] ?? $item['brand_slug'],
                    'is_active' => true,
                    'label_input_customer_no' => 'Masukan Nomor',
                    'customer_no_prefix' => $customer_no_prefix,
                    'logo' => 'assets/icons/'.strtolower(str_replace(' ', '', $item['brand'] ?? '')).'.png',
                ]);
            }
        }


        // category
        foreach ($all as $key => $item) {
            $category = Category::where('name', $item['category'])->first();
            if (! $category) {
                Category::create([
                    'name' => $item['category'],
                    'slug' => $item['category_slug'],
                    'is_active' => true,
                    'sort' => $key,
                ]);
            }
        }
 
    }

    public function getPrepaid($force = false)
    {
        if ($force) {
            cache()->forget('digiflazz_prepaid_pricelist');
        }

        $data = cache()->remember('digiflazz_prepaid_pricelist', now()->addDay(), function () {
            $post = [
                'cmd' => 'prepaid',
                'username' => $this->username,
                'sign' => $this->get_md5('pricelist'),
            ];
            $response = Http::post('https://api.digiflazz.com/v1/price-list', $post);

            $data = $response->json();

            return $data['data'] ?? [];
        });

        Log::info($data);

        // retry if rc != 00
        if (isset($data['rc']) && $data['rc'] != '00') {
            // return $this->getPrepaid(true);
        }

        return $data ?? [];
    }

    public function getPasca($force = false)
    {
        if ($force) {
            cache()->forget('digiflazz_pasca_pricelist');
        }

        $data = cache()->remember('digiflazz_pasca_pricelist', now()->addDay(), function () {
            $post = [
                'cmd' => 'pasca',
                'username' => $this->username,
                'sign' => $this->get_md5('pricelist'),
            ];
            $response = Http::post('https://api.digiflazz.com/v1/price-list', $post);

            $data = $response->json();

            // Log::info($data);

            return $data['data'] ?? [];
        });

        // Log::info($data);

        // retry if rc != 00
        if (isset($data['rc']) && $data['rc'] != '00') {
            // return $this->getPasca(true);
        }

        return $data ?? [];
    }

    public function getDeposit()
    {
        $post = [
            'cmd' => 'deposit',
            'username' => $this->username,
            'sign' => $this->get_md5('pricelist'),
        ];
        $response = Http::post('https://api.digiflazz.com/v1/price-list', $post);

        return $response->json();
    }

    public function get_md5($action)
    {

        return md5($this->username.$this->secret.$action);
    }

    public function checkProvider($phoneNumber)
    {
        // Clean up phone number (remove spaces, hyphens, and country code)
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Remove leading '62' (Indonesia country code)
        if (substr($phoneNumber, 0, 2) === '62') {
            $phoneNumber = '0'.substr($phoneNumber, 2);
        }

        // List of network prefixes
        $prefixes = [
            'Telkomsel' => ['0811', '0812', '0813', '0821', '0822', '0852', '0853'], // Kartu Halo is included in Telkomsel prefixes
            'Indosat' => ['0814', '0815', '0816', '0855', '0856', '0857', '0858'],
            'XL' => ['0817', '0818', '0819', '0859', '0877', '0878'],
            'Tri' => ['0895', '0896', '0897', '0898', '0899'],
            'Smartfren' => ['0881', '0882', '0883', '0884', '0885', '0886', '0887', '0888', '0889'],
        ];

        // Extract the first 4 digits (prefix)
        $prefix = substr($phoneNumber, 0, 4);

        // Check the prefix against each provider's list
        foreach ($prefixes as $provider => $providerPrefixes) {
            if (in_array($prefix, $providerPrefixes)) {
                return $provider;
            }
        }

        // Return 'Unknown' if the prefix does not match any provider
        return 'Unknown';
    }

    public function checkProviderPasca($phoneNumber)
    {
        // Clean up phone number (remove spaces, hyphens, and country code)
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Remove leading '62' (Indonesia country code)
        if (substr($phoneNumber, 0, 2) === '62') {
            $phoneNumber = '0'.substr($phoneNumber, 2);
        }

        // List of network prefixes
        $prefixes = [
            'Halo Postpaid' => ['0811', '0812', '0813'], // Specifically for Kartu Halo
            'Matrix' => ['0814', '0815', '0816'],
            'Smartfren Postpaid' => ['0881', '0882', '0883', '0884'],
            'Three Postpaid' => ['0895', '0896', '0897'],
            'XL Postpaid' => ['0817', '0818', '0819'],

        ];

        // Extract the first 4 digits (prefix)
        $prefix = substr($phoneNumber, 0, 4);

        // Check the prefix against each provider's list
        foreach ($prefixes as $provider => $providerPrefixes) {
            if (in_array($prefix, $providerPrefixes)) {
                return $provider;
            }
        }

        // Return 'Unknown' if the prefix does not match any provider
        return 'Unknown';
    }

    public function getAllProducts($show_all = false)
    {
        $prepaid = $this->getPrepaid();
        $pasca = $this->getPasca();
        // $deposit = $digiflazzService->getDeposit();

        $allitems = [];

        $products = [];

        // prepaid
        foreach ($prepaid as $item) {
            try {
                $allitems[] = [
                    'category' => $item['category'] ?? '',
                    'brand' => $item['brand'] ?? '',
                    'buyer_sku_code' => $item['buyer_sku_code'] ?? '',
                    'name' => $item['product_name'] ?? '',
                    'price' => $item['price'] ?? null,
                    'admin' => $item['admin'] ?? 0,
                    'commission' => $item['commission'] ?? 0,
                    'type' => 'prepaid',
                    'type_slug' => 'prepaid',
                    'brand_slug' => strtolower(str_replace(' ', '-', $item['brand'] ?? '')),
                    'brand_slug_pasca' => null,
                    'category_slug' => strtolower(str_replace(' ', '-', $item['category'] ?? '')),
                    'icon_brand' => asset('assets/icons/'.strtolower(str_replace(' ', '', $item['brand'] ?? '')).'.png'),
                    'icon_category' => asset('assets/icons/'.strtolower(str_replace(' ', '', $item['category'] ?? '')).'.png'),
                ];
            } catch (Exception $e) {
                Log::error($e);
            }
        }

        // pasca
        foreach ($pasca as $item) {
            try {
                $allitems[] = [
                    'category' => $item['brand'] ?? '',
                    'brand' => $item['product_name'] ?? '',
                    'buyer_sku_code' => $item['buyer_sku_code'] ?? '',
                    'name' => $item['product_name'] ?? '',
                    'price' => null,
                    'admin' => $item['admin'] ?? 0,
                    'commission' => $item['commission'] ?? 0,
                    'type' => 'pasca',
                    'type_slug' => 'pasca',
                    'brand_slug' => strtolower(str_replace(' ', '-', $item['brand'] ?? '')),
                    'brand_slug_pasca' => strtolower(str_replace(' ', '-', $item['product_name'] ?? '')),

                    'category_slug' => strtolower(str_replace(' ', '-', $item['category'] ?? '')),
                    'icon_brand' => asset('assets/icons/'.strtolower(str_replace(' ', '', $item['brand'] ?? '')).'.png'),
                    'icon_category' => asset('assets/icons/'.strtolower(str_replace(' ', '', $item['category'] ?? '')).'.png'),
                ];
            } catch (Exception $e) {
                Log::error($e);
            }
        }

        if (! $show_all) {
            // allowed categories
            $categories = Category::active()->get();

            $allowed_categories = $categories->pluck('slug')->toArray();

            // allowed brands
            $brands = Brand::active()->get();

            $allowed_brands = $brands->pluck('brand_slug')->toArray();

            foreach ($allitems as $key => $item) {

                if (! in_array($item['category_slug'], $allowed_categories)) {
                    unset($allitems[$key]);
                } else {

                    $allitems[$key]['category_sort'] = $categories->where('slug', $item['category_slug'])->first()->sort ?? 999999;

                    if (! in_array($item['brand_slug'], $allowed_brands)) {
                        unset($allitems[$key]);
                    } else {
                        // update

                        $brand = $brands->where('brand_slug', $item['brand_slug'])->first();

                        $allitems[$key]['icon_brand'] = $brand->brand_logo_url ?? $item['icon_brand'];
                        $allitems[$key]['brand_customer_no_prefix'] = $brand->customer_no_prefix != null ? explode(',', $brand->customer_no_prefix) : null;

                    }
                }
            }
        }

        return $allitems;
    }

    public function getAllProductGroup($show_all = false)
    {
        $allitems = $this->getAllProducts($show_all);

        $categories = collect($allitems)->groupBy('category')->toArray();
        // dd($categories);

        $products = [];

        foreach ($categories as $category => $items) {
            $brands_filters = collect($items)->groupBy('brand')->toArray();

            $brands = [];

            foreach ($brands_filters as $brand => $items) {
                $ps = [];
                foreach ($items as $item) {
                    $ps[] = $item;
                }
                if (count($items) > 0 ) {
                    $brands[] = [
                        'icon' => $items[0]['icon_brand'],
                        'type' => $items[0]['type'] ?? '',
                        'type_slug' => $items[0]['type_slug'] ?? '',
                        'brand' => $brand,
                        'brand_slug' => $items[0]['brand_slug'] ?? '',
                        'brand_slug_pasca' => $items[0]['brand_slug_pasca'] ?? '',
                        'products' => $ps,
                    ];
                }
            }

            usort($brands, function ($a, $b) {
                return strcasecmp($a['brand'], $b['brand']);
            });

            $products[] = [
                'icon' => $items[0]['icon_category'],
                'category' => $category,
                'category_sort' => $items[0]['category_sort'],
                'category_slug' => $items[0]['category_slug'] ?? 9999,
                'brands' => $brands,
            ];
        }   

        // dd($products);

        // sort by category
        usort($products, function ($a, $b) {
            return $a['category_sort'] - $b['category_sort'];
        });

        return $products;
    }

    public function getProductsByBrand($brand_slug)
    {
        $allitems = $this->getAllProducts();

        $products = collect($allitems)->where('brand_slug', $brand_slug)->toArray();

        $products_pasca = collect($allitems)->where('brand_slug_pasca', $brand_slug)->toArray();

        // merge
        $products = array_merge($products, $products_pasca);

        return $products;
    }

    public function getProductByBuyerSku($buyer_sku_code)
    {
        $allitems = $this->getAllProducts();

        $products = collect($allitems)->where('buyer_sku_code', $buyer_sku_code);

        return $products->first() ?? null;
    }

    public function payBilling($transaction_id)
    {

        $transaction = Transaction::find($transaction_id);

        if (! $transaction) {
            return 'Transaction not found';
        }

        $sku = $transaction->buyer_sku_code;

        $is_pasca = $transaction->type == 'pasca';

        $post = [
            'testing' => $this->isDev(),
            'buyer_sku_code' => $sku,
            'username' => $this->username,
            'sign' => $this->get_md5($transaction->code),
            'ref_id' => $transaction->code,
            'customer_no' => $transaction->customer_no,
        ];

        if ($is_pasca) {
            $post['commands'] = 'pay-pasca';
        }

        $response = Http::post('https://api.digiflazz.com/v1/transaction', $post);

        $res = $response->json();

        logger($res);

        return $res;
    }

    public function checkName($buyer_sku_code, $customer_no)
    {

        // get from cache
        $cache = Cache::get($customer_no . "_" . $buyer_sku_code);

        if ($cache) {
            $name = $cache['data']['sn'];
            if ($name != null && $name != "") {
                return $name;
            }
        }

        $product = $this->getProductByBuyerSku($buyer_sku_code);

        $type = $product['type'] ?? 'prepaid';

        $refid = date('Ymdhis').rand(10, 999999);

        $post = [
            'testing' => $this->isDev(),
            'buyer_sku_code' => $buyer_sku_code,
            'username' => $this->username,
            'sign' => $this->get_md5($refid),
            'ref_id' => $refid,
            'customer_no' => $customer_no,
        ];

        if ($type == 'pasca') {
            $post['commands'] = 'inq-pasca';
        }

        $response = Http::post('https://api.digiflazz.com/v1/transaction', $post);

        $res = $response->json();

        logger($res);


        if (!isset($res['data'])) {
            throw new Exception($res['message']);
        }

        $name = $res['data']['sn'];

        if ($name != null && $name != "") {
            return $name;
        }

        return null;
    }

    public function checkBilling($buyer_sku_code, $customer_no)
    {
        $product = $this->getProductByBuyerSku($buyer_sku_code);


        if ($product['type'] == 'pln') {
            return $this->checkBillingPln($customer_no);
        }

        $is_pasca = $product['type'] == 'pasca';

        $refid = date('Ymdhis').rand(10, 999999);
        $post = [
            'testing' => $this->isDev(),
            'buyer_sku_code' => $buyer_sku_code,
            'customer_no' => $customer_no,
            'username' => $this->username,
            'sign' => $this->get_md5($refid),
            'ref_id' => $refid,
        ];

        if ($is_pasca) {
            $post['commands'] = 'inq-pasca';
        }

        $response = Http::post('https://api.digiflazz.com/v1/transaction', $post);

        $res = $response->json();

        logger($res);

        if (!isset($res['data'])) {
            throw new Exception($res['message']);
        }

        if ($res['data']['rc'] != '00' && $res['data']['rc'] != '03') {
            throw new Exception($res['data']['message']);
        }

        return $res;
    }

    
    public function checkBillingPln($customer_no)
    {
   

        $refid = date('Ymdhis').rand(10, 999999);
        $post = [
            'customer_no' => $customer_no,
            'username' => $this->username,
            'sign' => $this->get_md5($customer_no),
        ];

  
        $response = Http::post('https://api.digiflazz.com/v1/inquiry-pln', $post);

        $res = $response->json();

        logger($res);

        if ($res['data']['rc'] != '00' && $res['data']['rc'] != '03') {
            throw new Exception($res['data']['message']);
        }

        return $res;
    }
}
