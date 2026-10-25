@props([
    'locale',
    'model' => $page ?? null,
    'imageFolder' => '',
    'base' => 'image',
    'label' => 'Hình ảnh',
    'dimensions' => '800x800 px'
])

@php
    $activeLocales = isset($systemConfig) ? ($systemConfig->active_languages ?? ['vi', 'en']) : ['vi', 'en'];
    
    $shouldRender = false;
    if ($locale === 'vn' && in_array('vi', $activeLocales)) {
        $shouldRender = true;
    }
    if ($locale === 'en' && in_array('en', $activeLocales)) {
        $shouldRender = true;
    }
@endphp

@if ($shouldRender)
@php
    $fieldName = $base . '_' . $locale;
    $currentImage = data_get($model, $fieldName);
    $inputUuid = 'file_' . $fieldName;
    $hiddenUuid = 'hidden_' . $fieldName;
    $clearUuid = 'clear_' . $fieldName;
    $previewUuid = 'preview_' . $fieldName;
    $containerUuid = 'container_' . $fieldName;

    $imageSrc = '';
    if (!empty($currentImage)) {
        $imageSrc = str_starts_with($currentImage, 'http') ? $currentImage : asset($currentImage);
    }
@endphp

<div class="col-md-6 mb-3" id="{{ $containerUuid }}">
    <label class="form-label font-weight-semibold d-flex justify-content-between align-items-center mb-1">
        <span>{{ $label }} {{ strtoupper($locale) }} <span class="text-muted font-weight-normal">({{ $dimensions }})</span></span>
    </label>

    <!-- Hidden Inputs -->
    <input type="file" id="{{ $inputUuid }}" name="{{ $fieldName }}" class="d-none @error($fieldName) is-invalid @enderror" accept="image/*">
    <input type="hidden" id="{{ $hiddenUuid }}" name="{{ $fieldName }}" value="{{ $currentImage ?? '' }}">
    <input type="hidden" id="{{ $clearUuid }}" name="{{ $fieldName }}_clear" value="0">

    <!-- Action Toolbar (Rule 13: 38px controls) -->
    <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
        <button type="button" class="btn btn-sm btn-primary rounded-2 px-3 shadow-xs d-inline-flex align-items-center gap-1.5" style="height: 38px;" onclick="selectMediaFor_{{ $fieldName }}()">
            <i class="ri-folder-image-line fs-15"></i>
            <span class="fw-semibold">Chọn từ Thư viện</span>
        </button>
        <button type="button" class="btn btn-sm btn-outline-secondary rounded-2 px-3 d-inline-flex align-items-center gap-1.5 bg-white" style="height: 38px;" onclick="document.getElementById('{{ $inputUuid }}').click();">
            <i class="ri-upload-2-line fs-15"></i>
            <span>Tải từ máy tính</span>
        </button>
        <button type="button" id="btn_clear_{{ $fieldName }}" class="btn btn-sm btn-outline-danger rounded-2 px-2.5 d-inline-flex align-items-center justify-content-center {{ empty($currentImage) ? 'd-none' : '' }}" style="height: 38px; width: 38px;" title="Xóa hình ảnh này" onclick="clearMediaFor_{{ $fieldName }}()">
            <i class="ri-delete-bin-line fs-16"></i>
        </button>
    </div>

    @error($fieldName)
        <div class="text-danger small mb-2">{{ $message }}</div>
    @enderror

    <!-- Image Preview Box -->
    <div id="{{ $previewUuid }}_box" class="border rounded-2 p-2 bg-light text-center position-relative d-inline-block {{ empty($currentImage) ? 'd-none' : '' }}" style="max-width: 280px; min-width: 180px;">
        <img id="{{ $previewUuid }}_img" src="{{ $imageSrc }}" alt="Preview" class="img-fluid rounded-1" style="max-height: 180px; object-fit: contain;">
        <span class="position-absolute top-0 end-0 m-1.5 badge bg-dark bg-opacity-75 text-xs text-uppercase" id="{{ $previewUuid }}_badge">Ảnh đã chọn</span>
    </div>
</div>

@push('scripts')
<script>
function selectMediaFor_{{ $fieldName }}() {
    if (typeof window.openMediaPicker === 'function') {
        window.openMediaPicker({
            mode: 'single',
            module: '{{ $imageFolder ?: "general" }}',
            onSelect: function(item) {
                if (!item) return;

                // Set relative path in hidden input
                const hiddenInput = document.getElementById('{{ $hiddenUuid }}');
                const clearInput = document.getElementById('{{ $clearUuid }}');
                const fileInput = document.getElementById('{{ $inputUuid }}');
                const previewBox = document.getElementById('{{ $previewUuid }}_box');
                const previewImg = document.getElementById('{{ $previewUuid }}_img');
                const clearBtn = document.getElementById('btn_clear_{{ $fieldName }}');
                const badge = document.getElementById('{{ $previewUuid }}_badge');

                if (hiddenInput) hiddenInput.value = item.path;
                if (clearInput) clearInput.value = '0';
                if (fileInput) fileInput.value = ''; // clear any staged local file

                if (previewImg) previewImg.src = item.url;
                if (previewBox) previewBox.classList.remove('d-none');
                if (clearBtn) clearBtn.classList.remove('d-none');
                if (badge) badge.textContent = item.extension ? item.extension.toUpperCase() : 'ĐÃ CHỌN';
            }
        });
    }
}

function clearMediaFor_{{ $fieldName }}() {
    const hiddenInput = document.getElementById('{{ $hiddenUuid }}');
    const clearInput = document.getElementById('{{ $clearUuid }}');
    const fileInput = document.getElementById('{{ $inputUuid }}');
    const previewBox = document.getElementById('{{ $previewUuid }}_box');
    const previewImg = document.getElementById('{{ $previewUuid }}_img');
    const clearBtn = document.getElementById('btn_clear_{{ $fieldName }}');

    if (hiddenInput) hiddenInput.value = '';
    if (clearInput) clearInput.value = '1';
    if (fileInput) fileInput.value = '';
    if (previewImg) previewImg.src = '';
    if (previewBox) previewBox.classList.add('d-none');
    if (clearBtn) clearBtn.classList.add('d-none');
}

document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('{{ $inputUuid }}');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                const previewBox = document.getElementById('{{ $previewUuid }}_box');
                const previewImg = document.getElementById('{{ $previewUuid }}_img');
                const clearBtn = document.getElementById('btn_clear_{{ $fieldName }}');
                const clearInput = document.getElementById('{{ $clearUuid }}');
                const badge = document.getElementById('{{ $previewUuid }}_badge');

                if (clearInput) clearInput.value = '0';
                if (previewImg) previewImg.src = URL.createObjectURL(file);
                if (previewBox) previewBox.classList.remove('d-none');
                if (clearBtn) clearBtn.classList.remove('d-none');
                if (badge) badge.textContent = 'TỆP MỚI';
            }
        });
    }
});
</script>
@endpush
@endif
