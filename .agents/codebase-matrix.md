# Ma Trận Định Vị Mã Nguồn Base (Fast Codebase Matrix)

> **MỤC ĐÍCH**: Bản đồ ánh xạ trực tiếp 1-1 giữa nghiệp vụ/tính năng và các file mã nguồn.
> Khi nhận lệnh từ người dùng, AI **BẮT BUỘC** tra cứu bảng này để mở thẳng file mục tiêu, **TUYỆT ĐỐI KHÔNG** quét (`Get-ChildItem`, scan thư mục) lần lượt toàn bộ dự án.

---

## 🚀 1. FRONTEND MATRIX (Giao diện & Luồng Người dùng)

| Nghiệp vụ / Màn hình | Controller | Service / Data Source | Blade Views Mục Tiêu | CSS / Assets |
|---|---|---|---|---|
| **Trang chủ (Home)** | `app/Http/Controllers/Frontend/HomeController.php` | `app/Services/HomeService.php` | `resources/views/frontend/modules/home/index.blade.php`<br>`resources/views/frontend/partials/slider.blade.php`<br>`resources/views/frontend/partials/contact_buttons.blade.php` | `public/frontend/css/theme-style.css`<br>`public/frontend/js/main.js` |
| **Khung dùng chung (Layout/Master)** | `app/View/Composers/FrontendComposer.php` (Inject `$web`, `$menu`, `$brands`...) | `FrontendComposer.php` (Cache: `frontend_global_data`) | `resources/views/frontend/master.blade.php`<br>`resources/views/frontend/partials/header.blade.php`<br>`resources/views/frontend/partials/header-mobi.blade.php`<br>`resources/views/frontend/partials/footer.blade.php` | `public/frontend/css/theme-style.css` |
| **Chi tiết Sản phẩm** | `app/Http/Controllers/Frontend/RouteController.php`<br>`app/Http/Controllers/Frontend/ProductController.php` | `app/Services/ProductService.php`<br>`app/Repositories/Eloquent/ProductRepository.php` | `resources/views/frontend/modules/product/detail.blade.php`<br>`resources/views/frontend/modules/product/components/` | `public/frontend/css/theme-style.css` |
| **Danh mục Sản phẩm** | `app/Http/Controllers/Frontend/RouteController.php`<br>`app/Http/Controllers/Frontend/ProductController.php` | `app/Services/ProductService.php`<br>`app/Repositories/Eloquent/CateProductRepository.php` | `resources/views/frontend/modules/product/index.blade.php` | `public/frontend/css/theme-style.css` |
| **Giỏ hàng (Cart Session)** | `app/Http/Controllers/Frontend/CartController.php` | `app/Services/CartService.php` | `resources/views/frontend/modules/cart/index.blade.php` | `public/frontend/js/main.js` |
| **Thanh toán (Checkout)** | `app/Http/Controllers/Frontend/CheckoutController.php` | `app/Services/CheckoutService.php` | `resources/views/frontend/modules/checkout/index.blade.php`<br>`resources/views/frontend/modules/checkout/success.blade.php` | `public/frontend/css/theme-style.css` |
| **Tin tức (News & Detail)** | `app/Http/Controllers/Frontend/RouteController.php`<br>`app/Http/Controllers/Frontend/NewsController.php` | `app/Services/NewsService.php`<br>`app/Repositories/Eloquent/NewsRepository.php` | `resources/views/frontend/modules/news/index.blade.php`<br>`resources/views/frontend/modules/news/detail.blade.php` | `public/frontend/css/theme-style.css` |
| **Trang tĩnh (About / Page)** | `app/Http/Controllers/Frontend/RouteController.php`<br>`app/Http/Controllers/Frontend/PageController.php` | `app/Repositories/Eloquent/PageRepository.php` | `resources/views/frontend/modules/page/index.blade.php` | `public/frontend/css/theme-style.css` |
| **Liên hệ (Contact Form)** | `app/Http/Controllers/Frontend/ContactController.php` | `app/Services/RateLimitService.php`<br>`app/Repositories/Eloquent/ContactRepository.php` | `resources/views/frontend/modules/contact/index.blade.php` | `public/frontend/js/main.js` |
| **Tìm kiếm (Search AJAX & Page)** | `app/Http/Controllers/Frontend/SearchController.php` | `app/Services/SearchService.php` | `resources/views/frontend/modules/search/index.blade.php` | `public/frontend/js/main.js` |
| **Dự án / Dịch vụ** | `app/Http/Controllers/Frontend/ProjectController.php`<br>`app/Http/Controllers/Frontend/ServiceController.php` | `ProjectRepository.php`<br>`ServiceRepository.php` | `resources/views/frontend/modules/project/`<br>`resources/views/frontend/modules/service/` | `public/frontend/css/theme-style.css` |

---

## 🛠️ 2. ADMIN MODULE MATRIX (Trang Quản trị)

| Module Admin | Controller | Form Request | Repository / Model | Blade Views Admin |
|---|---|---|---|---|
| **Cấu hình Web ($web)** | `app/Http/Controllers/Admin/SystemController.php` | `app/Http/Requests/Admin/SystemRequest.php` | `SystemRepository.php`<br>`app/Models/System.php` | `resources/views/admin/modules/system/index.blade.php` |
| **Menu điều hướng** | `app/Http/Controllers/Admin/MenuController.php` | `app/Http/Requests/Admin/MenuRequest.php` | `MenuRepository.php`<br>`app/Models/Menu.php` | `resources/views/admin/modules/menu/index.blade.php` |
| **Sản phẩm (Product)** | `app/Http/Controllers/Admin/ProductController.php` | `app/Http/Requests/Admin/ProductRequest.php` | `ProductRepository.php`<br>`app/Models/Product.php` | `resources/views/admin/modules/product/index.blade.php`<br>`resources/views/admin/modules/product/create.blade.php`<br>`resources/views/admin/modules/product/edit.blade.php` |
| **Danh mục SP** | `app/Http/Controllers/Admin/CateProductController.php`| `app/Http/Requests/Admin/CateProductRequest.php`| `CateProductRepository.php`<br>`app/Models/CateProduct.php`| `resources/views/admin/modules/cate_product/` |
| **Tin tức (News)** | `app/Http/Controllers/Admin/NewsController.php` | `app/Http/Requests/Admin/NewsRequest.php` | `NewsRepository.php`<br>`app/Models/News.php` | `resources/views/admin/modules/news/` |
| **Danh mục Tin** | `app/Http/Controllers/Admin/CateNewController.php` | `app/Http/Requests/Admin/CateNewRequest.php` | `CateNewRepository.php`<br>`app/Models/CateNew.php` | `resources/views/admin/modules/cate_new/` |
| **Đơn hàng (Orders)** | `app/Http/Controllers/Admin/OrderController.php` | N/A | `OrderRepository.php`<br>`app/Models/Order.php` | `resources/views/admin/modules/order/` |
| **Slider / Banner** | `app/Http/Controllers/Admin/SliderController.php` | `app/Http/Requests/Admin/SliderRequest.php` | `SliderRepository.php`<br>`app/Models/Slider.php` | `resources/views/admin/modules/slider/` |
| **Thương hiệu (Brand)**| `app/Http/Controllers/Admin/BrandController.php` | `app/Http/Requests/Admin/BrandRequest.php` | `BrandRepository.php`<br>`app/Models/Brand.php` | `resources/views/admin/modules/brand/` |
| **Giới thiệu (About)** | `app/Http/Controllers/Admin/AboutController.php` | N/A (Single-record module) | `AboutRepository.php`<br>`app/Models/About.php` | `resources/views/admin/modules/about/edit.blade.php` |
| **Trang tĩnh (Page)** | `app/Http/Controllers/Admin/PageController.php` | `app/Http/Requests/Admin/PageRequest.php` | `PageRepository.php`<br>`app/Models/Page.php` | `resources/views/admin/modules/page/` |
| **Khách hàng Liên hệ** | `app/Http/Controllers/Admin/ContactController.php` | N/A | `ContactRepository.php`<br>`app/Models/Contact.php` | `resources/views/admin/modules/contact/` |

---

## ⚡ 3. CORE INFRASTRUCTURE MATRIX (Hạ Tầng Kỹ Thuật)

| Thành phần | Vị trí file cốt lõi | Logic / Trách nhiệm |
|---|---|---|
| **Dynamic Slug Resolver** | `routes/frontend/dynamic.php`<br>`app/Http/Controllers/Frontend/RouteController.php` | UNION ALL 5 bảng (`Page`, `Product`, `News`, `CateProduct`, `CateNews`) điều hướng tự động. |
| **Tạo Slug Sạch** | `app/Services/SlugService.php` | Tạo `Str::slug`, kiểm tra unique liên bảng, không gắn hậu tố trừ khi trùng. |
| **Quản lý Cache & Clear** | `app/Services/CacheService.php`<br>`app/Providers/AppServiceProvider.php` | Cache tags, Redis, tự động xóa cache khi model `saved` / `deleted`. |
| **Upload Media & WebP** | `app/Services/ImageService.php`<br>`app/Traits/ImageHandlerTrait.php` | Upload ảnh/video, chuyển WebP, lưu cấu trúc `uploads/{module}/{YYYY}/{MM}/`. |
| **Dọn dẹp Dữ liệu & Ảnh**| `app/Services/DataRemovalService.php`<br>`app/Traits/DataRemovalTrait.php` | Xóa record kèm dọn file vật lý khỏi ổ cứng. |
| **Frontend Data Composer**| `app/View/Composers/FrontendComposer.php` | Cache 120 phút biến `$web`, `$menu`, `$cate_product`, `$brands`, `$footer_pages`. |

---

## 📌 4. DIRECTORY CHEAT SHEET (Chỉ mục nhanh)

* **CSS / Styling**: `public/frontend/css/theme-style.css` (Mọi style giao diện).
* **JS Tương tác**: `public/frontend/js/main.js` (Mọi animation, modal, AJAX, slider init).
* **Nút Floating**: `resources/views/frontend/partials/contact_buttons.blade.php`.
* **Blade Components Admin**: `resources/views/components/admin/` (`table-wrapper`, `table-switch`, `localized-fields`, `image-upload`).
* **Routes File**: `routes/web.php` (include `routes/frontend/` và `routes/admin/`).
