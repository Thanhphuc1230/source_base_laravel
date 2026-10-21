<div class="media-detail-uploader">
    <div id="multi-image-drop-area" class="border border-2 border-dashed p-4 text-center bg-light transition-all" style="cursor: pointer; border-color: #cbd5e1 !important; border-radius: 6px;">
        <input type="file" id="imageDetailFileInput" name="image_detail[]" multiple accept="image/*,video/mp4,video/webm,video/quicktime" class="d-none">
        <div class="py-2">
            <div class="mb-2">
                <i class="ri-folder-upload-line text-primary" style="font-size: 2.2rem;"></i>
            </div>
            <h6 class="fw-semibold text-dark mb-1">Kéo & thả hình ảnh và video clip vào đây hoặc nhấp để chọn</h6>
            <p class="text-muted small mb-3">Hỗ trợ JPG, PNG, WEBP, GIF, MP4, WebM (chọn nhiều file cùng lúc, khuyến nghị ảnh: 800x800 px - 1200x1200 px tỉ lệ 1:1; tối đa 50MB/video, 10MB/ảnh).</p>
            <button type="button" class="btn btn-sm btn-dark rounded-1 px-3 shadow-xs" onclick="document.getElementById('imageDetailFileInput').click();">
                <i class="ri-add-line me-1"></i> Chọn hình ảnh / video
            </button>
        </div>
    </div>

    <!-- Container for freshly selected files preview -->
    <div id="new-images-preview-grid" class="row g-2 mt-3" style="display: none;"></div>
</div>

<style>
#multi-image-drop-area.dragover {
    border-color: #3b82f6 !important;
    background-color: #eff6ff !important;
}
.detail-preview-card {
    position: relative;
    border-radius: 4px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    box-shadow: 0 1px 2px rgba(0,0,0,0.04);
}
.detail-preview-card .preview-media {
    width: 100%;
    height: 110px;
    object-fit: cover;
    background-color: #f8fafc;
    border-radius: 4px 4px 0 0;
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
(function() {
    const dropArea = document.getElementById('multi-image-drop-area');
    const fileInput = document.getElementById('imageDetailFileInput');
    const previewGrid = document.getElementById('new-images-preview-grid');
    
    // Manage staged files via DataTransfer
    let dt = new DataTransfer();

    if (!dropArea || !fileInput || !previewGrid) return;

    // Trigger file picker
    dropArea.addEventListener('click', function(e) {
        if (e.target.tagName !== 'BUTTON' && !e.target.closest('button')) {
            fileInput.click();
        }
    });

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
        renderPreview();
    }

    function renderPreview() {
        previewGrid.innerHTML = '';
        if (dt.files.length === 0) {
            previewGrid.style.display = 'none';
            return;
        }

        previewGrid.style.display = 'flex';

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
            }

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'btn-remove-new';
            removeBtn.innerHTML = '&times;';
            removeBtn.title = 'Bỏ file này';
            removeBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                removeFile(index);
            });

            const info = document.createElement('div');
            info.className = 'file-info text-truncate';
            info.innerText = file.name;

            card.appendChild(removeBtn);
            card.appendChild(info);
            col.appendChild(card);
            previewGrid.appendChild(col);
        });
    }

    function removeFile(index) {
        const newDt = new DataTransfer();
        for (let i = 0; i < dt.files.length; i++) {
            if (i !== index) {
                newDt.items.add(dt.files[i]);
            }
        }
        dt = newDt;
        fileInput.files = dt.files;
        renderPreview();
    }
})();
</script>
@endpush