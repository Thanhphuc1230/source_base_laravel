# Quy chuẩn UI/UX Quản trị Admin & Bảng danh sách chuẩn (Admin UI/UX Standard)

> Quy tắc chi tiết áp dụng cho toàn bộ view quản trị Admin trong `resources/views/admin/`.

---

## 1. Đồng bộ Kích thước & Header Controls (38px Standard)
- **Đồng bộ Height**: Tất cả nút Thêm mới, Xóa đã chọn, Select danh mục lọc và Search Input phải đồng bộ chiều cao chuẩn `38px` (`rounded-2`, `form-control-sm` hoặc `height: 38px`).
- **Search Box Hiện đại**:
  - Tích hợp icon kính lúp chìm bên trái ô input (`position: relative`, icon bên trong).
  - Không dùng nút "Search" màu xanh lá rời rạc bị tràn viền.
  - Hỗ trợ phím Enter và cơ chế debounce typing tự động search.

---

## 2. Chuẩn hóa Bảng dữ liệu (Table Layout & Spacing)
- **Căn lề (Table Alignment)**:
  - Checkbox, STT, Hình ảnh đại diện, Switches, STT Reorder, Ngày tháng, và Cột Hành động (Action) căn giữa (`text-center`).
  - Tiêu đề (Tên sản phẩm/bài viết) và Danh mục căn trái (`text-start`).
- **Hiệu ứng hàng (Hover Row)**: Hiệu ứng hover hàng nền nhạt thanh lịch (`#f8fafc`).
- **Cột Danh mục (Chip / Badge Tag)**:
  - Chuyển văn bản thành dạng Badge thanh lịch (nền `#f1f5f9`, chữ `#334155`, font đứng, bo góc mềm).
  - Tuyệt đối không dùng chữ in nghiêng màu xanh lỗi thời.
- **Cột Thứ tự (STT Reorder)**: Ô input số thu nhỏ vừa vặn `52px`, căn giữa `text-center`, ẩn nút mũi tên spinner tăng giảm của input number.

---

## 3. Đồng bộ Trạng thái Switches (Single Accent Color)
- Mọi switch khi bật (**ON**) đồng nhất sử dụng 1 màu Primary Accent duy nhất (`#2563eb`).
- Khi tắt (**OFF**) là màu xám nhạt (`#cbd5e1`).
- Tuyệt đối không dùng nhiều màu xanh/cam/xanh lơ lộn xộn gây rối mắt.

---

## 4. Cột Hành động (Icon-only Ghost Buttons)
100% nút thao tác là icon vuông `32x32px`, bo góc nhẹ `5px`, nền soft ghost nhạt (`#f1f5f9`), icon `#64748b`:
- **Xem ngoài website**: Hover nền `#e0f2fe`, icon `#0284c7` (`ri-eye-line`).
- **Chỉnh sửa**: Hover nền `#e0e7ff`, icon `#4338ca` (`ri-pencil-line`).
- **Xóa**: Hover nền `#fee2e2`, icon `#ef4444` (`ri-delete-bin-line`).
- **Khoảng cách**: Luôn bọc trong container `d-inline-flex` với khoảng cách `gap: 6px;`, căn giữa cột.

---

## 5. Tái sử dụng Blade Components Admin
Trang Admin bắt buộc dùng Blade Components chuẩn, không viết HTML thủ công:
- `<x-admin.table-wrapper>`: Bọc toàn bộ bảng quản lý kèm header controls.
- `<x-admin.table-switch>`: Render toggle switch cập nhật AJAX trạng thái.
- `<x-admin.localized-fields>`: Các tab đa ngôn ngữ (`_vn`, `_en`).
- `<x-admin.image-upload>`: Khung upload ảnh đại diện preview chuẩn.
