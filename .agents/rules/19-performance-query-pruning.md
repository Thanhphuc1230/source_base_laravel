# Rule 19: Origin Performance, Cache & Query Column Pruning

> **Mục tiêu:** Bảo đảm hệ thống đạt hiệu năng cao, không nghẽn MySQL/Redis và giữ số lượng truy vấn trang chủ ở mức tối thiểu (0–2 queries khi warm cache).

---

## 1. Quy chuẩn Cache TTL (Đơn vị: Giây)
- Trong Laravel 12, `Cache::remember($key, $ttl, ...)` và `CacheService::remember($tag, $key, $ttl, ...)` nhận **GIÂY** (seconds), tuyệt đối **KHÔNG** truyền phút.
- Sử dụng hằng số chuẩn trong `App\Services\CacheService::TTL`:
  - `short`: `900` (15 phút)
  - `medium`: `3600` (1 giờ)
  - `long`: `21600` (6 giờ)
  - `day`: `86400` (24 giờ)
- **Chặn 404 Storm ở `SlugResolutionService`:** Mọi lookup slug không tìm thấy phải cache giá trị sentinel (`'__NOT_FOUND__'`) để tránh quét `UNION ALL` lặp lại.

## 2. Xóa Cache Có Chọn Lọc (Selective Tag Invalidation)
- Trong `AppServiceProvider`, mỗi Model chỉ được phép xóa đúng tags liên quan đến nó (ví dụ `Product` chỉ flush `['products', 'frontend']`), **CẤM** xóa toàn bộ 11 tags hoặc gọi `Cache::flush()` khi cập nhật 1 bản ghi.

## 3. Query Column Pruning & Eager Loading
- **CẤM** lấy cột văn bản/JSON lớn (`content`, `image_detail`, `description`) trong truy vấn danh sách, sidebar, hoặc `related_products`. Luôn dùng `->select([...previewColumns])`.
- **CẤM** eager-load toàn bộ relation con chỉ để đếm số lượng (ví dụ `CateProduct::with(['products'])`). Bắt buộc dùng `->withCount(['products' => fn ($q) => $q->where('status', 1)])`.
- **ViewComposer Memoization:** `FrontendComposer` bắt buộc giữ `static::$memoizedData` ở cấp độ request để không lặp lại query qua các partials.

## 4. Redis & Queue Production
- Dùng `REDIS_CLIENT=phpredis` trong production và tách `REDIS_DB=0` (session/queue) với `REDIS_CACHE_DB=1` (cache).
- Tác vụ nặng (gửi mail, sitemap, nén media) phải đẩy qua Queue (`QUEUE_CONNECTION=redis`).
