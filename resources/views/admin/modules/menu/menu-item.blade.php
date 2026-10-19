{{-- resources/views/admin/modules/menu/menu-item.blade.php --}}
@props(['item', 'level' => 1, 'pageContent' => null, 'cateNew' => null, 'cateProduct' => null, 'brands' => null])

@php
    $nestedClass = 'nested-' . $level;
    $belongName = getMenuBelongName($item, $pageContent, $cateNew, $cateProduct, $brands);
    $typeLabel = getMenuTypeLabel($item->type);
    $viewUrl = getUrlMenu($item);
@endphp

<div class="list-group-item menu-item-node {{ $nestedClass }}" data-id="{{ $item->id_menu }}" data-uuid="{{ $item->uuid }}">
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center flex-grow-1 overflow-hidden me-2">
            <span class="drag-handle me-2" title="Kéo thả để sắp xếp vị trí">
                <i class="ri-drag-move-2-fill fs-18"></i>
            </span>
            <div class="text-truncate">
                <h6 class="mb-1 text-dark fw-semibold text-truncate">{{ $item->name_vn }}</h6>
                <div class="d-flex align-items-center gap-1 flex-wrap">
                    <span class="category-badge">{{ $typeLabel }}</span>
                    @if ($belongName)
                        <span class="text-muted small text-truncate" style="max-width: 220px;">: {{ $belongName }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="d-inline-flex align-items-center gap-1 flex-shrink-0">
            @if ($viewUrl && $viewUrl !== route('web.404') && $viewUrl !== '#')
                <a href="{{ $viewUrl }}" target="_blank" rel="noopener noreferrer" 
                   class="action-btn-ghost action-view" title="Xem ngoài website">
                    <i class="ri-eye-line"></i>
                </a>
            @endif
            <button type="button" class="action-btn-ghost action-edit" 
                    data-bs-toggle="modal" 
                    data-bs-target="#showModal{{ $item->id_menu }}" title="Chỉnh sửa">
                <i class="ri-pencil-line"></i>
            </button>
            <a href="{{ route('admin.menu.destroy', ['uuid' => $item->uuid]) }}" 
               onclick="return confirm('Xác nhận xóa menu và tất cả menu con?')" 
               class="action-btn-ghost action-delete" title="Xóa">
                <i class="ri-delete-bin-line"></i>
            </a>
        </div>
    </div>

    {{-- Render children recursively --}}
    <div class="list-group nested-list nested-sortable menu-children-container mt-2">
        @if ($item->children && $item->children->isNotEmpty())
            @foreach ($item->children as $child)
                @include('admin.modules.menu.menu-item', [
                    'item' => $child,
                    'level' => $level + 1,
                    'pageContent' => $pageContent,
                    'cateNew' => $cateNew,
                    'cateProduct' => $cateProduct,
                    'brands' => $brands
                ])
            @endforeach
        @endif
    </div>
</div>

{{-- Modal for this menu item --}}
<div class="modal fade" id="showModal{{ $item->id_menu }}" tabindex="-1" 
     aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <div>
                    <h5 class="modal-title" id="exampleModalLabel">Cập nhật menu</h5>
                    <p class="mb-0 text-muted small">
                        Thuộc: {{ $typeLabel }}
                        @if ($belongName)
                            : {{ $belongName }}
                        @endif
                    </p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                        aria-label="Close" id="close-modal"></button>
            </div>
            <form class="tablelist-form" 
                  action="{{ route('admin.menu.update', ['uuid' => $item->uuid]) }}" 
                  method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="customername-field" class="form-label">Tiêu đề VN</label>
                        <input type="text" name="name_vn" value="{{ $item->name_vn }}" 
                               class="form-control" placeholder="Nhập tên menu" required="">
                    </div>
                    @if ($item->type == 'link')
                        <div class="mb-3">
                            <label for="customername-field" class="form-label">Đường dẫn</label>
                            <input type="text" name="link" value="{{ $item->link }}" 
                                   class="form-control" placeholder="Nhập đường dẫn" required="">
                        </div>
                    @endif
                    <input type="hidden" name="stt" value="{{ $item->stt }}">
                    <div>
                        <label for="status-field" class="form-label">Tình trạng</label>
                        <select class="form-control" data-trigger="" name="status" 
                                id="status-field" required="">
                            <option value="1" {{ $item->status == 1 ? 'selected' : '' }}>Bật</option>
                            <option value="0" {{ $item->status == 0 ? 'selected' : '' }}>Tắt</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer" style="display: block;">
                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" 
                                data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-success" 
                                id="add-btn">Cập nhật</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>