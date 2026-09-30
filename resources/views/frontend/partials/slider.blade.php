@if(isset($sliders) && $sliders->isNotEmpty())
<div class="relative w-full aspect-[1920/1280] aspect-hero-slider overflow-hidden" id="main-slider">
    <!-- Slides Wrapper -->
    @foreach($sliders as $key => $slide)
        <div class="absolute inset-0 transition-all duration-1000 ease-in-out transform {{ $key === 0 ? 'opacity-100 scale-100 z-10' : 'opacity-0 scale-105 z-0' }} slide-item" data-slide-index="{{ $key }}">
            @if(!empty($slide->link) && $slide->link !== '#')
                <a href="{{ $slide->link }}" class="absolute inset-0 z-10" aria-label="{{ lang($slide, 'name') }}"></a>
            @endif

            <picture class="w-full h-full block">
                <source media="(max-width: 767px)" srcset="{{ asset(lang($slide, 'image_mobile')) }}">
                <img src="{{ asset(lang($slide, 'image_desktop')) }}" alt="{{ lang($slide, 'name') }}" class="w-full h-full object-cover object-center">
            </picture>

            <!-- Subtle atmospheric gradient overlay for textual readability without obscuring banner artwork -->
            <div class="absolute inset-0 bg-gradient-to-r from-charcoal-900/40 via-charcoal-900/15 to-transparent pointer-events-none"></div>
            
            <!-- Editorial Textual Overlay -->
            <div class="absolute inset-0 flex items-center pointer-events-none z-10">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                    <div class="max-w-xl text-white space-y-2 sm:space-y-4 md:space-y-5">
                        <div class="hidden sm:block">
                            <span class="inline-block bg-white/90 border border-white/20 text-charcoal text-3xs sm:text-2xs font-bold uppercase px-3 py-1 sm:px-4 sm:py-1.5 rounded-full tracking-widest backdrop-blur-md shadow-sm">
                                <span class="tag-line-oak"></span> {{ $web->name_vn ?? 'NỘI THẤT & KIẾN TRÚC BASE' }}
                            </span>
                        </div>

                        <h1 class="text-base sm:text-2xl md:text-4xl lg:text-5xl font-heading font-semibold leading-tight text-white drop-shadow-md tracking-tight">
                            {{ lang($slide, 'name') }}
                        </h1>

                        <!-- Action Buttons Row -->
                        <div class="pt-1 sm:pt-2 flex flex-wrap items-center gap-2 sm:gap-3 pointer-events-auto">
                            <a href="{{ url('/thiet-ke-thi-cong') }}" class="btn-editorial-light text-3xs sm:text-xs py-1.5 px-3 sm:py-2.5 sm:px-5">
                                <span>Khám Phá Dự Án</span>
                                <i class="fa-solid fa-arrow-right ml-1 text-3xs sm:text-xs"></i>
                            </a>
                            <a href="{{ url('/lien-he') }}" class="hidden xs:inline-flex btn-editorial-primary border-white/30 hover:border-taupe-oak text-3xs sm:text-xs py-1.5 px-3 sm:py-2.5 sm:px-5">
                                <span>Liên Hệ Tư Vấn</span>
                                <i class="fa-solid fa-phone ml-1 text-3xs sm:text-xs"></i>
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
    <div class="absolute bottom-3 sm:bottom-6 left-1/2 -translate-x-1/2 z-20 flex space-x-2.5">
        @foreach($sliders as $key => $slide)
            <button onclick="goToSlide({{ $key }})" class="w-3 h-3 rounded-full border border-white/50 transition-all duration-300 slide-dot {{ $key === 0 ? 'bg-taupe-oak w-8 border-taupe-oak' : 'bg-white/50' }}" data-slide-dot="{{ $key }}" aria-label="Slide {{ $key + 1 }}"></button>
        @endforeach
    </div>
</div>

<script src="{{ asset('frontend/js/slider.js') }}"></script>
@endif
