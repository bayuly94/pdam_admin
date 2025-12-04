@props([
    'companyName' => setting('app_name'),
    'description' => setting('app_description'),
    'email' => setting('contact_email'),
    'phone' => setting('contact_phone'),
    'address' => setting('contact_address'),
    'year' => null,
    'links' => [
        ['label' => 'Home', 'route' => 'home'],
        ['label' => 'Products', 'route' => 'home'],
        ['label' => 'About Us', 'route' => 'home'],
        ['label' => 'Contact', 'route' => 'home'],
    ]
])

<footer {{ $attributes->merge(['class' => 'bg-gray-800 text-white mt-auto']) }}>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Company Info -->
            <div>
                <h3 class="text-lg font-semibold mb-4">{{ $companyName ?? config('app.name', 'Laravel') }}</h3>
                <p class="text-gray-300 text-sm">{{ $description ?? '' }}</p>
            </div>
            
            <!-- Quick Links -->
            {{-- <div>
                <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                <ul class="space-y-2">
                    @foreach($links as $link)
                        <li>
                            <a href="{{ route($link['route']) }}" class="text-gray-300 hover:text-white text-sm">
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div> --}}
            
            <!-- Contact Info -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Contact Us</h3>
                <ul class="space-y-2 text-sm">
                    @if($email)
                    <li class="flex items-center text-gray-300">
                        
                        <a target="_blank" href="mailto:{{ $email }}" class="flex"><svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg> {{ $email }}</a>
                    </li>
                    @endif
                    
                    @if($phone)
                    <li class="flex items-center text-gray-300">
                        <a target="_blank" href="https://wa.me/{{ $phone }}" class="flex">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        {{ $phone }}
                        </a>
                    </li>
                    @endif
                    
                    @if($address)
                    <li class="flex items-center text-gray-300">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ $address }}
                        
                    </li>
                    @endif
                </ul>
            </div>
        </div>
        
        <!-- Copyright -->
        <div class="border-t border-gray-700 mt-8 pt-6 text-center text-sm text-gray-400">
            &copy; {{ $year ?? date('Y') }} {{ $companyName ?? config('app.name', 'Laravel') }}. All rights reserved.
        </div>
    </div>
</footer>
