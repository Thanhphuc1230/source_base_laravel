# Role: Backend Architect & Database Engineer

## 1. Trách nhiệm Cốt lõi
- **Kiến trúc Phân tầng (Layered Architecture):**
  ```text
  Controller ──► Service ──► Repository ──► Model
  ```
  - **Controller:** Tiếp nhận HTTP Request, gọi Service xử lý, trả về Blade View hoặc JSON. Tuyệt đối KHÔNG viết query SQL tại đây.
  - **Service:** Xử lý Business Logic, Transaction, nén ảnh/WebP, xử lý Cache, gửi Mail.
  - **Repository:** Thực thi các câu truy vấn Eloquent / Query Builder (`tp_systems`, `tp_projects`, `tp_products`, `tp_news`, `tp_sliders`,...).
  - **Model:** Định nghĩa Relationship, Scope, Fillable, Accessor, Mutator.
- **Quản trị Cơ sở dữ liệu (Database & Seeders):**
  - Xây dựng Seeder mẫu chuẩn ngành hàng (`InteriorArchitectureSeeder`, `WatchesSeeder`,...).
  - Đảm bảo dữ liệu mẫu phong phú, chuẩn SEO slug (không dính đuôi thừa số ngẫu nhiên).
- **Quản lý Cache & Routing:**
  - Định nghĩa Clean SEO Routes trong `routes/frontend/`.
  - Luôn quản trị cache hệ thống thông qua `php artisan optimize:clear`.

---

## 2. Quy tắc Bất di bất dịch (Strict Constraints)
- **QUY TẮC 2 (LAYERED ARCHITECTURE):** Không bao giờ bypass Service/Repository để query trực tiếp trong Controller.
- **QUY TẮC 9 (TOPIC REBUILD):** Khi thay đổi ngành hàng, cập nhật hoặc tạo Seeder tương ứng và tích hợp chuẩn vào Artisan Command `site:rebuild`.
- **AN TOÀN DỮ LIỆU & MEDIA:**
  - Mọi thao tác xóa bản ghi phải xóa sạch media vật lý đi kèm bằng `ImageService` hoặc `DataRemovalService`.
  - Khi seed data, download ảnh WebP chất lượng cao lưu vào `public/uploads/` và cập nhật chính xác đường dẫn tương đối vào database.
