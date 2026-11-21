@props(['model' => $page ?? null])

@php
    $sysBrand = $systemConfig->name_vn ?? $web->name_vn ?? config('app.name');
    $currentTitle = old('name_vn', data_get($model, 'name_vn', 'Tiêu đề bài viết'));
    $currentSlug = old('slug_vn', data_get($model, 'slug_vn', 'duong-dan-bai-viet'));
    $currentDesc = old('description_vn', data_get($model, 'description_vn', 'Đoạn mô tả ngắn hiển thị trên kết quả tìm kiếm Google giúp người dùng hiểu rõ nội dung và tăng tỷ lệ nhấp chuột (CTR)...'));
    $initialScore = (int) old('seo_score', data_get($model, 'seo_score', 0));

    // Đọc active languages
    $activeLocales = isset($systemConfig) ? ($systemConfig->active_languages ?? ['vi', 'en']) : ['vi', 'en'];
    $languages = [];
    if (in_array('vi', $activeLocales)) {
        $languages['vn'] = ['label' => 'Tiếng Việt', 'flag' => 'vietnam.png'];
    }
    if (in_array('en', $activeLocales)) {
        $languages['en'] = ['label' => 'Tiếng Anh (EN)', 'flag' => 'usa.png'];
    }
    $seoTabSuffix = uniqid('seo_');

    // Xác định tab SEO nào chứa lỗi để tự động active tab đó
    $seoTabErrors = [];
    $firstSeoErrorLocale = null;
    foreach ($languages as $locale => $info) {
        $seoTabErrors[$locale] = false;
        if ($errors->has('keyword_' . $locale) || $errors->has('description_' . $locale)) {
            $seoTabErrors[$locale] = true;
            if ($firstSeoErrorLocale === null) {
                $firstSeoErrorLocale = $locale;
            }
        }
    }
    $activeSeoLocale = $firstSeoErrorLocale ?? array_key_first($languages);
@endphp

<!-- Input ẩn lưu điểm SEO vào Cơ sở dữ liệu -->
<input type="hidden" name="seo_score" id="seo_score_input" value="{{ $initialScore }}">

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-light d-flex align-items-center justify-content-between py-3 border-0">
        <h5 class="card-title mb-0 font-weight-bold d-flex align-items-center">
            <i class="ri-google-fill text-danger me-2 fs-18"></i> Đánh giá Chuẩn SEO & SERP Preview
            @if ($errors->has('keyword_vn') || $errors->has('keyword_en') || $errors->has('description_vn') || $errors->has('description_en'))
                <span class="badge bg-danger ms-2">Có lỗi nhập liệu</span>
            @endif
        </h5>
        <button type="button" id="btn-reanalyze-seo" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center px-2 py-1" title="Chấm điểm lại SEO">
            <i class="ri-refresh-line me-1"></i> Chấm điểm lại
        </button>
    </div>

    <div class="card-body">
        <!-- Widget Điểm SEO Thang điểm 100 (Yoast SEO / Rank Math Style) -->
        <div class="p-3 mb-3 rounded-3 border bg-light-subtle position-relative overflow-hidden">
            <div class="d-flex align-items-center justify-content-between">
                <!-- Vòng tròn hiển thị điểm số -->
                <div class="d-flex align-items-center">
                    <div class="position-relative d-inline-flex align-items-center justify-content-center me-3" style="width: 76px; height: 76px;">
                        <svg viewBox="0 0 84 84" class="w-100 h-100" style="transform: rotate(-90deg);">
                            <circle cx="42" cy="42" r="36" stroke="#e2e8f0" stroke-width="7" fill="transparent"></circle>
                            <circle id="seo-score-circle" cx="42" cy="42" r="36" stroke="#ef4444" stroke-width="7" fill="transparent"
                                    stroke-dasharray="226.2" stroke-dashoffset="226.2" stroke-linecap="round"
                                    style="transition: stroke-dashoffset 0.6s ease, stroke 0.4s ease;"></circle>
                        </svg>
                        <div class="position-absolute text-center" style="top: 50%; left: 50%; transform: translate(-50%, -50%);">
                            <span id="seo-score-display" class="fw-bold fs-20 text-dark d-block leading-none">{{ $initialScore }}</span>
                            <span class="text-muted text-uppercase fs-10 fw-semibold">/100</span>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span id="seo-score-rating" class="badge bg-danger-subtle text-danger border border-danger-subtle fs-12 px-2 py-1">
                                Đang phân tích...
                            </span>
                            <span id="seo-passed-summary" class="badge bg-secondary fs-11">0/14 đạt</span>
                        </div>
                        <div class="text-muted small">
                            <i class="ri-shield-check-line text-success me-1"></i>Chuẩn đánh giá Google & Rank Math
                        </div>
                    </div>
                </div>

                <div class="text-end d-none d-sm-block">
                    <a class="btn btn-sm btn-link text-decoration-none p-0 fs-12 text-primary" data-bs-toggle="collapse" href="#seo-checklist-collapse" role="button" aria-expanded="true">
                        Xem chi tiết <i class="ri-arrow-down-s-line align-middle"></i>
                    </a>
                </div>
            </div>

            <!-- Thanh tiến trình (Progress Bar) -->
            <div class="progress mt-3 rounded-pill" style="height: 6px; background-color: #e2e8f0;">
                <div id="seo-score-progress" class="progress-bar bg-danger" role="progressbar" style="width: 0%; transition: width 0.6s ease;"></div>
            </div>
        </div>

        <!-- Bảng phân tích chi tiết các nhóm tiêu chí (Accordion Collapse) -->
        <div class="collapse show mb-4" id="seo-checklist-collapse">
            <div class="accordion accordion-flush border rounded-2" id="seoChecklistAccordion">
                
                <!-- 1. SEO Cơ bản (40đ) -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingBasic">
                        <button class="accordion-button py-2 px-3 fs-13 fw-semibold bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBasic" aria-expanded="true">
                            <span class="me-auto"><i class="ri-compass-3-line text-primary me-1"></i> 1. SEO Cơ bản</span>
                            <span id="seo-cat-basic-score" class="badge bg-light text-dark border me-2">0/40đ</span>
                        </button>
                    </h2>
                    <div id="collapseBasic" class="accordion-collapse collapse show">
                        <div class="accordion-body p-2 fs-12">
                            <div class="seo-item d-flex align-items-start py-1 border-bottom" id="seo-item-keyword_in_title">
                                <i class="seo-item-icon ri-close-circle-fill text-danger fs-16 me-2"></i>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold">Từ khóa chính trong Tiêu đề SEO</span>
                                        <span class="seo-item-score badge bg-light text-muted">0/10đ</span>
                                    </div>
                                    <div class="seo-item-msg text-muted fs-11 mt-0.5">Tiêu đề chưa chứa từ khóa mục tiêu.</div>
                                </div>
                            </div>

                            <div class="seo-item d-flex align-items-start py-1 border-bottom" id="seo-item-keyword_at_start_of_title">
                                <i class="seo-item-icon ri-close-circle-fill text-danger fs-16 me-2"></i>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold">Từ khóa ở phần đầu Tiêu đề</span>
                                        <span class="seo-item-score badge bg-light text-muted">0/5đ</span>
                                    </div>
                                    <div class="seo-item-msg text-muted fs-11 mt-0.5">Nên đặt từ khóa ở nửa đầu tiêu đề để tối ưu CTR.</div>
                                </div>
                            </div>

                            <div class="seo-item d-flex align-items-start py-1 border-bottom" id="seo-item-keyword_in_meta_description">
                                <i class="seo-item-icon ri-close-circle-fill text-danger fs-16 me-2"></i>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold">Từ khóa trong Thẻ mô tả Meta</span>
                                        <span class="seo-item-score badge bg-light text-muted">0/8đ</span>
                                    </div>
                                    <div class="seo-item-msg text-muted fs-11 mt-0.5">Thẻ mô tả chưa có từ khóa chính.</div>
                                </div>
                            </div>

                            <div class="seo-item d-flex align-items-start py-1 border-bottom" id="seo-item-keyword_in_slug">
                                <i class="seo-item-icon ri-close-circle-fill text-danger fs-16 me-2"></i>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold">Từ khóa trong Đường dẫn (Slug)</span>
                                        <span class="seo-item-score badge bg-light text-muted">0/7đ</span>
                                    </div>
                                    <div class="seo-item-msg text-muted fs-11 mt-0.5">Đường dẫn slug chưa chứa từ khóa không dấu.</div>
                                </div>
                            </div>

                            <div class="seo-item d-flex align-items-start py-1 border-bottom" id="seo-item-keyword_in_intro">
                                <i class="seo-item-icon ri-close-circle-fill text-danger fs-16 me-2"></i>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold">Từ khóa trong đoạn mở đầu / Sapo</span>
                                        <span class="seo-item-score badge bg-light text-muted">0/5đ</span>
                                    </div>
                                    <div class="seo-item-msg text-muted fs-11 mt-0.5">Từ khóa cần xuất hiện trong 10% đầu nội dung bài viết.</div>
                                </div>
                            </div>

                            <div class="seo-item d-flex align-items-start py-1" id="seo-item-content_length">
                                <i class="seo-item-icon ri-close-circle-fill text-danger fs-16 me-2"></i>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold">Độ dài bài viết tối thiểu</span>
                                        <span class="seo-item-score badge bg-light text-muted">0/5đ</span>
                                    </div>
                                    <div class="seo-item-msg text-muted fs-11 mt-0.5">Bài viết chuẩn SEO cần tối thiểu 300 - 600 từ.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Tối ưu Bổ sung (30đ) -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingAdd">
                        <button class="accordion-button collapsed py-2 px-3 fs-13 fw-semibold bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdd">
                            <span class="me-auto"><i class="ri-search-eye-line text-info me-1"></i> 2. Tối ưu Bổ sung</span>
                            <span id="seo-cat-additional-score" class="badge bg-light text-dark border me-2">0/30đ</span>
                        </button>
                    </h2>
                    <div id="collapseAdd" class="accordion-collapse collapse">
                        <div class="accordion-body p-2 fs-12">
                            <div class="seo-item d-flex align-items-start py-1 border-bottom" id="seo-item-keyword_in_headings">
                                <i class="seo-item-icon ri-close-circle-fill text-danger fs-16 me-2"></i>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold">Từ khóa trong tiêu đề con (H2, H3)</span>
                                        <span class="seo-item-score badge bg-light text-muted">0/8đ</span>
                                    </div>
                                    <div class="seo-item-msg text-muted fs-11 mt-0.5">Thẻ H2/H3 nên chứa từ khóa chính.</div>
                                </div>
                            </div>

                            <div class="seo-item d-flex align-items-start py-1 border-bottom" id="seo-item-keyword_density">
                                <i class="seo-item-icon ri-close-circle-fill text-danger fs-16 me-2"></i>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold">Mật độ từ khóa (1.0% - 2.5%)</span>
                                        <span class="seo-item-score badge bg-light text-muted">0/8đ</span>
                                    </div>
                                    <div class="seo-item-msg text-muted fs-11 mt-0.5">Từ khóa chưa xuất hiện trong nội dung bài viết.</div>
                                </div>
                            </div>

                            <div class="seo-item d-flex align-items-start py-1 border-bottom" id="seo-item-images_and_alt">
                                <i class="seo-item-icon ri-close-circle-fill text-danger fs-16 me-2"></i>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold">Hình ảnh & Thẻ Alt (Image SEO)</span>
                                        <span class="seo-item-score badge bg-light text-muted">0/8đ</span>
                                    </div>
                                    <div class="seo-item-msg text-muted fs-11 mt-0.5">Cần có ảnh đại diện và ảnh trong bài kèm thẻ Alt.</div>
                                </div>
                            </div>

                            <div class="seo-item d-flex align-items-start py-1" id="seo-item-slug_length">
                                <i class="seo-item-icon ri-close-circle-fill text-danger fs-16 me-2"></i>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold">Độ dài Slug thân thiện (<= 75 ký tự)</span>
                                        <span class="seo-item-score badge bg-light text-muted">0/6đ</span>
                                    </div>
                                    <div class="seo-item-msg text-muted fs-11 mt-0.5">Đường dẫn slug cần ngắn gọn, rõ nghĩa.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Khả năng Đọc & Trình Bày (20đ) -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingRead">
                        <button class="accordion-button collapsed py-2 px-3 fs-13 fw-semibold bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRead">
                            <span class="me-auto"><i class="ri-book-read-line text-warning me-1"></i> 3. Khả năng Đọc & Trình Bày</span>
                            <span id="seo-cat-readability-score" class="badge bg-light text-dark border me-2">0/20đ</span>
                        </button>
                    </h2>
                    <div id="collapseRead" class="accordion-collapse collapse">
                        <div class="accordion-body p-2 fs-12">
                            <div class="seo-item d-flex align-items-start py-1 border-bottom" id="seo-item-title_length">
                                <i class="seo-item-icon ri-close-circle-fill text-danger fs-16 me-2"></i>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold">Độ dài Tiêu đề (40 - 60 ký tự)</span>
                                        <span class="seo-item-score badge bg-light text-muted">0/7đ</span>
                                    </div>
                                    <div class="seo-item-msg text-muted fs-11 mt-0.5">Khuyến nghị tiêu đề từ 40 - 60 ký tự.</div>
                                </div>
                            </div>

                            <div class="seo-item d-flex align-items-start py-1 border-bottom" id="seo-item-desc_length">
                                <i class="seo-item-icon ri-close-circle-fill text-danger fs-16 me-2"></i>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold">Độ dài Mô tả Meta (120 - 160 ký tự)</span>
                                        <span class="seo-item-score badge bg-light text-muted">0/7đ</span>
                                    </div>
                                    <div class="seo-item-msg text-muted fs-11 mt-0.5">Khuyến nghị mô tả từ 120 - 160 ký tự.</div>
                                </div>
                            </div>

                            <div class="seo-item d-flex align-items-start py-1" id="seo-item-content_structure">
                                <i class="seo-item-icon ri-close-circle-fill text-danger fs-16 me-2"></i>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold">Cấu trúc phân đoạn (Headings)</span>
                                        <span class="seo-item-score badge bg-light text-muted">0/6đ</span>
                                    </div>
                                    <div class="seo-item-msg text-muted fs-11 mt-0.5">Sử dụng các thẻ H2, H3 để chia nhỏ nội dung bài viết.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Liên kết & Tương tác (10đ) -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingLinks">
                        <button class="accordion-button collapsed py-2 px-3 fs-13 fw-semibold bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLinks">
                            <span class="me-auto"><i class="ri-links-line text-success me-1"></i> 4. Liên kết & Đa phương tiện</span>
                            <span id="seo-cat-links-score" class="badge bg-light text-dark border me-2">0/10đ</span>
                        </button>
                    </h2>
                    <div id="collapseLinks" class="accordion-collapse collapse">
                        <div class="accordion-body p-2 fs-12">
                            <div class="seo-item d-flex align-items-start py-1 border-bottom" id="seo-item-internal_links">
                                <i class="seo-item-icon ri-close-circle-fill text-danger fs-16 me-2"></i>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold">Liên kết nội bộ (Internal Links)</span>
                                        <span class="seo-item-score badge bg-light text-muted">0/5đ</span>
                                    </div>
                                    <div class="seo-item-msg text-muted fs-11 mt-0.5">Nên gắn link điều hướng đến bài viết hoặc sản phẩm liên quan.</div>
                                </div>
                            </div>

                            <div class="seo-item d-flex align-items-start py-1" id="seo-item-external_links">
                                <i class="seo-item-icon ri-close-circle-fill text-danger fs-16 me-2"></i>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold">Liên kết ngoài nguồn uy tín</span>
                                        <span class="seo-item-score badge bg-light text-muted">0/5đ</span>
                                    </div>
                                    <div class="seo-item-msg text-muted fs-11 mt-0.5">Nên có liên kết trích dẫn nguồn uy tín bên ngoài.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Khung xem trước kết quả Google (Google SERP Preview Box) -->
        <div class="mb-3">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="small fw-semibold text-muted text-uppercase fs-11">
                    <i class="ri-eye-line me-1"></i> Xem trước Snippet Google
                </span>
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" id="serp-view-desktop" class="btn btn-primary active px-2 py-0.5 fs-11">
                        <i class="ri-macbook-line me-1"></i>Máy tính
                    </button>
                    <button type="button" id="serp-view-mobile" class="btn btn-outline-secondary px-2 py-0.5 fs-11">
                        <i class="ri-smartphone-line me-1"></i>Mobile
                    </button>
                </div>
            </div>

            <div id="serp-preview-container" class="p-3 rounded border bg-white mx-auto transition-all" style="border-color: #dfe1e5 !important; box-shadow: 0 1px 6px rgba(32,33,36,.1); max-width: 100%;">
                <div class="d-flex align-items-center mb-1">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-light me-2" style="width: 26px; height: 26px; font-size: 13px;">
                        <i class="ri-global-line text-muted"></i>
                    </div>
                    <div class="overflow-hidden">
                        <span class="fw-bold d-block text-dark text-truncate" style="font-size: 14px; line-height: 1.2;">{{ $sysBrand }}</span>
                        <span class="text-muted small font-monospace d-block text-truncate" style="font-size: 12px;">
                            {{ url('/') }}/<span id="serp-preview-slug" class="text-success">{{ $currentSlug }}</span>
                        </span>
                    </div>
                </div>

                <h6 class="mb-1 text-truncate" style="font-family: Arial, sans-serif; font-size: 17px; line-height: 1.3; color: #1a0dab; cursor: pointer;">
                    <span id="serp-preview-title">{{ $currentTitle }}</span> - {{ $sysBrand }}
                </h6>

                <p class="mb-0 text-muted" style="font-family: Arial, sans-serif; font-size: 13px; line-height: 1.45; color: #4d5156 !important;">
                    <span id="serp-preview-desc">{{ $currentDesc }}</span>
                </p>
            </div>
        </div>

        <!-- Bộ đếm ký tự thời gian thực -->
        <div class="row mb-3">
            <div class="col-sm-6 mb-2 mb-sm-0">
                <div class="p-2 rounded bg-light border d-flex justify-content-between align-items-center">
                    <span class="small text-muted"><i class="ri-text me-1"></i>Độ dài Tiêu đề:</span>
                    <span id="serp-title-counter" class="badge bg-primary">0 / 60 ký tự</span>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="p-2 rounded bg-light border d-flex justify-content-between align-items-center">
                    <span class="small text-muted"><i class="ri-file-text-line me-1"></i>Độ dài Mô tả:</span>
                    <span id="serp-desc-counter" class="badge bg-primary">0 / 160 ký tự</span>
                </div>
            </div>
        </div>

        <!-- Nav tabs Đa ngôn ngữ cho SEO Fields -->
        <ul class="nav nav-tabs nav-tabs-custom nav-success mb-3" role="tablist">
            @foreach ($languages as $locale => $info)
                <li class="nav-item">
                    <a class="nav-link {{ $locale === $activeSeoLocale ? 'active' : '' }}"
                       data-bs-toggle="tab"
                       href="#locale-seo-{{ $locale }}-{{ $seoTabSuffix }}"
                       role="tab">
                        <img src="{{ asset('uploads/icon/' . $info['flag']) }}"
                             alt="{{ $locale }}"
                             class="me-1 align-middle"
                             style="width: 18px; height: 12px; object-fit: cover; border-radius: 2px; margin-top: -2px;">
                        {{ $info['label'] }}
                        @if (!empty($seoTabErrors[$locale]))
                            <span class="badge bg-danger ms-1">Lỗi</span>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>

        <!-- Tab panes cho SEO Fields -->
        <div class="tab-content tab-content-localized">
            @foreach ($languages as $locale => $info)
                <div class="tab-pane {{ $locale === $activeSeoLocale ? 'active' : '' }}"
                     id="locale-seo-{{ $locale }}-{{ $seoTabSuffix }}"
                     role="tabpanel">

                    <!-- Từ khóa mục tiêu (Focus Keywords) -->
                    <div class="mb-3">
                        <label for="keyword_{{ $locale }}" class="form-label font-weight-semibold d-flex justify-content-between align-items-center">
                            <span>
                                <i class="ri-key-2-line text-warning me-1"></i> Từ khóa mục tiêu (Focus Keyword)
                                <span class="text-muted text-xs">({{ strtoupper($locale) }})</span>
                            </span>
                            <span class="badge bg-info-subtle text-info border border-info-subtle fs-11">Từ khóa chính đứng đầu</span>
                        </label>
                        <textarea class="form-control @error('keyword_'.$locale) is-invalid @enderror"
                                  id="keyword_{{ $locale }}"
                                  name="keyword_{{ $locale }}"
                                  rows="2"
                                  placeholder="Nhập từ khóa chính (vd: thiết kế nội thất, sofa gỗ óc chó)">{{ old('keyword_'.$locale, data_get($model, 'keyword_'.$locale, '')) }}</textarea>
                        @error('keyword_'.$locale)
                            <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                        @enderror
                        <small class="text-muted d-block mt-1">Từ khóa chính bài viết nhắm tới. Điểm SEO sẽ đánh giá mức độ tối ưu xoay quanh từ khóa này.</small>
                    </div>

                    <!-- Mô tả SEO (Meta Description) -->
                    <div class="mb-3">
                        <label for="{{ $locale == 'vn' ? 'seo_description_input' : 'description_'.$locale }}" class="form-label font-weight-semibold">
                            <i class="ri-file-text-line text-primary me-1"></i> Mô tả SEO (Meta Description)
                            <span class="text-muted text-xs">({{ strtoupper($locale) }})</span>
                        </label>
                        <textarea class="form-control @error('description_'.$locale) is-invalid @enderror"
                                  id="{{ $locale == 'vn' ? 'seo_description_input' : 'description_'.$locale }}"
                                  name="description_{{ $locale }}"
                                  rows="3"
                                  placeholder="Mô tả ngắn gọn, hấp dẫn dưới 160 ký tự">{{ old('description_'.$locale, data_get($model, 'description_'.$locale, '')) }}</textarea>
                        @error('description_'.$locale)
                            <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                        @enderror
                        <small class="text-muted d-block mt-1">Đoạn mô tả ngắn gọn tóm tắt nội dung bài viết, chuẩn SEO từ 120 - 160 ký tự.</small>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const titleInput = document.getElementById('name_vn') || document.querySelector('input[name="name_vn"]');
    const slugInput = document.getElementById('slug-input') || document.getElementById('slug_vn') || document.querySelector('input[name="slug_vn"]');
    const descInput = document.getElementById('seo_description_input') || document.querySelector('textarea[name="description_vn"]');

    const previewTitle = document.getElementById('serp-preview-title');
    const previewSlug = document.getElementById('serp-preview-slug');
    const previewDesc = document.getElementById('serp-preview-desc');

    const titleCounter = document.getElementById('serp-title-counter');
    const descCounter = document.getElementById('serp-desc-counter');

    function updateTitle() {
        if (!titleInput) return;
        const val = titleInput.value.trim();
        const len = titleInput.value.length;

        if (previewTitle) {
            previewTitle.textContent = val || 'Tiêu đề trang';
        }

        if (titleCounter) {
            titleCounter.textContent = len + ' / 60 ký tự';
            if (len === 0) {
                titleCounter.className = 'badge bg-secondary';
            } else if (len >= 40 && len <= 60) {
                titleCounter.className = 'badge bg-success';
            } else if (len > 60) {
                titleCounter.className = 'badge bg-danger';
            } else {
                titleCounter.className = 'badge bg-warning text-dark';
            }
        }
    }

    function updateSlug() {
        if (!slugInput || !previewSlug) return;
        const val = slugInput.value.trim();
        previewSlug.textContent = val || 'duong-dan-bai-viet';
    }

    function updateDesc() {
        if (!descInput) return;
        const val = descInput.value.trim();
        const len = descInput.value.length;

        if (previewDesc) {
            previewDesc.textContent = val || 'Đoạn mô tả ngắn hiển thị trên kết quả tìm kiếm Google giúp người dùng hiểu rõ nội dung và tăng tỷ lệ nhấp chuột (CTR)...';
        }

        if (descCounter) {
            descCounter.textContent = len + ' / 160 ký tự';
            if (len === 0) {
                descCounter.className = 'badge bg-secondary';
            } else if (len >= 120 && len <= 160) {
                descCounter.className = 'badge bg-success';
            } else if (len > 160) {
                descCounter.className = 'badge bg-danger';
            } else {
                descCounter.className = 'badge bg-warning text-dark';
            }
        }
    }

    if (titleInput) {
        titleInput.addEventListener('input', updateTitle);
        updateTitle();
    }
    if (slugInput) {
        slugInput.addEventListener('input', updateSlug);
        updateSlug();
    }
    if (descInput) {
        descInput.addEventListener('input', updateDesc);
        updateDesc();
    }
});
</script>

<!-- Tải SEO Analyzer JS Engine -->
<script src="{{ asset('admin/js/seo-analyzer.js') }}"></script>
