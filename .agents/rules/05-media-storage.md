# Quy chuẩn Lưu trữ Media & Upload Đồng nhất (Unified Media Storage)

> Quy tắc chi tiết về quản lý ảnh, video và tệp tin tải lên trong toàn bộ dự án.

---

## 1. Đồng nhất Thư mục Lưu trữ (Module Storage Path)
- **Cấu trúc lưu trữ**: Mọi hình ảnh và video tải lên từ Admin (`ImageService`, `ImageHandlerTrait`, `multiImage`) đều được lưu trữ trực tiếp trong thư mục module:
  - `public/uploads/{module}/` (ví dụ: `public/uploads/products/`, `public/uploads/news/`, `public/uploads/sliders/`)
  - Hoặc lưu theo thời gian `public/uploads/{module}/{Y}/{m}/` đối với khối dữ liệu lớn.
- **Không phân tách thư mục con**: Tuyệt đối KHÔNG tự ý tách riêng thư mục con như `videos/` hay `audios/` làm vỡ cấu trúc đồng bộ.

---

## 2. Hỗ trợ Media Đa phương tiện Xen kẽ (Mixed Media Support)
- Thư viện ảnh chi tiết (`image_detail`) hỗ trợ upload cả ảnh (tự động chuyển đổi sang WebP) và video (MP4, WebM, MOV).
- Bảo toàn đúng thứ tự sắp xếp xen kẽ do người dùng thiết lập khi cập nhật.

---

## 3. Khung hình Preview Video (Video Poster Snapshot)
- Mọi thẻ video preview ở cả Admin và Frontend bắt buộc dùng thuộc tính thời gian:
  ```html
  <video src="{{ asset($item->video_path) }}#t=0.5" preload="metadata" muted playsinline></video>
  ```
- Việc này lấy chính xác khung hình tại giây 0.5 làm ảnh đại diện tự nhiên, tránh tình trạng khung đen tĩnh.

---

## 4. Tự động Resize & Nén WebP (Performance Optimization)
- **Ảnh thẻ card / blog / sản phẩm**: Max width `600px - 800px` (WebP, quality 70-75, dung lượng < 60KB).
- **Ảnh Banner / Slider Hero**: Max width `1600px` (WebP, quality 80, dung lượng < 100KB).
- **Tuyệt đối cấm**: Lưu trữ nguyên vẹn ảnh gốc độ phân giải cao từ điện thoại/camera (> 1500px, dung lượng > 1MB) mà không qua xử lý nén.
