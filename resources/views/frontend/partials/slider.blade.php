@if(isset($sliders) && $sliders->isNotEmpty())
<div class="relative h-[370px] sm:h-[450px] md:h-[530px] lg:h-[610px] w-full overflow-hidden bg-[#2C2C2A]" id="main-slider">
    <!-- Slides Wrapper -->
    @foreach($sliders as $key => $slide)
        <div class="absolute inset-0 transition-all duration-1000 ease-in-out transform {{ $key === 0 ? 'opacity-100 scale-100 z-10' : 'opacity-0 scale-105 z-0' }} slide-item" data-slide-index="{{ $key }}">
            <picture class="w-full h-full block">
                <source media="(max-width: 767px)" srcset="{{ asset(lang($slide, 'image_mobile')) }}">
                <img src="{{ asset(lang($slide, 'image_desktop')) }}" alt="{{ lang($slide, 'name') }}" class="w-full h-full object-cover opacity-80">
            </picture>

            <!-- High-contrast warm architectural gradient overlay -->
            <div class="absolute inset-0 bg-gradient-to-r from-charcoal-900/85 via-charcoal/50 to-transparent"></div>
            
            <!-- Editorial Textual Overlay -->
            <div class="absolute inset-0 flex items-center">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                    <div class="max-w-2xl text-white space-y-4 sm:space-y-6">
                        <div>
                            <span class="inline-block bg-white/90 border border-white/20 text-charcoal text-3xs sm:text-2xs font-bold uppercase px-4 py-1.5 rounded-full tracking-widest backdrop-blur-md shadow-sm">
                                <span class="tag-line-oak"></span> {{ $web->name_vn ?? 'NỘI THẤT & KIẾN TRÚC BASE' }}
                            </span>
                        </div>

                        <h1 class="text-2xl sm:text-4xl md:text-5xl lg:text-6xl font-heading font-semibold leading-tight text-white drop-shadow-sm tracking-tight">
                            {{ lang($slide, 'name') }}
                        </h1>

                        <!-- Action Buttons Row -->
                        <div class="pt-2 flex flex-wrap items-center gap-3 sm:gap-4">
                            <a href="{{ url('/thiet-ke-thi-cong') }}" class="btn-editorial-light">
                                <span>Khám Phá Dự Án</span>
                                <i class="fa-solid fa-arrow-right ml-1 text-xs"></i>
                            </a>
                            <a href="{{ url('/lien-he') }}" class="btn-editorial-primary border-white/30 hover:border-taupe-oak">
                                <span>Liên Hệ Tư Vấn</span>
                                <i class="fa-solid fa-phone ml-1 text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Navigation arrows (Hidden on mobile per specification) -->
    <button onclick="prevSlide()" class="hidden sm:flex absolute left-4 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-white/80 hover:bg-taupe-oak text-charcoal hover:text-white border border-border-subtle shadow-md items-center justify-center transition-all duration-300 backdrop-blur-sm" aria-label="Slide trước">
        <i class="fa-solid fa-chevron-left text-sm"></i>
    </button>
    <button onclick="nextSlide()" class="hidden sm:flex absolute right-4 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-white/80 hover:bg-taupe-oak text-charcoal hover:text-white border border-border-subtle shadow-md items-center justify-center transition-all duration-300 backdrop-blur-sm" aria-label="Slide kế tiếp">
        <i class="fa-solid fa-chevron-right text-sm"></i>
    </button>

    <!-- Pagination dots -->
    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 flex space-x-2.5">
        @foreach($sliders as $key => $slide)
            <button onclick="goToSlide({{ $key }})" class="w-3 h-3 rounded-full border border-white/50 transition-all duration-300 slide-dot {{ $key === 0 ? 'bg-taupe-oak w-8 border-taupe-oak' : 'bg-white/50' }}" data-slide-dot="{{ $key }}" aria-label="Slide {{ $key + 1 }}"></button>
        @endforeach
    </div>
</div>

<script src="{{ asset('frontend/js/slider.js') }}"></script>
@endif
