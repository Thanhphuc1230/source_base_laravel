<div class="media-detail-uploader">
    <input type="file" id="imageDetailFileInput" name="image_detail[]" multiple accept="image/*,video/mp4,video/webm,video/quicktime" class="d-none">
    <input type="hidden" id="selected_detail_images_input" name="selected_detail_images" value="[]">

    <!-- Action Toolbar (Rule 13: 38px controls) -->
    <div id="multi-image-drop-area" class="border border-2 border-dashed p-4 text-center bg-light transition-all mb-3" style="cursor: pointer; border-color: #cbd5e1 !important; border-radius: 8px;">
        <div class="py-2">
            <div class="mb-2">
                <i class="ri-folder-upload-line text-primary" style="font-size: 2.2rem;"></i>
            </div>
            <h6 class="fw-semibold text-dark mb-1">Kéo & thả hình ảnh và video clip vào đây hoặc chọn từ thư viện</h6>
            <p class="text-muted small mb-3">Hỗ trợ JPG, PNG, WEBP, GIF, MP4, WebM (chọn nhiều file cùng lúc, khuyến nghị ảnh: 800x800 px - 1200x1200 px tỉ lệ 1:1; tối đa 50MB/video, 10MB/ảnh).</p>
            <div class="d-flex align-items-center justify-content-center gap-2 flex-wrap">
                <button type="button" class="btn btn-primary btn-sm rounded-2 px-3 shadow-xs d-inline-flex align-items-center gap-1.5" style="height: 38px;" onclick="openMediaPickerForGallery(event)">
                    <i class="ri-folder-image-line fs-15"></i>
                    <span class="fw-semibold">Chọn từ Thư viện Media</span>
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm rounded-2 px-3 bg-white d-inline-flex align-items-center gap-1.5" style="height: 38px;" onclick="document.getElementById('imageDetailFileInput').click();">
                    <i class="ri-upload-2-line fs-15"></i>
                    <span>Tải tệp từ máy tính</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Container for freshly selected files preview -->
    <div id="new-images-preview-grid" class="row g-2 mt-2" style="display: none;"></div>
</div>

<style>
#multi-image-drop-area.dragover {
    border-color: #3b82f6 !important;
    background-color: #eff6ff !important;
}
.detail-preview-card {
    position: relative;
    border-radius: 6px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}
.detail-preview-card .preview-media {
    width: 100%;
    height: 110px;
    object-fit: cover;
    background-color: #f8fafc;
    border-radius: 6px 6px 0 0;
    display: block;
}
.detail-preview-card .media-badge {
    position: absolute;
    bottom: 26px;
    left: 6px;
    font-size: 9px;
    font-weight: 700;
    padding: 2px 5px;
    border-radius: 2px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}
.detail-preview-card .btn-remove-new {
    position: absolute;
    top: 5px;
    right: 5px;
    width: 22px;
    height: 22px;
    padding: 0;
    line-height: 22px;
    font-size: 13px;
    border-radius: 3px;
    background: rgba(220, 38, 38, 0.9);
    color: white;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.15s ease;
    z-index: 2;
}
.detail-preview-card .btn-remove-new:hover {
    background: #b91c1c;
    transform: scale(1.05);
}
.detail-preview-card .file-info {
    font-size: 11px;
    padding: 4px 6px;
    color: #475569;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    background: #fff;
    border-top: 1px solid #f1f5f9;
}
</style>

@push('scripts')
<script>
let stagedLibraryMedia = []; // Array of selected media objects from media library

function openMediaPickerForGallery(e) {
    if (e) e.stopPropagation();
    if (typeof window.openMediaPicker === 'function') {
        window.openMediaPicker({
            mode: 'multiple',
            module: '{{ $imageFolder ?? "product" }}',
            onSelect: function(items) {
                if (!Array.isArray(items)) items = [items];
                items.forEach(item => {
                    if (!stagedLibraryMedia.some(m => m.path === item.path)) {
                        stagedLibraryMedia.push(item);
                    }
                });
                syncStagedLibraryMedia();
            }
        });
    }
}

function syncStagedLibraryMedia() {
    const hiddenInput = document.getElementById('selected_detail_images_input');
    if (hiddenInput) {
        hiddenInput.value = JSON.stringify(stagedLibraryMedia.map(m => m.path));
    }
    renderCombinedPreview();
}

(function() {
    const dropArea = document.getElementById('multi-image-drop-area');
    const fileInput = document.getElementById('imageDetailFileInput');
    const previewGrid = document.getElementById('new-images-preview-grid');
    
    // Manage staged files from PC via DataTransfer
    let dt = new DataTransfer();

    if (!dropArea || !fileInput || !previewGrid) return;

    // Drag and drop event handlers
    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropArea.classList.add('dragover');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropArea.classList.remove('dragover');
        }, false);
    });

    dropArea.addEventListener('drop', function(e) {
        const droppedFiles = e.dataTransfer.files;
        handleFiles(droppedFiles);
    });

    fileInput.addEventListener('change', function() {
        handleFiles(this.files);
    });

    function handleFiles(files) {
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const isImg = file.type.startsWith('image/');
            const isVid = file.type.startsWith('video/') || /\.(mp4|webm|mov|ogg)$/i.test(file.name);
            if (!isImg && !isVid) continue;
            dt.items.add(file);
        }
        fileInput.files = dt.files;
        renderCombinedPreview();
    }

    window.renderCombinedPreview = function() {
        previewGrid.innerHTML = '';
        const totalItems = dt.files.length + stagedLibraryMedia.length;

        if (totalItems === 0) {
            previewGrid.style.display = 'none';
            return;
        }

        previewGrid.style.display = 'flex';

        // 1. Render files selected from Media Library
        stagedLibraryMedia.forEach((media, index) => {
            const col = document.createElement('div');
            col.className = 'col-6 col-sm-4 col-md-3 col-lg-2';

            const card = document.createElement('div');
            card.className = 'detail-preview-card border-primary border-opacity-25';

            const isVideo = media.is_video || /\.(mp4|webm|mov|ogg)$/i.test(media.extension);
            if (isVideo) {
                const vid = document.createElement('video');
                vid.src = media.url + '#t=0.5';
                vid.className = 'preview-media';
                vid.muted = true;
                vid.playsInline = true;
                vid.preload = 'metadata';
                card.appendChild(vid);

                const badge = document.createElement('span');
                badge.className = 'media-badge bg-danger text-white';
                badge.innerHTML = '<i class="ri-video-fill me-0.5"></i> Video';
                card.appendChild(badge);
            } else {
                const img = document.createElement('img');
                img.src = media.url;
                img.alt = media.name;
                img.className = 'preview-media';
                card.appendChild(img);

                const badge = document.createElement('span');
                badge.className = 'media-badge bg-primary text-white';
                badge.innerHTML = '<i class="ri-folder-image-line me-0.5"></i> Thư viện';
                card.appendChild(badge);
            }

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'btn-remove-new';
            removeBtn.innerHTML = '&times;';
            removeBtn.title = 'Bỏ tệp này';
            removeBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                stagedLibraryMedia.splice(index, 1);
                syncStagedLibraryMedia();
            });

            const info = document.createElement('div');
            info.className = 'file-info text-truncate';
            info.innerText = media.name;

            card.appendChild(removeBtn);
            card.appendChild(info);
            col.appendChild(card);
            previewGrid.appendChild(col);
        });

        // 2. Render files selected from PC
        Array.from(dt.files).forEach((file, index) => {
            const col = document.createElement('div');
            col.className = 'col-6 col-sm-4 col-md-3 col-lg-2';

            const card = document.createElement('div');
            card.className = 'detail-preview-card';

            const isVideo = file.type.startsWith('video/') || /\.(mp4|webm|mov|ogg)$/i.test(file.name);
            if (isVideo) {
                const vid = document.createElement('video');
                vid.src = URL.createObjectURL(file);
                vid.className = 'preview-media';
                vid.muted = true;
                vid.playsInline = true;
                vid.preload = 'metadata';
                vid.onloadeddata = function() {
                    try { vid.currentTime = 0.5; } catch (e) {}
                };
                card.appendChild(vid);

                const badge = document.createElement('span');
                badge.className = 'media-badge bg-danger text-white';
                badge.innerHTML = '<i class="ri-video-fill me-0.5"></i> Video';
                card.appendChild(badge);
            } else {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.alt = file.name;
                img.className = 'preview-media';
                card.appendChild(img);

                const badge = document.createElement('span');
                badge.className = 'media-badge bg-success text-white';
                badge.innerHTML = '<i class="ri-upload-cloud-line me-0.5"></i> Mới';
                card.appendChild(badge);
            }

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'btn-remove-new';
            removeBtn.innerHTML = '&times;';
            removeBtn.title = 'Bỏ file này';
            removeBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                removeFileFromPC(index);
            });

            const info = document.createElement('div');
            info.className = 'file-info text-truncate';
            info.innerText = file.name;

            card.appendChild(removeBtn);
            card.appendChild(info);
            col.appendChild(card);
            previewGrid.appendChild(col);
        });
    };

    function removeFileFromPC(index) {
        const newDt = new DataTransfer();
        for (let i = 0; i < dt.files.length; i++) {
            if (i !== index) {
                newDt.items.add(dt.files[i]);
            }
        }
        dt = newDt;
        fileInput.files = dt.files;
        renderCombinedPreview();
    }
})();
</script>
@endpush
