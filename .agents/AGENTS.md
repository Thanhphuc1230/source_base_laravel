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

## QUY TẮC SỐ 5 – THẨM MỸ GIAO DIỆN CHUẨN UI/UX HIỆN ĐẠI (MODERN PREMIUM WEB DESIGN)

- **Định hướng thẩm mỹ:** Không sử dụng phong cách cũ kỹ. Giao diện phải đạt chuẩn Modern Premium Web: màu sắc hài hòa theo ngành hàng (Niche Palette), Typography cao cấp tối ưu tiếng Việt bắt buộc: `font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", sans-serif;`, khoảng trắng thoáng đãng, phân cấp typography rõ ràng.
- **UI/UX & Component Details:** 
  - Card/Button có shadow nhẹ (`shadow-sm` hover thành `shadow-xl`), bo góc viền chuẩn (`rounded-xl` hoặc `rounded-2xl`).
  - Mọi button, card phải có hiệu ứng mượt `transition-all duration-300 ease-in-out`.
  - Floating action buttons đặt bên phải (`fixed bottom-5 right-5 z-50`).
- **Icon Safety (An toàn Icon):** Dùng FontAwesome v6 chuẩn syntax (bắt buộc prefix `fa-`, ví dụ: `fas fa-shopping-cart`, `fas fa-phone-alt`, `fab fa-facebook`) hoặc SVG Inline để tuyệt đối KHÔNG bị vỡ/ô vuông icon.
- **Scroll Animations:** Tích hợp hiệu ứng xuất hiện khi cuộn trang (Fade/Slide/Zoom in) bằng Intersection Observer API (`.scroll-anim.fade-up`).
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

## CÁC TÀI LIỆU THAM KHẢO

| File | Nội dung |
|---|---|
| [`.agents/rules/01-architecture-boundary.md`](./rules/01-architecture-boundary.md) | Ranh giới kiến trúc & quy tắc Zero-Hardcode khi làm Theme |
| [`.agents/rules/02-theme-building-process.md`](./rules/02-theme-building-process.md) | Quy trình 3 bước chuẩn hóa khởi tạo & tùy biến Theme |
| [`.agents/frontend-design.md`](./frontend-design.md) | Quy chuẩn thiết kế Frontend (Font, Tailwind, Scroll Animation, UI/UX) |
| [`.agents/frontend-data-map.md`](./frontend-data-map.md) | Bảng đầy đủ biến động, partials, helper functions |
| [`.agents/architecture.md`](./architecture.md) | Cấu trúc thư mục, Models, Services, Repositories |
| [`.agents/new-website-workflow.md`](./new-website-workflow.md) | Quy trình tạo website mới trong 1 phiên chat |
| [`.agents/memory.md`](./memory.md) | Lịch sử quyết định kiến trúc & UI/UX dài hạn |
