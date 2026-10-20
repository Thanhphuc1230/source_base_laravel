# Laravel Base Source & Admin System

> Hệ thống quản trị và Base Source Laravel hiện đại với kiến trúc Layered (Controller → Service → Repository → Model), UI/UX chuẩn Modern Premium Retail, tối ưu hiệu năng và quy chuẩn Zero-Hardcode.

---

## 🚀 Tính năng cốt lõi

- **Kiến trúc Layered chuẩn mực**: Phân tách rõ ràng trách nhiệm `Controller → Service → Repository → Model`, không viết query SQL rải rác trong Controller.
- **Hệ Thống Multi-Agent AI Squad (`.agents/`)**: Tích hợp quy chuẩn phát triển phần mềm đa tác nhân chia vai trò rõ rệt (Tech Lead Orchestrator, Frontend Specialist, Backend Architect, QA Inspector) cùng bộ kỹ năng tự động hóa (Skills) giúp AI dựng website chính xác 100%, không bị phân tán hay hallucination.
- **Dynamic Website Data (Tuyệt đối KHÔNG Hardcode)**: Tự động dùng biến động từ `$web` (`$web->name_vn`, `$web->phone`, `$web->email`, `$web->logo`, `$web->map`, `$web->zalo`,...).
- **Chuyển Đổi Chủ Đề Website Siêu Tốc (`site:rebuild`)**: Lệnh Artisan độc quyền `php artisan site:rebuild --topic="<tên_topic>"` tự động dọn sạch media rác, reset database, tải hình ảnh WebP chất lượng cao và seed dữ liệu chuẩn ngành nghề (ví dụ: `watches`, `travel`,...).
- **Giao Diện Light Luxury & Modern Retail**: Phong cách thiết kế hiện đại, nền sáng thoáng (`bg-[#FAFAFA]`), chữ đen tuyền tương phản cao (`#111111`), điểm nhấn Vàng Kim Gold (`#D4AF37`), thẻ sản phẩm bo tròn `rounded-2xl` viền mờ `border-slate-100` với hiệu ứng nâng khối mượt mà `hover:-translate-y-1.5 hover:shadow-2xl`.
- **Hệ Thống Nút Liên Hệ Nổi (Floating Contact Buttons)**: Xếp dọc cố định góc phải màn hình gồm: Hotline Gọi điện (Đỏ rực kèm sóng lan `animate-ping`), Chat Zalo (Xanh Zalo), và Nút Cuộn Lên Đầu Trang (Move to Top Navy đậm thông minh tự ẩn/hiện khi cuộn).
- **Hiệu Ứng Cuộn Mượt (Scroll Animations)**: Tích hợp Intersection Observer API chuẩn (`.scroll-anim.fade-up`), các section và card trượt xuất hiện êm ái khi cuộn trang.
- **Slider Responsive 2 Banner (Desktop & Mobile)**: Quản lý banner linh hoạt theo đa ngôn ngữ (`image_desktop_vn`, `image_desktop_en`, `image_mobile_vn`, `image_mobile_en`). Render thẻ HTML5 `<picture>` chuẩn responsive.
- **CKEditor 4 Full Package + File Manager 1-Click**: Tích hợp trình soạn thảo CKEditor 4 đầy đủ tính năng, chọn ảnh 1-click mở trực tiếp Laravel File Manager, hỗ trợ định dạng danh sách số/chữ (`a.b.c.`, `1.2.3.`).
- **Nói Không Với CDN (Local Assets)**: 100% thư viện JS/CSS, Fonts (`RemixIcon`, `Select2`, `Choices.js`, `Flatpickr`, `Toastify`, `HKGrotesk`) được lưu trữ cục bộ trong `public/admin/libs/` & `public/admin/fonts/`.
- **Tập Trung Asset & Tách Biệt Code**: Không viết CSS inline hay thẻ `<style>`/`<script>` tĩnh trong file Blade. Không tạo file `.js`/`.css` lẻ tẻ cho từng component nhỏ; gom tương tác vào file chuẩn `theme-style.css` và `main.js`.
- **Blade Components Admin**: Trang quản trị dùng 100% Blade Components (`<x-admin.table-wrapper>`, `<x-admin.table-switch>`, `<x-admin.localized-fields>`, `<x-admin.image-upload>`).
- **Xử lý Media Tự Động**: Tự động convert WebP, nén ảnh thông minh, dọn dẹp ảnh vật lý trong bộ nhớ khi bản ghi tương ứng bị xóa khỏi CSDL.
- **Hệ thống Phân quyền (RBAC)**: Phân quyền chi tiết theo từng module và hành động với 61 permissions cho 13 modules.
- **SEO & Multi-Language**: Hỗ trợ SEO friendly, slug tự động đa ngôn ngữ (Việt - Anh) kiểm tra duy nhất toàn hệ thống (Cross-Tables), sitemap generator.

---

## 🏗️ Cấu trúc Kiến trúc (Layered Architecture)

```text
HTTP Request
     │
     ▼
┌─────────────┐
│ Controller  │  (Điều hướng Request, gọi Service & trả về View / JSON Response)
└──────┬──────┘
       │
       ▼
┌─────────────┐
│   Service   │  (Chứa toàn bộ Business Logic, xử lý Cache, Upload, WebP, Email,...)
└──────┬──────┘
       │
       ▼
┌─────────────┐
│ Repository  │  (Thực hiện các thao tác Query dữ liệu từ Database)
└──────┬──────┘
       │
       ▼
┌─────────────┐
│    Model    │  (Định nghĩa Eloquent Relationship, Accessors, Mutators, Fillable)
└─────────────┘
```

---

## 📂 Cấu trúc Thư mục Dự án

```text
source_laravel/
├── .agents/                    # Bộ quy tắc & Hệ thống Multi-Agent AI Squad
│   ├── AGENTS.md               # 10 Quy tắc cốt lõi & Master Orchestration
│   ├── roles/                  # 4 Chuyên viên chuyên biệt:
│   │   ├── orchestrator.md     # [Tech Lead] Tiếp nhận, phân rã task & quản lý commit
│   │   ├── frontend-specialist.md # [UI/UX & Frontend] Blade, Tailwind, Mobile 2-cols, Zero-CDN
│   │   ├── backend-architect.md # [Backend] Layered Architecture, Seeder, Service, Cache
│   │   └── qa-inspector.md     # [QA & Auditor] Quét hardcode, audit routes 200/302, dọn media
│   ├── skills/                 # Bộ kỹ năng tự động hóa on-demand:
│   │   ├── theme-rebuild/      # Tự động hóa chuyển đổi ngành hàng trọn gói
│   │   ├── hardcode-scanner/   # Quét phát hiện text/data cứng trong views
│   │   └── asset-optimizer/    # Đối chiếu CSDL & dọn dẹp uploads
│   ├── rules/                  # Ràng buộc kiến trúc chuyên sâu
│   ├── architecture.md         # Quy chuẩn kiến trúc Controller - Service - Repository
│   ├── frontend-design.md      # Quy chuẩn thiết kế Frontend (Light Luxury, Tailwind, Animations)
│   ├── frontend-data-map.md    # Bản đồ biến động $web & Blade Partials
│   ├── new-website-workflow.md # Quy trình tạo website mới trong 1 phiên chat
│   └── memory.md               # Lịch sử quyết định kiến trúc & UI/UX dài hạn
├── app/
│   ├── Console/Commands/       # Artisan Commands (site:rebuild, slug:clean,...)
│   ├── Http/
│   │   ├── Controllers/Admin/  # Controller quản trị Admin
│   │   ├── Controllers/Frontend/ # Controller phía người dùng
│   │   └── Requests/Admin/     # Validation Form Requests
│   ├── Models/                 # Eloquent Models
│   ├── Repositories/           # Data Access Layer
│   ├── Services/               # Business Logic Layer (ImageService, DataRemovalService,...)
│   └── Traits/                 # Reuse Traits (AutoImagePathsTrait, ImageHandlerTrait,...)
├── config/                     # Cấu hình hệ thống (filemanager.php, lfm.php, filesystems.php,...)
├── database/
│   ├── migrations/             # Migrations chuẩn CSDL
│   └── seeders/                # Seeders khởi tạo dữ liệu mẫu theo chủ đề
├── public/
│   ├── admin/                  # Assets giao diện Admin (Css, Js, Fonts, Libs)
│   ├── frontend/               # Assets giao diện Frontend (css/theme-style.css, js/scroll-animate.js,...)
│   ├── ckeditor/               # CKEditor 4 Full Build + Custom Plugins
│   └── uploads/                # Thư mục lưu trữ media & file upload (`public/uploads/...`)
└── resources/
    └── views/
        ├── admin/              # Giao diện Admin
        │   └── components/     # Blade Components dùng chung cho Admin
        └── frontend/           # Giao diện Frontend
            ├── components/     # Blade Components người dùng (product-card.blade.php,...)
            ├── modules/        # Views theo module (home, product, news, cart, checkout, page, contact,...)
            └── partials/       # Partials (header.blade.php, footer.blade.php, contact_buttons.blade.php,...)
```

---

## 📌 16 Quy tắc Cốt lõi của Dự án

1. **Tuyệt đối KHÔNG Hardcode**: Trong Blade View bắt buộc dùng biến động `$web` (`$web->name_vn`, `$web->phone`, `$web->email`, `$web->logo`, `$web->map`, `$web->zalo`).
2. **Kiến trúc Layered**: `Controller → Service → Repository → Model`. Không viết SQL query trực tiếp trong Controller.
3. **Blade Components Admin**: Dùng 100% Blade Components cho giao diện Admin (`<x-admin.table-wrapper>`, `<x-admin.table-switch>`,...).
4. **Cache & Performance**: Sau mọi thay đổi code/config chạy `php artisan optimize:clear` hoặc bấm nút **"Xoá cache"** trong Admin.
5. **Thẩm mỹ Giao diện Premium**: Thiết kế theo chuẩn *Modern Premium Web*, card bo góc `rounded-xl`/`rounded-2xl`, shadow nhẹ, hover mượt mà, padding section giới hạn `py-[15px]`, hiển thị sản phẩm/tin tức 2 cột trên di động.
6. **Không Dùng CDN (Local Assets)**: Tải toàn bộ thư viện JS/CSS, Font, Icon về lưu trữ cục bộ tại `public/admin/` và `public/frontend/`.
7. **Tách biệt Code CSS & JS**: Không viết CSS inline hay thẻ `<style>`/`<script>` tĩnh trong file Blade.
8. **Quy trình Git & Commit có Kiểm soát**: Không tự ý commit hay push; commit theo yêu cầu đơn lẻ và luôn hỏi lại ở task tiếp theo.
9. **Quy trình Chuyển đổi Chủ đề (Topic Rebuild)**: Khi đổi ngành hàng website, bắt buộc chạy Artisan command `php artisan site:rebuild --topic="<tên_topic>"` đầu tiên để dọn sạch media rác và nạp bộ dữ liệu mẫu chuẩn.
10. **Quy tắc Tập trung Asset (Không tạo file rác)**: Cấm tạo file `.js`/`.css` lẻ cho từng component nhỏ. Gom tương tác UI/UX về file chuẩn (`main.js`, `theme-style.css`), gom các nút UI nổi vào `contact_buttons.blade.php`.
11. **Quy chuẩn Comment & Commit**: 100% bằng tiếng Anh theo chuẩn Conventional Commits. Không viết tiếng Việt trong comment code và commit message. Giữ comment tối giản, sạch sẽ.
12. **Quy chuẩn Lưu trữ Media & Upload Đồng nhất**: Lưu trữ trực tiếp trong `public/uploads/{module}/`, hỗ trợ xen kẽ ảnh và video, preview video lấy khung hình tại `#t=0.5`.
13. **Quy chuẩn UI/UX Admin & Bảng Quản lý**: Đồng bộ chiều cao controls 38px, ô tìm kiếm chìm tinh tế, icon buttons vuông 32x32px ghost hover, status switch màu Primary đồng nhất.
14. **Bộ nhớ đệm Redis & Tối ưu Truy vấn**: Kích hoạt Redis cache driver, truy vấn danh mục phân cấp tối ưu hóa `whereIn` và lược bỏ `orderBy` dư thừa.
15. **Tham khảo chức năng cũ, Zero-Hardcode & Kiểm thử thực tế**: Tham khảo pattern cũ, không tự sáng tạo cấu trúc lạ; kiểm thử thực tế và xóa cache trước khi thông báo hoàn thành.
16. **Định vị Mã nguồn Siêu tốc (Instant Codebase Routing Protocol)**: Cấm quét thư mục mò mẫm. Tra cứu trực tiếp bảng ma trận [`.agents/codebase-matrix.md`](./.agents/codebase-matrix.md) để mở thẳng file mục tiêu.

---

## 💡 Hướng Dẫn Ra Lệnh Hiệu Quả Cho AI (AI Prompting Guide)

> Hệ thống được tích hợp **Ma trận Định vị Siêu tốc** (`.agents/codebase-matrix.md`) và **Quy tắc số 16 (Rule 16)**. Để AI phản hồi và thực thi nhanh nhất mà không phải quét (scan) file lần lượt, hãy áp dụng công thức ra lệnh dưới đây:

### 🎯 1. Công Thức Ra Lệnh Chuẩn (The 3-Element Formula)

```text
[Tên Module / Phạm vi] + [Hành động / Yêu cầu cụ thể] + [Quy chuẩn mong muốn]
```

* **Module / Phạm vi**: Ghi rõ tên module theo ma trận (ví dụ: `home`, `product`, `cart`, `checkout`, `system`, `news`, `menu`...).
* **Hành động cụ thể**: Nêu rõ thêm cột DB, sửa class Tailwind, đổi layout, sửa logic query...
* **Quy chuẩn mong muốn**: Nhắc ngắn gọn các ràng buộc như *Zero-Hardcode*, *Layered Architecture*, *Mobile 2 cột*, *Không inline CSS*.

---

### 📋 2. Các Mẫu Lệnh (Prompt Templates) Thực Chiến

#### Mẫu 1: Tùy biến Giao diện Frontend (Theme & Blade Views)
```text
Trên trang chủ (module `home`): Tùy biến lại khối Sản phẩm Nổi bật theo chuẩn Modern Premium UI. Bo góc card `rounded-2xl`, shadow nhẹ, hover scale 1.03. Đảm bảo hiển thị mobile 2 cột (`grid-cols-2`), dùng biến động $web, không inline CSS.
```

#### Mẫu 2: Bổ sung trường CSDL & Cập nhật Form Admin
```text
Module `product`: Bổ sung trường `shopee_url` (nullable). Hãy cập nhật migration gốc, file database/patch_schema.sql, Model Product, Form Request và hiển thị ô nhập trong tab thông tin chung của view edit Admin.
```

#### Mẫu 3: Đổi ngành hàng website mới (Theme Rebuild)
```text
Chuyển đổi website sang chủ đề Đồng hồ thời trang (`watches`). Kích hoạt skill theme-rebuild và đồng bộ lại màu sắc trong theme-style.css.
```

#### Mẫu 4: Sửa Logic Backend hoặc Tối ưu Cache / Query
```text
Module `cart`: Cập nhật logic tính phí ship trong CartService theo chính sách miễn phí cho đơn trên 1.000.000đ. Đảm bảo tuân thủ Layered Architecture, không viết logic trong Controller.
```

#### Mẫu 5: Rà soát & Kiểm thử chất lượng (QA Inspection)
```text
Đóng vai QA Inspector: Quét Zero-Hardcode toàn bộ view trong `resources/views/frontend/` và kiểm tra route `php artisan route:list`.
```

---

### ⚡ 3. Mẹo Tối Ưu Tốc Độ Thực Thi Cho AI
1. **Dùng lệnh `/compact` khi chat lâu:** Khi phiên trò chuyện dài, gõ `/compact` để dọn sạch ngữ cảnh đệm (context window), AI sẽ phản hồi nhanh hơn gấp 2-3 lần.
2. **Nêu tên file đích nếu đã biết:** Nếu bạn biết chính xác file cần sửa, hãy tag trực tiếp (ví dụ: `@[resources/views/frontend/modules/home/index.blade.php]`) để AI bỏ qua hoàn toàn khâu tìm kiếm.
3. **Commit có kiểm soát (Rule 8):** AI sẽ không tự ý commit. Khi hoàn thành task, bạn chỉ cần gõ *"Commit code giúp tôi"* để AI tạo commit tiếng Anh theo chuẩn Conventional Commits.

---

## 🔧 Cài đặt & Khởi chạy

```bash
# 1. Clone repository
git clone https://github.com/Thanhphuc1230/source_base_laravel.git
cd source_laravel

# 2. Cài đặt Composer Dependencies
composer install

# 3. Cấu hình môi trường
cp .env.example .env
php artisan key:generate

# 4. Khởi tạo Database & Nạp bộ dữ liệu mẫu theo chủ đề (Ví dụ: Đồng hồ)
php artisan site:rebuild --topic="watches"

# 5. Xóa Cache hệ thống
php artisan optimize:clear

# 6. Khởi chạy Server
php artisan serve
```

---

## 🔄 Lệnh Quản Trị Hệ Thống Hữu Ích

```bash
# Đổi chủ đề website sang ngành hàng mới (Tự động reset DB, tải demo WebP)
php artisan site:rebuild --topic="watches"

# Làm sạch toàn bộ các slug dính đuôi thừa trong CSDL về chuẩn SEO
php artisan slug:clean

# Xóa toàn bộ cache compiled view, config, route
php artisan optimize:clear
```

---

## 🔑 Tài khoản Quản trị Mặc định (Admin Panel)

Đường dẫn đăng nhập: `/admin/login`

| Vai trò | Email | Mật khẩu | Quyền hạn |
|---|---|---|---|
| **Admin** | `admin@gmail.com` | `@admin123` | Toàn quyền hệ thống (61 permissions) |
| **Manager** | `manager@gmail.com` | `@manager123` | Quản lý nội dung & sản phẩm (40+ permissions) |
| **Editor** | `editor@gmail.com` | `@editor123` | Biên tập tin tức & trang (14 permissions) |

---

## 📄 License & Contact

- **License**: MIT License.
- **Repository**: [GitHub Source Base Laravel](https://github.com/Thanhphuc1230/source_base_laravel)
