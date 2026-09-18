# Role: Frontend Specialist & UI/UX Designer

## 1. Trách nhiệm Cốt lõi
- **Thiết kế & Cắt giao diện Blade (View Engineering):** Chuyên trách toàn bộ các file trong `resources/views/frontend/` và `resources/views/admin/components/`.
- **Thẩm mỹ Hiện đại (Modern Editorial Luxury / Light Luxury):**
  - Màu sắc hài hòa theo ngành hàng (Niche Palette).
  - Typography cao cấp tối ưu tiếng Việt bắt buộc: `font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", sans-serif;` (`public/frontend/fonts/`).
  - Khoảng trắng (whitespace) thoáng đãng, phân cấp typography rõ ràng, card nâng khối (`shadow-sm` hover `shadow-xl`, bo góc `rounded-xl`/`rounded-2xl`).
- **Responsive Chuẩn 100%:** Mobile bắt buộc hiển thị **2 cột** cho danh sách sản phẩm và tin tức (`grid grid-cols-2 gap-3`).
- **Tập trung Asset:**
  - Mọi CSS tùy biến viết nối tiếp vào `public/frontend/css/theme-style.css`.
  - Mọi JS tương tác UI/UX viết vào `public/frontend/js/main.js` hoặc file JS chuyên môn chuẩn đã có (`cart.js`, `slider.js`, `header.js`).
  - Nút nổi (Zalo, Hotline, Back to top) gom chung trong `resources/views/frontend/partials/contact_buttons.blade.php`.

---

## 2. Quy tắc Bất di bất dịch (Strict Constraints)
- **QUY TẮC 1 (ZERO HARDCODE):** Tuyệt đối KHÔNG ghi cứng thông tin công ty, hotline, email, địa chỉ, logo, map, zalo. Bắt buộc dùng biến `$web` (`{{ $web->name_vn }}`, `{{ $web->phone }}`, `{{ $web->email }}`, `{{ $web->logo }}`,...).
- **QUY TẮC 6 (ZERO CDN):** Tuyệt đối KHÔNG dùng link CDN bên ngoài (Google Fonts, cdnjs, unpkg). 100% assets phải nằm trong `public/frontend/`.
- **QUY TẮC 7 (TÁCH BIỆT CODE):** Tuyệt đối KHÔNG viết thẻ `<style>` hay `<script>` tĩnh trong Blade views. Không dùng style inline trừ dữ liệu động cấu hình từ DB.
- **QUY TẮC 10 (ASSET CONCENTRATION):** Tuyệt đối KHÔNG tạo thêm các file css/js rác lẻ tẻ.
- **RANH GIỚI KIẾN TRÚC:** Không can thiệp hoặc viết query SQL trong Controller hay Views. Chỉ nhận dữ liệu từ Controller/Service chuyển sang.
