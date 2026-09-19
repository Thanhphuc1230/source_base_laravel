@extends('admin.master')
@section('module', 'Cấu hình')
@section('action', 'Thông tin hệ thống')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Cấu hình hệ thống</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a>Hệ thống</a></li>
                                <li class="breadcrumb-item active">Thông tin website</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.system.update', ['id' => $system->id_system]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <!-- Cấu hình thông tin Website -->
                    <div class="col-xxl-12">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-light border-0 py-3">
                                <h5 class="card-title mb-0 font-weight-bold">Thông tin website</h5>
                            </div>
                            <div class="card-body">
                                <!-- Cấu hình ngôn ngữ hoạt động -->
                                <div class="mb-4 pb-3 border-b border-gray-150">
                                    <label class="form-label font-weight-bold d-block">Ngôn ngữ hoạt động</label>
                                    <div class="d-flex align-items-center">
                                        <div class="form-check form-switch form-switch-success me-4">
                                            <input class="form-check-input" type="checkbox" role="switch" id="lang-vi" name="active_languages[]" value="vi" {{ in_array('vi', $system->active_languages ?? ['vi', 'en']) ? 'checked' : '' }}>
                                            <label class="form-check-label font-weight-semibold" for="lang-vi">Tiếng Việt (VI)</label>
                                        </div>
                                        <div class="form-check form-switch form-switch-success">
                                            <input class="form-check-input" type="checkbox" role="switch" id="lang-en" name="active_languages[]" value="en" {{ in_array('en', $system->active_languages ?? ['vi', 'en']) ? 'checked' : '' }}>
                                            <label class="form-check-label font-weight-semibold" for="lang-en">Tiếng Anh (EN)</label>
                                        </div>
                                    </div>
                                    @error('active_languages')
                                        <span class="text-danger d-block mt-1">{{ $message }}</span>
                                    @enderror
                                    <small class="text-muted d-block mt-1">Khi tắt một ngôn ngữ, các ô nhập liệu của ngôn ngữ đó trên toàn trang quản trị sẽ tự động ẩn đi.</small>
                                </div>

                                @include('admin.partials.localized-fields', [
                                    'model' => $system,
                                    'fields' => [
                                        ['base' => 'name', 'label' => 'Tên công ty', 'type' => 'text', 'col' => 'col-md-6'],
                                        ['base' => 'address', 'label' => 'Địa chỉ', 'type' => 'text', 'col' => 'col-md-6'],
                                    ]
                                ])

                                <div class="row">
                                    @foreach(['phone' => 'Số điện thoại', 'email' => 'Email', 'email_alert' => 'Email nhận thông báo'] as $key => $label)
                                        <div class="col-md-4 mb-3">
                                            <label for="{{ $key }}" class="form-label">{{ $label }}</label>
                                            <input type="text" id="{{ $key }}" name="{{ $key }}" class="form-control"
                                                placeholder="{{ $label }}" value="{{ old($key, $system->$key) }}">
                                            @error($key)<span class="text-danger">{{ $message }}</span>@enderror
                                        </div>
                                    @endforeach
                                </div>

                                @include('admin.partials.localized-fields', [
                                    'model' => $system,
                                    'fields' => [
                                        ['base' => 'footer', 'label' => 'Footer content', 'type' => 'textarea', 'col' => 'col-md-6', 'rows' => 5, 'ckeditor' => true]
                                    ]
                                ])
                            </div>
                        </div>
                    </div>

                    <!-- Cấu hình Mạng xã hội -->
                    <div class="col-xxl-12">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-light border-0 py-3">
                                <h5 class="card-title mb-0 font-weight-bold">Mạng xã hội</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach(['facebook' => 'Facebook', 'twitter' => 'Twitter/X', 'youtube' => 'Youtube', 'instagram' => 'Instagram', 'zalo' => 'Zalo'] as $key => $label)
                                        <div class="col-md-4 mb-3">
                                            <label for="{{ $key }}" class="form-label">{{ $label }}</label>
                                            <input type="text" id="{{ $key }}" name="{{ $key }}" class="form-control"
                                                placeholder="Đường dẫn {{ $label }}" value="{{ old($key, $system->$key) }}">
                                            @error($key)<span class="text-danger">{{ $message }}</span>@enderror
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cấu hình Hình ảnh (Logo, Favicon & OG Image) -->
                    <div class="col-xxl-12">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-light border-0 py-3">
                                <h5 class="card-title mb-0 font-weight-bold">Hình ảnh hệ thống</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach([
                                        'logo' => ['label' => 'Logo (200x100)', 'width' => '150px'],
                                        'favicon' => ['label' => 'Favicon (48x48)', 'width' => '48px'],
                                        'share_image' => ['label' => 'Ảnh chia sẻ MXH / OG Image (1200x630px)', 'width' => '180px']
                                    ] as $key => $config)
                                        <div class="col-md-4 mb-3">
                                            <label for="{{ $key }}" class="form-label font-weight-semibold">{{ $config['label'] }}</label>
                                            <input type="file" id="{{ $key }}" name="{{ $key }}" class="form-control mb-2">
                                            @if($system->$key)
                                                <div class="mt-2 p-2 border rounded bg-light d-inline-block">
                                                    <img src="{{ $system->$key }}" alt="{{ $key }}" style="max-height: 80px; max-width: {{ $config['width'] }}; object-fit: contain;">
                                                </div>
                                            @endif
                                            @error($key)<span class="text-danger">{{ $message }}</span>@enderror
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cấu hình SEO & Bản đồ -->
                    <div class="col-xxl-12">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-light border-0 py-3">
                                <h5 class="card-title mb-0 font-weight-bold">Cấu hình SEO Trang chủ & Bản đồ</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="meta_title" class="form-label font-weight-semibold">Tiêu đề SEO Trang chủ (Meta Title)</label>
                                    <input type="text" class="form-control" id="meta_title" name="meta_title"
                                           value="{{ old('meta_title', $system->meta_title) }}"
                                           placeholder="Nhập tiêu đề SEO hiển thị trên Google (tối đa 255 ký tự)">
                                    @error('meta_title')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>

                                @include('admin.partials.localized-fields', [
                                    'model' => $system,
                                    'fields' => [
                                        ['base' => 'keyword', 'label' => 'Từ khóa SEO', 'type' => 'textarea', 'rows' => 3],
                                        ['base' => 'description', 'label' => 'Mô tả SEO', 'type' => 'textarea', 'rows' => 3],
                                    ]
                                ])

                                <div class="mb-3">
                                    <label for="map" class="form-label font-weight-semibold">Iframe Google Map</label>
                                    <textarea class="form-control" id="map" name="map" rows="3" placeholder="Nhập mã nhúng Iframe Google Map">{{ old('map', $system->map) }}</textarea>
                                    @error('map')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cấu hình Mã Script (Header & Body JS) -->
                    <div class="col-xxl-12">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-light border-0 py-3">
                                <h5 class="card-title mb-0 font-weight-bold d-flex align-items-center">
                                    <i class="ri-code-s-slash-line text-primary me-2 fs-18"></i> Mã Script nhúng (Tracking, Tag Manager, Pixel)
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="header_js" class="form-label font-weight-semibold">Mã nhúng trong thẻ &lt;head&gt; (Header JS)</label>
                                        <textarea class="form-control font-monospace" id="header_js" name="header_js" rows="6"
                                                  placeholder="<!-- Google Tag Manager, GA4, Search Console, Facebook Pixel, etc. -->">{{ old('header_js', $system->header_js) }}</textarea>
                                        <small class="text-muted d-block mt-1">Được chèn tự động trước thẻ đóng <code>&lt;/head&gt;</code> trên toàn bộ giao diện.</small>
                                        @error('header_js')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="body_js" class="form-label font-weight-semibold">Mã nhúng sau thẻ &lt;body&gt; (Body JS)</label>
                                        <textarea class="form-control font-monospace" id="body_js" name="body_js" rows="6"
                                                  placeholder="<!-- Google Tag Manager (noscript), Livechat widget, etc. -->">{{ old('body_js', $system->body_js) }}</textarea>
                                        <small class="text-muted d-block mt-1">Được chèn tự động ngay sau thẻ mở <code>&lt;body&gt;</code> trên toàn bộ giao diện.</small>
                                        @error('body_js')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cấu hình Sitemap & Robots -->
                    <div class="col-xxl-12">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-light border-0 py-3 d-flex align-items-center justify-content-between">
                                <h5 class="card-title mb-0 font-weight-bold d-flex align-items-center">
                                    <i class="ri-node-tree text-success me-2 fs-18"></i> Quản lý Sitemap XML & SEO Index
                                </h5>
                                <span class="badge bg-info-subtle text-info border border-info-subtle">XML Sitemap v0.9</span>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 p-3 bg-light rounded border">
                                    <div>
                                        <div class="fw-semibold text-dark mb-1">Đường dẫn tệp Sitemap XML:</div>
                                        <a href="{{ url('/sitemap.xml') }}" target="_blank" class="text-primary font-monospace fw-medium text-decoration-underline">
                                            {{ url('/sitemap.xml') }} <i class="ri-external-link-line ms-1"></i>
                                        </a>
                                        <div class="text-muted small mt-1">Sitemap tự động cập nhật &lt;lastmod&gt;, hỗ trợ Google, Bing, Yahoo đánh chỉ mục nhanh.</div>
                                    </div>
                                    <div>
                                        <button type="button" id="btn-generate-sitemap" class="btn btn-success px-4 py-2 shadow-sm d-flex align-items-center">
                                            <i class="ri-refresh-line me-2 fs-16" id="sitemap-icon"></i>
                                            <span id="sitemap-btn-text">Tạo lại Sitemap ngay</span>
                                        </button>
                                    </div>
                                </div>
                                <div id="sitemap-alert" class="mt-3 d-none"></div>

                                <div class="text-end mt-4">
                                    <button type="submit" class="btn btn-primary px-4 py-2 shadow-sm">
                                        <i class="ri-save-line align-bottom me-1"></i> Lưu toàn bộ cấu hình
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('admin.partials.ckeditor')
    <script src="{{ asset('admin/js/system-lang.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnSitemap = document.getElementById('btn-generate-sitemap');
            const sitemapIcon = document.getElementById('sitemap-icon');
            const sitemapText = document.getElementById('sitemap-btn-text');
            const sitemapAlert = document.getElementById('sitemap-alert');

            if (btnSitemap) {
                btnSitemap.addEventListener('click', function(e) {
                    e.preventDefault();
                    btnSitemap.disabled = true;
                    sitemapIcon.classList.add('spinner-border', 'spinner-border-sm');
                    sitemapIcon.classList.remove('ri-refresh-line');
                    sitemapText.textContent = 'Đang sinh sitemap...';

                    fetch("{{ route('admin.sitemap.generate') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        sitemapAlert.className = 'alert alert-success alert-dismissible fade show mt-3';
                        sitemapAlert.innerHTML = '<i class="ri-checkbox-circle-line me-2"></i>' + (data.message || 'Sitemap đã được tạo thành công!') +
                            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                        sitemapAlert.classList.remove('d-none');
                    })
                    .catch(error => {
                        sitemapAlert.className = 'alert alert-danger alert-dismissible fade show mt-3';
                        sitemapAlert.innerHTML = '<i class="ri-error-warning-line me-2"></i>Có lỗi xảy ra khi tạo sitemap: ' + error.message +
                            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                        sitemapAlert.classList.remove('d-none');
                    })
                    .finally(() => {
                        btnSitemap.disabled = false;
                        sitemapIcon.classList.remove('spinner-border', 'spinner-border-sm');
                        sitemapIcon.classList.add('ri-refresh-line');
                        sitemapText.textContent = 'Tạo lại Sitemap ngay';
                    });
                });
            }
        });
    </script>
@endsection
