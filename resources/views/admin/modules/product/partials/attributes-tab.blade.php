@php
    $reusableAttributes = $reusableAttributes ?? [];
    $existingAttributes = isset($page) && $page->attributes ? $page->attributes : collect();
    $existingVariants = isset($page) && $page->variants ? $page->variants : collect();
@endphp

<div class="row">
    <div class="col-12">
        <div class="alert alert-info border-0 shadow-2xs mb-4 p-3 rounded" style="background-color: #f0f7ff; border-left: 4px solid #2563eb !important;">
            <div class="d-flex align-items-center">
                <i class="ri-information-line fs-18 text-primary me-2"></i>
                <div class="text-dark small">
                    <strong>Tính năng Phân loại hàng (Biến thể nội thất):</strong> Chọn thuộc tính có sẵn từ <strong>Dropdown mẫu</strong> (hoặc thêm tùy biến), sau đó bấm <strong>"Tạo ma trận biến thể tự động"</strong> để hệ thống tự động sinh bảng giá và tồn kho riêng cho từng phân loại.
                </div>
            </div>
        </div>

        {{-- KHỐI 1: DANH SÁCH THUỘC TÍNH --}}
        <div class="card border shadow-2xs mb-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center py-2 flex-wrap gap-2">
                <h6 class="card-title mb-0 fs-14 fw-bold text-dark">
                    <i class="ri-equalizer-line me-1 text-primary"></i> 1. Danh sách Nhóm thuộc tính
                </h6>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    {{-- DROPDOWN MẪU THUỘC TÍNH TÁI SỬ DỤNG --}}
                    <div class="d-flex align-items-center">
                        <select id="select-reusable-preset" class="form-select form-select-sm border-primary" style="min-width: 280px;" onchange="handleSelectPreset(this)">
                            <option value="">⚡ Chọn mẫu thuộc tính để thêm nhanh...</option>
                            @foreach($reusableAttributes as $preset)
                                <option value="{{ $preset['name_vn'] }}" data-values="{{ implode(', ', $preset['values']) }}">
                                    {{ $preset['name_vn'] }} ({{ count($preset['values']) }} giá trị)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="button" class="btn btn-sm btn-primary" onclick="addAttributeRow()">
                        <i class="ri-add-fill"></i> + Thêm thuộc tính tùy biến
                    </button>
                </div>
            </div>
            <div class="card-body p-3">
                <div id="attributes-container" class="space-y-3">
                    @forelse($existingAttributes as $index => $attr)
                        <div class="attribute-row card border p-3 mb-3 bg-white" data-index="{{ $index }}">
                            <input type="hidden" name="product_attributes[{{ $index }}][id]" value="{{ $attr->id }}">
                            <div class="row align-items-center g-2">
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold mb-1">Tên thuộc tính</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control form-control-sm attr-name" 
                                               name="product_attributes[{{ $index }}][name_vn]" 
                                               value="{{ $attr->name_vn }}" 
                                               list="reusable-attr-datalist"
                                               placeholder="vd: Kích thước, Màu sắc..." 
                                               onchange="onAttrNameChanged(this)"
                                               required>
                                        <button class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Chọn mẫu tái sử dụng"></button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm fs-13" style="max-height: 250px; overflow-y: auto;">
                                            <li><h6 class="dropdown-header text-uppercase fs-11">Thuộc tính mẫu có sẵn</h6></li>
                                            @foreach($reusableAttributes as $preset)
                                                <li>
                                                    <a class="dropdown-item py-1" href="javascript:void(0)" 
                                                       onclick="pickAttributeForRow(this, '{{ addslashes($preset['name_vn']) }}', '{{ addslashes(implode(', ', $preset['values'])) }}')">
                                                        <strong>{{ $preset['name_vn'] }}</strong>
                                                        <small class="text-muted d-block text-truncate" style="max-width: 220px;">{{ implode(', ', $preset['values']) }}</small>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <label class="form-label small fw-bold mb-1">Các giá trị lựa chọn (Cách nhau bởi dấu phẩy ",")</label>
                                    @php
                                        $valStr = is_array($attr->values_json) ? implode(', ', $attr->values_json) : $attr->values_json;
                                    @endphp
                                    <input type="text" class="form-control form-control-sm attr-values" 
                                           name="product_attributes[{{ $index }}][values]" 
                                           value="{{ $valStr }}" placeholder="vd: 1m6 x 2m, 1m8 x 2m, 2m x 2m2" required>
                                </div>
                                <div class="col-md-1 text-end pt-3">
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeAttributeRow(this)" title="Xóa thuộc tính">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div id="empty-attribute-msg" class="text-center py-4 text-muted small">
                            <i class="ri-inbox-line fs-24 d-block mb-1 text-secondary"></i>
                            Chưa có thuộc tính nào. Chọn từ dropdown <strong>"⚡ Chọn mẫu thuộc tính để thêm nhanh"</strong> ở trên hoặc bấm <strong>"Thêm thuộc tính tùy biến"</strong>.
                        </div>
                    @endforelse
                </div>

                {{-- DATALIST TỰ ĐỘNG GỢI Ý KHI GÕ TÊN THUỘC TÍNH --}}
                <datalist id="reusable-attr-datalist">
                    @foreach($reusableAttributes as $preset)
                        <option value="{{ $preset['name_vn'] }}" label="{{ implode(', ', $preset['values']) }}">
                    @endforeach
                </datalist>

                <div class="mt-3 text-end">
                    <button type="button" class="btn btn-success btn-sm px-3" onclick="generateVariantsMatrix()">
                        <i class="ri-magic-line me-1"></i> Tạo ma trận biến thể tự động
                    </button>
                </div>
            </div>
        </div>

        {{-- KHỐI 2: BẢNG MA TRẬN BIẾN THỂ (SKUs) --}}
        <div class="card border shadow-2xs">
            <div class="card-header bg-light d-flex justify-content-between align-items-center py-2 flex-wrap gap-2">
                <h6 class="card-title mb-0 fs-14 fw-bold text-dark">
                    <i class="ri-grid-line me-1 text-success"></i> 2. Bảng Biến thể & Giá phân loại (<span id="variant-count">0</span> phân loại)
                </h6>
                {{-- Áp dụng hàng loạt --}}
                <div class="d-flex align-items-center gap-2">
                    <span class="small text-muted d-none d-md-inline">Điền nhanh:</span>
                    <input type="number" id="bulk-price" class="form-control form-control-sm" placeholder="Giá bán" style="width: 110px;">
                    <input type="number" id="bulk-old-price" class="form-control form-control-sm" placeholder="Giá cũ" style="width: 100px;">
                    <input type="number" id="bulk-qty" class="form-control form-control-sm" placeholder="SL" style="width: 70px;">
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="applyBulkValues()">
                        Áp dụng tất cả
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0" id="variants-table">
                        <thead class="table-light text-center small">
                            <tr>
                                <th style="width: 50px;">Mặc định</th>
                                <th>Phiên bản / Phân loại</th>
                                <th style="width: 140px;">Mã SKU</th>
                                <th style="width: 140px;">Giá bán (đ) <span class="text-danger">*</span></th>
                                <th style="width: 130px;">Giá gốc (đ)</th>
                                <th style="width: 90px;">Kho <span class="text-danger">*</span></th>
                                <th style="width: 70px;">Bật</th>
                                <th style="width: 50px;">Xóa</th>
                            </tr>
                        </thead>
                        <tbody id="variants-tbody">
                            @forelse($existingVariants as $index => $variant)
                                @php
                                    $optVals = is_array($variant->option_values) ? $variant->option_values : (json_decode($variant->option_values, true) ?: []);
                                    $displayName = implode(' / ', array_values($optVals));
                                @endphp
                                <tr class="variant-row" data-index="{{ $index }}">
                                    <input type="hidden" name="product_variants[{{ $index }}][id]" value="{{ $variant->id }}">
                                    <input type="hidden" name="product_variants[{{ $index }}][option_values]" value="{{ json_encode($optVals) }}">

                                    <td class="text-center">
                                        <input type="radio" name="default_variant_radio" 
                                               class="form-check-input default-radio" 
                                               {{ $variant->is_default ? 'checked' : '' }} 
                                               onchange="setDefaultVariant(this)">
                                        <input type="hidden" name="product_variants[{{ $index }}][is_default]" 
                                               class="is-default-input" 
                                               value="{{ $variant->is_default ? '1' : '0' }}">
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark fs-13">{{ $displayName }}</div>
                                        <small class="text-muted">{{ json_encode($optVals, JSON_UNESCAPED_UNICODE) }}</small>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm variant-sku" 
                                               name="product_variants[{{ $index }}][sku]" 
                                               value="{{ $variant->sku }}" placeholder="SKU-{{ $index + 1 }}">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm text-end variant-price" 
                                               name="product_variants[{{ $index }}][price]" 
                                               value="{{ (int) $variant->price }}" required min="0">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm text-end variant-price-old" 
                                               name="product_variants[{{ $index }}][price_old]" 
                                               value="{{ $variant->price_old ? (int) $variant->price_old : '' }}" min="0">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm text-center variant-qty" 
                                               name="product_variants[{{ $index }}][quantity]" 
                                               value="{{ $variant->quantity ?? 50 }}" min="0">
                                    </td>
                                    <td class="text-center">
                                        <input type="hidden" name="product_variants[{{ $index }}][status]" value="0">
                                        <input type="checkbox" name="product_variants[{{ $index }}][status]" 
                                               class="form-check-input" value="1" {{ $variant->status ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-link text-danger p-0" onclick="removeVariantRow(this)">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr id="empty-variant-row">
                                    <td colspan="8" class="text-center py-4 text-muted small">
                                        Chưa có phiên bản phân loại nào. Hãy chọn/nhập thuộc tính ở trên rồi bấm <strong>"Tạo ma trận biến thể tự động"</strong>.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- JAVASCRIPT XỬ LÝ MA TRẬN BIẾN THỂ THÔNG MINH --}}
<script>
    let attributeIndexCounter = {{ count($existingAttributes) }};
    let variantIndexCounter = {{ count($existingVariants) }};
    const reusablePresets = @json($reusableAttributes);

    function updateVariantCount() {
        const count = document.querySelectorAll('#variants-tbody .variant-row').length;
        document.getElementById('variant-count').innerText = count;
        const emptyRow = document.getElementById('empty-variant-row');
        if (emptyRow) {
            emptyRow.style.display = count === 0 ? '' : 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateVariantCount();
    });

    function getPresetDropdownHtml() {
        let itemsHtml = '';
        if (Array.isArray(reusablePresets)) {
            reusablePresets.forEach(preset => {
                const vals = Array.isArray(preset.values) ? preset.values.join(', ') : preset.values;
                const safeName = preset.name_vn.replace(/'/g, "\\'");
                const safeVals = vals.replace(/'/g, "\\'");
                itemsHtml += `
                    <li>
                        <a class="dropdown-item py-1" href="javascript:void(0)" 
                           onclick="pickAttributeForRow(this, '${safeName}', '${safeVals}')">
                            <strong>${preset.name_vn}</strong>
                            <small class="text-muted d-block text-truncate" style="max-width: 220px;">${vals}</small>
                        </a>
                    </li>
                `;
            });
        }
        return itemsHtml;
    }

    function addAttributeRow(name = '', values = '') {
        const emptyMsg = document.getElementById('empty-attribute-msg');
        if (emptyMsg) emptyMsg.style.display = 'none';

        const index = attributeIndexCounter++;
        const container = document.getElementById('attributes-container');

        const html = `
            <div class="attribute-row card border p-3 mb-3 bg-white" data-index="${index}">
                <div class="row align-items-center g-2">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold mb-1">Tên thuộc tính</label>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control form-control-sm attr-name" 
                                   name="product_attributes[${index}][name_vn]" 
                                   value="${name}" 
                                   list="reusable-attr-datalist"
                                   placeholder="vd: Kích thước, Màu sắc..." 
                                   onchange="onAttrNameChanged(this)"
                                   required>
                            <button class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Chọn mẫu tái sử dụng"></button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm fs-13" style="max-height: 250px; overflow-y: auto;">
                                <li><h6 class="dropdown-header text-uppercase fs-11">Thuộc tính mẫu có sẵn</h6></li>
                                ${getPresetDropdownHtml()}
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <label class="form-label small fw-bold mb-1">Các giá trị lựa chọn (Cách nhau bởi dấu phẩy ",")</label>
                        <input type="text" class="form-control form-control-sm attr-values" 
                               name="product_attributes[${index}][values]" 
                               value="${values}" placeholder="vd: 1m6 x 2m, 1m8 x 2m, 2m x 2m2" required>
                    </div>
                    <div class="col-md-1 text-end pt-3">
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeAttributeRow(this)" title="Xóa thuộc tính">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    }

    function handleSelectPreset(select) {
        const selectedOption = select.options[select.selectedIndex];
        if (!selectedOption || !selectedOption.value) return;

        const name = selectedOption.value;
        const values = selectedOption.getAttribute('data-values') || '';

        // Kiểm tra xem thuộc tính đã có trong danh sách chưa
        const existingNames = Array.from(document.querySelectorAll('#attributes-container .attr-name'))
                                   .map(input => input.value.trim().toLowerCase());
        
        if (existingNames.includes(name.toLowerCase())) {
            alert(`Thuộc tính "${name}" đã có trong danh sách nhóm thuộc tính!`);
            select.value = '';
            return;
        }

        addAttributeRow(name, values);
        select.value = ''; // Reset select
    }

    function pickAttributeForRow(element, name, values) {
        const row = element.closest('.attribute-row');
        if (!row) return;

        const nameInput = row.querySelector('.attr-name');
        const valuesInput = row.querySelector('.attr-values');

        if (nameInput) nameInput.value = name;
        if (valuesInput && (!valuesInput.value.trim() || confirm('Bạn có muốn điền các giá trị mẫu cho thuộc tính này không?'))) {
            valuesInput.value = values;
        }
    }

    function onAttrNameChanged(input) {
        const name = input.value.trim().toLowerCase();
        if (!name) return;

        const matched = reusablePresets.find(p => p.name_vn.toLowerCase() === name);
        if (matched) {
            const row = input.closest('.attribute-row');
            if (row) {
                const valuesInput = row.querySelector('.attr-values');
                if (valuesInput && !valuesInput.value.trim()) {
                    const vals = Array.isArray(matched.values) ? matched.values.join(', ') : matched.values;
                    valuesInput.value = vals;
                }
            }
        }
    }

    function removeAttributeRow(btn) {
        const row = btn.closest('.attribute-row');
        if (row) {
            row.remove();
            const rows = document.querySelectorAll('#attributes-container .attribute-row');
            if (rows.length === 0) {
                const emptyMsg = document.getElementById('empty-attribute-msg');
                if (emptyMsg) emptyMsg.style.display = '';
            }
        }
    }

    function removeVariantRow(btn) {
        const row = btn.closest('.variant-row');
        if (row) {
            row.remove();
            updateVariantCount();
        }
    }

    function setDefaultVariant(radio) {
        document.querySelectorAll('.is-default-input').forEach(input => input.value = '0');
        const row = radio.closest('.variant-row');
        if (row) {
            const input = row.querySelector('.is-default-input');
            if (input) input.value = '1';
        }
    }

    function applyBulkValues() {
        const price = document.getElementById('bulk-price').value;
        const oldPrice = document.getElementById('bulk-old-price').value;
        const qty = document.getElementById('bulk-qty').value;

        document.querySelectorAll('#variants-tbody .variant-row').forEach(row => {
            if (price) row.querySelector('.variant-price').value = price;
            if (oldPrice) row.querySelector('.variant-price-old').value = oldPrice;
            if (qty) row.querySelector('.variant-qty').value = qty;
        });
    }

    function generateVariantsMatrix() {
        const attributeRows = document.querySelectorAll('#attributes-container .attribute-row');
        const attrGroups = [];

        attributeRows.forEach(row => {
            const name = row.querySelector('.attr-name').value.trim();
            const valuesStr = row.querySelector('.attr-values').value.trim();
            if (name && valuesStr) {
                const values = valuesStr.split(',').map(v => v.trim()).filter(v => v.length > 0);
                if (values.length > 0) {
                    attrGroups.push({ name: name, values: values });
                }
            }
        });

        if (attrGroups.length === 0) {
            alert('Vui lòng chọn hoặc nhập ít nhất 1 nhóm thuộc tính và các giá trị trước khi tạo ma trận biến thể!');
            return;
        }

        // Tích Descartes để sinh tất cả các tổ hợp
        function cartesian(groups) {
            return groups.reduce((acc, curr) => {
                return acc.flatMap(a => curr.values.map(b => ({ ...a, [curr.name]: b })));
            }, [{}]);
        }

        const combinations = cartesian(attrGroups);
        const tbody = document.getElementById('variants-tbody');
        tbody.innerHTML = '';

        const defaultPrice = document.querySelector('input[name="price"]')?.value || '0';
        const defaultOldPrice = document.querySelector('input[name="price_old"]')?.value || '';

        combinations.forEach((combo, idx) => {
            const index = variantIndexCounter++;
            const displayParts = Object.values(combo);
            const displayName = displayParts.join(' / ');
            const jsonStr = JSON.stringify(combo);
            const isFirst = idx === 0;

            const tr = document.createElement('tr');
            tr.className = 'variant-row';
            tr.setAttribute('data-index', index);
            tr.innerHTML = `
                <input type="hidden" name="product_variants[${index}][option_values]" value='${jsonStr}'>
                <td class="text-center">
                    <input type="radio" name="default_variant_radio" 
                           class="form-check-input default-radio" 
                           ${isFirst ? 'checked' : ''} 
                           onchange="setDefaultVariant(this)">
                    <input type="hidden" name="product_variants[${index}][is_default]" 
                           class="is-default-input" 
                           value="${isFirst ? '1' : '0'}">
                </td>
                <td>
                    <div class="fw-semibold text-dark fs-13">${displayName}</div>
                    <small class="text-muted">${jsonStr}</small>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm variant-sku" 
                           name="product_variants[${index}][sku]" 
                           placeholder="SKU-${idx + 1}">
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm text-end variant-price" 
                           name="product_variants[${index}][price]" 
                           value="${defaultPrice}" required min="0">
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm text-end variant-price-old" 
                           name="product_variants[${index}][price_old]" 
                           value="${defaultOldPrice}" min="0">
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm text-center variant-qty" 
                           name="product_variants[${index}][quantity]" 
                           value="50" min="0">
                </td>
                <td class="text-center">
                    <input type="hidden" name="product_variants[${index}][status]" value="0">
                    <input type="checkbox" name="product_variants[${index}][status]" 
                           class="form-check-input" value="1" checked>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-link text-danger p-0" onclick="removeVariantRow(this)">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });

        updateVariantCount();
    }
</script>
