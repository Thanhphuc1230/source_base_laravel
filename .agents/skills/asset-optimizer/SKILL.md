---
name: asset-optimizer
description: >-
  Kỹ năng audit, thanh lọc và tối ưu hóa tài nguyên media trong public/uploads/ đối chiếu với cơ sở dữ liệu.
  Sử dụng khi cần dọn rác project, giải phóng dung lượng trước khi bàn giao hoặc chuyển đổi ngành hàng.
---

# Asset Optimizer Skill

## 1. Mục tiêu
- Đối chiếu tất cả các file hình ảnh trong thư mục `public/uploads/` với các bản ghi trong cơ sở dữ liệu (`tp_systems`, `tp_abouts`, `tp_sliders`, `tp_projects`, `tp_products`, `tp_news`,...).
- Loại bỏ các file "mồ côi" (orphaned files) không còn được bất kỳ bản ghi nào trỏ đến.
- Bảo vệ các asset hệ thống bắt buộc (như `uploads/bg/auth-one-bg.jpg`, `uploads/icon/vietnam.png`,...).

## 2. Quy trình Thực thi
1. **Audit (Kiểm kê):**
   - Trích xuất toàn bộ đường dẫn ảnh từ các bảng database.
   - Thêm danh sách whitelist (các file giao diện hệ thống cần giữ).
   - Duyệt đệ quy cây thư mục `public/uploads/`.
2. **So sánh (Diffing):**
   - Phân loại file thành 2 nhóm: **Active** (Đang sử dụng) và **Unused** (Rác).
3. **Thanh lọc An toàn (Pruning):**
   - Xóa các file thuộc nhóm Unused.
   - Dọn sạch các thư mục con rỗng.
4. **Xóa Cache:**
   ```bash
   php artisan optimize:clear
   ```
