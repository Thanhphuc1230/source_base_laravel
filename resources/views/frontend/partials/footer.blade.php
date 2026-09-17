<footer class="bg-charcoal text-stone-300 pt-16 pb-8 border-t border-charcoal-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">
            <!-- Brand Widget -->
            <div class="space-y-4">
                <a href="{{ route('web.home') }}" class="flex items-center space-x-2 group">
                    @if(!empty($web->logo))
                        <img src="{{ asset($web->logo) }}" alt="{{ $web->name_vn ?? 'Base Interior' }}" class="h-10 w-auto object-contain">
                    @else
                        <span class="text-lg font-heading font-bold tracking-wider uppercase text-white group-hover:text-taupe-light transition-colors">
                            {{ $web->name_vn ?? 'NỘI THẤT & KIẾN TRÚC BASE' }}
                        </span>
                    @endif
                </a>
                <p class="text-xs leading-relaxed text-stone-400">
                    {{ $web->footer_vn ?? ($web->description_vn ?? 'Tổng thầu thiết kế thi công kiến trúc & nội thất may đo độc bản với nhà máy sản xuất quy chuẩn 5.000m².') }}
                </p>
                <div class="flex space-x-3 pt-2">
                    @if(!empty($web->facebook))
                    <a href="{{ $web->facebook }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-full bg-charcoal-700 border border-charcoal-600 flex items-center justify-center text-stone-300 hover:text-white hover:bg-taupe-oak hover:border-taupe-oak transition-all duration-300 shadow-2xs" aria-label="Facebook">
                        <i class="fa-brands fa-facebook-f text-xs"></i>
                    </a>
                    @endif
                    @if(!empty($web->youtube))
                    <a href="{{ $web->youtube }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-full bg-charcoal-700 border border-charcoal-600 flex items-center justify-center text-stone-300 hover:text-white hover:bg-taupe-oak hover:border-taupe-oak transition-all duration-300 shadow-2xs" aria-label="YouTube">
                        <i class="fa-brands fa-youtube text-xs"></i>
                    </a>
                    @endif
                    @if(!empty($web->instagram))
                    <a href="{{ $web->instagram }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-full bg-charcoal-700 border border-charcoal-600 flex items-center justify-center text-stone-300 hover:text-white hover:bg-taupe-oak hover:border-taupe-oak transition-all duration-300 shadow-2xs" aria-label="Instagram">
                        <i class="fa-brands fa-instagram text-xs"></i>
                    </a>
                    @endif
                    @if(!empty($web->email))
                    <a href="mailto:{{ $web->email }}" class="w-8 h-8 rounded-full bg-charcoal-700 border border-charcoal-600 flex items-center justify-center text-stone-300 hover:text-white hover:bg-taupe-oak hover:border-taupe-oak transition-all duration-300 shadow-2xs" aria-label="Email">
                        <i class="fa-solid fa-envelope text-xs"></i>
                    </a>
                    @endif
                </div>
            </div>

            <!-- Products / Collections Links -->
            <div>
                <h4 class="text-white font-heading font-semibold text-sm uppercase tracking-wider mb-4 border-l-2 border-taupe-oak pl-3">
                    Bộ Sưu Tập Nội Thất
                </h4>
                <ul class="space-y-2.5 text-xs">
                    @if(isset($category_product_footer) && $category_product_footer->isNotEmpty())
                        @foreach($category_product_footer as $cate)
                            <li>
                                <a href="{{ route('web.resolve', ['slug' => $cate->slug]) }}" class="text-stone-400 hover:text-taupe-light transition-colors duration-200 flex items-center group">
                                    <i class="fa-solid fa-angle-right text-3xs text-taupe-oak mr-2 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                    {{ lang($cate, 'name') }}
                                </a>
                            </li>
                        @endforeach
                    @else
                        <li>
                            <a href="{{ url('/thiet-ke-thi-cong') }}" class="text-stone-400 hover:text-taupe-light transition-colors duration-200">
                                Thiết Kế Thi Công Biệt Thự
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            <!-- Factory & Information Links -->
            <div>
                <h4 class="text-white font-heading font-semibold text-sm uppercase tracking-wider mb-4 border-l-2 border-taupe-oak pl-3">
                    Thông Tin & Dịch Vụ
                </h4>
                <ul class="space-y-2.5 text-xs">
                    <li>
                        <a href="{{ route('web.factory') }}" class="text-stone-400 hover:text-taupe-light transition-colors duration-200 flex items-center group">
                            <i class="fa-solid fa-angle-right text-3xs text-taupe-oak mr-2 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            Quy Mô Nhà Máy 5.000m²
                        </a>
                    </li>
                    @if(isset($footer_pages) && $footer_pages->isNotEmpty())
                        @foreach($footer_pages as $page)
                            <li>
                                <a href="{{ route('web.resolve', ['slug' => $page->slug]) }}" class="text-stone-400 hover:text-taupe-light transition-colors duration-200 flex items-center group">
                                    <i class="fa-solid fa-angle-right text-3xs text-taupe-oak mr-2 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                    {{ lang($page, 'name') }}
                                </a>
                            </li>
                        @endforeach
                    @else
                        <li>
                            <a href="{{ route('web.contact.clean') }}" class="text-stone-400 hover:text-taupe-light transition-colors duration-200 flex items-center group">
                                <i class="fa-solid fa-angle-right text-3xs text-taupe-oak mr-2 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                Liên hệ tư vấn & Báo giá
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            <!-- Consultation / Newsletter Subscription Widget -->
            <div>
                <h4 class="text-white font-heading font-semibold text-sm uppercase tracking-wider mb-4 border-l-2 border-taupe-oak pl-3">
                    Đăng Ký Tư Vấn
                </h4>
                <p class="text-xs text-stone-400 mb-3 leading-relaxed">
                    Nhận tư vấn thiết kế mặt bằng và catalogue các bộ sưu tập nội thất mới nhất.
                </p>
                <form action="{{ route('web.postSubscribe') }}" method="POST" class="space-y-2">
                    @csrf
                    <div class="relative">
                        <input type="email" name="email" required placeholder="Nhập địa chỉ email..." class="w-full bg-charcoal-700 border border-charcoal-600 text-white rounded-lg pl-4 pr-10 py-2.5 text-xs focus:outline-none focus:border-taupe-oak placeholder-stone-500 transition-colors">
                        <button type="submit" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-taupe-light hover:text-white transition-colors p-1" title="Gửi đăng ký">
                            <i class="fa-solid fa-paper-plane text-xs"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bottom Copyright & Dynamic Contact Info -->
        <div class="border-t border-charcoal-700/80 pt-6 mt-6 flex flex-col md:flex-row items-center justify-between text-xs text-stone-400">
            <p>&copy; {{ date('Y') }} {{ $web->name_vn ?? 'Base Interior' }}. All rights reserved.</p>
            <div class="flex flex-wrap gap-4 mt-3 md:mt-0 text-xs">
                @if(!empty($web->address_vn ?? $web->address))
                <p class="flex items-center"><i class="fa-solid fa-location-dot text-taupe-light mr-1.5"></i> {{ $web->address_vn ?? $web->address }}</p>
                @endif
                @if(!empty($web->phone))
                <p class="flex items-center"><i class="fa-solid fa-phone text-taupe-light mr-1.5"></i> {{ $web->phone }}</p>
                @endif
            </div>
        </div>
    </div>
</footer>
