@props(['model' => $page ?? null])

@php
    $sysBrand = $systemConfig->name_vn ?? $web->name_vn ?? config('app.name');
    $currentTitle = old('name_vn', data_get($model, 'name_vn', 'Tiêu đề trang'));
    $currentSlug = old('slug_vn', data_get($model, 'slug_vn', 'duong-dan-bai-viet'));
    $currentDesc = old('description_vn', data_get($model, 'description_vn', 'Đoạn mô tả ngắn hiển thị trên kết quả tìm kiếm Google giúp người dùng hiểu rõ nội dung và tăng tỷ lệ nhấp chuột (CTR)...'));

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
@endphp

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-light d-flex align-items-center justify-content-between py-3 border-0">
        <h5 class="card-title mb-0 font-weight-bold d-flex align-items-center">
            <i class="ri-google-fill text-danger me-2 fs-18"></i> Cấu hình SEO & Xem trước Google
        </h5>
        <span class="badge bg-success-subtle text-success border border-success-subtle">Chuẩn SEO Google</span>
    </div>
    <div class="card-body">
        <!-- Khung xem trước kết quả Google (Google SERP Preview Box) -->
        <div class="p-3 mb-4 rounded border bg-white" style="border-color: #dfe1e5 !important; box-shadow: 0 1px 6px rgba(32,33,36,.1);">
            <div class="d-flex align-items-center mb-1">
                <div class="rounded-circle d-flex align-items-center justify-content-center bg-light me-2" style="width: 26px; height: 26px; font-size: 13px;">
                    <i class="ri-global-line text-muted"></i>
                </div>
                <div>
                    <span class="fw-bold d-block text-dark" style="font-size: 14px; line-height: 1.2;">{{ $sysBrand }}</span>
                    <span class="text-muted small font-monospace d-block text-truncate" style="font-size: 12px; max-width: 450px;">
                        {{ url('/') }}/<span id="serp-preview-slug" class="text-success">{{ $currentSlug }}</span>.html
                    </span>
                </div>
            </div>
            
            <h6 class="mb-1 text-truncate" style="font-family: Arial, sans-serif; font-size: 18px; line-height: 1.3; color: #1a0dab; cursor: pointer;">
                <span id="serp-preview-title">{{ $currentTitle }}</span> - {{ $sysBrand }}
            </h6>
            
            <p class="mb-0 text-muted" style="font-family: Arial, sans-serif; font-size: 13px; line-height: 1.45; color: #4d5156 !important;">
                <span id="serp-preview-desc">{{ $currentDesc }}</span>
            </p>
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

        <!-- Nav tabs Đa ngôn ngữ cho SEO -->
        <ul class="nav nav-tabs nav-tabs-custom nav-success mb-3" role="tablist">
            @foreach ($languages as $locale => $info)
                <li class="nav-item">
                    <a class="nav-link {{ $loop->first ? 'active' : '' }}" 
                       data-bs-toggle="tab" 
                       href="#locale-seo-{{ $locale }}-{{ $seoTabSuffix }}" 
                       role="tab">
                        <img src="{{ asset('uploads/icon/' . $info['flag']) }}" 
                             alt="{{ $locale }}" 
                             class="me-1 align-middle" 
                             style="width: 18px; height: 12px; object-fit: cover; border-radius: 2px; margin-top: -2px;">
                        {{ $info['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>

        <!-- Tab panes cho SEO Fields -->
        <div class="tab-content tab-content-localized">
            @foreach ($languages as $locale => $info)
                <div class="tab-pane {{ $loop->first ? 'active' : '' }}" 
                     id="locale-seo-{{ $locale }}-{{ $seoTabSuffix }}" 
                     role="tabpanel">
                    
                    <!-- Từ khóa mục tiêu (Keywords) -->
                    <div class="mb-3">
                        <label for="keyword_{{ $locale }}" class="form-label font-weight-semibold">
                            Từ khóa mục tiêu (Meta Keywords) <span class="text-muted text-xs">({{ strtoupper($locale) }})</span>
                        </label>
                        <textarea class="form-control @error('keyword_'.$locale) is-invalid @enderror"
                                  id="keyword_{{ $locale }}"
                                  name="keyword_{{ $locale }}"
                                  rows="2"
                                  placeholder="Nhập các từ khóa cách nhau bởi dấu phẩy (vd: noi that phong khach, sofa cao cap)">{{ old('keyword_'.$locale, data_get($model, 'keyword_'.$locale, '')) }}</textarea>
                        @error('keyword_'.$locale)<span class="text-danger small">{{ $message }}</span>@enderror
                    </div>

                    <!-- Mô tả SEO (Meta Description) -->
                    <div class="mb-3">
                        <label for="{{ $locale == 'vn' ? 'seo_description_input' : 'description_'.$locale }}" class="form-label font-weight-semibold">
                            Mô tả SEO (Meta Description) <span class="text-muted text-xs">({{ strtoupper($locale) }})</span>
                        </label>
                        <textarea class="form-control @error('description_'.$locale) is-invalid @enderror"
                                  id="{{ $locale == 'vn' ? 'seo_description_input' : 'description_'.$locale }}"
                                  name="description_{{ $locale }}"
                                  rows="3"
                                  placeholder="Mô tả ngắn gọn, hấp dẫn dưới 160 ký tự">{{ old('description_'.$locale, data_get($model, 'description_'.$locale, '')) }}</textarea>
                        @error('description_'.$locale)<span class="text-danger small">{{ $message }}</span>@enderror
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
            } else if (len <= 60) {
                titleCounter.className = 'badge bg-success';
            } else {
                titleCounter.className = 'badge bg-danger';
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
            previewDesc.textContent = val || 'Đoạn mô tả ngắn hiển thị trên kết quả tìm kiếm Google...';
        }

        if (descCounter) {
            descCounter.textContent = len + ' / 160 ký tự';
            if (len === 0) {
                descCounter.className = 'badge bg-secondary';
            } else if (len >= 120 && len <= 160) {
                descCounter.className = 'badge bg-success';
            } else if (len < 120) {
                descCounter.className = 'badge bg-warning text-dark';
            } else {
                descCounter.className = 'badge bg-danger';
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
