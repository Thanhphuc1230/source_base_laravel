<nav class="bg-white/95 backdrop-blur-md border-b border-border-subtle sticky top-0 z-40 shadow-xs transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <!-- Brand Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('web.home') }}" class="flex items-center space-x-2 group">
                    @if(!empty($web->logo))
                        <img src="{{ asset($web->logo) }}" alt="{{ $web->name_vn ?? 'Interior & Architecture' }}" class="header-brand-logo h-[52px] md:h-[60px] max-h-[56px] md:max-h-[60px] w-auto object-contain">
                    @else
                        <span class="text-lg md:text-xl font-heading font-bold tracking-wider uppercase text-charcoal group-hover:text-taupe-oak transition-colors">
                            {{ $web->name_vn ?? 'NỘI THẤT & KIẾN TRÚC BASE' }}
                        </span>
                    @endif
                </a>
            </div>

            <!-- Dynamic Multi-level Menu Loop -->
            <div class="hidden lg:block flex-grow mx-6">
                <ul class="flex space-x-1 xl:space-x-3 justify-center items-center">
                    @if(isset($menu) && $menu->isNotEmpty())
                        @foreach($menu as $item)
                            @php
                                $url = getUrlMenu($item);
                                $isActive = isActiveMenu($item);
                                $hasChildren = isset($item->children) && $item->children->isNotEmpty();
                            @endphp
                            <li class="flex-shrink-0 flex items-center relative group">
                                <a href="{{ $url }}" class="nav-link-editorial inline-flex items-center whitespace-nowrap {{ $isActive ? 'active' : '' }}">
                                    <span>{{ lang($item, 'name') }}</span>
                                    @if($hasChildren)
                                        <i class="fa-solid fa-chevron-down text-3xs ml-1.5 opacity-60 group-hover:rotate-180 transition-transform duration-200"></i>
                                    @endif
                                </a>

                                @if($hasChildren)
                                    <!-- Dropdown Menu with pt-1.5 seamless hover bridge -->
                                    <div class="dropdown-menu-editorial absolute left-0 top-full pt-1.5 w-60 z-50">
                                        <div class="bg-white rounded-2xl shadow-xl border border-border-subtle p-2 space-y-1">
                                            @foreach($item->children as $child)
                                                @php
                                                    $childUrl = getUrlMenu($child);
                                                    $isChildActive = isActiveMenu($child);
                                                @endphp
                                                <a href="{{ $childUrl }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-medium text-charcoal hover:bg-beige-warm hover:text-taupe-oak transition-colors {{ $isChildActive ? 'bg-beige-warm text-taupe-oak font-semibold' : '' }}">
                                                    <span>{{ lang($child, 'name') }}</span>
                                                    <i class="fa-solid fa-chevron-right text-3xs opacity-40"></i>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>

            <!-- Header Actions (Cart, Hotline, Language, Mobile Toggle) -->
            <div class="flex items-center space-x-3 sm:space-x-4">
                <!-- Cart Icon with Badge (No background, no border-radius) -->
                <a href="{{ route('web.cart') }}" class="relative inline-flex items-center text-charcoal hover:text-taupe-oak transition-colors duration-200 px-1 py-1" title="Giỏ hàng">
                    <i class="fa-solid fa-bag-shopping text-lg text-charcoal hover:text-taupe-oak transition-colors"></i>
                    <span id="cart-badge-count" class="absolute -top-1 -right-2 bg-taupe-oak text-white text-3xs font-extrabold rounded-full h-4 w-4 flex items-center justify-center shadow-xs transition-transform duration-300 {{ ($cart_count ?? 0) > 0 ? 'scale-100' : 'scale-0' }}">
                        {{ $cart_count ?? 0 }}
                    </span>
                </a>

                <!-- Language Switcher (Two flags side-by-side, no background, no border-radius) -->
                <div class="flex items-center space-x-2">
                    <a href="{{ route('lang', ['locale' => 'vn']) }}" class="inline-block transition-opacity duration-200 {{ session('locale') == 'vn' || !session('locale') ? 'opacity-100' : 'opacity-40 hover:opacity-100' }}" title="Tiếng Việt">
                        <svg class="w-5 h-3.5 block" viewBox="0 0 640 480">
                            <path fill="#da251d" d="M0 0h640v480H0z"/>
                            <path fill="#ff0" d="M320 133l27 82h86l-70 51 27 82-70-51-70 51 27-82-70-51h86z"/>
                        </svg>
                    </a>
                    <a href="{{ route('lang', ['locale' => 'en']) }}" class="inline-block transition-opacity duration-200 {{ session('locale') == 'en' ? 'opacity-100' : 'opacity-40 hover:opacity-100' }}" title="English">
                        <svg class="w-5 h-3.5 block" viewBox="0 0 640 480">
                            <path fill="#bd3d44" d="M0 0h640v480H0z"/>
                            <path stroke="#fff" stroke-width="37" d="M0 55h640M0 129h640M0 203h640M0 277h640M0 351h640M0 425h640"/>
                            <path fill="#192f5d" d="M0 0h256v258H0z"/>
                        </svg>
                    </a>
                </div>


                <!-- Hamburger Mobile Menu Toggle -->
                <button type="button" onclick="toggleMobileMenu()" class="lg:hidden p-2 text-charcoal hover:text-taupe-oak focus:outline-none transition-colors" aria-label="Toggle Navigation">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Drawer Menu -->
    <div id="mobile-menu" class="hidden lg:hidden bg-white border-t border-border-subtle py-4 px-6 shadow-xl max-h-[80vh] overflow-y-auto">
        <ul class="space-y-2">
            @if(isset($menu) && $menu->isNotEmpty())
                @foreach($menu as $item)
                    @php
                        $url = getUrlMenu($item);
                        $isActive = isActiveMenu($item);
                        $hasChildren = isset($item->children) && $item->children->isNotEmpty();
                    @endphp
                    <li class="border-b border-border-subtle/50 pb-2">
                        <div class="flex items-center justify-between">
                            <a href="{{ $url }}" class="block py-1.5 text-sm font-semibold uppercase tracking-wider {{ $isActive ? 'text-taupe-oak' : 'text-charcoal hover:text-taupe-oak' }}">
                                {{ lang($item, 'name') }}
                            </a>
                            @if($hasChildren)
                                <button type="button" onclick="this.nextElementSibling ? this.nextElementSibling.classList.toggle('hidden') : null; this.parentElement.nextElementSibling.classList.toggle('hidden')" class="p-1 text-charcoal-light">
                                    <i class="fa-solid fa-chevron-down text-xs"></i>
                                </button>
                            @endif
                        </div>

                        @if($hasChildren)
                            <ul class="pl-4 mt-2 space-y-1.5 border-l-2 border-border-subtle hidden">
                                @foreach($item->children as $child)
                                    @php
                                        $childUrl = getUrlMenu($child);
                                        $isChildActive = isActiveMenu($child);
                                    @endphp
                                    <li>
                                        <a href="{{ $childUrl }}" class="block py-1 text-xs font-medium {{ $isChildActive ? 'text-taupe-oak font-semibold' : 'text-charcoal-muted hover:text-taupe-oak' }}">
                                            {{ lang($child, 'name') }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            @endif
            @if(!empty($web->phone))
            <li class="pt-3">
                <a href="tel:{{ $web->phone }}" class="flex items-center space-x-2 text-taupe-oak font-bold py-2">
                    <i class="fa-solid fa-phone"></i>
                    <span class="text-xs uppercase tracking-wider">Hotline: {{ $web->phone }}</span>
                </a>
            </li>
            @endif
        </ul>
    </div>
</nav>

<script src="{{ asset('frontend/js/header.js') }}"></script>
