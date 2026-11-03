@php
    $orgSocials = array_values(array_filter([
        $website->facebook ?? null,
        $website->youtube ?? null,
        $website->twitter ?? null,
        $website->instagram ?? null,
    ]));

    $orgLogo = $website->logo ?? '';
    if (!empty($orgLogo) && !str_starts_with($orgLogo, 'http')) {
        $orgLogo = asset($orgLogo);
    }

    $orgImage = $website->share_image ?? $website->logo ?? '';
    if (!empty($orgImage) && !str_starts_with($orgImage, 'http')) {
        $orgImage = asset($orgImage);
    }
@endphp

{{-- Schema Organization / LocalBusiness --}}
<script type="application/ld+json">
{
    "{{ '@context' }}": "https://schema.org",
    "@type": "LocalBusiness",
    "name": "{{ addslashes($website->name_vn ?? $website->meta_name ?? config('app.name')) }}",
    "url": "{{ url('/') }}",
    @if(!empty($orgLogo))
    "logo": "{{ $orgLogo }}",
    @endif
    @if(!empty($orgImage))
    "image": "{{ $orgImage }}",
    @endif
    @if(!empty($website->phone))
    "telephone": "{{ $website->phone }}",
    @endif
    @if(!empty($website->email))
    "email": "{{ $website->email }}",
    @endif
    @if(!empty($website->address_vn))
    "address": {
        "@type": "PostalAddress",
        "streetAddress": "{{ addslashes($website->address_vn) }}",
        "addressCountry": "VN"
    },
    @endif
    @if(!empty($website->description_vn))
    "description": "{{ addslashes(strip_tags($website->description_vn)) }}",
    @endif
    @if(!empty($orgSocials))
    "sameAs": {!! json_encode($orgSocials, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!},
    @endif
    "priceRange": "$$"
}
</script>

{{-- Schema BreadcrumbList (Tự động lặp mảng $breadcrumbs nếu tồn tại) --}}
@if(isset($breadcrumbs) && is_iterable($breadcrumbs) && count($breadcrumbs) > 0)
<script type="application/ld+json">
{
    "{{ '@context' }}": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        @foreach($breadcrumbs as $index => $item)
        {
            "@type": "ListItem",
            "position": {{ $index + 1 }},
            "name": "{{ addslashes($item['name'] ?? $item['title'] ?? '') }}",
            "item": "{{ $item['url'] ?? $item['link'] ?? url()->current() }}"
        }{{ !$loop->last ? ',' : '' }}
        @endforeach
    ]
}
</script>
@endif

{{-- Schema Product (Tự động render khi ở trang chi tiết sản phẩm) --}}
@if(isset($product_detail) && $product_detail)
@php
    $prodImage = !empty($product_detail->image_vn) ? asset($product_detail->image_vn) : $orgImage;
    $prodPrice = $product_detail->price ?: 0;
    $currencyCode = (function_exists('currency_unit') && in_array(currency_unit(), ['$', 'USD'])) ? 'USD' : 'VND';
@endphp
<script type="application/ld+json">
{
    "{{ '@context' }}": "https://schema.org",
    "@type": "Product",
    "name": "{{ addslashes(lang($product_detail, 'name')) }}",
    "image": [
        "{{ $prodImage }}"
    ],
    "description": "{{ addslashes(strip_tags(lang($product_detail, 'intro') ?: lang($product_detail, 'content'))) }}",
    "sku": "{{ $product_detail->uuid ?? $product_detail->id_product }}",
    "brand": {
        "@type": "Brand",
        "name": "{{ addslashes($website->name_vn ?? 'Base') }}"
    },
    "offers": {
        "@type": "Offer",
        "url": "{{ url()->current() }}",
        "priceCurrency": "{{ $currencyCode }}",
        "price": "{{ $prodPrice }}",
        "availability": "https://schema.org/InStock",
        "itemCondition": "https://schema.org/NewCondition"
    }
}
</script>
@endif

{{-- Schema NewsArticle (Tự động render khi ở trang chi tiết tin tức) --}}
@if(isset($news_detail) && $news_detail)
@php
    $newsImage = !empty($news_detail->image_vn) ? asset($news_detail->image_vn) : $orgImage;
    $publishedAt = $news_detail->created_at ? $news_detail->created_at->toIso8601String() : now()->toIso8601String();
    $modifiedAt = $news_detail->updated_at ? $news_detail->updated_at->toIso8601String() : $publishedAt;
@endphp
<script type="application/ld+json">
{
    "{{ '@context' }}": "https://schema.org",
    "@type": "NewsArticle",
    "headline": "{{ addslashes(lang($news_detail, 'name')) }}",
    "image": [
        "{{ $newsImage }}"
    ],
    "datePublished": "{{ $publishedAt }}",
    "dateModified": "{{ $modifiedAt }}",
    "description": "{{ addslashes(strip_tags(lang($news_detail, 'intro') ?: lang($news_detail, 'content'))) }}",
    "author": {
        "@type": "Organization",
        "name": "{{ addslashes($website->name_vn ?? 'Base') }}"
    },
    "publisher": {
        "@type": "Organization",
        "name": "{{ addslashes($website->name_vn ?? 'Base') }}",
        "logo": {
            "@type": "ImageObject",
            "url": "{{ $orgLogo }}"
        }
    }
}
</script>
@endif
