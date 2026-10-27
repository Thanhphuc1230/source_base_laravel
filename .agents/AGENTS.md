# AGENTS.md – Quy tắc cốt lõi & Đội ngũ Multi-Agent dự án Base

> File này được AI tự động nạp mỗi phiên làm việc. Hệ thống vận hành theo mô hình **Tổ đội Kỹ thuật Đa tác nhân (Multi-Agent Engineering Squad)**.

---

## 👥 ĐỘI NGŨ MULTI-AGENT CHUYÊN MÔN HÓA (ROLES)

Khi nhận prompt từ người dùng, AI sẽ phân tách nhiệm vụ và đóng vai trò tương ứng:

| Role | File định nghĩa | Trách nhiệm chính |
|---|---|---|
| **Lead Orchestrator** | [`.agents/roles/orchestrator.md`](./roles/orchestrator.md) | Phân rã yêu cầu, điều phối chuyên viên, kiểm soát Git & Commit (Rule 8). |
| **Frontend Specialist** | [`.agents/roles/frontend-specialist.md`](./roles/frontend-specialist.md) | Blade views, Tailwind CSS, Responsive (Mobile 2 cột), Zero-CDN, Zero-Hardcode. |
| **Backend Architect** | [`.agents/roles/backend-architect.md`](./roles/backend-architect.md) | Layered Architecture (`Controller → Service → Repository → Model`), Seeder, DB. |
| **QA Inspector** | [`.agents/roles/qa-inspector.md`](./roles/qa-inspector.md) | Kiểm thử độc lập, quét Zero-Hardcode, xác thực Route 200/302, audit media. |

---

## ⚡ BỘ KỸ NĂNG TỰ ĐỘNG HÓA (SKILLS)

| Kỹ năng | File cấu hình | Mô tả |
|---|---|---|
| **`theme-rebuild`** | [`.agents/skills/theme-rebuild/SKILL.md`](./skills/theme-rebuild/SKILL.md) | Chuyển đổi toàn bộ chủ đề website sang ngành hàng mới (`site:rebuild`). |
| **`hardcode-scanner`** | [`.agents/skills/hardcode-scanner/SKILL.md`](./skills/hardcode-scanner/SKILL.md) | Quét tự động mã nguồn Blade Views để phát hiện vi phạm Rule 1. |
| **`asset-optimizer`** | [`.agents/skills/asset-optimizer/SKILL.md`](./skills/asset-optimizer/SKILL.md) | Audit và thanh lọc thư mục `public/uploads/` đối chiếu cơ sở dữ liệu. |

---

## QUY TẮC SỐ 1 – TUYỆT ĐỐI KHÔNG HARDCODE

Khi viết blade view, **KHÔNG ĐƯỢC** ghi cứng tên công ty, SĐT, email, địa chỉ, logo, Google Map URL, link mạng xã hội.

Phải dùng biến động từ `$web`. Xem chi tiết: `.agents/frontend-data-map.md`

| Sai ❌ | Đúng ✅ |
|---|---|
| `Base` | `{{ $web->name_vn }}` |
| `0979.248.298` | `{{ $web->phone }}` |
| `contact@base.local` | `{{ $web->email }}` |
| `src="https://www.google.com/maps/embed..."` | `src="{{ $web->map }}"` |
| `<img src="/images/logo/logo.png">` | `<img src="{{ asset($web->logo) }}">` |

---

## QUY TẮC SỐ 2 – KIẾN TRÚC LAYERED

```
Controller → Service → Repository → Model
```
Không viết query SQL trực tiếp trong Controller. Xem chi tiết: `.agents/architecture.md`

---

## QUY TẮC SỐ 3 – BLADE COMPONENTS ADMIN

Trang Admin dùng 100% Blade Components, không viết HTML bảng thủ công:
`<x-admin.table-wrapper>`, `<x-admin.table-switch>`, `<x-admin.localized-fields>`, `<x-admin.image-upload>`

---

## QUY TẮC SỐ 4 – CACHE

Sau mọi thay đổi code/config: `php artisan optimize:clear`
Admin có nút **"Xoá cache"** tại: Admin → Hệ thống → Xoá cache

---

## QUY TẮC SỐ 5 – THẨM MỸ GIAO DIỆN CHUẨN BASE: CẤU TRÚC VỮNG CHẮC & TRÁNH "CHẤT AI"

Chi tiết xem tại: [`.agents/rules/03-anti-ai-design-system.md`](./rules/03-anti-ai-design-system.md)

- **Nguyên tắc gốc (Anti-AI Principles):** 
  - Thiết kế từ sản phẩm & nghiệp vụ thật của doanh nghiệp. Ưu tiên sự gọn gàng, cụ thể, có lực thị giác.
  - Mỗi màn hình (viewport) có đúng 1 điểm nhấn thị giác (ảnh lớn, mảng màu thương hiệu lớn, hoặc số liệu năng lực thật).
  - Đa dạng bố cục section: chia 2 cột lệch (60/40), bảng thông số kỹ thuật (Spec Table), quy trình từng bước, dải CTA. Toàn trang chỉ có tối đa 1 loại card duy nhất (dành cho Danh mục Sản phẩm).
  - ❌ CẤM khối 4 ô thống kê đều nhau nếu là số liệu ảo; CẤM icon đặt trong ô vuông/tròn nền pastel; CẤM badge pill chữ hoa nhỏ dẫn đường trên tiêu đề section; CẤM pill tên công ty trên Hero; CẤM emoji; CẤM gradient cầu vồng/neon.
- **Hệ thống màu sắc theo bản sắc ngành hàng (Niche Color System):**
  - Màu chính thương hiệu (Brand Primary) dùng cho các MẢNG LỚN CÓ LỰC (dải CTA, footer, banner).
  - Quy tắc nhịp nền xen kẽ: Nền Trắng → Nền màu thương hiệu đậm chữ trắng (ít nhất 2 section trên trang chủ) → Nền rất nhạt (5-8% tint) → Ảnh tràn lề. CẤM 3 section liên tiếp cùng màu nền.
  - Màu nhấn (Accent): CHỈ dùng cho nút CTA chính và tối đa 1 điểm nhấn mỗi viewport.
- **Lưới bố cục, Khoảng cách & Bo góc thống nhất:**
  - Container chuẩn 1200px (`max-w-[1200px]`, `.container-b2b`).
  - Bo góc thống nhất: Duy nhất **8px** (`rounded-[8px]`). CẤM nút tròn dạng pill (`rounded-full`).
  - Bóng đổ có kiểm soát: Chỉ dùng bóng nhẹ (`0 10px 30px rgba(0, 0, 0, 0.08)`) khi hover card hoặc header dính. CẤM shadow mềm lớn lan tràn.
- **Thẻ sản phẩm cân bằng chiều cao tuyệt đối (Equal Height Product Cards):**
  - Khung ảnh vuông chuẩn 1:1 (`aspect-square`), `<img>` dùng `absolute inset-0 object-cover`.
  - Cột bọc: `w-full h-full flex flex-col`. Thẻ `<article>`: `h-full flex flex-col justify-between`.
  - Tiêu đề khóa chiều cao tối thiểu `min-h-[3rem]` (2 dòng chữ); Thông số khóa tối thiểu `min-h-[46px]`.
  - Hàng giá & Nút CTA đặt `mt-auto`, đảm bảo luôn nằm thẳng tắp trên cùng một đường kẻ ngang đáy thẻ.
- **Nút/Icon Header & Floating Buttons:**
  - **Không để SĐT trên Header:** Hotline & Zalo tập trung tại floating action button bên phải (`contact_buttons.blade.php`).
  - **Icon Giỏ hàng:** Giữ nguyên bản mộc, KHÔNG bọc hình tròn/vuông nền màu (`no border-radius, no background`), icon kèm badge nhỏ.
  - **Lá cờ Ngôn ngữ:** KHÔNG dùng border-radius hay background/nền, hiển thị trực tiếp 2 lá cờ nằm ngang (`flex items-center gap-2`), mộc, phẳng.
  - **Floating Action Buttons:** Nằm tập trung trong `contact_buttons.blade.php`: Hotline đỏ chuông rung, Zalo xanh, Back-to-top tối màu.
- **Chuyển động có kiểm soát (Controlled Transitions):**
  - Scroll reveal: Trượt 20px + fade 600ms, chỉ chạy 1 lần duy nhất khi cuộn tới (`.scroll-reveal.is-visible`).
  - Bắt buộc tuân thủ `@media (prefers-reduced-motion: reduce)`: Tắt toàn bộ animation khi người dùng bật chế độ giảm chuyển động.
- **Responsive:** Chuẩn Responsive 100%. Mobile: tin tức và sản phẩm bắt buộc hiển thị **2 cột** (`grid grid-cols-2 gap-3`).

---

## QUY TẮC SỐ 6 – KHÔNG DÙNG CDN (DOWNLOAD LOCAL ASSETS)

- Tải local toàn bộ Font ("Noto Sans", "Roboto"), Tailwind CSS (compiled), FontAwesome, và thư viện Scroll Animation vào `public/frontend/`.
- Không nhúng CDN từ bên thứ ba.

---

## QUY TẮC SỐ 7 – TÁCH BIỆT CODE CSS & JS (NO INLINE/EMBEDDED)

* **Không viết** code CSS hoặc JS tĩnh trực tiếp trong các file Blade (thẻ `<style>`, `<script>`). Phải tổ chức chúng vào các file `.css`, `.js` chuẩn cấu trúc trong thư mục `public/`.
* **Không viết** thuộc tính CSS inline (`style="..."`) trực tiếp vào các thẻ HTML, trừ trường hợp dữ liệu đó thực sự động và được cấu hình từ database (như màu sắc, font-size cấu hình từ Admin).

---

## QUY TẮC SỐ 8 – QUY TRÌNH GIT & COMMIT CÓ KIỂM SOÁT (TUYỆT ĐỐI BẮT BUỘC)

1. **KHÔNG TỰ Ý COMMIT HAY PUSH:** AI tuyệt đối KHÔNG ĐƯỢC tự động chạy lệnh `git commit` hoặc `git push` trong bất kỳ trường hợp nào trừ khi user ra lệnh trực tiếp trong phiên chat đó.
2. **COMMIT THEO YÊU CẦU ĐƠN LẺ:** Khi user yêu cầu *"Commit code giúp tôi"*, AI CHỈ ĐƯỢC COMMIT DUY NHẤT LẦN ĐÓ cho công việc/task hiện tại.
3. **LUÔN HỎI LẠI Ở TASK TIẾP THEO:** Sau khi hoàn thành một task mới tiếp theo, AI KHÔNG ĐƯỢC tự động commit dựa trên lệnh cũ. AI phải dừng lại và hỏi user: *"Tôi đã hoàn thành task [Tên Task]. Bạn có muốn tôi commit các thay đổi này không?"*.
4. **CHUẨN COMMITS TIẾNG ANH (CONVENTIONAL COMMITS):** Commit message BẮT BUỘC viết bằng tiếng Anh theo chuẩn Conventional Commits (ví dụ: `feat: ...`, `fix: ...`, `refactor: ...`, `chore: ...`). Tuyệt đối KHÔNG viết commit message bằng tiếng Việt.

---

## QUY TẮC SỐ 9 – QUY TRÌNH CHUYỂN ĐỔI CHỦ ĐỀ/THEME (TOPIC REBUILD)

Khi người dùng yêu cầu đổi chủ đề website sang ngành hàng mới (ví dụ: Đồng hồ, Mỹ phẩm, Spa, Du lịch, Nội thất...):
1. BẮT BUỘC thực thi Artisan command đầu tiên: `php artisan site:rebuild --topic="<tên_topic>"` (Để hệ thống dọn sạch media rác, reset DB và seed đúng bộ dữ liệu/hình ảnh của ngành hàng đó).
2. SAU ĐÓ MỚI tiến hành tùy biến file Blade và CSS (`theme-style.css`).
3. Chạy lệnh xóa cache: `php artisan optimize:clear`.

---

## QUY TẮC SỐ 10 – QUY TẮC TẬP TRUNG ASSET (KHÔNG TẠO FILE JS/CSS RÁC)

- **Cấm phân mảnh File:** Tuyệt đối KHÔNG TẠO thêm các file `.js` hoặc `.css` lẻ cho từng component nhỏ (ví dụ: KHÔNG tạo `back-to-top.js`, `cart-fix.js`, `popup.js`).
- **Gom về File chuẩn:** 
  - Mọi JS tương tác UI/UX phải viết nối tiếp vào `public/frontend/js/main.js` (hoặc `resources/views/frontend/partials/script.blade.php`).
  - Mọi CSS tùy biến phải viết vào `public/frontend/css/theme-style.css`.
- **Tái sử dụng Partials:** Các phần tử UI nổi (như nút Cuộn lên đầu trang, Hotline, Zalo) BẮT BUỘC nằm chung trong `resources/views/frontend/partials/contact_buttons.blade.php`.
- **Tối giản Code:** Ưu tiên dùng Vanilla JS ngắn gọn hoặc Tailwind CSS classes thay vì viết thêm các thư viện/script cồng kềnh.

---

## QUY TẮC SỐ 11 – QUY CHUẨN COMMENT & COMMIT (ENGLISH ONLY & MINIMAL COMMENTS)

- **Ngôn ngữ 100% tiếng Anh (No Vietnamese in Code Comments & Commits):**
  - Mọi comment trong toàn bộ mã nguồn (PHP, Blade, JS, CSS, SQL, Config...) BẮT BUỘC viết bằng tiếng Anh. Tuyệt đối KHÔNG viết comment bằng tiếng Việt trong code.
  - Mọi Commit message BẮT BUỘC viết bằng tiếng Anh theo chuẩn Conventional Commits. Tuyệt đối KHÔNG viết commit message bằng tiếng Việt.
- **Tối giản & Giữ sạch mã nguồn (Minimal & Clean Comments):**
  - Tuyệt đối KHÔNG sửa đổi comment tràn lan hoặc thêm comment giải thích thừa thãi, rườm rà.
  - Ưu tiên code tự tường minh (Self-documenting code). Chỉ viết comment khi thật sự cần thiết để giải thích lý do (rationale) hoặc logic nghiệp vụ phức tạp.

---

## QUY TẮC SỐ 12 – QUY CHUẨN LƯU TRỮ MEDIA & UPLOAD ĐỒNG NHẤT (UNIFIED MEDIA STORAGE)

- **Đồng nhất thư mục lưu trữ:** Mọi hình ảnh và video tải lên từ Admin (`ImageService`, `ImageHandlerTrait`, `multiImage`) đều được lưu trữ trực tiếp trong thư mục module `public/uploads/{module}/` (hoặc theo ngày tháng `uploads/{module}/{Y}/{m}/`). Tuyệt đối KHÔNG tự ý tách riêng thư mục con như `videos/`.
- **Hỗ trợ Media đa phương tiện xen kẽ:** Thư viện ảnh chi tiết (`image_detail`) hỗ trợ upload cả ảnh (tự động chuyển WebP) và video (MP4, WebM, MOV), bảo toàn đúng thứ tự sắp xếp xen kẽ do người dùng chọn.
- **Khung hình Preview Video:** Mọi thẻ video preview ở cả Admin và Frontend bắt buộc dùng cú pháp `<video src="...#t=0.5" preload="metadata" muted playsinline>` để lấy khung hình tại giây 0.5 làm ảnh đại diện, tránh khung đen tĩnh.

---

## QUY TẮC SỐ 13 – QUY CHUẨN UI/UX ADMIN & BẢNG QUẢN LÝ (MODERN ADMIN LIST STANDARD)

- **Header Controls (Bộ lọc & Ô tìm kiếm):**
  - **Đồng bộ Height:** Tất cả nút Thêm mới, Xóa đã chọn, Select danh mục và Search Input phải đồng bộ chiều cao chuẩn `38px` (`rounded-2`).
  - **Search Box:** Tích hợp icon kính lúp chìm bên trái ô input, không dùng nút "Search" màu xanh lá rời rạc bị tràn viền. Hỗ trợ Enter và debounce typing tự động search.
- **Cột Danh mục (Chip / Badge Tag):** Chuyển văn bản thành dạng Badge thanh lịch (`#f1f5f9`, chữ `#334155` font đứng), không dùng chữ in nghiêng màu xanh.
- **Đồng bộ Status Switches:** Mọi switch khi bật (ON) đồng nhất dùng 1 màu Primary Accent duy nhất (`#2563eb`), khi tắt (OFF) là màu xám nhạt (`#cbd5e1`). Không dùng nhiều màu xanh/cam/xanh lơ gây rối mắt.
- **Cột Thứ tự (STT Reorder):** Ô input số thu nhỏ vừa vặn `52px`, căn giữa `text-center`, ẩn nút mũi tên spinner của number input.
- **Cột Hành động (Icon-only Ghost Buttons):**
  - 100% nút dạng icon vuông `32x32px`, bo góc nhẹ `5px`, nền soft ghost nhạt (`#f1f5f9`), icon `#64748b`:
    * Xem ngoài web: Hover nền `#e0f2fe`, icon `#0284c7` (`ri-eye-line`).
    * Sửa: Hover nền `#e0e7ff`, icon `#4338ca` (`ri-pencil-line`).
    * Xóa: Hover nền `#fee2e2`, icon `#ef4444` (`ri-delete-bin-line`).
  - Khoảng cách giữa các nút luôn dùng `d-inline-flex` với `gap: 6px;`, căn giữa cột.
- **Table Alignment & Spacing:** Checkbox/STT/Hình ảnh/Switches/STT/Ngày/Action căn giữa (`text-center`); Tiêu đề và Danh mục căn trái (`text-start`). Hiệu ứng hover hàng nền nhạt `#f8fafc`.

---

## QUY TẮC SỐ 14 – BỘ NHỚ ĐỆM REDIS & TỐI ƯU TRUY VẤN DỮ LIỆU (REDIS CACHE & OPTIMIZED QUERIES)

- **Redis Cache Driver:** Hệ thống kích hoạt Redis Cache (`CACHE_DRIVER=redis`) để tăng tốc độ phản hồi và giảm tải MySQL.
- **Truy vấn danh mục phân cấp (Hierarchy Category Query):** Khi lọc theo danh mục trong Repository, bắt buộc truy vấn gồm cả danh mục con (`whereIn`), chỉ `select` các cột cần thiết cho quan hệ, và loại bỏ mệnh đề `orderBy` dư thừa.

---

## QUY TẮC SỐ 15 – THAM KHẢO CHỨC NĂNG CŨ, TUYỆT ĐỐI ZERO-HARDCODE & KIỂM THỬ XÁC MINH TRƯỚC KHI BÁO HOÀN THÀNH (FEATURE REFERENCE, ZERO-HARDCODE & RIGOROUS TESTING)

1. **Tham khảo chức năng cũ tương tự (Pattern Reference & Consistency):**
   - Khi tạo hoặc nâng cấp bất kỳ chức năng mới nào, AI BẮT BUỘC phải đọc và tham chiếu các module/chức năng tương tự đã chạy ổn định trong codebase (Controllers, Services, Repositories, Models, Requests, Blade components, UI/UX conventions, Traits).
   - Bảo đảm tái sử dụng đúng chuẩn kiến trúc hiện hành (`Controller → Service → Repository → Model`), không tự sáng tạo cấu trúc lạ làm phân mảnh hệ thống.
2. **Tuyệt đối không Hardcode (Zero-Hardcode Mandatory):**
   - Mọi chức năng mới BẮT BUỘC dùng biến động từ CSDL, helper, `$web`, route name, lang helper.
   - Tuyệt đối KHÔNG hardcode chuỗi hiển thị, link tĩnh, email, hotline, hoặc magic number.
3. **Kiểm thử thực tế, debug triệt để trước khi báo hoàn thành (Rigorous Verification & Debugging):**
   - AI KHÔNG ĐƯỢC báo hoàn thành task khi chưa kiểm tra và debug thực tế.
   - BẮT BUỘC thực hiện các bước xác thực:
     - Kiểm tra cú pháp (Syntax check, Blade compile `php artisan view:cache` rồi `php artisan view:clear`).
     - Kiểm tra Routes (`php artisan route:list`).
     - Xóa cache hệ thống (`php artisan optimize:clear`).
     - Rà soát các trường hợp ngoại lệ (Edge cases, validation rules, permissions, DB transactions).
   - Chỉ khi đã xác minh chức năng chạy đúng 100% và không còn bất kỳ lỗi nào, AI mới được thông báo hoàn thành task cho người dùng.

---

## QUY TẮC SỐ 16 – ĐỊNH VỊ MÃ NGUỒN SIÊU TỐC QUA AGENTS (INSTANT CODEBASE ROUTING PROTOCOL)

- **Cấm quét thư mục mù mờ (No Blind Recursive Scanning):**
  - Khi nhận câu lệnh/task từ người dùng, AI TUYỆT ĐỐI KHÔNG ĐƯỢC chạy các lệnh tìm kiếm mò mẫm (`find`, `dir -r`, `Get-ChildItem -Recurse`) hoặc đọc lần lượt từng thư mục dự án để tìm file.
- **Quy trình định vị 3 bước tức thì:**
  1. **Bước 1 (Tra cứu Ma Trận):** Đọc ngay bảng ánh xạ [`.agents/codebase-matrix.md`](./codebase-matrix.md) và [`.agents/frontend-data-map.md`](./frontend-data-map.md).
  2. **Bước 2 (Mở thẳng File đích):** Xác định chính xác danh sách file mục tiêu (Controller, Service, Repository, Blade View, CSS/JS) tương ứng với nghiệp vụ được yêu cầu và mở trực tiếp các file này.
  3. **Bước 3 (Thực thi đúng Phân vai):** Chuyển giao đúng chuyên trách (`backend-architect` nếu dính logic DB/Controller, `frontend-specialist` nếu là Blade/CSS/JS, `qa-inspector` để kiểm thử).

---

## QUY TẮC SỐ 17 – QUY TRÌNH TẠO CHỨC NĂNG MỚI & VÒNG LẶP KIỂM THỬ TỰ THÂN (AUTONOMOUS SELF-TESTING LOOP & ZERO-USER-DEBUGGING PROTOCOL)

### 1. Quy trình chuẩn khi tạo chức năng mới (Layered Feature Pipeline)
Khi xây dựng chức năng/module mới, bắt buộc triển khai tuần tự theo 6 tầng kiến trúc, không nhảy cóc:
1. **Database Schema:** Cập nhật file migration gốc trong `database/migrations/` và đồng thời ghi câu lệnh `ALTER TABLE` vào `database/patch_schema.sql` (Rule 12).
2. **Model & Traits:** Khai báo `$fillable`, `$casts`, gán `AutoImagePathsTrait`, quan hệ Eloquent (`belongsTo`, `hasMany`).
3. **Repository Layer:** Khai báo contract trong `app/Repositories/Interfaces/` trước, sau đó implement trong `app/Repositories/Eloquent/`.
4. **Service Layer:** Viết business logic, transaction, dọn cache bằng tags trong `app/Services/`.
5. **Controller & Form Request:** Viết Form Request xác thực input (`app/Http/Requests/`), Controller chỉ điều hướng và gọi Service/Repository, trả về View hoặc JSON.
6. **Blade Views & Assets:** Dùng Blade Components Admin (`<x-admin.table-wrapper>`), áp dụng chuẩn UI 38px controls, ghost action buttons, CSS viết vào `theme-style.css`, JS gom vào `main.js`.

### 2. Vòng lặp kiểm thử tự thân bắt buộc (The Autonomous 4-Step Test Loop)
> ⛔ **CẤM TUYỆT ĐỐI**: AI không được báo "hoàn thành" khi chưa tự mình chạy vòng lặp kiểm thử thực tế. Tuyệt đối không để người dùng phải làm "tester" phát hiện lỗi cú pháp, thiếu method, filter không chạy hay submit rỗng.

Trước khi bàn giao task, AI **BẮT BUỘC** tự động chạy tuần tự vòng lặp 4 bước sau:
* **Loop Step 1 (Syntax & Compile Check):**
  - Kiểm tra cú pháp PHP: `php -l <file_path>` cho mọi file mới tạo/sửa.
  - Compile Blade Views kiểm tra lỗi tag/cú pháp: `php artisan view:cache` rồi `php artisan view:clear`.
* **Loop Step 2 (Contract & Route Integrity Check):**
  - Kiểm tra Route: `php artisan route:list` xác nhận route đã được đăng ký và controller action tồn tại.
  - Kiểm tra Interface Contract: Xác nhận mọi method gọi từ Controller đã được khai báo đầy đủ trong Interface tương ứng để tránh lỗi Intelephense / Fatal Error `Call to undefined method`.
* **Loop Step 3 (Runtime Execution Test qua Tinker / CLI):**
  - Chạy thử logic thực tế bằng CLI: Dùng `php artisan tinker --execute="..."` để test gọi hàm Model/Repository/Service với dữ liệu mẫu (mock data).
  - Kiểm tra các trường hợp biên (Edge cases): Dữ liệu rỗng (`empty`), mảng rỗng `[]`, ID không tồn tại, ký tự đặc biệt trong slug.
* **Loop Step 4 (Self-Correction & Loop Retry):**
  - **NẾU GẶP BẤT KỲ LỖI NÀO:** AI phải tự phân tích log lỗi, tự sửa code ngay lập tức và **QUAY LẠI LOOP STEP 1** để chạy lại toàn bộ quy trình kiểm thử từ đầu.
  - Chỉ khi **TẤT CẢ 4 BƯỚC ĐỀU PASS 100%**, AI mới được xóa cache (`php artisan optimize:clear`) và thông báo hoàn thành cho người dùng.

---

## CÁC TÀI LIỆU THAM KHẢO

| File | Nội dung |
|---|---|
| [`.agents/rules/03-anti-ai-design-system.md`](./rules/03-anti-ai-design-system.md) | **Bộ Quy Chuẩn Thiết Kế Giao Diện Nền Tảng: Cấu Trúc Vững Chắc & Tránh "Chất AI"** |
| [`.agents/codebase-matrix.md`](./codebase-matrix.md) | **Bản đồ ma trận định vị siêu tốc: Nghiệp vụ → Controller → Service → View** |
| [`.agents/rules/01-architecture-boundary.md`](./rules/01-architecture-boundary.md) | Ranh giới kiến trúc & quy tắc Zero-Hardcode khi làm Theme |
| [`.agents/rules/02-theme-building-process.md`](./rules/02-theme-building-process.md) | Quy trình 3 bước chuẩn hóa khởi tạo & tùy biến Theme |
| [`.agents/frontend-design.md`](./frontend-design.md) | Quy chuẩn thiết kế Frontend (Font, Tailwind, Scroll Animation, UI/UX) |
| [`.agents/frontend-data-map.md`](./frontend-data-map.md) | Bảng đầy đủ biến động, partials, helper functions |
| [`.agents/architecture.md`](./architecture.md) | Cấu trúc thư mục, Models, Services, Repositories |
| [`.agents/new-website-workflow.md`](./new-website-workflow.md) | Quy trình tạo website mới trong 1 phiên chat |
| [`.agents/memory.md`](./memory.md) | Lịch sử quyết định kiến trúc & UI/UX dài hạn |
