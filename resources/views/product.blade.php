@extends('layouts.app')

@section('content')
    <div class="py-8">

        <form action="{{ route('checkout') }}" id="buyForm" method="POST">
            @csrf
            <input type="hidden" name="buyer_sku_code" id="buyer_sku_code" value="{{ old('buyer_sku_code', $buyer_sku_code) }}">
            <input type="hidden" name="price" id="price" value="{{ old('price') }}">
            <input type="hidden" name="type" id="type" value="{{ $type }}">
            <input type="hidden" name="customer_no" id="customer_no_hidden" value="{{ old('customer_no', request()->customer_no) }}">
            <input type="hidden" name="payment_method_code" id="payment_method_code"
                value="{{ old('payment_method_code') }}">
            <input type="hidden" name="payment_method_name" id="payment_method_name"
                value="{{ old('payment_method_name') }}">
            <input type="hidden" name="payment_method_category" id="payment_method_category"
                value="{{ old('payment_method_category') }}">

        </form>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 lg:flex lg:gap-3 mb-4">
            <a href="{{ route('home') }}"
                class="inline-flex items-center gap-2 px-3 py-2 text-sm text-gray-700 transition-colors duration-200 bg-white rounded-lg shadow-lg shadow-gray-300 hover:text-gray-900 w-fit">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                <span>Kembali</span>
            </a>




        </div>

        @if (session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 lg:flex lg:gap-3 mb-4">
                <div class="w-full bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">

                    <p>{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 lg:flex lg:gap-3 mb-4">
                <div class="w-full bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                  
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 lg:flex lg:gap-3">

            <div class="lg:w-9/12 mb-4">

                <div class="bg-white rounded-lg shadow-lg shadow-gray-200">

                    <div class="flex gap-6 p-6 items-center">
                        <img src="{{ $brand->brand_logo_url ?? '' }}" alt="{{ $brand->brand ?? '' }}"
                            class="w-16 h-16 object-contain"
                            onerror="this.onerror=null; this.src='{{ asset('logo.png') }}'">

                        <h3 class="text-2xl font-semibold text-gray-800">{{ strtoupper($brand->brand) }}</h3>
                    </div>

                    <x-buy-section-title step="1" title="{{ strtoupper($brand->label_input_customer_no) }}" />


                    {{-- input nomor telepon --}}
                    <div class="p-6">

                        @if ($type == 'pasca')
                            <form action="{{ request()->url() }}" method="GET" id="checkBillForm">
                        @endif
                        <label for="customer_no"
                            class="block text-sm font-medium text-gray-700 mb-2">{{ $brand->label_input_customer_no }}</label>
                        <div class="flex gap-3">
                            <input type="number" id="customer_no" name="customer_no"
                                class="flex-1 p-3 border border-gray-200 rounded-lg"
                                placeholder="{{ $brand->label_input_customer_no }}"
                                value="{{ request('customer_no', old('customer_no')) }}">

                            @if ($type == 'pasca')
                                <button type="submit"
                                    class="px-6 py-3 whitespace-nowrap bg-green-500 hover:bg-green-600 text-white font-medium rounded-lg transition-colors duration-200">
                                    Cek Tagihan
                                </button>
                            @endif
                        </div>

                        @if ($type == 'pasca')
                            </form>
                        @endif
                    </div>

                    <x-buy-section-title step="2"
                        title="{{ $type == 'prepaid' ? 'PILIH NOMINAL' : 'INFORMASI TAGIHAN' }}" />


                    <div class="grid grid-cols-2 gap-4 mt-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 p-6">

                        @if (count($products) == 0)
                            <div class="w-full h-full flex items-center justify-center">
                                <p class="text-gray-500">Tidak ada informasi</p>
                            </div>
                        @endif

                        @foreach ($products as $product)
                            <a href="#" class="block group h-full rounded-lg transition-all duration-200"
                                data-product-id="{{ $product['buyer_sku_code'] }}" data-price="{{ $product['price'] }}">
                                <div class="h-full flex flex-col">
                                    <div
                                        class=" flex items-center justify-center rounded-lg border border-gray-200 group-hover:shadow-md overflow-hidden bg-white product-content">
                                        <div class="w-full">
                                            <div
                                                class="min-h-[4rem] w-full p-3 text-start group-hover:bg-gray-50 transition-colors">
                                                <span
                                                    class="text-sm font-medium text-gray-900 group-hover:text-green-600 leading-tight line-clamp-2">
                                                    {{ $product['name'] }}
                                                </span>
                                            </div>


                                            <div
                                                class="p-3 bg-gray-50 flex items-center justify-between border-t border-gray-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                                <span class="font-medium text-gray-900">Rp
                                                    {{ number_format($product['price'], 0, ',', '.') }}</span>
                                            </div>
                                        </div>

                                    </div>



                                </div>



                            </a>
                        @endforeach
                    </div>



                    <x-buy-section-title step="3" title="PILIH METODE BAYAR" />


                    <div class="w-full mx-auto p-6">

                        @foreach ($payments as $key => $payment)
                            <div class="accordion border rounded-xl overflow-hidden mb-4 payment-method-group">
                                <!-- Header -->
                                <button
                                    class="accordion-header w-full flex justify-between items-center p-4 bg-white hover:bg-gray-50 transition"
                                    onclick="toggleAccordion(this)">
                                    <div class="flex items-center space-x-3">
                                        {{-- <img src="https://cdn-icons-png.flaticon.com/512/2331/2331941.png" alt="wallet"
                                            class="w-6 h-6"> --}}
                                        <span class="font-semibold text-gray-800">{{ $key }}</span>
                                    </div>

                                    <div class="flex items-center space-x-3">
                                        <span class="font-semibold text-gray-700">
                                            {{-- Rp32.678 --}}
                                        </span>
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="w-5 h-5 text-gray-600 transition-transform duration-200" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </button>

                                <!-- Content -->
                                <div class="accordion-content hidden bg-white border-t p-4 ">
                                    <div class="grid grid-cols-2 gap-3">
                                        <!-- Item -->

                                        @foreach ($payment as $item)
                                            <div class="payment-method  bg-white p-3 rounded-lg border cursor-pointer hover:bg-gray-50 transition-colors border-gray-200"
                                                data-payment-channel="{{ $item['payment_channel'] }}"
                                                data-category="{{ $key }}"
                                                data-min-amount="{{ $item['min_amount'] }}"
                                                onclick="selectPaymentMethod(this, '{{ $item['payment_channel'] }}', '{{ $key }}')">
                                                <div class="flex items-center space-x-2 w-full justify-between  ">
                                                    <img src="{{ asset('assets/payment_methods/' . strtolower($item['payment_channel']) . '.svg') }}"
                                                        class="w-10 h-10 object-contain mr-3"
                                                        onerror="this.onerror=null; this.src='{{ asset('logo.png') }}'">
                                                    <div class="w-full">
                                                        <span class="text-sm font-medium text-gray-800">
                                                            {{ $item['payment_channel'] }}

                                                        </span>
                                                        <div class="flex justify-between items-center mt-1">
                                                            <span class="text-xs text-gray-500">
                                                                {{ $key }}
                                                                <br>
                                                                Min: Rp
                                                                {{ number_format($item['min_amount'], 0, ',', '.') }}
                                                            </span>
                                                            @if (isset($item['fee']))
                                                                <span class="text-xs text-gray-500">Fee:
                                                                    {{ number_format($item['fee'], 0, ',', '.') }}</span>
                                                            @endif


                                                        </div>
                                                    </div>
                                                    <div class="payment-check ml-2 hidden">
                                                        <svg class="w-5 h-5 text-green-500" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </div>


                                                </div>

                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>


                </div>


                {{-- <button class="bg-green-500 text-white w-full py-3 rounded-lg mt-6" id="checkout">CHECKOUT</button> --}}
            </div>


            <div
                class="bg-white rounded-lg shadow-lg shadow-gray-200 lg:w-3/12 h-fit  lg:sticky lg:top-4 overflow-y-auto p-4">

                <h4 class="text-lg font-semibold text-green-800 mb-4">Layanan Lainnya</h4>
                <div class="gap-4">

                    @foreach ($other_services as $service)
                        <a href="{{ route('product', ['type' => $service['type_slug'], 'category' => $category_slug, 'brand' => $service['brand_slug']]) }}"
                            class="flex items-center gap-2 border p-2 rounded-lg w-full mb-3 hover:bg-gray-50 transition-colors cursor-pointer hover:border-green-500">
                            <img src="{{ $service['icon'] }}" alt="wallet" class="w-6 h-6">
                            <span class="font-semibold text-gray-800">{{ $service['brand'] }}</span>
                        </a>
                    @endforeach

                </div>
            </div>

        </div>



    </div>
@endsection



@push('styles')
    <style>
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }
    </style>
@endpush

@push('scripts')
    <script>
        
        document.addEventListener('DOMContentLoaded', function() {
            const productItems = document.querySelectorAll('a[data-product-id]');
            const buyerSkuCode = document.getElementById('buyer_sku_code').value;

            // Function to select a product by its ID
            function selectProduct(productId) {
                productItems.forEach(item => {
                    if (item.dataset.productId === productId) {
                        item.classList.add('ring-2', 'ring-green-500');
                        const content = item.querySelector('.product-content');
                        if (content) content.classList.add('bg-green-50');
                        document.getElementById('buyer_sku_code').value = productId;
                        document.getElementById('price').value = item.dataset.price;


                    } else {
                        item.classList.remove('ring-2', 'ring-green-500');
                        const content = item.querySelector('.product-content');
                        if (content) content.classList.remove('bg-green-50');
                    }
                });

                disableAllPaymentMethod();
                // Clear any selected payment method when price changes
                document.getElementById('payment_method_code').value = '';
                document.getElementById('payment_method_name').value = '';
                document.getElementById('payment_method_category').value = '';
            }

            // Auto-select product if buyer_sku_code exists
            if (buyerSkuCode) {
                selectProduct(buyerSkuCode);
            }

            // Handle click on product items
            productItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const productId = this.dataset.productId;
                    selectProduct(productId);
                });
            });

            // Handle form submission
            document.getElementById('buyForm')?.addEventListener('submit', function() {
                if (!document.getElementById('buyer_sku_code').value) {
                    e.preventDefault();
                    alert('Please select a product');
                    return false;
                }
                return true;
            });
        });


    </script>


    <script>
        function toggleAccordion(btn) {
            const content = btn.nextElementSibling;
            const icon = btn.querySelector("svg");

            // close other accordions if multiple exist
            document.querySelectorAll(".accordion-content").forEach((el) => {
                if (el !== content) {
                    el.classList.add("hidden");
                    el.previousElementSibling.querySelector("svg").classList.remove("rotate-180");
                }
            });

            // toggle selected
            content.classList.toggle("hidden");
            icon.classList.toggle("rotate-180");
        }
    </script>


    <script>
        // disable all payment method
        function disableAllPaymentMethod() {
            const price = parseInt(document.getElementById('price').value) || 0;

            // First, process all payment methods
            document.querySelectorAll('.payment-method-group').forEach(group => {
                const paymentMethods = group.querySelectorAll('.payment-method');
                let allHidden = paymentMethods.length > 0; // Start true if there are payment methods
                
                paymentMethods.forEach(item => {
                    const minAmount = parseInt(item.dataset.minAmount) || 0;
                    item.classList.remove('border-green-500', 'ring-2', 'ring-green-500');
                    const checkElement = item.querySelector('.payment-check');
                    if (checkElement) {
                        checkElement.classList.add('hidden');
                    }

                    // Show/hide based on minimum amount
                    if (price < minAmount) {
                        item.classList.add('hidden');
                    } else {
                        item.classList.remove('hidden');
                        allHidden = false; // At least one payment method is visible
                    }
                });

                // Toggle visibility of the payment method group
                const groupHeader = group.previousElementSibling;
                if (allHidden) {
                    group.classList.add('hidden');
                    group.classList.remove('border');
                } else {
                    group.classList.remove('hidden');
                    group.classList.add('border');
                }
            });
        }

        // Initial disable check
        disableAllPaymentMethod();


        function selectPaymentMethod(element, channel, category) {
            const minAmount = parseInt(element.dataset.minAmount) || 0;
            const price = parseInt(document.getElementById('price').value) || 0;

            // Prevent selection if price is less than minimum amount
            if (price < minAmount) {
                return false;
            }


            // Remove selection from all payment methods
            document.querySelectorAll('.payment-method').forEach(item => {
                item.classList.remove('border-green-500', 'ring-2', 'ring-green-500');
                item.querySelector('.payment-check').classList.add('hidden');
            });

            // Add selection to clicked payment method
            element.classList.add('border-green-500', 'ring-2', 'ring-green-500');
            element.querySelector('.payment-check').classList.remove('hidden');

            // Update hidden form fields
            document.getElementById('payment_method_code').value = channel;
            document.getElementById('payment_method_name').value = channel;
            document.getElementById('payment_method_category').value = category;

            // Enable submit button if not already enabled
            const submitBtn = document.querySelector('button[type="submit"]');
            if (submitBtn && submitBtn.disabled) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                submitBtn.classList.add('bg-green-600', 'hover:bg-green-700');
            }

            submit();
        }

        // Add click handler to all payment methods on page load
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.payment-method').forEach(item => {
                item.addEventListener('click', function() {
                    const channel = this.dataset.paymentChannel;
                    const category = this.dataset.category;
                    selectPaymentMethod(this, channel, category);
                });
            });
        });


        function submit() {
            // Show loading overlay
            const loadingOverlay = document.createElement('div');
            loadingOverlay.id = 'loadingOverlay';
            loadingOverlay.style.position = 'fixed';
            loadingOverlay.style.top = '0';
            loadingOverlay.style.left = '0';
            loadingOverlay.style.width = '100%';
            loadingOverlay.style.height = '100%';
            loadingOverlay.style.backgroundColor = 'rgba(0, 0, 0, 0.7)';
            loadingOverlay.style.display = 'flex';
            loadingOverlay.style.justifyContent = 'center';
            loadingOverlay.style.alignItems = 'center';
            loadingOverlay.style.zIndex = '9999';

            const spinner = document.createElement('div');
            spinner.innerHTML = `
                <div style="text-align: center; color: white;">
                    <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-white mx-auto"></div>
                    <p class="mt-4 text-xl font-semibold">Processing...</p>
                </div>
            `;

            loadingOverlay.appendChild(spinner);
            document.body.appendChild(loadingOverlay);
            document.body.style.overflow = 'hidden';

            // Update hidden field and submit form
            document.getElementById('customer_no_hidden').value = document.getElementById('customer_no').value;
            document.getElementById('buyForm').submit();
        }
    </script>


    <script>
        // document.getElementById('checkout').addEventListener('click', function() {
        //     document.getElementById('customer_no_hidden').value = document.getElementById('customer_no').value;
        //     document.getElementById('buyForm').submit();
        // });
    </script>
@endpush
