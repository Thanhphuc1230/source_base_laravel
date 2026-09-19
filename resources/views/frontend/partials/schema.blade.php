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
    "@context": "https://schema.org",
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
    "@context": "https://schema.org",
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
