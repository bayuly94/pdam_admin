@extends('layouts.app')

@section('content')
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Hero Carousel -->
            <x-carousel :slides="$banners" />



            @foreach ($products as $category)
                <div class="mb-8">

                    <div class="flex items-center justify-between gap-4">
                        <h4 class="text-xl">{{ $category['category'] }}</h4>

                    </div>

                    <hr class="my-4 text-gray-200">

                    <div class="grid grid-cols-2 gap-4 mt-6 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6">


                        @foreach ($category['brands'] as $brand)
                            <a href="{{ route('product', ['type' => $brand['type_slug'], 'category' => $category['category_slug'], 'brand' => $brand['type_slug'] == 'prepaid' ? $brand['brand_slug'] : $brand['brand_slug_pasca']]) }}" class="block">
                                <div class="group relative">
                                    <div class="w-full aspect-square rounded-xl border bg-white border-gray-200 transition-all duration-200 group-hover:shadow-md overflow-hidden">
                                        <div class="w-full h-full flex items-center justify-center p-2">
                                            <img 
                                                src="{{ $brand['icon'] }}" 
                                                alt="{{ $brand['brand'] }}"
                                                class="max-w-full max-h-full object-contain"
                                                onerror="this.onerror=null; this.src='{{ asset('logo.png') }}'"
                                            >
                                        </div>
                                    </div>
                                    <div class="mt-4 mb-2 text-center">
                                        <h3 class="text-sm font-medium text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $brand['brand'] }}</h3>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach



        </div>
    </div>
@endsection
