<!--=============== basic  ===============-->
    <meta charset="utf-8">
    <title>@yield('module')</title>
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="@yield('description')">
    <meta name="keywords" content="@yield('keywords')">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ $web->favicon }}" sizes="48x48">
    <link rel="icon" type="image/png" href="{{ $web->favicon }}" sizes="48x48">

    <!-- CSS (Font, Vendor, Icon, Plugins & Style CSS files) -->
    <link rel="preload" href="@yield('images')" as="image">
    <link rel="alternate" hreflang="x-default" href="{{ route('web.home') }}">
    <link rel="alternate" hreflang="vi" href="{{ route('web.home') }}">
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}" />

    <!-- Open Graph / Meta Facebook & Zalo & Viber -->
    <meta property="og:locale" content="vi_VN">
    <meta property="og:type" content="@yield('og_type', $website->meta_name ?? 'website')">
    <meta property="og:site_name" content="{{ $website->name_vn ?? $website->meta_name ?? 'Base' }}">
    <meta property="og:title" content="@yield('module', $website->meta_title ?? $website->name_vn ?? 'Base')">
    <meta property="og:description" content="@yield('description', $website->description_vn ?? '')">
    <meta property="og:url" content="{{ url()->current() }}">

    @php
        $seoImage = null;
        if (View::hasSection('images') && trim(View::yieldContent('images')) != '') {
            $seoImage = trim(View::yieldContent('images'));
        } elseif (!empty($website->share_image)) {
            $seoImage = $website->share_image;
        } elseif (!empty($website->logo)) {
            $seoImage = $website->logo;
        }
        if ($seoImage && !str_starts_with($seoImage, 'http')) {
            $seoImage = asset($seoImage);
        }
    @endphp
    @if($seoImage)
        <meta property="og:image" content="{{ $seoImage }}">
        <meta property="og:image:secure_url" content="{{ $seoImage }}">
        <meta property="og:image:alt" content="@yield('module', $website->name_vn ?? 'Base')">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        <meta name="twitter:image" content="{{ $seoImage }}">
    @endif

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('module', $website->meta_title ?? $website->name_vn ?? 'Base')">
    <meta name="twitter:description" content="@yield('description', $website->description_vn ?? '')">

    {{-- Schema Structured Data JSON-LD --}}
    @include('frontend.partials.schema')

    {{-- Dynamic Header Scripts (Google Tag Manager, GA4, Search Console, Pixel) --}}
    @if(!empty($website->header_js))
        {!! $website->header_js !!}
    @endif
    <!-- STYLESHEETS -->
    <link rel="stylesheet" href="{{ asset('frontend/css/image-flip.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/theme-style.css') }}">

<!-- Font (Inter & Plus Jakarta Sans) -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- FontAwesome 6 Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Tailwind CSS Play CDN (Editorial Luxury Interior & Architecture Configuration) -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    cream: '#F9F8F6',
                    'cream-surface': '#FFFFFF',
                    'beige-warm': '#F4F0EA',
                    charcoal: {
                        DEFAULT: '#2C2C2A',
                        900: '#1E1E1C',
                        800: '#2C2C2A',
                        700: '#3D3D3A',
                        600: '#555550',
                        500: '#6E6E6A',
                        400: '#9E9E98',
                        300: '#C7C5BF',
                        200: '#E8E4DE',
                        100: '#F4F0EA',
                    },
                    taupe: {
                        oak: '#8C7A6B',
                        light: '#A69282',
                        dark: '#6F5E50',
                        50: '#F9F7F5',
                        100: '#F2EDE7',
                    },
                    'border-subtle': '#E8E2D9',
                    'border-warm': '#E2DBD0',
                },
                fontFamily: {
                    sans: ['Inter', '"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                    heading: ['Inter', '"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                }
            }
        }
    }
</script>
