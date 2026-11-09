# AGENTS.md – Master Router & Đội ngũ Kỹ thuật Đa tác nhân Base

> File cấu hình trung tâm nạp tự động cho AI Coding Assistants. Vận hành theo mô hình **Tổ đội Kỹ thuật Đa tác nhân (Multi-Agent Engineering Squad)**.

---

## 👥 ĐỘI NGŨ MULTI-AGENT CHUYÊN MÔN HÓA (ROLES)

Khi nhận prompt từ người dùng, AI tự động kích hoạt vai trò chuyên trách tương ứng:

| Role | Định nghĩa | Trách nhiệm chính |
|---|---|---|
| **Lead Orchestrator** | [`.agents/roles/orchestrator.md`](./roles/orchestrator.md) | Phân rã yêu cầu, điều phối chuyên viên, kiểm soát Git & Commit (Rule 8). |
| **Frontend Specialist** | [`.agents/roles/frontend-specialist.md`](./roles/frontend-specialist.md) | Blade views, Tailwind CSS, Responsive (Mobile 2 cột), Zero-CDN, Zero-Hardcode. |
| **Backend Architect** | [`.agents/roles/backend-architect.md`](./roles/backend-architect.md) | Layered Architecture (`Controller → Service → Repository → Model`), Seeder, DB. |
| **QA Inspector** | [`.agents/roles/qa-inspector.md`](./roles/qa-inspector.md) | Kiểm thử độc lập, quét Zero-Hardcode, xác thực Route 200/302, audit media. |

---

## ⚡ BỘ KỸ NĂNG TỰ ĐỘNG HÓA (SKILLS)

| Kỹ năng | Cấu hình | Mô tả |
|---|---|---|
| **`theme-rebuild`** | [`.agents/skills/theme-rebuild/SKILL.md`](./skills/theme-rebuild/SKILL.md) | Chuyển đổi toàn bộ chủ đề website sang ngành hàng mới (`site:rebuild`). |
| **`hardcode-scanner`** | [`.agents/skills/hardcode-scanner/SKILL.md`](./skills/hardcode-scanner/SKILL.md) | Quét tự động mã nguồn Blade Views để phát hiện vi phạm Rule 1. |
| **`asset-optimizer`** | [`.agents/skills/asset-optimizer/SKILL.md`](./skills/asset-optimizer/SKILL.md) | Audit và thanh lọc thư mục `public/uploads/` đối chiếu cơ sở dữ liệu. |
| **`module-scaffolder`** | [`.agents/skills/module-scaffolder/SKILL.md`](./skills/module-scaffolder/SKILL.md) | Tự động sinh trọn bộ 6 tầng chuẩn cho module mới (Migration -> Model -> Repo -> Service -> Controller -> Views). |
| **`query-audit`** | [`.agents/skills/query-audit/SKILL.md`](./skills/query-audit/SKILL.md) | Rà soát lỗi N+1 queries, câu lệnh select * bị cấm hoặc SQL thô trong Controller (Rule 2 & 19). |

---

## 📋 MA TRẬN ĐIỀU PHỐI QUY TẮC DỰ ÁN (CORE RULES ROUTER)

Mọi thay đổi mã nguồn bắt buộc tuân thủ bộ quy tắc chuyên sâu tương ứng:

| ID | Tên Quy Tắc | Tóm tắt Bắt buộc | Tài liệu Chi tiết |
|---|---|---|---|
| **01** | **Zero-Hardcode** | 100% dữ liệu lấy từ CSDL / Admin; cấm tuyệt đối text tĩnh trong Blade. | [`.agents/rules/01-architecture-boundary.md`](./rules/01-architecture-boundary.md) |
| **02** | **Layered Architecture** | `Controller → Service → Repository → Model`. Không viết SQL trong Controller. | [`.agents/architecture.md`](./architecture.md) |
| **03** | **Anti-AI Design System** | Lưới 1200px, bo góc duy nhất 8px, cấm pill/gradient ảo, mobile 2 cột. | [`.agents/rules/03-anti-ai-design-system.md`](./rules/03-anti-ai-design-system.md) |
| **04** | **Admin UI/UX Standard** | Height 38px đồng bộ, switches accent `#2563eb`, ghost action buttons 32px. | [`.agents/rules/04-admin-ui-ux.md`](./rules/04-admin-ui-ux.md) |
| **05** | **Unified Media Storage** | Lưu ảnh/video trực tiếp `public/uploads/{module}/`, nén WebP, preview `#t=0.5`. | [`.agents/rules/05-media-storage.md`](./rules/05-media-storage.md) |
| **06** | **SEO & Cloudflare Edge** | Dynamic sitemap, schema JSON-LD, 2 Cloudflare Cache Rules (Bypass Admin). | [`.agents/rules/06-seo-cloudflare.md`](./rules/06-seo-cloudflare.md) |
| **07** | **Autonomous Testing** | Quy trình 6 tầng, vòng lặp 4 bước tự test trước khi báo hoàn thành cho user. | [`.agents/rules/07-autonomous-testing.md`](./rules/07-autonomous-testing.md) |
| **08** | **Git & Commit Safety** | CẤM tự ý commit/push; commit message tiếng Anh chuẩn Conventional. | _Xem mục Invariants bên dưới_ |
| **09** | **Theme Rebuilding** | Chạy `php artisan site:rebuild --topic="..."` trước khi sửa view & CSS. | [`.agents/rules/02-theme-building-process.md`](./rules/02-theme-building-process.md) |
| **10** | **Asset Consolidation** | Cấm tạo file lẻ; CSS vào `theme-style.css`, JS vào `main.js`. | [`.agents/frontend-design.md`](./frontend-design.md) |

---

## 🔒 3 NGUYÊN TẮC BẤT BIẾN (CORE INVARIANTS)

1. **Tuyệt đối Không Hardcode (Zero-Hardcode)**:
   - Mọi thông tin (công ty, số điện thoại, banner, tiêu đề khối, sản phẩm) đều phải chỉnh sửa được từ trang Quản trị Admin hoặc biến `$web`.
   - Tra cứu biến động tại [`.agents/frontend-data-map.md`](./frontend-data-map.md).
2. **Quy trình Git có kiểm soát (Strict Git Protocol)**:
   - AI tuyệt đối **KHÔNG ĐƯỢC TỰ Ý COMMIT HOẶC PUSH** khi người dùng không yêu cầu trực tiếp.
   - Khi hoàn thành task, luôn hỏi: *"Tôi đã hoàn thành task [Tên Task]. Bạn có muốn tôi commit các thay đổi này không?"*.
   - Mọi commit message bắt buộc viết bằng **tiếng Anh chuẩn Conventional Commits** (`feat:`, `fix:`, `refactor:`, `chore:`).
3. **Kiểm thử tự thân trước khi bàn giao (Zero-User-Debugging)**:
   - Phải tự chạy lint syntax PHP (`php -l`), compile Blade (`php artisan view:cache`), check route (`php artisan route:list`), và xóa cache (`php artisan optimize:clear`) trước khi thông báo xong việc.

---

## 🚀 TÀI LIỆU ĐỊNH VỊ TỨC THÌ (INSTANT NAVIGATION)

| Tài liệu | Đường dẫn | Công dụng cho AI |
|---|---|---|
| **Codebase Skeleton Map** | [`.agents/codebase-skeleton.md`](./codebase-skeleton.md) | Tra cứu chữ ký hàm, class, Eloquent relationships trong 0.1s. |
| **Codebase Summary (Repomix)** | [`.agents/codebase-summary.xml`](./codebase-summary.xml) | Nạp toàn bộ kiến trúc lõi Models, Repositories, Services, Routes. |
| **Codebase Routing Matrix** | [`.agents/codebase-matrix.md`](./codebase-matrix.md) | Ánh xạ nghiệp vụ ➔ Controller ➔ Service ➔ View. |
| **Frontend Data Map** | [`.agents/frontend-data-map.md`](./frontend-data-map.md) | Danh mục toàn bộ biến động và Helper ngoài Frontend. |
| **Architecture Specification** | [`.agents/architecture.md`](./architecture.md) | Chi tiết kiến trúc Layered chuẩn mực của hệ thống. |
