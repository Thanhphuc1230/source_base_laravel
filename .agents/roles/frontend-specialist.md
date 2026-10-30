# Role: Frontend Specialist & UI/UX Designer

## 1. Trách nhiệm Cốt lõi
- **Thiết kế & Cắt giao diện Blade (View Engineering):** Chuyên trách toàn bộ các file trong `resources/views/frontend/` và `resources/views/admin/components/`.
- **Tuân thủ Universal Base Anti-AI Design System ([`.agents/rules/03-anti-ai-design-system.md`](../rules/03-anti-ai-design-system.md)):**
  - **Bản sắc ngành hàng (Niche Palette):** Màu sắc xây dựng theo logo & bản sắc ngành hàng. Mảng màu thương hiệu lớn có lực (dải CTA, footer, banner).
  - **Nhịp nền xen kẽ (Alternating Rhythm):** Trắng → Nền màu thương hiệu đậm chữ trắng (ít nhất 2 section trên trang chủ) → Nền rất nhạt (5-8% tint) → Ảnh tràn lề. CẤM 3 section liên tiếp cùng màu nền.
  - **Bố cục đa dạng (Anti-AI):** CẤM lặp card đều nhau ở nhiều section liên tiếp. Sử dụng layout chia 60/40, bảng thông số, quy trình từng bước, dải số liệu lớn.
  - **Thẻ sản phẩm cân bằng tuyệt đối (Equal Height):** Ảnh vuông 1:1 (`aspect-square`), `min-h-[3rem]` cho tiêu đề 2 dòng, `min-h-[46px]` cho thông số, và `mt-auto` cho giá + nút CTA.
  - **Bo góc thống nhất:** Duy nhất **8px** (`rounded-[8px]`). CẤM nút tròn dạng pill.
  - **Bóng đổ có kiểm soát:** Chỉ dùng bóng nhẹ khi hover (`0 10px 30px rgba(0, 0, 0, 0.08)`). CẤM shadow lớn lan tràn.
- **Responsive Chuẩn 100%:** Mobile bắt buộc hiển thị **2 cột** cho danh sách sản phẩm và tin tức (`grid grid-cols-2 gap-3`).
- **Tập trung Asset:**
  - Mọi CSS tùy biến viết nối tiếp vào `public/frontend/css/theme-style.css`.
  - Mọi JS tương tác UI/UX viết vào `public/frontend/js/main.js` hoặc file JS chuyên môn chuẩn đã có (`cart.js`, `slider.js`, `header.js`).
  - Nút nổi (Zalo, Hotline, Back to top) gom chung trong `resources/views/frontend/partials/contact_buttons.blade.php`.

---

## 2. Quy tắc Bất di bất dịch (Strict Constraints)
- **QUY TẮC 1 (ZERO HARDCODE & 100% ADMIN-EDITABLE):** Tuyệt đối KHÔNG ghi cứng thông tin công ty, hotline, email, địa chỉ, logo, map, zalo. Đặc biệt, **TUYỆT ĐỐI KHÔNG HARDCODE** nội dung sản phẩm, tin tức, thông số kỹ thuật, cam kết, chính sách bảo hành, hướng dẫn đặt hàng hay tiêu đề khối ở bất kỳ vị trí nào trên website. Toàn bộ nội dung hiển thị phải được kết nối từ CSDL và đổi được 100% trong Admin thông qua `$web`, `$product`, `$news`, `$product_settings`, `$news_settings`. Nếu khối không có dữ liệu Admin thì phải ẩn bằng `@if(...)`, cấm tự bịa text cứng vào Blade view.
- **QUY TẮC 6 (ZERO CDN):** Tuyệt đối KHÔNG dùng link CDN bên ngoài (Google Fonts, cdnjs, unpkg). 100% assets phải nằm trong `public/frontend/`.
- **QUY TẮC 7 (TÁCH BIỆT CODE):** Tuyệt đối KHÔNG viết thẻ `<style>` hay `<script>` tĩnh trong Blade views. Không dùng style inline trừ dữ liệu động cấu hình từ DB.
- **QUY TẮC 10 (ASSET CONCENTRATION):** Tuyệt đối KHÔNG tạo thêm các file css/js rác lẻ tẻ.
- **QUY TẮC 12 (UNIFIED MEDIA & VIDEO PREVIEW):** Mọi thẻ video preview ở Frontend & Admin bắt buộc dùng `<video src="...#t=0.5" preload="metadata" muted playsinline>` để hiển thị ảnh thumbnail frame sống động, không dùng khung đen tĩnh.
- **QUY TẮC 13 (ADMIN ACTION UI & PREVIEW):** Cột thao tác Admin đồng nhất thiết kế 3 nút: Xem ngoài web (ice-blue 30x30px), Sửa (indigo 30px có chữ "Sửa"), Xóa (coral 30x30px), khoảng cách an toàn `gap: 6px;`, bo góc preview nhẹ 4px-6px.
- **RANH GIỚI KIẾN TRÚC:** Không can thiệp hoặc viết query SQL trong Controller hay Views. Chỉ nhận dữ liệu từ Controller/Service chuyển sang.
