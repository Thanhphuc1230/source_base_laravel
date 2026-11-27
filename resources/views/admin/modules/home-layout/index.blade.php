@extends('admin.master')
@section('module', 'Trang chủ')
@section('action', 'Bố cục Trang chủ (Kéo thả)')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            @include('admin.partials.error-banner')
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row g-4">
                <!-- Left Column: Accordion Add Blocks -->
                <div class="col-lg-5 col-xl-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="ri-add-box-line me-1 text-primary"></i> Thêm khối vào trang chủ</h5>
                        </div>
                        <div class="card-body">
                            <div class="accordion accordion-border-box" id="homeBuilderAccordion">
                                <!-- 1. Product Categories -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingCateProduct">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCateProduct" aria-expanded="true" aria-controls="collapseCateProduct">
                                            Khối Danh mục Sản phẩm
                                        </button>
                                    </h2>
                                    <div id="collapseCateProduct" class="accordion-collapse collapse show" aria-labelledby="headingCateProduct" data-bs-parent="#homeBuilderAccordion">
                                        <div class="accordion-body">
                                            <form action="{{ route('admin.home-layout.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="type" value="category_products">
                                                <div class="mb-3" style="max-height: 220px; overflow-y: auto;">
                                                    @forelse($cateProducts as $cate)
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="checkbox" name="reference_ids[]" value="{{ $cate->id_cate_product }}" id="cp_{{ $cate->id_cate_product }}">
                                                            <label class="form-check-label" for="cp_{{ $cate->id_cate_product }}">
                                                                {{ $cate->getTranslation('name', 'vi') ?: $cate->name }}
                                                            </label>
                                                        </div>
                                                    @empty
                                                        <p class="text-muted small mb-0">Chưa có danh mục sản phẩm nào.</p>
                                                    @endforelse
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small text-muted mb-1">Số lượng sản phẩm hiển thị</label>
                                                    <input type="number" name="limit" value="8" min="1" max="50" class="form-control" style="height: 38px;">
                                                </div>
                                                <button type="submit" class="btn btn-primary w-100" style="height: 38px;">
                                                    <i class="ri-add-line me-1"></i> Thêm vào trang chủ
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- 2. News Categories -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingCateNews">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCateNews" aria-expanded="false" aria-controls="collapseCateNews">
                                            Khối Danh mục Tin tức
                                        </button>
                                    </h2>
                                    <div id="collapseCateNews" class="accordion-collapse collapse" aria-labelledby="headingCateNews" data-bs-parent="#homeBuilderAccordion">
                                        <div class="accordion-body">
                                            <form action="{{ route('admin.home-layout.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="type" value="news">
                                                <div class="mb-3" style="max-height: 220px; overflow-y: auto;">
                                                    @forelse($cateNews as $cate)
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="checkbox" name="reference_ids[]" value="{{ $cate->id_cate_new }}" id="cn_{{ $cate->id_cate_new }}">
                                                            <label class="form-check-label" for="cn_{{ $cate->id_cate_new }}">
                                                                {{ $cate->getTranslation('name', 'vi') ?: $cate->name }}
                                                            </label>
                                                        </div>
                                                    @empty
                                                        <p class="text-muted small mb-0">Chưa có danh mục tin tức nào.</p>
                                                    @endforelse
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small text-muted mb-1">Số lượng bài viết hiển thị</label>
                                                    <input type="number" name="limit" value="3" min="1" max="50" class="form-control" style="height: 38px;">
                                                </div>
                                                <button type="submit" class="btn btn-primary w-100" style="height: 38px;">
                                                    <i class="ri-add-line me-1"></i> Thêm vào trang chủ
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- 3. Banner & System Blocks -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingSystemBlocks">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSystemBlocks" aria-expanded="false" aria-controls="collapseSystemBlocks">
                                            Khối Banner & Hệ thống
                                        </button>
                                    </h2>
                                    <div id="collapseSystemBlocks" class="accordion-collapse collapse" aria-labelledby="headingSystemBlocks" data-bs-parent="#homeBuilderAccordion">
                                        <div class="accordion-body">
                                            <form action="{{ route('admin.home-layout.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="type" value="system_blocks">
                                                <div class="mb-3">
                                                    @foreach($systemBlocks as $blockKey => $blockLabel)
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="checkbox" name="block_types[]" value="{{ $blockKey }}" id="sb_{{ $blockKey }}">
                                                            <label class="form-check-label" for="sb_{{ $blockKey }}">
                                                                {{ $blockLabel }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small text-muted mb-1">Số lượng item mặc định</label>
                                                    <input type="number" name="limit" value="8" min="1" max="50" class="form-control" style="height: 38px;">
                                                </div>
                                                <button type="submit" class="btn btn-primary w-100" style="height: 38px;">
                                                    <i class="ri-add-line me-1"></i> Thêm vào trang chủ
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: SortableJS Active Sections -->
                <div class="col-lg-7 col-xl-8">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="card-title mb-0"><i class="ri-layout-masonry-line me-1 text-primary"></i> Cấu trúc Trang chủ (Kéo & Thả để đổi thứ tự)</h5>
                                <small class="text-muted">Mọi thay đổi tự động lưu và xoá cache trang chủ ngay lập tức.</small>
                            </div>
                            <span id="reorder-status" class="badge bg-success-subtle text-success d-none">Đã lưu thứ tự</span>
                        </div>
                        <div class="card-body">
                            <div id="home-sections-sortable" class="d-flex flex-column gap-3">
                                @forelse($sections as $section)
                                    <div class="card border shadow-none mb-0 home-section-item" data-uuid="{{ $section->uuid }}">
                                        <div class="card-body p-3">
                                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="drag-handle text-muted" style="cursor: grab;" title="Kéo để sắp xếp">
                                                        <i class="ri-drag-move-2-line fs-18"></i>
                                                    </span>
                                                    <span class="badge bg-primary-subtle text-primary">{{ $section->type }}</span>
                                                    @if($section->type === 'category_products' && $section->categoryProduct)
                                                        <span class="badge bg-info-subtle text-info">{{ $section->categoryProduct->name }}</span>
                                                    @elseif($section->type === 'news' && $section->categoryNews)
                                                        <span class="badge bg-info-subtle text-info">{{ $section->categoryNews->name }}</span>
                                                    @endif
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="form-check form-switch mb-0">
                                                        <input class="form-check-input toggle-section-status" type="checkbox" role="switch"
                                                            data-uuid="{{ $section->uuid }}"
                                                            style="accent-color: #2563eb;"
                                                            {{ $section->status ? 'checked' : '' }}>
                                                    </div>
                                                    <button type="button" class="btn btn-sm btn-outline-danger delete-section-btn"
                                                        data-uuid="{{ $section->uuid }}"
                                                        style="width: 32px; height: 32px; padding: 0;" title="Xóa khối">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <form class="row g-2 align-items-end quick-update-section-form" data-uuid="{{ $section->uuid }}">
                                                <div class="col-md-5">
                                                    <label class="form-label small text-muted mb-1">Tiêu đề (VN)</label>
                                                    <input type="text" name="title_vn" value="{{ $section->title_vn }}" class="form-control" style="height: 38px;" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small text-muted mb-1">Tiêu đề (EN)</label>
                                                    <input type="text" name="title_en" value="{{ $section->title_en }}" class="form-control" style="height: 38px;">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small text-muted mb-1">Số lượng</label>
                                                    <input type="number" name="limit" value="{{ $section->limit }}" min="1" max="50" class="form-control" style="height: 38px;" required>
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="submit" class="btn btn-outline-primary w-100" style="height: 38px; padding: 0;" title="Lưu thay đổi">
                                                        <i class="ri-save-line"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center text-muted py-4">
                                        Chưa có khối nào trên trang chủ. Hãy chọn từ cột bên trái để thêm.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('admin/libs/sortablejs/Sortable.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = '{{ csrf_token() }}';
    const sortableEl = document.getElementById('home-sections-sortable');
    const reorderStatus = document.getElementById('reorder-status');

    if (sortableEl && typeof Sortable !== 'undefined') {
        new Sortable(sortableEl, {
            handle: '.drag-handle',
            animation: 150,
            onEnd: function () {
                const order = Array.from(sortableEl.querySelectorAll('.home-section-item'))
                    .map(el => el.dataset.uuid);

                fetch('{{ route('admin.home-layout.reorder') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ order: order })
                }).then(res => res.json()).then(data => {
                    if (data.success && reorderStatus) {
                        reorderStatus.classList.remove('d-none');
                        setTimeout(() => reorderStatus.classList.add('d-none'), 2000);
                    }
                });
            }
        });
    }

    document.querySelectorAll('.toggle-section-status').forEach(sw => {
        sw.addEventListener('change', function () {
            const uuid = this.dataset.uuid;
            const status = this.checked ? 1 : 0;
            fetch(`{{ url('admin/home-layout/status') }}/${uuid}/${status}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
        });
    });

    document.querySelectorAll('.quick-update-section-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const uuid = this.dataset.uuid;
            const formData = new FormData(this);
            const btn = this.querySelector('button[type="submit"]');
            fetch(`{{ url('admin/home-layout/update') }}/${uuid}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            }).then(res => res.json()).then(data => {
                if (data.success && btn) {
                    btn.classList.remove('btn-outline-primary');
                    btn.classList.add('btn-success');
                    setTimeout(() => {
                        btn.classList.add('btn-outline-primary');
                        btn.classList.remove('btn-success');
                    }, 1200);
                }
            });
        });
    });

    document.querySelectorAll('.delete-section-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            if (!confirm('Xác nhận xóa khối này khỏi trang chủ?')) return;
            const uuid = this.dataset.uuid;
            const card = this.closest('.home-section-item');
            fetch(`{{ url('admin/home-layout/destroy') }}/${uuid}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            }).then(res => res.json()).then(data => {
                if (data.success && card) {
                    card.remove();
                }
            });
        });
    });
});
</script>
@endpush
