@extends('frontend.master')
@section('module', $web->meta_name ?? $web->name_vn)
@section('keywords', $web->meta_keyword)
@section('description', $web->meta_description)
@section('images', $web->favicon)

@section('content')
    {{-- Semantic Page Heading for Technical SEO (Hidden visually via .sr-only) --}}
    <h1 class="sr-only">{{ $web->name_company ?? $web->name_vn }} - {{ $web->name_vn }}</h1>

    {{-- Dynamic Home Sections ordered by Admin Drag & Drop Builder --}}
    @foreach(($home_sections ?? []) as $section)
        @switch($section->type)
            @case('hero')
                @include('frontend.partials.slider')
                @break

            @case('features')
                @if(isset($features) && $features->isNotEmpty())
                    <section class="py-14 md:py-20 bg-white border-t border-border-subtle scroll-anim fade-up">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 border-t border-l border-border-subtle">
                                @foreach($features->take($section->limit) as $feat)
                                    <div class="p-6 border-r border-b border-border-subtle bg-white space-y-2.5 group">
                                        <div class="text-taupe-oak text-lg">
                                            <i class="{{ $feat->image ?? 'fa-solid fa-gem' }}"></i>
                                        </div>
                                        <h3 class="font-heading font-semibold text-sm text-charcoal group-hover:text-taupe-oak transition-colors">
                                            {{ lang($feat, 'title') }}
                                        </h3>
                                        <p class="text-xs text-charcoal-muted leading-relaxed">
                                            {{ lang($feat, 'content') }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>
                @endif
                @break

            @case('icon_categories')
                @if(isset($category_product) && $category_product->isNotEmpty())
                    <section class="py-12 bg-white border-b border-border-subtle scroll-anim fade-up">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            @if($section->localized_title)
                                <h2 class="text-xl sm:text-2xl font-heading font-semibold text-charcoal mb-6">{{ $section->localized_title }}</h2>
                            @endif
                            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3">
                                @foreach($category_product->take($section->limit) as $cat)
                                    <a href="{{ route('web.resolve', ['slug' => lang($cat, 'slug')]) }}" class="border border-border-subtle p-3 text-center hover:border-charcoal transition-colors">
                                        @if($cat->image_vn)
                                            <img src="{{ asset($cat->image_vn) }}" alt="{{ lang($cat, 'name') }}" class="w-10 h-10 object-cover mx-auto mb-2" loading="lazy">
                                        @endif
                                        <span class="block text-xs font-medium text-charcoal line-clamp-2">{{ lang($cat, 'name') }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </section>
                @endif
                @break

            @case('flash_sale')
                <section class="py-14 md:py-20 bg-white border-t border-border-subtle scroll-anim fade-up" id="bespoke-products">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex flex-col md:flex-row items-center justify-between mb-10">
                            <h2 class="text-2xl sm:text-3xl font-heading font-semibold text-charcoal tracking-tight">
                                {{ $section->localized_title }}
                            </h2>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-6">
                            @if(isset($hot_products) && $hot_products->isNotEmpty())
                                @foreach($hot_products->take($section->limit) as $product)
                                    <div class="w-full">
                                        @include('frontend.components.product-card', ['product' => $product])
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </section>
                @break

            @case('category_products')
                @php
                    $sectionProducts = $section->relationLoaded('resolved_items')
                        ? $section->getRelation('resolved_items')
                        : ($hot_products ?? collect())->take($section->limit);
                @endphp
                @if($sectionProducts->isNotEmpty())
                    <section class="py-14 md:py-20 bg-white border-t border-border-subtle scroll-anim fade-up">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            <div class="flex items-center justify-between mb-8">
                                <h2 class="text-2xl sm:text-3xl font-heading font-semibold text-charcoal tracking-tight">
                                    {{ $section->localized_title }}
                                </h2>
                                @if($section->categoryProduct)
                                    <a href="{{ route('web.resolve', ['slug' => lang($section->categoryProduct, 'slug')]) }}" class="text-xs font-semibold uppercase tracking-wider text-charcoal hover:text-taupe-oak">
                                        Xem tất cả <i class="fa-solid fa-arrow-right ml-1"></i>
                                    </a>
                                @endif
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-6">
                                @foreach($sectionProducts->take($section->limit) as $product)
                                    <div class="w-full">
                                        @include('frontend.components.product-card', ['product' => $product])
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>
                @endif
                @break

            @case('mid_banner')
                @if(isset($sliders) && $sliders->count() > 1)
                    <section class="py-10 bg-white border-t border-border-subtle">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($sliders->take($section->limit) as $banner)
                                <a href="{{ $banner->link ?: '#' }}" class="block border border-border-subtle overflow-hidden">
                                    <img src="{{ asset($banner->image_vn) }}" alt="{{ lang($banner, 'name') }}" class="w-full h-48 object-cover" loading="lazy">
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif
                @break

            @case('brands')
                @if(isset($brands) && $brands->isNotEmpty())
                    <section class="py-10 bg-white border-t border-border-subtle">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            @if($section->localized_title)
                                <h2 class="text-xl font-heading font-semibold text-charcoal mb-6">{{ $section->localized_title }}</h2>
                            @endif
                            <div class="grid grid-cols-3 sm:grid-cols-6 gap-4">
                                @foreach($brands->take($section->limit) as $brand)
                                    <div class="border border-border-subtle p-3 flex items-center justify-center bg-white">
                                        <img src="{{ asset($brand->image) }}" alt="{{ lang($brand, 'name') }}" class="max-h-10 object-contain" loading="lazy">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>
                @endif
                @break

            @case('news')
                @php
                    $sectionNews = $section->relationLoaded('resolved_items')
                        ? $section->getRelation('resolved_items')
                        : ($latest_news ?? collect())->take($section->limit);
                @endphp
                <section class="py-14 md:py-20 bg-white border-t border-border-subtle scroll-anim fade-up" id="architecture-journal">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex flex-col md:flex-row items-center justify-between mb-10">
                            <h2 class="text-2xl sm:text-3xl font-heading font-semibold text-charcoal tracking-tight">
                                {{ $section->localized_title }}
                            </h2>
                            @if($section->categoryNews)
                                <a href="{{ route('web.resolve', ['slug' => lang($section->categoryNews, 'slug')]) }}" class="text-xs font-semibold uppercase tracking-wider text-charcoal hover:text-taupe-oak">
                                    Đọc thêm bài viết <i class="fa-solid fa-arrow-right ml-1"></i>
                                </a>
                            @endif
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 sm:gap-6">
                            @foreach($sectionNews->take($section->limit) as $post)
                                <article class="card-editorial overflow-hidden flex flex-col justify-between group h-full rounded-none border border-border-subtle bg-white">
                                    <div class="aspect-16-11 overflow-hidden bg-neutral-50 zoom-container relative">
                                        <a href="{{ route('web.resolve', ['slug' => lang($post, 'slug')]) }}" class="block w-full h-full">
                                            <img src="{{ asset($post->image_vn) }}" alt="{{ lang($post, 'name') }}" class="w-full h-full object-cover" loading="lazy">
                                        </a>
                                    </div>
                                    <div class="p-3.5 sm:p-5 flex-grow flex flex-col justify-between space-y-3">
                                        <h3 class="font-heading font-semibold text-xs sm:text-sm text-charcoal leading-snug group-hover:text-taupe-oak transition-colors line-clamp-2">
                                            <a href="{{ route('web.resolve', ['slug' => lang($post, 'slug')]) }}">{{ lang($post, 'name') }}</a>
                                        </h3>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                </section>
                @break
        @endswitch
    @endforeach

    <!-- About Section (Dynamic from DB) -->
    @if(isset($about_section) && $about_section)
        <section class="py-14 md:py-20 bg-white border-b border-border-subtle scroll-anim fade-up" id="factory-about">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-center">
                    <!-- Left Column: Sharp 4:3 Image -->
                    <div class="lg:col-span-6">
                        <div class="aspect-4-3 w-full rounded-none overflow-hidden border border-border-subtle relative zoom-container">
                            <img src="{{ asset($about_section->image) }}" alt="{{ lang($about_section, 'name') }}" class="w-full h-full object-cover">
                        </div>
                    </div>

                    <!-- Right Column: Heading, HTML content, hairline stats grid -->
                    <div class="lg:col-span-6 space-y-6">
                        <div class="space-y-2">
                            @if(!empty(lang($about_section, 'intro')))
                                <span class="block text-xs uppercase tracking-widest text-taupe-oak font-semibold">
                                    {{ lang($about_section, 'intro') }}
                                </span>
                            @endif
                            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-heading font-semibold text-charcoal leading-tight">
                                {{ lang($about_section, 'name') }}
                            </h2>
                        </div>

                        <div class="text-xs sm:text-sm text-charcoal-muted leading-relaxed space-y-3">
                            {!! lang($about_section, 'content') !!}
                        </div>

                        <!-- 2x2 Hairline Stats Grid from JSON -->
                        @if(!empty($about_section->stats) && is_array($about_section->stats))
                            <div class="grid grid-cols-2 border-t border-l border-border-subtle pt-0">
                                @foreach($about_section->stats as $stat)
                                    <div class="p-4 border-r border-b border-border-subtle bg-white">
                                        <div class="flex items-center gap-2 text-taupe-oak text-sm mb-1.5">
                                            <i class="{{ $stat['icon'] ?? 'fa-solid fa-check' }}"></i>
                                            <span class="text-lg sm:text-xl font-heading font-bold text-charcoal tabular-nums">
                                                {{ $stat['value'] ?? '' }}
                                            </span>
                                        </div>
                                        <div class="text-xs text-charcoal-muted font-medium">
                                            {{ app()->getLocale() === 'en' ? ($stat['name_en'] ?? $stat['name_vn'] ?? '') : ($stat['name_vn'] ?? '') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Action Buttons Row -->
                        <div class="pt-4 flex flex-wrap items-center gap-3.5">
                            <a href="{{ route('web.factory') }}" class="btn-editorial-primary">
                                <span>Tìm Hiểu Chi Tiết Xưởng</span>
                                <i class="fa-solid fa-arrow-right text-xs"></i>
                            </a>
                            <a href="{{ route('web.contact.clean') }}" class="btn-editorial-outline">
                                <span>Đăng Ký Tham Quan</span>
                                <i class="fa-solid fa-calendar-check text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- 3. Section Dự Án Chọn Lọc (Grid 3 cột md:grid-cols-2 lg:grid-cols-3 gap-8, tỉ lệ ảnh 16:11, tag Modern Organic) -->
    <section class="py-[15px] bg-cream scroll-anim fade-up" id="featured-projects">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between mb-12">
                <div class="text-center md:text-left space-y-1.5">
                    <span class="badge-editorial">
                        Tuyển Tập Dự Án Tiêu Biểu
                    </span>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-heading font-semibold text-charcoal tracking-tight">
                        Kiệt Tác Kiến Trúc & Nội Thất
                    </h2>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="{{ url('/thiet-ke-thi-cong') }}" class="inline-flex items-center space-x-2 text-xs font-semibold uppercase tracking-wider text-charcoal hover:text-taupe-oak transition-colors group">
                        <span>Xem tất cả công trình</span>
                        <i class="fa-solid fa-arrow-right text-taupe-oak group-hover:translate-x-1.5 transition-transform"></i>
                    </a>
                </div>
            </div>

            <!-- Projects Grid: 3 columns desktop, 16:11 aspect ratio -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @if(isset($featured_projects) && $featured_projects->isNotEmpty())
                    @foreach($featured_projects as $proj)
                        @php
                            $projName = lang($proj, 'name');
                            $projSlug = lang($proj, 'slug');
                            $projImage = $proj->image;
                        @endphp
                        <article class="card-editorial overflow-hidden flex flex-col group h-full">
                            <!-- Image Container (aspect-16-11) -->
                            <div class="aspect-16-11 w-full overflow-hidden bg-beige-warm relative zoom-container">
                                <a href="{{ route('web.resolve', ['slug' => $projSlug]) }}" class="block w-full h-full">
                                    <img src="{{ asset($projImage) }}" alt="{{ $projName }}" class="w-full h-full object-cover" loading="lazy">
                                </a>
                                
                                <!-- Category Badge -->
                                @if($proj->cate)
                                    <span class="absolute top-3 left-3 bg-white border border-border-subtle text-charcoal text-3xs font-semibold px-2.5 py-0.5 rounded-[2px]">
                                        {{ lang($proj->cate, 'name') }}
                                    </span>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="p-5 sm:p-6 flex-grow flex flex-col justify-between space-y-3">
                                <div class="space-y-2">
                                    <h3 class="font-heading font-semibold text-base sm:text-lg text-charcoal group-hover:text-taupe-oak transition-colors line-clamp-2 leading-snug">
                                        <a href="{{ route('web.resolve', ['slug' => $projSlug]) }}">
                                            {{ $projName }}
                                        </a>
                                    </h3>
                                    <p class="text-xs text-charcoal-muted line-clamp-2 leading-relaxed">
                                        {{ strip_tags(lang($proj, 'intro')) }}
                                    </p>
                                </div>

                                <div class="pt-3 border-t border-border-subtle flex items-center justify-between">
                                    <a href="{{ route('web.resolve', ['slug' => $projSlug]) }}" class="inline-flex items-center space-x-1.5 text-xs font-semibold text-charcoal group-hover:text-taupe-oak transition-colors">
                                        <span>Chi tiết không gian</span>
                                        <i class="fa-solid fa-arrow-right-long text-3xs text-taupe-oak"></i>
                                    </a>
                                    <span class="text-3xs text-charcoal-light uppercase tracking-wider font-medium">Bespoke Design</span>
                                </div>
                            </div>
                        </article>
                    @endforeach
                @else
                    <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-12 text-charcoal-light">
                        Đang cập nhật danh mục công trình kiến trúc.
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
