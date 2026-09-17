@php
    $productImage = $product->image;
    $productName = lang($product, 'name');
    $productSlug = lang($product, 'slug');
    $price = $product->price;
    $priceOld = $product->price_old;
@endphp
<div class="card-editorial overflow-hidden flex flex-col group h-full scroll-anim fade-up">
    <!-- Clickable Product Image (aspect-square) -->
    <div class="aspect-square w-full overflow-hidden bg-beige-warm relative p-3 sm:p-4 flex items-center justify-center zoom-container">
        <a href="{{ route('web.resolve', ['slug' => $productSlug]) }}" class="block w-full h-full flex items-center justify-center">
            <img src="{{ asset($productImage) }}" alt="{{ $productName }}" class="w-full h-full object-cover rounded-xl transition-transform duration-700 ease-out group-hover:scale-105" loading="lazy">
        </a>
        
        <!-- Hot / Bespoke Badge -->
        @if($product->hot)
            <span class="absolute top-2.5 left-2.5 badge-editorial text-3xs font-semibold z-10">
                Bespoke
            </span>
        @endif

        <!-- Category Badge -->
        @if($product->cate)
            <span class="absolute top-2.5 right-2.5 bg-white/90 backdrop-blur-sm border border-border-subtle text-charcoal-muted text-3xs font-medium px-2 py-0.5 rounded-full shadow-2xs z-10 hidden sm:inline-block">
                {{ lang($product->cate, 'name') }}
            </span>
        @endif
    </div>

    <!-- Product Info -->
    <div class="p-3.5 sm:p-5 flex-grow flex flex-col justify-between space-y-3">
        <div class="space-y-1.5">
            <!-- Quality Indicator -->
            <div class="flex items-center justify-between text-3xs sm:text-2xs text-taupe-oak">
                <span class="font-medium uppercase tracking-wider flex items-center">
                    <i class="fa-solid fa-couch text-3xs mr-1 text-taupe-oak"></i> Gỗ Tự Nhiên Cao Cấp
                </span>
                <div class="flex items-center text-amber-500 text-3xs">
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                </div>
            </div>

            <!-- Product Title -->
            <h3 class="font-heading font-medium text-xs sm:text-sm text-charcoal group-hover:text-taupe-oak transition-colors line-clamp-2 leading-snug">
                <a href="{{ route('web.resolve', ['slug' => $productSlug]) }}">
                    {{ $productName }}
                </a>
            </h3>

            <!-- Intro / Highlights (hidden on small mobile for compact card) -->
            @if(!empty(lang($product, 'intro')))
                <p class="text-3xs text-charcoal-light line-clamp-2 leading-relaxed hidden sm:block">
                    {{ strip_tags(lang($product, 'intro')) }}
                </p>
            @endif
        </div>

        <!-- Prices and Actions Row -->
        <div class="pt-2.5 border-t border-border-subtle flex items-center justify-between gap-1">
            <div class="flex flex-col">
                @if($priceOld > $price)
                    <span class="text-3xs text-charcoal-light line-through leading-none">{{ number_format($priceOld, 0, ',', '.') }}đ</span>
                @endif
                <span class="text-xs sm:text-base font-bold text-charcoal leading-none mt-1 group-hover:text-taupe-oak transition-colors">
                    {{ number_format($price, 0, ',', '.') }}đ
                </span>
            </div>

            <!-- Add to Cart Action Button -->
            <button type="button" onclick="addToCartAjax('{{ $product->uuid }}', this)" class="inline-flex items-center justify-center bg-beige-warm hover:bg-taupe-oak text-charcoal hover:text-white border border-border-subtle hover:border-taupe-oak font-medium p-2 sm:p-2.5 rounded-xl transition-all duration-300 shadow-2xs group/btn flex-shrink-0" title="Thêm vào giỏ hàng">
                <i class="fa-solid fa-bag-shopping text-xs sm:text-sm group-hover/btn:scale-110 transition-transform"></i>
            </button>
        </div>
    </div>
</div>
