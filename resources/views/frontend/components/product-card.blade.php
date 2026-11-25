@php
    $productImage = $product->image;
    $productName = lang($product, 'name');
    $productSlug = lang($product, 'slug');
    $price = $product->price;
    $priceOld = $product->price_old;
@endphp
<div class="card-editorial overflow-hidden flex flex-col group h-full scroll-anim fade-up rounded-none border border-border-subtle bg-white">
    <!-- Clickable Product Image (aspect-square, sharp corners) -->
    <div class="aspect-square w-full overflow-hidden bg-neutral-50 relative flex items-center justify-center zoom-container">
        <a href="{{ route('web.resolve', ['slug' => $productSlug]) }}" class="block w-full h-full flex items-center justify-center">
            <img src="{{ asset($productImage) }}" alt="{{ $productName }}" width="400" height="400" class="w-full h-full object-cover rounded-none transition-transform duration-700 ease-out group-hover:scale-105" loading="lazy" decoding="async">
        </a>

        <!-- Hot Badge -->
        @if($product->hot)
            <span class="absolute top-2.5 left-2.5 bg-charcoal text-white text-3xs font-semibold px-2 py-0.5 rounded-[2px] z-10">
                Hot
            </span>
        @endif
    </div>

    <!-- Product Info -->
    <div class="p-3.5 sm:p-4 flex-grow flex flex-col justify-between space-y-3">
        <div class="space-y-1.5">
            @if($product->cate)
                <div class="text-3xs text-charcoal-light uppercase tracking-wider font-medium">
                    {{ lang($product->cate, 'name') }}
                </div>
            @endif

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
                    <span class="text-3xs text-charcoal-light line-through leading-none">{{ number_format($priceOld, 0, ',', '.') }}{{ currency_unit() }}</span>
                @endif
                <span class="text-xs sm:text-base font-bold text-charcoal leading-none mt-1 tabular-nums group-hover:text-taupe-oak transition-colors">
                    {{ number_format($price, 0, ',', '.') }}{{ currency_unit() }}
                </span>
            </div>

            <!-- Add to Cart Action Button -->
            <button type="button" onclick="addToCartAjax('{{ $product->uuid }}', this)" class="inline-flex items-center justify-center min-w-[40px] min-h-[40px] bg-white hover:bg-charcoal text-charcoal hover:text-white border border-border-subtle hover:border-charcoal font-medium p-2 rounded-[4px] transition-colors duration-200 group/btn flex-shrink-0" title="Thêm vào giỏ hàng" aria-label="Thêm {{ $productName }} vào giỏ hàng">
                <i class="fa-solid fa-bag-shopping text-xs sm:text-sm"></i>
            </button>
        </div>
    </div>
</div>
