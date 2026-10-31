@php
    $isEn = session('locale') === 'en';
@endphp
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box px-3">
        <!-- Dark Logo-->
        <a href="{{ route('admin.analytics.index') }}" class="logo logo-dark text-decoration-none">
            <span class="logo-sm">
                <span class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-2" style="width: 32px; height: 32px; font-weight: 800; font-size: 16px;">B</span>
            </span>
            <span class="logo-lg">
                <span class="d-flex align-items-center gap-2" style="height: 70px;">
                    <span class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-2 shadow-sm" style="width: 30px; height: 30px; font-size: 15px;">
                        <i class="ri-dashboard-fill"></i>
                    </span>
                    <span class="fw-bold fs-15 text-white tracking-wide" style="letter-spacing: 0.5px;">BASE <span class="badge bg-primary-subtle text-primary fs-11 px-1.5 py-0.5 rounded-1">ADMIN</span></span>
                </span>
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{ route('admin.analytics.index') }}" class="logo logo-light text-decoration-none">
            <span class="logo-sm">
                <span class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-2" style="width: 32px; height: 32px; font-weight: 800; font-size: 16px;">B</span>
            </span>
            <span class="logo-lg">
                <span class="d-flex align-items-center gap-2" style="height: 70px;">
                    <span class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-2 shadow-sm" style="width: 30px; height: 30px; font-size: 15px;">
                        <i class="ri-dashboard-fill"></i>
                    </span>
                    <span class="fw-bold fs-15 text-white tracking-wide" style="letter-spacing: 0.5px;">BASE <span class="badge bg-primary-subtle text-primary fs-11 px-1.5 py-0.5 rounded-1">ADMIN</span></span>
                </span>
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                @hasPermission('analytics.view')
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}" href="{{ route('admin.analytics.index') }}">
                        <i class="ri-bar-chart-line"></i> <span data-key="t-analytics">{{ $isEn ? 'Analytics' : 'Thống kê' }}</span>
                    </a>
                </li>
                @endhasPermission

                <!-- Dropdown Trang chủ (Quản lý các section trang chủ) -->
                @php
                    $isHomeActive = request()->routeIs('admin.slider.*') ||
                                    request()->routeIs('admin.about.*') ||
                                    request()->routeIs('admin.feature.*') ||
                                    request()->routeIs('admin.gallery.*') ||
                                    request()->routeIs('admin.brand.*');
                @endphp
                @hasAnyPermission(['slider.view', 'about.view', 'feature.view', 'gallery.view', 'brand.view'])
                <li class="nav-item">
                    <a class="nav-link menu-link {{ $isHomeActive ? 'active' : '' }}" href="#sidebarHome" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ $isHomeActive ? 'true' : 'false' }}" aria-controls="sidebarHome">
                        <i class="ri-home-4-line"></i> <span data-key="t-home">{{ $isEn ? 'Home Page' : 'Trang chủ' }}</span>
                    </a>
                    <div class="collapse menu-dropdown {{ $isHomeActive ? 'show' : '' }}" id="sidebarHome">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('web.home') }}" class="nav-link" target="_blank">
                                    <i class="ri-external-link-line me-1"></i> {{ $isEn ? 'View Website' : 'Xem trang chủ' }}
                                </a>
                            </li>
                            @hasPermission('slider.view')
                            <li class="nav-item">
                                <a href="{{ route('admin.slider.index') }}" class="nav-link {{ request()->routeIs('admin.slider.*') ? 'active' : '' }}">
                                    {{ $isEn ? 'Sliders' : 'Slider' }}
                                </a>
                            </li>
                            @endhasPermission
                            @hasPermission('about.view')
                            <li class="nav-item">
                                <a href="{{ route('admin.about.index') }}" class="nav-link {{ request()->routeIs('admin.about.*') ? 'active' : '' }}">
                                    {{ $isEn ? 'About Us' : 'Giới thiệu' }}
                                </a>
                            </li>
                            @endhasPermission
                            @hasPermission('feature.view')
                            <li class="nav-item">
                                <a href="{{ route('admin.feature.index') }}" class="nav-link {{ request()->routeIs('admin.feature.*') ? 'active' : '' }}">
                                    {{ $isEn ? 'Features' : 'Tính năng' }}
                                </a>
                            </li>
                            @endhasPermission
                            @hasPermission('gallery.view')
                            <li class="nav-item">
                                <a href="{{ route('admin.gallery.index') }}" class="nav-link {{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}">
                                    {{ $isEn ? 'Gallery' : 'Hình ảnh' }}
                                </a>
                            </li>
                            @endhasPermission
                            @hasPermission('brand.view')
                            <li class="nav-item">
                                <a href="{{ route('admin.brand.index') }}" class="nav-link {{ request()->routeIs('admin.brand.*') ? 'active' : '' }}">
                                    {{ $isEn ? 'Brands' : 'Thương hiệu' }}
                                </a>
                            </li>
                            @endhasPermission
                        </ul>
                    </div>
                </li>
                @endhasAnyPermission

                @hasPermission('page.view')
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('admin.page.*') ? 'active' : '' }}" href="{{ route('admin.page.index') }}">
                        <i class="ri-file-list-3-line"></i> <span data-key="t-pages">{{ $isEn ? 'Pages' : 'Trang nội dung' }}</span>
                    </a>
                </li>
                @endhasPermission
                @hasAnyPermission(['menu.view', 'font.view'])
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarLayouts" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarLayouts">
                        <i class="ri-layout-4-line"></i> <span data-key="t-layouts">{{ $isEn ? 'Layouts' : 'Giao diện' }}</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarLayouts">
                        <ul class="nav nav-sm flex-column">
                            @hasPermission('menu.view')
                            <li class="nav-item">
                                <a href="{{ route('admin.menu.index') }}" class="nav-link" data-key="t-calendar">{{ $isEn ? 'Menu' : 'Menu' }}
                                </a>
                            </li>
                            @endhasPermission
                            @hasPermission('font.view')
                            <li class="nav-item">
                                <a href="{{ route('admin.fonts.index') }}" class="nav-link" data-key="t-fonts">{{ $isEn ? 'Fonts' : 'Quản lý Font' }}
                                </a>
                            </li>
                            @endhasPermission
                        </ul>
                    </div>
                </li>
                @endhasAnyPermission
                @hasAnyPermission(['cate_product.view', 'product.view'])
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarProduct" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarProduct">
                            <i class="ri-shopping-bag-3-line"></i> <span data-key="t-tables">{{ $isEn ? 'Products' : 'Sản phẩm' }}</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarProduct">
                            <ul class="nav nav-sm flex-column">
                                @hasPermission('cate_product.view')
                                <li class="nav-item">
                                    <a href="{{ route('admin.cate_product.index') }}" class="nav-link"
                                        data-key="t-basic-tables">{{ $isEn ? 'Categories' : 'Danh mục' }}</a>
                                </li>
                                @endhasPermission
                                @hasPermission('product.view')
                                <li class="nav-item">
                                    <a href="{{ route('admin.product.index') }}" class="nav-link"
                                        data-key="t-grid-js">{{ $isEn ? 'Items' : 'Bài viết' }}</a>
                                </li>
                                @endhasPermission
                                @hasPermission('order.view')
                                <li class="nav-item">
                                    <a href="{{ route('admin.order.index') }}" class="nav-link" data-key="t-grid-js">{{ $isEn ? 'Orders' : 'Đơn hàng' }}</a>
                                </li>
                                @endhasPermission
                                @hasPermission('product_setting.view')
                                <li class="nav-item">
                                    <a href="{{ route('admin.product-setting.index') }}" class="nav-link"
                                        data-key="t-grid-js">{{ $isEn ? 'Settings' : 'Cài đặt' }}</a>
                                </li>
                                @endhasPermission
                            </ul>
                        </div>
                    </li>
                @endhasAnyPermission
                @hasAnyPermission(['cate_news.view', 'news.view'])
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarNews" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarNews">
                            <i class="ri-newspaper-line"></i> <span data-key="t-layouts">{{ $isEn ? 'News' : 'Tin tức' }}</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarNews">
                            <ul class="nav nav-sm flex-column">
                                @hasPermission('cate_news.view')
                                <li class="nav-item">
                                    <a href="{{ route('admin.cate_new.index') }}" class="nav-link">{{ $isEn ? 'Categories' : 'Chủ đề' }}</a>
                                </li>
                                @endhasPermission
                                @hasPermission('news.view')
                                <li class="nav-item">
                                    <a href="{{ route('admin.news.index') }}" class="nav-link">{{ $isEn ? 'Articles' : 'Bài viết' }}</a>
                                </li>
                                @endhasPermission
                                @hasPermission('system.view')
                                <li class="nav-item">
                                    <a href="{{ route('admin.news-setting.index') }}" class="nav-link">{{ $isEn ? 'Settings' : 'Cài đặt' }}</a>
                                </li>
                                @endhasPermission
                            </ul>
                        </div>
                    </li>
                @endhasAnyPermission
                @hasPermission('feedback.view')
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('admin.feedback.index') }}">
                        <i class="ri-feedback-line"></i> <span data-key="t-widgets">{{ $isEn ? 'Customer Reviews' : 'Đánh giá khách hàng' }}</span>
                    </a>
                </li>
                @endhasPermission

                @hasPermission('media.view')
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('admin.media.index') }}">
                        <i class="ri-image-2-line"></i> <span data-key="t-media">{{ $isEn ? 'Media Library' : 'Thư viện Media' }}</span>
                    </a>
                </li>
                @endhasPermission
                @hasAnyPermission(['contact.view', 'comment.view'])
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#Contact" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarNews">
                            <i class="ri-message-line"></i> <span data-key="t-layouts">{{ $isEn ? 'Contacts' : 'Liên hệ' }}</span>
                        </a>
                        <div class="collapse menu-dropdown" id="Contact">
                            <ul class="nav nav-sm flex-column">
                                @hasPermission('contact.view')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.contact.index') }}" class="nav-link">{{ $isEn ? 'Inquiries' : 'Liên hệ' }}</a>
                                    </li>
                                @endhasPermission
                                @hasPermission('comment.view')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.comment.index') }}" class="nav-link">{{ $isEn ? 'Comments' : 'Bình luận' }}</a>
                                    </li>
                                @endhasPermission
                            </ul>
                        </div>
                    </li>
                @endhasAnyPermission

                {{-- mail --}}
                @hasAnyPermission(['mail-config.view', 'mail-template.view'])
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarMail" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarMail">
                            <i class="ri-mail-line"></i> <span data-key="t-mail">{{ $isEn ? 'Mail' : 'Mail' }}</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarMail">
                            <ul class="nav nav-sm flex-column">
                                @hasPermission('mail-config.view')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.mail-config.index') }}" class="nav-link"
                                            data-key="t-mail-config">{{ $isEn ? 'Mail Settings' : 'Cấu hình mail' }}
                                        </a>
                                    </li>
                                @endhasPermission
                                @hasPermission('mail-template.view')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.mail-template.index') }}" class="nav-link"
                                            data-key="t-mail-template">{{ $isEn ? 'Mail Templates' : 'Template mail' }}
                                        </a>
                                    </li>
                                @endhasPermission
                            </ul>
                        </div>
                    </li>
                @endhasAnyPermission
                {{-- system --}}
                @hasAnyPermission(['system.view', 'user.view', 'role.view', 'activity_log.view'])
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarApps" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarApps">
                            <i class="ri-settings-3-line"></i> <span data-key="t-dashboards">{{ $isEn ? 'System' : 'Hệ thống' }}</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarApps">
                            <ul class="nav nav-sm flex-column">
                                @hasPermission('system.view')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.system.index') }}" class="nav-link"
                                             data-key="t-calendar">{{ $isEn ? 'System Settings' : 'Quản lý hệ thống' }}
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.system.editContact') }}" class="nav-link"
                                            data-key="t-calendar">{{ $isEn ? 'Contact Config' : 'Cấu hình liên hệ' }}
                                        </a>
                                    </li>
                                @endhasPermission
                                @hasPermission('activity_log.view')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.activity_log.index') }}" class="nav-link"
                                            data-key="t-activity-log">
                                            {{ $isEn ? 'Activity Logs' : 'Nhật ký hoạt động' }}
                                        </a>
                                    </li>
                                @endhasPermission
                                @hasPermission('user.view')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.user.index') }}" class="nav-link"
                                            data-key="t-user-management">{{ $isEn ? 'Users' : 'Quản lý người dùng' }}
                                        </a>
                                    </li>
                                @endhasPermission
                                @hasPermission('role.view')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.user-role.roles.index') }}" class="nav-link"
                                            data-key="t-roles">{{ $isEn ? 'Roles' : 'Quản lý vai trò' }}
                                        </a>
                                    </li>
                                @endhasPermission
                                @hasPermission('user.view')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.user-role.index') }}" class="nav-link"
                                            data-key="t-user-role">{{ $isEn ? 'Permissions' : 'Phân quyền người dùng' }}
                                        </a>
                                    </li>
                                @endhasPermission
                                @hasPermission('system.edit')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.system.clearCache') }}" class="nav-link text-danger font-weight-bold"
                                            data-key="t-clear-cache">
                                            <i class="ri-delete-bin-line mr-1"></i> {{ $isEn ? 'Clear Cache' : 'Xóa bộ nhớ đệm' }}
                                        </a>
                                    </li>
                                @endhasPermission
                            </ul>
                        </div>
                    </li>
                @endhasAnyPermission
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
