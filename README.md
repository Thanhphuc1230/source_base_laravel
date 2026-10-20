# Laravel Base Source & Admin System

> Hệ thống quản trị và mã nguồn nền tảng (Base Source) Laravel hiện đại theo kiến trúc Layered (`Controller → Service → Repository → Model`), UI/UX chuẩn Modern Premium Retail, tích hợp Multi-Agent AI Squad (`.agents/`) và quy chuẩn Zero-Hardcode.

---

## 🏗️ 1. Kiến Trúc Phân Tầng (Layered Architecture)

```text
HTTP Request ──► Controller ──► Service ──► Repository ──► Model (Eloquent) ──► Database
                      │             │
                      ▼             ▼
              Validation / JSON   Business Logic, Cache, Upload WebP
```

* **Controller**: Chỉ tiếp nhận Request, gọi Service và trả về Response/View. Không viết SQL query.
* **Service**: Xử lý toàn bộ nghiệp vụ, transaction, cache Redis, email, xử lý ảnh.
* **Repository**: Đảm nhiệm truy vấn Eloquent DB qua Interface.
* **Model**: Khai báo Trait (`AutoImagePathsTrait`, `SlugHandlerTrait`), Accessor đa ngôn ngữ.

---

## 📌 2. 16 Quy Tắc Cốt Lõi Dự Án

| STT | Quy tắc | Tóm tắt yêu cầu |
|:---:|---|---|
| **01** | **Zero-Hardcode** | Dùng biến động 100% từ `$web` (`name_vn`, `phone`, `email`, `logo`, `map`, `zalo`...). |
| **02** | **Layered Architecture** | Nghiêm cấm query DB trong Controller; tuân thủ Controller → Service → Repository. |
| **03** | **Blade Components** | Admin dùng 100% components (`table-wrapper`, `table-switch`, `localized-fields`...). |
| **04** | **Cache Discipline** | Xóa cache sau mọi thay đổi: `php artisan optimize:clear`. |
| **05** | **Modern UI/UX** | Giao diện chuẩn Modern Premium; padding section `py-[15px]`; mobile chia 2 cột (`grid-cols-2`). |
| **06** | **Zero-CDN** | 100% assets (JS, CSS, Font, Icons) lưu trữ cục bộ trong `public/`. |
| **07** | **No Inline CSS/JS** | Không viết `<style>`, `<script>` tĩnh hay `style="..."` trong Blade. |
| **08** | **Controlled Git Commit** | Không tự ý commit; commit theo yêu cầu đơn lẻ và luôn hỏi lại ở task tiếp theo. |
| **09** | **Topic Rebuild** | Khi đổi ngành hàng: bắt buộc chạy `php artisan site:rebuild --topic="<tên_topic>"` đầu tiên. |
| **10** | **Tập trung Asset** | Gom JS vào `main.js`, CSS vào `theme-style.css`, nút nổi vào `contact_buttons.blade.php`. |
| **11** | **English Commits & Code** | 100% comment và commit message bằng tiếng Anh chuẩn Conventional Commits. |
| **12** | **Unified Media** | Lưu trực tiếp tại `public/uploads/{module}/`; preview video lấy frame tại `#t=0.5`. |
| **13** | **Modern Admin UI** | Controls cao 38px, icon action ghost buttons 32x32px, status switch đồng nhất xanh Primary. |
| **14** | **Redis & Query Optimize**| Kích hoạt Redis cache; lọc danh mục phân cấp tối ưu `whereIn`, bỏ `orderBy` thừa. |
| **15** | **Strict Verification** | Tham khảo pattern cũ; kiểm thử cú pháp và route thực tế trước khi báo hoàn thành. |
| **16** | **Instant Routing Map** | Tra cứu ngay [`.agents/codebase-matrix.md`](./.agents/codebase-matrix.md); cấm quét tìm file mò mẫm. |

---

## 💡 3. Hướng Dẫn Ra Lệnh Hiệu Quả Cho AI (Prompting Guide)

### 🎯 Công thức 3 thành phần chuẩn
```text
[Tên Module / Phạm vi] + [Hành động cụ thể] + [Quy chuẩn mong muốn]
```

### 📋 Mẫu lệnh thực chiến có sẵn
* **Frontend Theme (Trang chủ/Blade):**
  > *"Trên trang chủ (module `home`): Tùy biến lại khối Sản phẩm Nổi bật theo chuẩn Modern Premium UI. Bo góc card `rounded-2xl`, shadow nhẹ, hover scale 1.03. Đảm bảo mobile 2 cột (`grid-cols-2`), dùng biến động `$web`, không inline CSS."*
* **Thêm trường CSDL & Admin Form:**
  > *"Module `product`: Bổ sung trường `shopee_url` (nullable). Cập nhật migration gốc, file `database/patch_schema.sql`, Model Product, Form Request và hiển thị trong tab thông tin chung của view edit Admin."*
* **Đổi ngành hàng mới:**
  > *"Chuyển website sang chủ đề Đồng hồ (`watches`). Kích hoạt skill theme-rebuild và đồng bộ lại màu sắc trong `theme-style.css`."*
* **Sửa Logic Backend / Service:**
  > *"Module `cart`: Cập nhật logic tính phí ship trong CartService miễn phí cho đơn trên 1.000.000đ. Tuân thủ Layered Architecture, không viết trong Controller."*
* **Kiểm thử chất lượng (QA):**
  > *"Đóng vai QA Inspector: Quét Zero-Hardcode toàn bộ view `resources/views/frontend/` và kiểm tra route `php artisan route:list`."*

---

## ⚡ 4. Đóng Gói Codebase Tức Thì Cho AI (Repomix)

Dự án đã tích hợp sẵn cấu hình [`repomix.config.json`](./repomix.config.json) giúp nén toàn bộ 400+ file mã nguồn cốt lõi thành 1 file XML duy nhất (đã tự động lọc `vendor/`, `node_modules/`, `uploads/`, `logs`):

1. **Chạy lệnh đóng gói:**
   ```bash
   npx repomix
   ```
2. **Ra lệnh cho AI kèm file nén (Đọc hiểu 100% trong 1-2 giây):**
   > `@[repomix-output.xml] hãy phân tích logic giỏ hàng và đề xuất phương án tối ưu giúp tôi`

> [!TIP]
> **Dọn dẹp ngữ cảnh khi chat lâu:** Gõ lệnh `/compact` trong phiên chat để giải phóng bộ nhớ đệm, giúp AI tăng tốc độ xử lý gấp 2-3 lần.

---

## 🔧 5. Cài Đặt & Lệnh Thường Dùng

```bash
# 1. Khởi tạo môi trường
composer install
cp .env.example .env
php artisan key:generate

# 2. Khởi tạo Database & Seed dữ liệu mẫu theo chủ đề (Ví dụ: watches / travel / cosmetics)
php artisan site:rebuild --topic="watches"

# 3. Làm sạch toàn bộ slug thừa trong CSDL về chuẩn SEO
php artisan slug:clean

# 4. Xóa cache hệ thống
php artisan optimize:clear

# 5. Đóng gói mã nguồn cho AI
npx repomix

# 6. Khởi chạy Server
php artisan serve
```

---

## 🔑 6. Tài Khoản Quản Trị Mặc Định

Đường dẫn: `/admin/login`

| Vai trò | Email | Mật khẩu | Quyền hạn |
|---|---|---|---|
| **Admin** | `admin@gmail.com` | `@admin123` | Toàn quyền hệ thống (61 permissions) |
| **Manager** | `manager@gmail.com` | `@manager123` | Quản lý nội dung & sản phẩm |
| **Editor** | `editor@gmail.com` | `@editor123` | Biên tập tin tức & trang tĩnh |

---

## 📄 Bản Quyền & Tham Khảo
* **License**: MIT License.
* **Hệ thống AI Multi-Agent**: Chi tiết tại thư mục [`.agents/`](./.agents/AGENTS.md).
