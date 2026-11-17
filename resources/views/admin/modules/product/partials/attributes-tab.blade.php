<div class="row">
    <div class="col-12">
        <div class="alert alert-info border-0 shadow-2xs mb-4 p-3 rounded" style="background-color: #f0f7ff; border-left: 4px solid #2563eb !important;">
            <div class="d-flex align-items-center">
                <i class="ri-information-line fs-18 text-primary me-2"></i>
                <div class="text-dark small">
                    <strong>Tính năng Phân loại hàng (Biến thể nội thất):</strong> Thêm các thuộc tính như <strong>Kích thước</strong> (1m6 x 2m, 1m8 x 2m) hoặc <strong>Màu sắc / Chất liệu</strong> (Gỗ Sồi, Gỗ Óc Chó). Sau đó bấm <strong>"Tạo ma trận biến thể tự động"</strong> để hệ thống tự động sinh bảng giá và tồn kho riêng cho từng phân loại.
                </div>
            </div>
        </div>

        {{-- KHỐI 1: DANH SÁCH THUỘC TÍNH --}}
        <div class="card border shadow-2xs mb-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                <h6 class="card-title mb-0 fs-14 fw-bold text-dark">
                    <i class="ri-equalizer-line me-1 text-primary"></i> 1. Danh sách Nhóm thuộc tính
                </h6>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addPresetAttribute('Kích thước', '1m6 x 2m, 1m8 x 2m, 2m x 2m2')">
                        <i class="ri-add-line"></i> + Mẫu Kích thước
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addPresetAttribute('Màu sắc', 'Gỗ Sồi Tự Nhiên, Gỗ Óc Chó, Trắng Bắc Âu')">
                        <i class="ri-add-line"></i> + Mẫu Màu sắc
                    </button>
                    <button type="button" class="btn btn-sm btn-primary" onclick="addAttributeRow()">
                        <i class="ri-add-fill"></i> Thêm thuộc tính
                    </button>
                </div>
            </div>
            <div class="card-body p-3">
                <div id="attributes-container" class="space-y-3">
                    @php
                        $existingAttributes = isset($page) && $page->attributes ? $page->attributes : collect();
                    @endphp

                    @forelse($existingAttributes as $index => $attr)
                        <div class="attribute-row card border p-3 mb-3 bg-white" data-index="{{ $index }}">
                            <input type="hidden" name="product_attributes[{{ $index }}][id]" value="{{ $attr->id }}">
                            <div class="row align-items-center g-2">
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold mb-1">Tên thuộc tính (Ví dụ: Kích thước)</label>
                                    <input type="text" class="form-control form-control-sm attr-name" 
                                           name="product_attributes[{{ $index }}][name_vn]" 
                                           value="{{ $attr->name_vn }}" placeholder="vd: Kích thước, Màu sắc" required>
                                </div>
                                <div class="col-md-8">
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
                            Chưa có thuộc tính nào. Bấm nút <strong>"+ Mẫu Kích thước"</strong> hoặc <strong>"Thêm thuộc tính"</strong> ở trên để bắt đầu.
                        </div>
                    @endforelse
                </div>

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
                                <th style="min-width: 160px;">Phân loại hàng</th>
                                <th style="min-width: 120px;">Mã SKU</th>
                                <th style="min-width: 140px;">Giá bán ({{ currency_unit() }}) <span class="text-danger">*</span></th>
                                <th style="min-width: 130px;">Giá cũ ({{ currency_unit() }})</th>
                                <th style="min-width: 90px;">Tồn kho</th>
                                <th style="min-width: 140px;">Ảnh biến thể</th>
                                <th style="width: 80px;">Bật/Tắt</th>
                                <th style="width: 50px;">Xóa</th>
                            </tr>
                        </thead>
                        <tbody id="variants-tbody">
                            @php
                                $existingVariants = isset($page) && $page->variants ? $page->variants : collect();
                            @endphp

                            @forelse($existingVariants as $vIndex => $variant)
                                @php
                                    $optValuesJson = is_array($variant->option_values) ? json_encode($variant->option_values, JSON_UNESCAPED_UNICODE) : $variant->option_values;
                                    $displayName = $variant->variant_name ?: 'Biến thể #' . ($vIndex + 1);
                                @endphp
                                <tr class="variant-row" data-index="{{ $vIndex }}">
                                    <input type="hidden" name="product_variants[{{ $vIndex }}][id]" value="{{ $variant->id }}">
                                    <input type="hidden" name="product_variants[{{ $vIndex }}][option_values]" class="variant-opt-json" value="{{ $optValuesJson }}">
                                    
                                    {{-- Mặc định --}}
                                    <td class="text-center">
                                        <input type="radio" name="default_variant_radio" value="{{ $vIndex }}" 
                                               class="form-check-input default-variant-radio" 
                                               {{ $variant->is_default ? 'checked' : '' }} 
                                               onchange="setDefaultVariant(this)">
                                        <input type="hidden" name="product_variants[{{ $vIndex }}][is_default]" class="is-default-input" value="{{ $variant->is_default ? '1' : '0' }}">
                                    </td>

                                    {{-- Tên phân loại --}}
                                    <td>
                                        <div class="fw-bold text-dark fs-13 variant-label">{{ $displayName }}</div>
                                    </td>

                                    {{-- SKU --}}
                                    <td>
                                        <input type="text" name="product_variants[{{ $vIndex }}][sku]" 
                                               class="form-control form-control-sm" 
                                               value="{{ $variant->sku }}" placeholder="SKU">
                                    </td>

                                    {{-- Giá bán --}}
                                    <td>
                                        <input type="number" name="product_variants[{{ $vIndex }}][price]" 
                                               class="form-control form-control-sm variant-price fw-bold text-primary" 
                                               value="{{ (int)$variant->price }}" placeholder="0" required>
                                    </td>

                                    {{-- Giá cũ --}}
                                    <td>
                                        <input type="number" name="product_variants[{{ $vIndex }}][price_old]" 
                                               class="form-control form-control-sm variant-price-old text-muted" 
                                               value="{{ $variant->price_old ? (int)$variant->price_old : '' }}" placeholder="0">
                                    </td>

                                    {{-- Tồn kho --}}
                                    <td>
                                        <input type="number" name="product_variants[{{ $vIndex }}][quantity]" 
                                               class="form-control form-control-sm variant-qty text-center" 
                                               value="{{ $variant->quantity ?? 50 }}" placeholder="50">
                                    </td>

                                    {{-- Ảnh biến thể --}}
                                    <td>
                                        <input type="text" name="product_variants[{{ $vIndex }}][image]" 
                                               class="form-control form-control-sm variant-image" 
                                               value="{{ $variant->image }}" placeholder="URL ảnh hoặc uploads/...">
                                    </td>

                                    {{-- Trạng thái Bật/Tắt --}}
                                    <td class="text-center">
                                        <div class="form-check form-switch d-inline-block">
                                            <input type="checkbox" name="product_variants[{{ $vIndex }}][status]" value="1" 
                                                   class="form-check-input" style="accent-color: #2563eb;" 
                                                   {{ $variant->status ? 'checked' : '' }}>
                                        </div>
                                    </td>

                                    {{-- Xóa --}}
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-link text-danger p-0" onclick="removeVariantRow(this)">
                                            <i class="ri-delete-bin-line fs-16"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr id="empty-variant-row">
                                    <td colspan="9" class="text-center py-4 text-muted small">
                                        Chưa có biến thể nào được tạo. Hãy nhập thuộc tính ở mục 1 và bấm <strong>"Tạo ma trận biến thể tự động"</strong>.
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

    function addAttributeRow(name = '', values = '') {
        const emptyMsg = document.getElementById('empty-attribute-msg');
        if (emptyMsg) emptyMsg.style.display = 'none';

        const index = attributeIndexCounter++;
        const container = document.getElementById('attributes-container');

        const html = `
            <div class="attribute-row card border p-3 mb-3 bg-white" data-index="${index}">
                <div class="row align-items-center g-2">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold mb-1">Tên thuộc tính</label>
                        <input type="text" class="form-control form-control-sm attr-name" 
                               name="product_attributes[${index}][name_vn]" 
                               value="${name}" placeholder="vd: Kích thước, Màu sắc" required>
                    </div>
                    <div class="col-md-8">
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

    function addPresetAttribute(name, values) {
        addAttributeRow(name, values);
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
            alert('Vui lòng nhập ít nhất 1 nhóm thuộc tính và các giá trị trước khi tạo ma trận biến thể!');
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
            
            // Generate SKU slug-like code
            const skuParts = displayParts.map(p => p.toLowerCase().replace(/[^a-z0-9]/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, ''));
            const sku = 'SKU-' + skuParts.join('-').toUpperCase();

            const isFirst = idx === 0;

            const tr = `
                <tr class="variant-row" data-index="${index}">
                    <input type="hidden" name="product_variants[${index}][option_values]" class="variant-opt-json" value='${jsonStr}'>
                    
                    <td class="text-center">
                        <input type="radio" name="default_variant_radio" value="${index}" 
                               class="form-check-input default-variant-radio" 
                               ${isFirst ? 'checked' : ''} 
                               onchange="setDefaultVariant(this)">
                        <input type="hidden" name="product_variants[${index}][is_default]" class="is-default-input" value="${isFirst ? '1' : '0'}">
                    </td>

                    <td>
                        <div class="fw-bold text-dark fs-13 variant-label">${displayName}</div>
                    </td>

                    <td>
                        <input type="text" name="product_variants[${index}][sku]" 
                               class="form-control form-control-sm" 
                               value="${sku}" placeholder="SKU">
                    </td>

                    <td>
                        <input type="number" name="product_variants[${index}][price]" 
                               class="form-control form-control-sm variant-price fw-bold text-primary" 
                               value="${defaultPrice}" placeholder="0" required>
                    </td>

                    <td>
                        <input type="number" name="product_variants[${index}][price_old]" 
                               class="form-control form-control-sm variant-price-old text-muted" 
                               value="${defaultOldPrice}" placeholder="0">
                    </td>

                    <td>
                        <input type="number" name="product_variants[${index}][quantity]" 
                               class="form-control form-control-sm variant-qty text-center" 
                               value="50" placeholder="50">
                    </td>

                    <td>
                        <input type="text" name="product_variants[${index}][image]" 
                               class="form-control form-control-sm variant-image" 
                               value="" placeholder="URL ảnh hoặc uploads/...">
                    </td>

                    <td class="text-center">
                        <div class="form-check form-switch d-inline-block">
                            <input type="checkbox" name="product_variants[${index}][status]" value="1" 
                                   class="form-check-input" style="accent-color: #2563eb;" checked>
                        </div>
                    </td>

                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-link text-danger p-0" onclick="removeVariantRow(this)">
                            <i class="ri-delete-bin-line fs-16"></i>
                        </button>
                    </td>
                </tr>
            `;
            tbody.insertAdjacentHTML('beforeend', tr);
        });

        updateVariantCount();
    }
</script>
