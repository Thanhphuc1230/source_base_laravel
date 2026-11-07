# Quy chuẩn SEO Kỹ thuật, Hiệu năng Tối ưu & Cloudflare Edge Cache

> Quy tắc bắt buộc nhằm đảm bảo website đạt 95-100 điểm Google Lighthouse/PageSpeed và TTFB mili-giây (~50ms - 200ms).

---

## 1. SEO Kỹ thuật & Cấu trúc On-Page
- **Dynamic Sitemap XML**:
  - Route `/sitemap.xml` kết nối CSDL động (Home, Pages, Categories, Products, News).
  - Có đầy đủ thẻ: `<loc>`, `<lastmod>`, `<changefreq>`, `<priority>`.
  - Bắt buộc bọc trong `Cache::remember()` (TTL 12h - 24h) để phản hồi tức thì, không gây tải CSDL.
- **Quy chuẩn `robots.txt`**:
  - Khai báo Sitemap: `Sitemap: https://{domain}/sitemap.xml`.
  - Disallow toàn bộ vùng quản trị & nhạy cảm: `/admintv`, `/admin/`, `/filemanager/`, `/cart`, `/checkout`, `/add-to-cart`, `/checkout-store`.
- **Thẻ Heading Semantics**:
  - CẤM dùng `<h1 style="display: none">`.
  - Mỗi trang đúng duy nhất 1 thẻ `<h1>`:
    - Trang chủ: `<h1 class="sr-only">{{ $web->name_company }} - {{ $web->name_vn }}</h1>`.
    - Chi tiết: `<h1>{{ $product->name }}</h1>` hoặc `<h1>{{ $news->name_vn }}</h1>`.
- **Dữ liệu cấu trúc Schema (JSON-LD)**:
  - Layout chung: Schema `@type: "Organization"` hoặc `"LocalBusiness"` từ biến `$web`.
  - Chi tiết sản phẩm: Schema `@type: "Product"` kèm giá VND, `offers`, `InStock` và `@type: "BreadcrumbList"`.
  - Chi tiết bài viết: Schema `@type: "NewsArticle"`.
- **Thẻ OpenGraph & Twitter**:
  - Đầy đủ `og:title`, `og:description`, `og:image`, `og:type` (`website`, `product`, `article`).
  - Kích thước ảnh chuẩn `og:image:width: 1200` và `og:image:height: 630`.

---

## 2. Tối ưu Hiệu năng Ứng dụng (Origin Optimization)
- **Thanh lọc cột truy vấn**: Trong các hàm Controller/Repository hiển thị danh sách, TUYỆT ĐỐI KHÔNG `select *` hoặc lấy các cột text lớn (`content_vn`, `content_en`, `image_detail`). Chỉ chọn đúng các cột hiển thị thẻ card (`id`, `name`, `slug`, `avatar`, `price`, `stt`).
- **Bộ nhớ đệm Redis & Multi-tier Cache**: Toàn bộ query slider, menu, danh mục hot, cấu hình web đều bọc trong `Cache::remember()`.

---

## 3. Quy chuẩn 2 Cache Rules của Cloudflare (The 2-Rule Standard)
> Cơ chế hoạt động: **"Last Match Wins"** (Rule nằm dưới cùng sẽ ghi đè lên các rule nằm phía trên).

### Rule 1 – Bảo vệ Admin & Dữ liệu động (`Bypass Admin`)
- **Expression**:
  `(starts_with(http.request.uri.path, "/admintv") or starts_with(http.request.uri.path, "/admin") or starts_with(http.request.uri.path, "/filemanager") or starts_with(http.request.uri.path, "/laravel-filemanager") or starts_with(http.request.uri.path, "/cart") or starts_with(http.request.uri.path, "/checkout") or starts_with(http.request.uri.path, "/add-to-cart") or starts_with(http.request.uri.path, "/update-cart") or starts_with(http.request.uri.path, "/remove-item") or starts_with(http.request.uri.path, "/order-success"))`
- **Action**: `Bypass cache`

### Rule 2 – Tăng tốc toàn bộ Frontend (`Cache Frontend Everything`)
- **Expression**:
  `(not starts_with(http.request.uri.path, "/admintv") and not starts_with(http.request.uri.path, "/admin") and not starts_with(http.request.uri.path, "/filemanager") and not starts_with(http.request.uri.path, "/laravel-filemanager") and not starts_with(http.request.uri.path, "/cart") and not starts_with(http.request.uri.path, "/checkout") and not starts_with(http.request.uri.path, "/add-to-cart") and not starts_with(http.request.uri.path, "/update-cart") and not starts_with(http.request.uri.path, "/remove-item") and not starts_with(http.request.uri.path, "/order-success"))`
- **Action**: `Eligible for cache`
- **Edge TTL**: `Ignore cache-control header and use this TTL` ➔ Đặt `2 hours` hoặc `4 hours`.
- **Browser TTL**: `Respect origin TTL`.

---

## 4. Tối ưu Core Web Vitals & Google PageSpeed 95-100
- **LCP & CLS**:
  - Slide đầu tiên: `fetchpriority="high"`, `<link rel="preload" as="image" href="..." fetchpriority="high">`.
  - 100% `<img>` có thuộc tính `width`, `height`, `aspect-ratio` rõ ràng.
  - Ảnh below-the-fold: `loading="lazy"` và `decoding="async"`.
- **Render-blocking & JS**:
  - Không nhúng Play CDN Tailwind trên Production; biên dịch CSS tĩnh cục bộ.
  - Font chữ dùng format `.woff2` local có `crossorigin`.
  - Scripts dùng `defer` và đặt cuối thẻ `<body>`.
- **Phòng chống lỗi 419 (CSRF Protection)**:
  - Tuyệt đối không cache HTML đối với trang form có CSRF token.
