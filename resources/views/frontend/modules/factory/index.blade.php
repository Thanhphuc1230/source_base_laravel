@extends('frontend.master')
@section('module', 'Quy Mô & Năng Lực Nhà Máy Sản Xuất 5.000m² - ' . ($web->name_vn ?? 'Base'))
@section('keywords', 'nha may go noi that, xuong san xuat go, may cnc go, go oc cho bac my')
@section('description', 'Khám phá tổ hợp nhà máy sản xuất nội thất may đo 5.000m² trang bị dây chuyền máy CNC châu Âu hiện đại.')
@section('images', $about_factory->image ?? ($web->logo ?? ''))

@section('content')
    <!-- Hero Banner Header -->
    <section class="relative py-20 lg:py-28 bg-[#2C2C2A] text-white overflow-hidden">
        <div class="absolute inset-0 opacity-25">
            @if(!empty($about_factory->image))
                <img src="{{ asset($about_factory->image) }}" alt="Nhà máy sản xuất" class="w-full h-full object-cover">
            @endif
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-charcoal via-charcoal/80 to-transparent"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <span class="badge-editorial-dark">
                <i class="fa-solid fa-industry mr-1.5 text-taupe-light"></i> Năng Lực Sản Xuất
            </span>
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-heading font-semibold tracking-tight text-white max-w-3xl mx-auto">
                {{ lang($about_factory, 'name') ?: 'Quy Mô & Năng Lực Sản Xuất Nhà Máy 5.000m²' }}
            </h1>
            <p class="text-xs sm:text-sm text-stone-300 max-w-2xl mx-auto leading-relaxed">
                Tổ hợp nhà máy sản xuất quy chuẩn tại Thạch Thất với dây chuyền công nghệ tự động hóa, làm chủ 100% chất lượng từ nguyên liệu đến hoàn thiện.
            </p>
        </div>
    </section>

    <!-- Factory Overview & Stats Grid -->
    <section class="py-16 sm:py-24 bg-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center mb-20">
                <!-- Visual Showcase -->
                <div class="lg:col-span-6">
                    <div class="aspect-4-3 rounded-3xl overflow-hidden shadow-editorial border border-border-subtle relative zoom-container">
                        @if(!empty($about_factory->image))
                            <img src="{{ asset($about_factory->image) }}" alt="Nhà máy 5.000m2" class="w-full h-full object-cover">
                        @endif
                        <div class="absolute bottom-4 left-4 right-4 bg-white/95 backdrop-blur-md rounded-2xl p-4 border border-border-subtle shadow-sm flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-beige-warm text-taupe-oak flex items-center justify-center text-lg">
                                    <i class="fa-solid fa-industry"></i>
                                </div>
                                <div>
                                    <span class="block text-3xs font-semibold text-charcoal-light uppercase">Địa Điểm Nhà Máy</span>
                                    <span class="block text-xs font-heading font-bold text-charcoal">Cụm CN Thạch Thất, Hà Nội</span>
                                </div>
                            </div>
                            <span class="badge-editorial text-3xs font-bold">5.000 m²</span>
                        </div>
                    </div>
                </div>

                <!-- Descriptive Content -->
                <div class="lg:col-span-6 space-y-6">
                    <div class="space-y-2">
                        <span class="badge-editorial-oak">
                            Tiêu Chuẩn Sản Xuất Quy Chuẩn
                        </span>
                        <h2 class="text-2xl sm:text-3xl font-heading font-semibold text-charcoal">
                            Làm Chủ Công Nghệ & Tinh Hoa Chế Tác Mộc
                        </h2>
                    </div>

                    <div class="text-xs sm:text-sm text-charcoal-muted leading-relaxed space-y-4">
                        @if(!empty(lang($about_factory, 'content')))
                            {!! lang($about_factory, 'content') !!}
                        @else
                            <p>Nhà máy sản xuất 5.000m² của chúng tôi được quy hoạch khoa học thành các phân xưởng chuyên biệt: Xưởng xẻ sấy gỗ nguyên liệu, Xưởng gia công cắt CNC tự động, Xưởng hoàn thiện lắp ráp mộc tinh, và Tổ hợp phòng sơn áp lực âm vô trùng đạt chuẩn châu Âu.</p>
                            <p>Toàn bộ nguyên liệu gỗ tự nhiên gồm Gỗ Óc chó Bắc Mỹ (American Walnut FAS), Gỗ Sồi trắng và Gõ đỏ đều được nhập khẩu chính ngạch, kiểm tra độ ẩm nghiêm ngặt (dưới 12%) nhằm thích ứng hoàn hảo với khí hậu nhiệt đới gió mùa tại Việt Nam.</p>
                        @endif
                    </div>

                    <!-- 4 Core Indicators -->
                    @if(!empty($about_factory->stats) && is_array($about_factory->stats))
                        <div class="grid grid-cols-2 gap-4 pt-2">
                            @foreach($about_factory->stats as $stat)
                                <div class="bg-white p-4 rounded-2xl border border-border-subtle shadow-2xs">
                                    <div class="w-9 h-9 rounded-xl bg-beige-warm text-taupe-oak flex items-center justify-center text-sm mb-2">
                                        <i class="{{ $stat['icon'] ?? 'fa-solid fa-microchip' }}"></i>
                                    </div>
                                    <div class="text-lg font-heading font-bold text-charcoal">
                                        {{ $stat['value'] ?? '' }}
                                    </div>
                                    <div class="text-3xs text-charcoal-muted font-medium mt-0.5">
                                        {{ app()->getLocale() === 'en' ? ($stat['name_en'] ?? $stat['name_vn'] ?? '') : ($stat['name_vn'] ?? '') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Machinery Specifications & Material Standards -->
            <div class="pt-8 border-t border-border-subtle">
                <div class="text-center max-w-2xl mx-auto mb-12 space-y-2">
                    <span class="badge-editorial">Trang Thiết Bị & Tiêu Chuẩn</span>
                    <h2 class="text-2xl sm:text-3xl font-heading font-semibold text-charcoal">
                        Hệ Thống Máy Móc Đồng Bộ & Chứng Chỉ Vật Liệu
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Tech Spec 1 -->
                    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-border-subtle shadow-2xs space-y-4 hover:border-taupe-oak transition-all duration-300">
                        <div class="w-12 h-12 rounded-2xl bg-beige-warm text-taupe-oak flex items-center justify-center text-xl">
                            <i class="fa-solid fa-microchip"></i>
                        </div>
                        <h3 class="text-base font-heading font-bold text-charcoal">Trung Tâm Gia Công CNC 5 Trục</h3>
                        <p class="text-xs text-charcoal-muted leading-relaxed">
                            Chuyển giao công nghệ từ CHLB Đức, gia công uốn lượn 3D với sai số dưới 0.1mm, đảm bảo độ ăn khớp hoàn mỹ giữa các mối ghép mộng âm dương.
                        </p>
                    </div>

                    <!-- Tech Spec 2 -->
                    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-border-subtle shadow-2xs space-y-4 hover:border-taupe-oak transition-all duration-300">
                        <div class="w-12 h-12 rounded-2xl bg-beige-warm text-taupe-oak flex items-center justify-center text-xl">
                            <i class="fa-solid fa-spray-can-sparkles"></i>
                        </div>
                        <h3 class="text-base font-heading font-bold text-charcoal">Phòng Sơn Áp Lực Âm Vô Trùng</h3>
                        <p class="text-xs text-charcoal-muted leading-relaxed">
                            Quy trình sơn 6 lớp sử dụng sơn gốc nước cao cấp an toàn cho sức khỏe, bề mặt láng mịn như nhung và không đọng bọt khí.
                        </p>
                    </div>

                    <!-- Tech Spec 3 -->
                    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-border-subtle shadow-2xs space-y-4 hover:border-taupe-oak transition-all duration-300">
                        <div class="w-12 h-12 rounded-2xl bg-beige-warm text-taupe-oak flex items-center justify-center text-xl">
                            <i class="fa-solid fa-tree"></i>
                        </div>
                        <h3 class="text-base font-heading font-bold text-charcoal">100% Gỗ Nhập Khẩu Chứng Nhận FSC</h3>
                        <p class="text-xs text-charcoal-muted leading-relaxed">
                            Gỗ Óc chó Bắc Mỹ tuyển chọn loại FAS (First and Seconds), vân nu xoáy tự nhiên và được cấp chứng chỉ quản lý rừng bền vững quốc tế.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Focused Registration Action -->
            <div class="mt-20 bg-charcoal text-white rounded-3xl p-8 sm:p-12 border border-charcoal-700 shadow-elevated text-center max-w-4xl mx-auto space-y-6">
                <span class="badge-editorial-dark">
                    Trải Nghiệm Trực Tiếp
                </span>
                <h3 class="text-2xl sm:text-3xl font-heading font-semibold text-white">
                    Đăng Ký Tham Quan Trực Tiếp Nhà Máy 5.000m²
                </h3>
                <p class="text-xs sm:text-sm text-stone-300 max-w-xl mx-auto leading-relaxed">
                    Chúng tôi sẵn sàng đón tiếp quý khách hàng ghé thăm xưởng để tận mắt kiểm chứng quy trình sản xuất và chất lượng hoàn thiện thực tế trước khi ký kết hợp đồng.
                </p>
                <div class="pt-2">
                    <a href="{{ route('web.contact.clean') }}" class="btn-editorial-light">
                        <i class="fa-solid fa-calendar-check mr-1.5 text-xs"></i>
                        <span>Đặt Lịch Tham Quan Xưởng</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
