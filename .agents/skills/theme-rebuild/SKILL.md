---
name: theme-rebuild
description: >-
  Kỹ năng tái thiết lập toàn bộ chủ đề website sang ngành hàng mới.
  Sử dụng khi người dùng yêu cầu đổi ngành hàng (ví dụ: Đồng hồ, Nội thất, Spa, Du lịch,...).
---

# Theme Rebuild Skill

Quy trình chuẩn hóa 3 bước để chuyển đổi theme an toàn và trọn gói:

## Bước 1: Chuẩn bị & Dọn dẹp Database + Media
1. Chạy lệnh Artisan độc quyền:
   ```bash
   php artisan site:rebuild --topic="<tên_topic>"
   ```
2. Lệnh này sẽ:
   - Dọn sạch media demo cũ trong `public/uploads/demo/`.
   - Chạy migration fresh hoặc xóa dữ liệu cũ các bảng `tp_*`.
   - Tải bộ ảnh WebP chất lượng cao phù hợp ngành hàng về local.
   - Nạp seeder chuẩn (`$web`, slider, dự án, sản phẩm, tin tức, cấu hình SEO).

## Bước 2: Tinh chỉnh Giao diện & Typography
1. Tùy biến CSS trong `public/frontend/css/theme-style.css` (Màu nhấn Accent color, padding, hiệu ứng lướt).
2. Tinh chỉnh các Blade view đặc thù ngành hàng (như section thông số xưởng, bảng giá, hoặc form đặt lịch).
3. Đảm bảo toàn bộ thẻ ảnh sử dụng `{{ asset($item->image) }}` và dữ liệu lấy từ biến `$web` hoặc `$homeData`.

## Bước 3: Kiểm định & Xóa Cache
1. Chạy lệnh xóa cache:
   ```bash
   php artisan optimize:clear
   ```
2. Bàn giao cho `qa-inspector` chạy kịch bản kiểm tra toàn bộ routes và assets.
