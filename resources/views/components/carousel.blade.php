@props(['slides' => []])

<div class="relative w-full mb-12 rounded-lg overflow-hidden">
    <div class="relative w-full" style="padding-bottom: 37.5%;"> <!-- 16:6 aspect ratio -->
        <div class="absolute inset-0 swiper-container">
            <div class="swiper-wrapper">
                @foreach($slides as $slide)
                    <div class="swiper-slide">
                        <div class="w-full h-full relative">
                            <!-- Background Image with Overlay -->
                            {{-- <div class="absolute inset-0 bg-black/30 z-0"></div> --}}
                            <img 
                                src="{{ $slide['image'] }}" 
                                alt="{{ $slide['alt'] }}"
                                class="w-full h-full object-cover"
                                onerror="this.onerror=null; this.src='{{ asset('logo.png') }}'"
                            >
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>
            <!-- Navigation buttons -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>
</div>

@once
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <style>
        .swiper-container {
            width: 100%;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .swiper-slide {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .swiper-button-next,
        .swiper-button-prev {
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        .swiper-pagination-bullet {
            background: white;
            opacity: 0.5;
        }
        .swiper-pagination-bullet-active {
            opacity: 1;
        }
    </style>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.swiper-container').forEach(container => {
                new Swiper(container, {
                    loop: true,
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    pagination: {
                        el: container.querySelector('.swiper-pagination'),
                        clickable: true,
                    },
                    navigation: {
                        nextEl: container.querySelector('.swiper-button-next'),
                        prevEl: container.querySelector('.swiper-button-prev'),
                    },
                });
            });
        });
    </script>
@endonce
