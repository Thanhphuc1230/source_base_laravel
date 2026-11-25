# Rule 08: Security, Validation & Data Integrity

> **Mục tiêu:** Bảo đảm toàn vẹn dữ liệu và an toàn bảo mật ở mọi tầng từ Request đến Blade View.

---

## 1. Input Validation & Mass Assignment
- Mọi endpoint `POST`/`PUT`/`PATCH` từ Frontend và Admin bắt buộc đi qua `FormRequest` hoặc `$request->validate(...)` tường minh.
- Model bắt buộc khai báo `$fillable` rõ ràng; **CẤM** `protected $guarded = [];` trên các Model nhạy cảm (`User`, `Order`, `System`, `MailConfig`).

## 2. Chống XSS trong Blade Views
- Luôn dùng `{{ $value }}` cho dữ liệu text thông thường.
- Chỉ dùng `{!! $value !!}` cho nội dung rich-text từ Admin (CKEditor) như `$product->content`, `$news->content`, `$page->content`. Tuyệt đối không render `{!! ... !!}` từ input của khách truy cập (comment, contact, search query).

## 3. An toàn Upload Media
- Mọi file tải lên qua Admin hoặc API bắt buộc kiểm tra MIME type và dung lượng tối đa qua `FormRequest` / `ImageService`.

## 4. Rate Limiting & Auth
- Các route nhạy cảm (login, password reset, checkout, contact, comment, chat API) bắt buộc gắn middleware `throttle:...` hoặc `RateLimitService`.
