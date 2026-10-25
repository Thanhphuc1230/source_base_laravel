<!-- Media Picker Modal (Modern Enterprise Standard) -->
<div class="modal fade" id="mediaPickerModal" tabindex="-1" aria-labelledby="mediaPickerModalLabel" aria-hidden="true" style="z-index: 1065;">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" style="max-width: 1140px; height: 90vh;">
        <div class="modal-content border-0 shadow-lg" style="height: 100%; border-radius: 12px; overflow: hidden;">
            <!-- Modal Header -->
            <div class="modal-header bg-white border-bottom py-2.5 px-4 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-2" style="width: 32px; height: 32px;">
                        <i class="ri-folder-image-line fs-16"></i>
                    </span>
                    <div>
                        <h6 class="modal-title fw-bold text-dark mb-0" id="mediaPickerModalLabel">Thư viện Media</h6>
                        <small class="text-muted text-xs" id="media-modal-subtitle">Chọn hoặc tải lên hình ảnh / video</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Toolbar Controls (Rule 13: 38px height) -->
            <div class="bg-light border-bottom px-4 py-2.5">
                <div class="row g-2 align-items-center">
                    <!-- Search Input -->
                    <div class="col-12 col-md-4">
                        <div class="position-relative">
                            <input type="text" id="media-search-input" class="form-control form-control-sm ps-4 rounded-2" style="height: 38px;" placeholder="Tìm tên tệp...">
                            <i class="ri-search-line position-absolute top-50 start-0 translate-middle-y ms-2.5 text-muted"></i>
                        </div>
                    </div>

                    <!-- Module Filter -->
                    <div class="col-6 col-md-3">
                        <select id="media-module-select" class="form-select form-select-sm rounded-2" style="height: 38px;">
                            <option value="">Tất cả thư mục</option>
                        </select>
                    </div>

                    <!-- Type Filter -->
                    <div class="col-6 col-md-2">
                        <select id="media-type-select" class="form-select form-select-sm rounded-2" style="height: 38px;">
                            <option value="">Tất cả định dạng</option>
                            <option value="image">Hình ảnh</option>
                            <option value="video">Video clip</option>
                        </select>
                    </div>

                    <!-- Upload Action Button -->
                    <div class="col-12 col-md-3 text-md-end">
                        <input type="file" id="media-modal-upload-input" multiple accept="image/*,video/mp4,video/webm,video/quicktime" class="d-none">
                        <button type="button" class="btn btn-primary btn-sm rounded-2 px-3 w-100 w-md-auto d-inline-flex align-items-center justify-content-center gap-1 shadow-xs" style="height: 38px;" onclick="document.getElementById('media-modal-upload-input').click();">
                            <i class="ri-upload-cloud-2-line fs-15"></i>
                            <span class="fw-semibold">Tải lên tệp mới</span>
                        </button>
                    </div>
                </div>

                <!-- Upload Progress Bar (hidden by default) -->
                <div id="media-upload-progress-container" class="mt-2 d-none">
                    <div class="progress" style="height: 6px;">
                        <div id="media-upload-progress-bar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 0%;"></div>
                    </div>
                    <small id="media-upload-status-text" class="text-primary text-xs mt-1 d-block fw-semibold">Đang tải lên và tối ưu WebP...</small>
                </div>
            </div>

            <!-- Modal Body: Main Grid & Inspector -->
            <div class="modal-body p-0 position-relative" style="flex: 1; overflow-y: auto;">
                <div class="row g-0 h-100">
                    <!-- Media Items Grid Area -->
                    <div class="col-12 col-lg-8 col-xl-9 p-3 border-end h-100" style="overflow-y: auto;" id="media-grid-scroll-container">
                        <!-- Dropzone indicator banner -->
                        <div id="media-drop-zone-overlay" class="border border-2 border-dashed rounded-3 p-3 text-center mb-3 bg-light text-muted transition-all" style="border-color: #cbd5e1 !important; cursor: pointer;" onclick="document.getElementById('media-modal-upload-input').click();">
                            <i class="ri-drag-drop-line fs-20 align-middle me-1 text-primary"></i>
                            <span class="text-xs fw-semibold">Kéo & thả file vào đây để tự động nén WebP và lưu vào thư viện</span>
                        </div>

                        <!-- Grid -->
                        <div class="row g-2" id="media-items-grid"></div>

                        <!-- Loading Spinner -->
                        <div id="media-loading-indicator" class="text-center py-4 d-none">
                            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                            <span class="text-muted text-xs ms-2">Đang tải tệp tin...</span>
                        </div>

                        <!-- Empty State -->
                        <div id="media-empty-state" class="text-center py-5 d-none">
                            <i class="ri-image-line text-muted display-6 mb-2"></i>
                            <p class="text-muted text-sm mb-0">Chưa có tệp tin nào phù hợp</p>
                        </div>

                        <!-- Load More Button -->
                        <div id="media-load-more-container" class="text-center mt-3 pb-3 d-none">
                            <button type="button" id="media-load-more-btn" class="btn btn-outline-secondary btn-sm rounded-pill px-4">
                                <i class="ri-more-line me-1"></i> Xem thêm
                            </button>
                        </div>
                    </div>

                    <!-- Media Inspector Sidebar -->
                    <div class="col-12 col-lg-4 col-xl-3 bg-light p-3 d-flex flex-column justify-content-between h-100" style="overflow-y: auto;">
                        <div id="media-inspector-content">
                            <h6 class="fw-bold text-dark text-xs text-uppercase mb-2 text-muted">Chi tiết tệp tin</h6>
                            <div id="media-inspector-empty" class="text-center py-5 text-muted">
                                <i class="ri-file-info-line fs-24 mb-1 d-block text-secondary"></i>
                                <span class="text-xs">Nhấp vào một tệp tin để xem chi tiết và kích thước</span>
                            </div>

                            <div id="media-inspector-details" class="d-none">
                                <div class="bg-white border rounded-2 p-2 mb-3 text-center position-relative">
                                    <img id="inspector-preview-img" src="" alt="preview" class="img-fluid rounded-1" style="max-height: 180px; object-fit: contain;">
                                    <video id="inspector-preview-video" src="" controls class="w-100 rounded-1 d-none" style="max-height: 180px;"></video>
                                    <span id="inspector-badge-ext" class="badge bg-dark position-absolute top-0 end-0 m-2 text-uppercase">WEBP</span>
                                </div>
                                <table class="table table-sm table-borderless text-xs mb-3">
                                    <tbody>
                                        <tr>
                                            <td class="text-muted ps-0" style="width: 80px;">Tên file:</td>
                                            <td class="fw-semibold text-break" id="inspector-file-name">-</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted ps-0">Kích thước:</td>
                                            <td class="fw-semibold text-primary" id="inspector-dimensions">-</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted ps-0">Dung lượng:</td>
                                            <td class="fw-semibold" id="inspector-size">-</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted ps-0">Thư mục:</td>
                                            <td><span class="badge bg-secondary-subtle text-secondary" id="inspector-module">-</span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted ps-0">Ngày tạo:</td>
                                            <td class="text-muted" id="inspector-date">-</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="mb-2">
                                    <label class="form-label text-xs text-muted mb-1">Đường dẫn tệp (Relative Path):</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" id="inspector-path-input" readonly class="form-control form-control-sm bg-white" value="">
                                        <button class="btn btn-outline-secondary" type="button" id="inspector-copy-btn" title="Sao chép">
                                            <i class="ri-file-copy-line"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="mt-3 pt-2 border-top text-end">
                                    <button type="button" id="inspector-delete-btn" class="btn btn-outline-danger btn-sm text-xs rounded-1">
                                        <i class="ri-delete-bin-line me-1"></i> Xóa khỏi thư viện
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Selected Counter Summary -->
                        <div class="pt-2 border-top mt-auto">
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="text-xs fw-semibold text-dark">Đã chọn: <span id="media-selected-count" class="badge bg-primary rounded-pill">0</span></span>
                                <button type="button" id="media-clear-selection-btn" class="btn btn-link btn-sm text-xs text-muted p-0 text-decoration-none d-none">Bỏ chọn</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer (Controls 38px) -->
            <div class="modal-footer bg-white border-top py-2.5 px-4 d-flex justify-content-between align-items-center">
                <span class="text-muted text-xs">
                    <i class="ri-information-line me-1"></i>Hỗ trợ WebP, JPG, PNG, GIF, SVG, MP4, WebM (tự động tối ưu WebP chuẩn)
                </span>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-light btn-sm rounded-2 px-3" style="height: 38px;" data-bs-dismiss="modal">Hủy bỏ</button>
                    <button type="button" id="media-confirm-btn" class="btn btn-primary btn-sm rounded-2 px-4 d-inline-flex align-items-center gap-1 shadow-xs" style="height: 38px;" disabled>
                        <i class="ri-check-line fs-16"></i>
                        <span class="fw-semibold">Sử dụng tệp đã chọn</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.media-card-item {
    cursor: pointer;
    border: 2px solid transparent;
    border-radius: 8px;
    background: #ffffff;
    transition: all 0.2s ease-in-out;
    position: relative;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06);
}
.media-card-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    border-color: #cbd5e1;
}
.media-card-item.selected {
    border-color: #2563eb !important;
    background-color: #eff6ff !important;
}
.media-card-item .selection-badge {
    position: absolute;
    top: 6px;
    right: 6px;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: rgba(255,255,255,0.9);
    color: #cbd5e1;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    transition: all 0.15s ease;
    z-index: 2;
    border: 1px solid #e2e8f0;
}
.media-card-item.selected .selection-badge {
    background: #2563eb;
    color: #ffffff;
    border-color: #2563eb;
}
.media-card-item .media-thumb-box {
    width: 100%;
    height: 105px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8fafc;
    overflow: hidden;
}
.media-card-item .media-thumb-box img {
    max-width: 100%;
    max-height: 100%;
    object-fit: cover;
    width: 100%;
    height: 100%;
}
.media-card-item .media-info-box {
    padding: 6px 8px;
    border-top: 1px solid #f1f5f9;
}
.media-card-item .media-title {
    font-size: 11px;
    font-weight: 600;
    color: #334155;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 2px;
}
.media-card-item .media-meta {
    font-size: 10px;
    color: #64748b;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
</style>

<script>
window.MediaPicker = (function() {
    let options = {
        mode: 'single', // 'single' or 'multiple'
        module: 'all',
        onSelect: null
    };

    let state = {
        page: 1,
        lastPage: 1,
        isLoading: false,
        items: [],
        selectedItems: new Map(), // id => item
        filters: {
            search: '',
            module: '',
            type: ''
        }
    };

    let modalInstance = null;
    let searchDebounceTimer = null;

    function init() {
        const modalEl = document.getElementById('mediaPickerModal');
        if (!modalEl) return;

        if (window.bootstrap && window.bootstrap.Modal) {
            modalInstance = new bootstrap.Modal(modalEl);
        }

        // Search typing listener with debounce
        const searchInput = document.getElementById('media-search-input');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchDebounceTimer);
                searchDebounceTimer = setTimeout(() => {
                    state.filters.search = this.value.trim();
                    resetAndFetch();
                }, 300);
            });
        }

        // Module filter change
        const moduleSelect = document.getElementById('media-module-select');
        if (moduleSelect) {
            moduleSelect.addEventListener('change', function() {
                state.filters.module = this.value;
                resetAndFetch();
            });
        }

        // Type filter change
        const typeSelect = document.getElementById('media-type-select');
        if (typeSelect) {
            typeSelect.addEventListener('change', function() {
                state.filters.type = this.value;
                resetAndFetch();
            });
        }

        // Load more button
        const loadMoreBtn = document.getElementById('media-load-more-btn');
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', function() {
                if (state.page < state.lastPage && !state.isLoading) {
                    state.page++;
                    fetchMedia(true);
                }
            });
        }

        // Upload input change
        const uploadInput = document.getElementById('media-modal-upload-input');
        if (uploadInput) {
            uploadInput.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    handleUploadFiles(this.files);
                }
            });
        }

        // Drag & drop on overlay
        const dropZone = document.getElementById('media-drop-zone-overlay');
        if (dropZone) {
            ['dragenter', 'dragover'].forEach(name => {
                dropZone.addEventListener(name, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropZone.style.borderColor = '#2563eb';
                    dropZone.style.backgroundColor = '#eff6ff';
                });
            });
            ['dragleave', 'drop'].forEach(name => {
                dropZone.addEventListener(name, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropZone.style.borderColor = '#cbd5e1';
                    dropZone.style.backgroundColor = '';
                });
            });
            dropZone.addEventListener('drop', (e) => {
                if (e.dataTransfer && e.dataTransfer.files.length > 0) {
                    handleUploadFiles(e.dataTransfer.files);
                }
            });
        }

        // Confirm Selection button
        const confirmBtn = document.getElementById('media-confirm-btn');
        if (confirmBtn) {
            confirmBtn.addEventListener('click', function() {
                const selected = Array.from(state.selectedItems.values());
                if (selected.length === 0) return;

                if (typeof options.onSelect === 'function') {
                    options.onSelect(options.mode === 'single' ? selected[0] : selected);
                }

                if (modalInstance) {
                    modalInstance.hide();
                }
            });
        }

        // Clear selection button
        const clearBtn = document.getElementById('media-clear-selection-btn');
        if (clearBtn) {
            clearBtn.addEventListener('click', function() {
                state.selectedItems.clear();
                updateSelectionUI();
                renderItems();
            });
        }

        // Copy path button
        const copyBtn = document.getElementById('inspector-copy-btn');
        if (copyBtn) {
            copyBtn.addEventListener('click', function() {
                const input = document.getElementById('inspector-path-input');
                if (input && input.value) {
                    navigator.clipboard.writeText(input.value).then(() => {
                        alert('Đã sao chép đường dẫn ảnh!');
                    });
                }
            });
        }

        // Delete button
        const deleteBtn = document.getElementById('inspector-delete-btn');
        if (deleteBtn) {
            deleteBtn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                if (!id) return;
                if (!confirm('Bạn có chắc muốn xóa tệp này khỏi thư viện?')) return;

                fetch(`{{ url('admin/media') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        state.selectedItems.delete(parseInt(id));
                        resetAndFetch();
                        resetInspector();
                    } else {
                        alert(data.message || 'Lỗi khi xóa tệp');
                    }
                })
                .catch(err => alert('Lỗi kết nối khi xóa tệp'));
            });
        }

        // Fetch distinct module list once
        fetchModules();
    }

    function open(userOptions) {
        options = Object.assign({
            mode: 'single',
            module: 'all',
            onSelect: null
        }, userOptions);

        state.selectedItems.clear();
        updateSelectionUI();
        resetInspector();

        const subtitle = document.getElementById('media-modal-subtitle');
        if (subtitle) {
            subtitle.textContent = options.mode === 'single' 
                ? 'Chọn 1 tệp tin làm hình đại diện hoặc biểu tượng'
                : 'Chọn nhiều tệp tin cùng lúc (có thể chọn hàng loạt)';
        }

        if (options.module && options.module !== 'all') {
            state.filters.module = options.module;
            const moduleSelect = document.getElementById('media-module-select');
            if (moduleSelect) moduleSelect.value = options.module;
        }

        if (modalInstance) {
            modalInstance.show();
        }

        resetAndFetch();
    }

    function resetAndFetch() {
        state.page = 1;
        state.items = [];
        fetchMedia(false);
    }

    function fetchMedia(append = false) {
        state.isLoading = true;
        const spinner = document.getElementById('media-loading-indicator');
        const emptyState = document.getElementById('media-empty-state');
        const loadMoreBox = document.getElementById('media-load-more-container');

        if (spinner) spinner.classList.remove('d-none');
        if (emptyState) emptyState.classList.add('d-none');

        const params = new URLSearchParams({
            page: state.page,
            search: state.filters.search,
            module: state.filters.module,
            type: state.filters.type,
            per_page: 24
        });

        fetch(`{{ route('admin.media.list') }}?${params.toString()}`, {
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(res => {
            state.isLoading = false;
            if (spinner) spinner.classList.add('d-none');

            if (res.success) {
                state.lastPage = res.last_page;
                if (append) {
                    state.items = state.items.concat(res.data);
                } else {
                    state.items = res.data;
                }

                renderItems();

                if (state.items.length === 0) {
                    if (emptyState) emptyState.classList.remove('d-none');
                }

                if (loadMoreBox) {
                    if (res.has_more) {
                        loadMoreBox.classList.remove('d-none');
                    } else {
                        loadMoreBox.classList.add('d-none');
                    }
                }
            }
        })
        .catch(err => {
            state.isLoading = false;
            if (spinner) spinner.classList.add('d-none');
            console.error('Fetch media error:', err);
        });
    }

    function fetchModules() {
        fetch(`{{ route('admin.media.modules') }}`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(res => {
            if (res.success && res.modules) {
                const select = document.getElementById('media-module-select');
                if (select) {
                    let html = '<option value="">Tất cả thư mục</option>';
                    res.modules.forEach(mod => {
                        html += `<option value="${mod}">${mod.toUpperCase()}</option>`;
                    });
                    select.innerHTML = html;
                }
            }
        })
        .catch(e => console.error(e));
    }

    function renderItems() {
        const grid = document.getElementById('media-items-grid');
        if (!grid) return;

        grid.innerHTML = '';

        state.items.forEach(item => {
            const isSelected = state.selectedItems.has(item.id);
            const isVid = item.is_video || /\.(mp4|webm|mov|ogg)$/i.test(item.extension);

            const col = document.createElement('div');
            col.className = 'col-6 col-sm-4 col-md-3 col-xl-2';

            const card = document.createElement('div');
            card.className = `media-card-item ${isSelected ? 'selected' : ''}`;
            card.setAttribute('data-id', item.id);

            let thumbHtml = '';
            if (isVid) {
                thumbHtml = `
                    <div class="media-thumb-box position-relative">
                        <video src="${item.url}#t=0.5" preload="metadata" muted playsinline style="width: 100%; height: 100%; object-fit: cover;"></video>
                        <i class="ri-play-circle-fill position-absolute text-white" style="font-size: 26px; text-shadow: 0 1px 3px rgba(0,0,0,0.6);"></i>
                    </div>
                `;
            } else {
                thumbHtml = `
                    <div class="media-thumb-box">
                        <img src="${item.url}" alt="${item.name}" loading="lazy">
                    </div>
                `;
            }

            card.innerHTML = `
                <div class="selection-badge">
                    <i class="ri-check-line"></i>
                </div>
                ${thumbHtml}
                <div class="media-info-box">
                    <div class="media-title" title="${item.name}">${item.name}</div>
                    <div class="media-meta">
                        <span>${item.formatted_size || ''}</span>
                        <span class="badge bg-light text-secondary border px-1">${item.extension.toUpperCase()}</span>
                    </div>
                </div>
            `;

            card.addEventListener('click', function(e) {
                handleItemClick(item);
            });

            col.appendChild(card);
            grid.appendChild(col);
        });
    }

    function handleItemClick(item) {
        if (options.mode === 'single') {
            state.selectedItems.clear();
            state.selectedItems.set(item.id, item);
        } else {
            if (state.selectedItems.has(item.id)) {
                state.selectedItems.delete(item.id);
            } else {
                state.selectedItems.set(item.id, item);
            }
        }

        updateSelectionUI();
        renderItems();
        showInspector(item);
    }

    function updateSelectionUI() {
        const count = state.selectedItems.size;
        const countBadge = document.getElementById('media-selected-count');
        const confirmBtn = document.getElementById('media-confirm-btn');
        const clearBtn = document.getElementById('media-clear-selection-btn');

        if (countBadge) countBadge.textContent = count;
        if (confirmBtn) confirmBtn.disabled = count === 0;

        if (clearBtn) {
            if (count > 0) {
                clearBtn.classList.remove('d-none');
            } else {
                clearBtn.classList.add('d-none');
            }
        }
    }

    function showInspector(item) {
        const emptyBox = document.getElementById('media-inspector-empty');
        const detailsBox = document.getElementById('media-inspector-details');
        if (emptyBox) emptyBox.classList.add('d-none');
        if (detailsBox) detailsBox.classList.remove('d-none');

        const isVid = item.is_video || /\.(mp4|webm|mov|ogg)$/i.test(item.extension);
        const imgPreview = document.getElementById('inspector-preview-img');
        const vidPreview = document.getElementById('inspector-preview-video');
        const badgeExt = document.getElementById('inspector-badge-ext');

        if (isVid) {
            if (imgPreview) imgPreview.classList.add('d-none');
            if (vidPreview) {
                vidPreview.classList.remove('d-none');
                vidPreview.src = item.url;
            }
        } else {
            if (vidPreview) {
                vidPreview.classList.add('d-none');
                vidPreview.src = '';
            }
            if (imgPreview) {
                imgPreview.classList.remove('d-none');
                imgPreview.src = item.url;
            }
        }

        if (badgeExt) badgeExt.textContent = (item.extension || '').toUpperCase();

        document.getElementById('inspector-file-name').textContent = item.name + '.' + item.extension;
        document.getElementById('inspector-dimensions').textContent = item.dimensions || 'N/A';
        document.getElementById('inspector-size').textContent = item.formatted_size || '';
        document.getElementById('inspector-module').textContent = (item.module || 'general').toUpperCase();
        document.getElementById('inspector-date').textContent = item.created_at ? new Date(item.created_at).toLocaleDateString('vi-VN') : '-';
        document.getElementById('inspector-path-input').value = item.path;

        const delBtn = document.getElementById('inspector-delete-btn');
        if (delBtn) delBtn.setAttribute('data-id', item.id);
    }

    function resetInspector() {
        const emptyBox = document.getElementById('media-inspector-empty');
        const detailsBox = document.getElementById('media-inspector-details');
        if (emptyBox) emptyBox.classList.remove('d-none');
        if (detailsBox) detailsBox.classList.add('d-none');
    }

    function handleUploadFiles(fileList) {
        const progressContainer = document.getElementById('media-upload-progress-container');
        const progressBar = document.getElementById('media-upload-progress-bar');
        const statusText = document.getElementById('media-upload-status-text');

        if (progressContainer) progressContainer.classList.remove('d-none');
        if (progressBar) progressBar.style.width = '30%';
        if (statusText) statusText.textContent = `Đang tải lên ${fileList.length} tệp tin và nén WebP...`;

        const formData = new FormData();
        Array.from(fileList).forEach(file => {
            formData.append('files[]', file);
        });
        formData.append('module', state.filters.module || options.module || 'general');

        fetch(`{{ route('admin.media.upload') }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(res => res.json())
        .then(res => {
            if (progressBar) progressBar.style.width = '100%';

            setTimeout(() => {
                if (progressContainer) progressContainer.classList.add('d-none');
                if (progressBar) progressBar.style.width = '0%';
            }, 600);

            if (res.success && res.files) {
                // If single mode, select the newly uploaded file
                if (options.mode === 'single' && res.files.length > 0) {
                    state.selectedItems.clear();
                    state.selectedItems.set(res.files[0].id, res.files[0]);
                    showInspector(res.files[0]);
                } else if (options.mode === 'multiple') {
                    res.files.forEach(f => state.selectedItems.set(f.id, f));
                }
                updateSelectionUI();
                resetAndFetch();
            } else {
                alert(res.message || 'Lỗi khi tải file lên');
            }
        })
        .catch(err => {
            if (progressContainer) progressContainer.classList.add('d-none');
            alert('Lỗi kết nối khi tải file');
            console.error(err);
        });
    }

    // Initialize on DOM ready
    document.addEventListener('DOMContentLoaded', init);

    return {
        open: open
    };
})();

// Global convenience shortcut
window.openMediaPicker = function(opts) {
    if (window.MediaPicker) {
        window.MediaPicker.open(opts);
    }
};
</script>
