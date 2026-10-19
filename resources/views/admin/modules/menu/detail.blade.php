@extends('admin.master')
@section('module', $nameItem)
@section('action', 'Quản lý')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            @if ($errors->any())
                <div class="alert alert-danger mb-xl-0" role="alert" style="list-style-type: none">
                    @foreach ($errors->all() as $error)
                        <li>
                            <h4>{{ $error }}</h4>
                        </li>
                    @endforeach
                </div>
            @endif
            @if (Session::get('error'))
                <div class="alert alert-danger mb-xl-0" role="alert" style="list-style-type: none">
                    <li>
                        <h4>{!! Session::get('error') !!}</h4>
                    </li>
                </div>
            @endif

            <div class="row">
                <div class="col-xxl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Quản lý trang menu</h4>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div class="live-preview">
                                <div class="row g-4 mb-4">
                                    <div class="col-lg-5 col-xl-4">
                                        <div class="mt-3">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="flex-shrink-0 me-1">
                                                    <i class="ri-pencil-fill fs-24 align-middle text-success me-1"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h5 class="fs-16 mb-0 fw-semibold">Chọn lựa</h5>
                                                </div>
                                            </div>

                                            <div class="accordion accordion-border-box" id="genques-accordion">
                                                {{-- category new --}}
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="genques-headingOne">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#genques-collapseOne"
                                                            aria-expanded="false" aria-controls="genques-collapseOne">
                                                            Trang nội dung
                                                        </button>
                                                    </h2>
                                                    <div id="genques-collapseOne" class="accordion-collapse collapse"
                                                        aria-labelledby="genques-headingOne"
                                                        data-bs-parent="#genques-accordion" style="">
                                                        <div class="accordion-body">
                                                            <form action="{{ route('admin.menu.store') }}" method="POST">
                                                                @csrf
                                                                @foreach ($page_content as $item)
                                                                    <div class="form-check mb-2">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            name="object_ids[]" value="{{ $item->id_page }}"
                                                                            id="formCheck{{ $item->id_page }}">
                                                                        <label class="form-check-label"
                                                                            for="formCheck{{ $item->id_page }}">
                                                                            {{ $item->name_vn }}
                                                                        </label>
                                                                    </div>
                                                                @endforeach
                                                                <p class="text-muted">Chọn vị trí thêm vào</p>
                                                                <select class="form-select"
                                                                    aria-label=".form-select-sm example" name="parent_id">
                                                                    <option value="0" selected="">Chủ đề cha
                                                                    </option>
                                                                    @foreach ($menus as $item)
                                                                        {!! renderMenuOptions($item) !!}
                                                                    @endforeach
                                                                </select>
                                                                <input type="hidden" name="type" value="page">
                                                                <div class="col-lg-12" style="padding-top: 1rem">
                                                                    <div class="text-start">
                                                                        <input type="submit"
                                                                            class="btn btn-secondary waves-effect waves-light"
                                                                            value="Thêm vào menu">
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- category product --}}
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="genques-headingThree">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#genques-collapseThree" aria-expanded="false"
                                                            aria-controls="genques-collapseThree">
                                                            Danh mục sản phẩm
                                                        </button>
                                                    </h2>
                                                    <div id="genques-collapseThree" class="accordion-collapse collapse"
                                                        aria-labelledby="genques-headingThree"
                                                        data-bs-parent="#genques-accordion" style="">
                                                        <div class="accordion-body">
                                                            <form action="{{ route('admin.menu.store') }}" method="POST">
                                                                @csrf
                                                                @foreach ($cate_product as $item)
                                                                    {!! renderCategoryCheckbox($item, 'id_cate_product') !!}
                                                                @endforeach
                                                                <p class="text-muted">Chọn vị trí thêm vào</p>
                                                                <select class="form-select"
                                                                    aria-label=".form-select-sm example" name="parent_id">
                                                                    <option value="0" selected="">Chủ đề cha
                                                                    </option>
                                                                    @foreach ($menus as $item)
                                                                        {!! renderMenuOptions($item) !!}
                                                                    @endforeach
                                                                </select>
                                                                <input type="hidden" name="type" value="cate_product">
                                                                <div class="col-lg-12" style="padding-top: 1rem">
                                                                    <div class="text-start">
                                                                        <input type="submit"
                                                                            class="btn btn-secondary waves-effect waves-light"
                                                                            value="Thêm vào menu">
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="genques-headingTwo">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#genques-collapseTwo" aria-expanded="false"
                                                            aria-controls="genques-collapseTwo">
                                                            Danh mục tin tức
                                                        </button>
                                                    </h2>
                                                    <div id="genques-collapseTwo" class="accordion-collapse collapse"
                                                        aria-labelledby="genques-headingTwo"
                                                        data-bs-parent="#genques-accordion" style="">
                                                        <div class="accordion-body">
                                                            <form action="{{ route('admin.menu.store') }}" method="POST">
                                                                @csrf
                                                                @foreach ($cate_new as $item)
                                                                    {!! renderCategoryCheckbox($item, 'id_cate_new') !!}
                                                                @endforeach
                                                                <p class="text-muted">Chọn vị trí thêm vào</p>
                                                                <select class="form-select"
                                                                    aria-label=".form-select-sm example" name="parent_id">
                                                                    <option value="0" selected="">Chủ đề cha
                                                                    </option>
                                                                    @foreach ($menus as $item)
                                                                        {!! renderMenuOptions($item) !!}
                                                                    @endforeach
                                                                </select>
                                                                <input type="hidden" name="type" value="cate_new">
                                                                <div class="col-lg-12" style="padding-top: 1rem">
                                                                    <div class="text-start">
                                                                        <input type="submit"
                                                                            class="btn btn-secondary waves-effect waves-light"
                                                                            value="Thêm vào menu">
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- category service --}}
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="genques-headingService">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#genques-collapseService" aria-expanded="false"
                                                            aria-controls="genques-collapseService">
                                                            Danh mục dịch vụ
                                                        </button>
                                                    </h2>
                                                    <div id="genques-collapseService" class="accordion-collapse collapse"
                                                        aria-labelledby="genques-headingService"
                                                        data-bs-parent="#genques-accordion">
                                                        <div class="accordion-body">
                                                            <form action="{{ route('admin.menu.store') }}" method="POST">
                                                                @csrf
                                                                @if(isset($cate_service) && count($cate_service) > 0)
                                                                    @foreach ($cate_service as $item)
                                                                        {!! renderCategoryCheckbox($item, 'id_cate_service') !!}
                                                                    @endforeach
                                                                @else
                                                                    <p class="text-muted">Chưa có danh mục dịch vụ nào.</p>
                                                                @endif
                                                                <p class="text-muted">Chọn vị trí thêm vào</p>
                                                                <select class="form-select"
                                                                    aria-label=".form-select-sm example" name="parent_id">
                                                                    <option value="0" selected="">Chủ đề cha
                                                                    </option>
                                                                    @foreach ($menus as $item)
                                                                        {!! renderMenuOptions($item) !!}
                                                                    @endforeach
                                                                </select>
                                                                <input type="hidden" name="type" value="cate_service">
                                                                <div class="col-lg-12" style="padding-top: 1rem">
                                                                    <div class="text-start">
                                                                        <input type="submit"
                                                                            class="btn btn-secondary waves-effect waves-light"
                                                                            value="Thêm vào menu">
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- category project --}}
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="genques-headingProject">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#genques-collapseProject" aria-expanded="false"
                                                            aria-controls="genques-collapseProject">
                                                            Danh mục dự án
                                                        </button>
                                                    </h2>
                                                    <div id="genques-collapseProject" class="accordion-collapse collapse"
                                                        aria-labelledby="genques-headingProject"
                                                        data-bs-parent="#genques-accordion">
                                                        <div class="accordion-body">
                                                            <form action="{{ route('admin.menu.store') }}" method="POST">
                                                                @csrf
                                                                @if(isset($cate_project) && count($cate_project) > 0)
                                                                    @foreach ($cate_project as $item)
                                                                        {!! renderCategoryCheckbox($item, 'id_cate_project') !!}
                                                                    @endforeach
                                                                @else
                                                                    <p class="text-muted">Chưa có danh mục dự án nào.</p>
                                                                @endif
                                                                <p class="text-muted">Chọn vị trí thêm vào</p>
                                                                <select class="form-select"
                                                                    aria-label=".form-select-sm example" name="parent_id">
                                                                    <option value="0" selected="">Chủ đề cha
                                                                    </option>
                                                                    @foreach ($menus as $item)
                                                                        {!! renderMenuOptions($item) !!}
                                                                    @endforeach
                                                                </select>
                                                                <input type="hidden" name="type" value="cate_project">
                                                                <div class="col-lg-12" style="padding-top: 1rem">
                                                                    <div class="text-start">
                                                                        <input type="submit"
                                                                            class="btn btn-secondary waves-effect waves-light"
                                                                            value="Thêm vào menu">
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- Brand --}}
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="genques-headingBrand">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#genques-collapseBrand" aria-expanded="false"
                                                            aria-controls="genques-collapseBrand">
                                                            Thương hiệu
                                                        </button>
                                                    </h2>
                                                    <div id="genques-collapseBrand" class="accordion-collapse collapse"
                                                        aria-labelledby="genques-headingBrand"
                                                        data-bs-parent="#genques-accordion">
                                                        <div class="accordion-body">
                                                            <form action="{{ route('admin.menu.store') }}" method="POST">
                                                                @csrf
                                                                @if(isset($brands) && count($brands) > 0)
                                                                    @foreach ($brands as $item)
                                                                        <div class="form-check mb-2">
                                                                            <input class="form-check-input" type="checkbox"
                                                                                name="object_ids[]" value="{{ $item->id_brand }}"
                                                                                id="formCheckBrand{{ $item->id_brand }}">
                                                                            <label class="form-check-label d-flex align-items-center gap-2"
                                                                                for="formCheckBrand{{ $item->id_brand }}">
                                                                                @if ($item->image)
                                                                                    <img src="{{ $item->image }}" alt="{{ $item->name_vn }}" style="height: 20px; width: auto; object-fit: contain;">
                                                                                @endif
                                                                                <span>{{ $item->name_vn }}</span>
                                                                            </label>
                                                                        </div>
                                                                    @endforeach
                                                                @else
                                                                    <p class="text-muted">Chưa có thương hiệu nào.</p>
                                                                @endif
                                                                <p class="text-muted">Chọn vị trí thêm vào</p>
                                                                <select class="form-select"
                                                                    aria-label=".form-select-sm example" name="parent_id">
                                                                    <option value="0" selected="">Chủ đề cha
                                                                    </option>
                                                                    @foreach ($menus as $item)
                                                                        {!! renderMenuOptions($item) !!}
                                                                    @endforeach
                                                                </select>
                                                                <input type="hidden" name="type" value="brand">
                                                                <div class="col-lg-12" style="padding-top: 1rem">
                                                                    <div class="text-start">
                                                                        <input type="submit"
                                                                            class="btn btn-secondary waves-effect waves-light"
                                                                            value="Thêm vào menu">
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="genques-headingFour">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#genques-collapseFour" aria-expanded="false"
                                                            aria-controls="genques-collapseFour">
                                                            Thêm liên kết
                                                        </button>
                                                    </h2>
                                                    <div id="genques-collapseFour" class="accordion-collapse collapse"
                                                        aria-labelledby="genques-headingFour"
                                                        data-bs-parent="#genques-accordion" style="">
                                                        <div class="accordion-body">
                                                            <form action="{{ route('admin.menu.store') }}" method="POST">
                                                                @csrf
                                                                <div class="mb-3">
                                                                    <label class="form-label"
                                                                        for="product-title-input">Tiêu đề
                                                                    </label>
                                                                    <input type="text" class="form-control"
                                                                        name="name_vn" value="{{ old('name_vn') }}">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label"
                                                                        for="product-title-input">Đường dẫn</label>
                                                                    <input type="text" class="form-control"
                                                                        name="link" value="{{ old('link') }}">
                                                                </div>
                                                                <input type="hidden" name="type" value="link">
                                                                <p class="text-muted">Chọn vị trí thêm vào</p>
                                                                <select class="form-select"
                                                                    aria-label=".form-select-sm example" name="parent_id">
                                                                    <option value="0" selected="">Chủ đề cha
                                                                    </option>
                                                                    @foreach ($menus as $item)
                                                                        {!! renderMenuOptions($item) !!}
                                                                    @endforeach
                                                                </select>
                                                                <input type="hidden" name="type" value="link">
                                                                <div class="col-lg-12" style="padding-top: 1rem">
                                                                    <div class="text-start">
                                                                        <input type="submit"
                                                                            class="btn btn-secondary waves-effect waves-light"
                                                                            value="Thêm vào menu">
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end accordion-->
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-xl-8">
                                        <div class="mt-3">
                                            <div class="d-flex align-items-center justify-content-between mb-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-2">
                                                        <i class="ri-node-tree fs-22 align-middle text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <h5 class="fs-16 mb-0 fw-semibold">Cấu trúc Menu</h5>
                                                        <p class="text-muted small mb-0">Kéo thả để sắp xếp thứ tự hoặc kéo lùi sang phải để làm menu con</p>
                                                    </div>
                                                </div>
                                                <div id="menu-save-indicator" class="menu-save-indicator" style="display: none;">
                                                    <i class="ri-checkbox-circle-line"></i>
                                                    <span class="indicator-text">Đã lưu vị trí</span>
                                                </div>
                                            </div>

                                            <div class="card border shadow-none bg-light-subtle">
                                                <div class="card-body p-3">
                                                    <div id="root-menu-list" class="list-group nested-list nested-sortable">
                                                        @forelse ($menus as $item)
                                                            @include('admin.modules.menu.menu-item', [
                                                                'item' => $item,
                                                                'level' => 1,
                                                                'pageContent' => $page_content,
                                                                'cateNew' => $cate_new,
                                                                'cateProduct' => $cate_product,
                                                                'brands' => $brands ?? null,
                                                            ])
                                                        @empty
                                                            <div class="text-center py-4 text-muted">
                                                                <i class="ri-menu-line fs-32 mb-2 d-block"></i>
                                                                Chưa có menu nào. Hãy chọn mục bên trái để thêm vào menu.
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('admin/libs/sortablejs/Sortable.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const rootContainer = document.getElementById('root-menu-list');
        const indicator = document.getElementById('menu-save-indicator');
        const indicatorText = indicator ? indicator.querySelector('.indicator-text') : null;
        let isSaving = false;

        function setIndicator(state, message) {
            if (!indicator) return;
            indicator.style.display = 'inline-flex';
            indicator.className = 'menu-save-indicator ' + state;
            if (indicatorText) indicatorText.textContent = message;

            if (state === 'saved') {
                setTimeout(function () {
                    indicator.style.display = 'none';
                }, 2500);
            }
        }

        function serializeTree(container) {
            const items = [];
            const directNodes = Array.from(container.children).filter(function (el) {
                return el.classList.contains('menu-item-node');
            });

            directNodes.forEach(function (node, index) {
                const id = node.dataset.id;
                const subContainer = node.querySelector(':scope > .menu-children-container');
                const children = subContainer ? serializeTree(subContainer) : [];

                items.push({
                    id: parseInt(id, 10),
                    stt: index + 1,
                    children: children
                });
            });

            return items;
        }

        function saveMenuStructure() {
            if (!rootContainer || isSaving) return;

            const hierarchy = serializeTree(rootContainer);
            isSaving = true;
            setIndicator('saving', 'Đang lưu vị trí...');

            fetch('{{ route("admin.menu.reorder") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ hierarchy: hierarchy })
            })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Network error: ' + response.status);
                }
                return response.json();
            })
            .then(function (data) {
                isSaving = false;
                if (data.status) {
                    setIndicator('saved', data.message || 'Đã lưu vị trí');
                } else {
                    setIndicator('error', data.message || 'Lỗi khi lưu vị trí');
                }
            })
            .catch(function (error) {
                isSaving = false;
                console.error('Menu reorder error:', error);
                setIndicator('error', 'Lỗi kết nối khi lưu');
            });
        }

        function initNestedSortables() {
            const sortables = document.querySelectorAll('.nested-sortable');
            sortables.forEach(function (el) {
                new Sortable(el, {
                    group: 'nested-menu',
                    handle: '.drag-handle',
                    animation: 150,
                    fallbackOnBody: true,
                    swapThreshold: 0.65,
                    emptyInsertThreshold: 10,
                    ghostClass: 'menu-sortable-ghost',
                    chosenClass: 'menu-sortable-chosen',
                    dragClass: 'menu-sortable-drag',
                    onStart: function () {
                        if (rootContainer) rootContainer.classList.add('is-dragging');
                    },
                    onMove: function (evt) {
                        let depth = 1;
                        let parent = evt.to.closest('.menu-item-node');
                        while (parent) {
                            depth++;
                            parent = parent.parentElement ? parent.parentElement.closest('.menu-item-node') : null;
                        }

                        function getSubtreeDepth(node) {
                            const sub = node.querySelector(':scope > .menu-children-container');
                            if (!sub) return 0;
                            const children = Array.from(sub.children).filter(function (c) {
                                return c.classList.contains('menu-item-node');
                            });
                            if (children.length === 0) return 0;
                            return 1 + Math.max.apply(null, children.map(getSubtreeDepth));
                        }

                        const draggedDepth = getSubtreeDepth(evt.dragged);
                        if (depth + draggedDepth > 3) {
                            return false;
                        }
                    },
                    onEnd: function () {
                        if (rootContainer) rootContainer.classList.remove('is-dragging');
                        saveMenuStructure();
                    }
                });
            });
        }

        initNestedSortables();
    });
</script>
@endpush
