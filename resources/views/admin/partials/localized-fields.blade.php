@props(['fields' => [], 'model' => $page ?? null])

@php
    // Đọc active languages từ global systemConfig
    $activeLocales = isset($systemConfig) ? ($systemConfig->active_languages ?? ['vi', 'en']) : ['vi', 'en'];
    $languages = [];
    if (in_array('vi', $activeLocales)) {
        $languages['vn'] = [
            'label' => 'Tiếng Việt',
            'flag' => 'vietnam.png'
        ];
    }
    if (in_array('en', $activeLocales)) {
        $languages['en'] = [
            'label' => 'Tiếng Anh (EN)',
            'flag' => 'usa.png'
        ];
    }
    
    // Tạo unique ID cho tab tránh xung đột nếu nhúng nhiều lần trên cùng 1 trang
    $tabSuffix = uniqid();

    // Xác định tab nào chứa lỗi để tự động active tab đó
    $tabErrors = [];
    $firstErrorLocale = null;
    foreach ($languages as $locale => $info) {
        $tabErrors[$locale] = false;
        foreach ($fields as $field) {
            $checkName = $field['base'] . '_' . $locale;
            if ($errors->has($checkName)) {
                $tabErrors[$locale] = true;
                if ($firstErrorLocale === null) {
                    $firstErrorLocale = $locale;
                }
            }
        }
    }
    // Nếu không tab nào có lỗi, active tab đầu tiên
    $activeLocale = $firstErrorLocale ?? array_key_first($languages);
@endphp

<!-- Nav tabs -->
<ul class="nav nav-tabs nav-tabs-custom nav-success mb-3" role="tablist">
    @foreach ($languages as $locale => $info)
        <li class="nav-item">
            <a class="nav-link {{ $locale === $activeLocale ? 'active' : '' }}" 
               data-bs-toggle="tab" 
               href="#locale-{{ $locale }}-{{ $tabSuffix }}" 
               role="tab">
                <img src="{{ asset('uploads/icon/' . $info['flag']) }}" 
                     alt="{{ $locale }}" 
                     class="me-1 align-middle" 
                     style="width: 18px; height: 12px; object-fit: cover; border-radius: 2px; margin-top: -2px;">
                {{ $info['label'] }}
                @if (!empty($tabErrors[$locale]))
                    <span class="badge bg-danger ms-1">Lỗi</span>
                @endif
            </a>
        </li>
    @endforeach
</ul>

<!-- Tab panes -->
<div class="tab-content tab-content-localized">
    @foreach ($languages as $locale => $info)
        <div class="tab-pane {{ $locale === $activeLocale ? 'active' : '' }}" 
             id="locale-{{ $locale }}-{{ $tabSuffix }}" 
             role="tabpanel">
            <div class="row">
                @foreach ($fields as $field)
                    @php
                        $type = $field['type'] ?? 'text';
                        $base = $field['base'];
                        $label = $field['label'] ?? ucfirst($base);
                        $rows = $field['rows'] ?? 6;
                        $col = $field['col'] ?? 'col-12';
                        $useEditor = $field['ckeditor'] ?? false;
                        
                        $name = $base.'_'.$locale;
                        $id = ($useEditor) ? ($base.'-'.$locale) : null; 
                        $value = old($name, data_get($model, $name, ''));
                    @endphp

                    <div class="{{ $col }} mb-3">
                        <label for="{{ $id ?? $name }}" class="form-label font-weight-semibold">
                            {{ $label }} <span class="text-muted text-xs">({{ strtoupper($locale) }})</span>
                        </label>
                        
                        @if ($type === 'textarea')
                            <textarea
                                @if($id) id="{{ $id }}" data-ckeditor="true" @endif
                                class="form-control @error($name) is-invalid @enderror"
                                name="{{ $name }}"
                                rows="{{ $rows }}"
                                placeholder="Nhập {{ strtolower($label) }}">{{ $value }}</textarea>
                        @else
                            <input type="text"
                                @if($id) id="{{ $id }}" @endif
                                class="form-control @error($name) is-invalid @enderror"
                                name="{{ $name }}"
                                value="{{ $value }}"
                                placeholder="Nhập {{ strtolower($label) }}">
                        @endif
                        
                        @error($name)
                            <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
                        @enderror
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
