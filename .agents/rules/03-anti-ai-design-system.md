# UNIVERSAL BASE ANTI-AI DESIGN SYSTEM
## Bộ Quy Chuẩn Thiết Kế Giao Diện Nền Tảng: Cấu Trúc Vững Chắc & Tránh "Chất AI"

> **Bắt buộc áp dụng:** Mọi Agent/Specialist khi tạo mới, tinh chỉnh hoặc rebuild giao diện Frontend đều phải tuân thủ nghiêm ngặt tài liệu này để loại bỏ triệt để cảm giác "template AI sinh ra", tạo nên website có chiều sâu, nghiệp vụ thật và đẳng cấp.

---

## 1. NGUYÊN TẮC GỐC – TRÁNH "CHẤT AI" (ANTI-AI DESIGN PRINCIPLES)

- **Thiết kế từ sản phẩm & nghiệp vụ thật:** 
  - Giao diện phải trông như do một Senior Designer/Dev chuyên nghiệp làm riêng cho doanh nghiệp thật của ngành hàng này, **KHÔNG** dùng template AI sinh ra.
  - Bắt đầu từ quy cách sản phẩm, thông số kỹ thuật, phiếu kiểm định, quy trình dịch vụ và năng lực vận hành thực tế.
- **Ưu tiên sự gọn gàng, cụ thể và có lực:** 
  - Khi phân vân giữa "đẹp kiểu quen thuộc AI" và "gọn gàng, thực tế, có lực thị giác", chọn cái sau.
  - Không thêm thành phần chỉ để lấp chỗ trống. Khách hàng cần thông số, giá, quy cách, cách đặt hàng, không cần hiệu ứng lấp lánh rườm rà.
- **Mỗi màn hình (viewport) có đúng 1 điểm nhấn thị giác:** 
  - Ảnh lớn chất lượng cao, mảng màu đậm của thương hiệu, hoặc con số/thông số lớn.
  - Tránh cảm giác trang bị trống trải hoặc giống tài liệu văn bản.
- **Đa dạng bố cục Section (CẤM lặp card đều tăm tắp):**
  - **CẤM** lặp lại lưới card đều nhau ở nhiều section liên tiếp.
  - Mỗi section dùng một cấu trúc layout khác nhau: Danh sách chia 2 cột lệch (60/40), Bảng thông số kỹ thuật (Spec Table), Dải số liệu năng lực lớn, Quy trình từng bước (Step Workflow), Dải kêu gọi hành động (CTA Band).
  - Toàn trang chỉ có tối đa 1 loại card duy nhất (dành riêng cho Danh mục Sản phẩm). Mọi khối nội dung khác phân nhóm bằng khoảng trắng, đường kẻ ngang 1px mộc và phân cấp chữ.
- **CẤM các thành phần mang "Chất AI" điển hình:**
  - ❌ CẤM khối 4 ô thống kê đều nhau nếu là số liệu ảo, không có thực.
  - ❌ CẤM icon đặt trong ô vuông/tròn có nền pastel (hồng, xanh lơ, tím).
  - ❌ CẤM badge pill chữ hoa nhỏ dẫn đường đặt phía trên tiêu đề section. Tiêu đề tự đứng vững, không cần nhãn dẫn.
  - ❌ CẤM pill chứa tên công ty phía trên tiêu đề Hero.
  - ❌ CẤM emoji trong toàn bộ giao diện chuyên nghiệp.
  - ❌ CẤM gradient nhiều màu sắc, màu neon phản quang, hiệu ứng glassmorphism hoặc glow lan tràn.

---

## 2. HỆ THỐNG MÀU SẮC THEO BẢN SẮC NGÀNH HÀNG (NICHE COLOR SYSTEM)

Màu sắc không được hardcode mà phải tự động xây dựng theo Logo thương hiệu và tâm lý thị giác của ngành hàng được yêu cầu:

- **Màu chính thương hiệu (Brand Primary):** 
  - Lấy từ màu chủ đạo của Logo hoặc màu đại diện của ngành hàng:
    * *Xanh lá:* Tự nhiên, nông sản, dược liệu, y tế sinh học.
    * *Xanh navy / Xanh dương:* Công nghệ, kỹ thuật số, logistics, tài chính.
    * *Nâu đồng / Vàng kim:* Nội thất kiến trúc, resort, trang sức, cao cấp.
    * *Đen luxury / Than chì:* Đồng hồ, thời trang cao cấp, tối giản tinh tế.
  - Dùng cho các **MẢNG LỚN CÓ LỰC**: Nền section dải ngang, banner CTA, Footer, nút bấm chính.
- **Hệ thống tông phụ của màu chính:**
  - *Tông vừa (Medium):* Dùng cho trạng thái hover/active.
  - *Tông sẫm (Dark/Deep):* Cho nền Footer và các khối cần độ sâu thị giác.
  - *Tông rất nhạt (Light / 5-8% tint):* Làm màu nền cho các dải thông tin, dải quy trình, khối phụ trợ.
- **Màu nhấn (Accent Color):** 
  - Màu tương phản nổi bật lấy từ điểm nhấn của logo (hoặc màu bổ túc).
  - **CHỈ dùng cho:** Nút CTA chính và vài chi tiết điểm nhấn nhỏ (tối đa 1 lần mỗi màn hình).
- **Nền chủ đạo:** Trắng tinh khiết (`#FFFFFF`) hoặc Off-white sạch sẽ (`#F9FAFB` / `#F9F8F6`).
- **Quy tắc nhịp nền xen kẽ (Alternating Rhythm):**
  - Luân chuyển nhịp: Nền Trắng → Nền màu chính thương hiệu (chữ trắng) → Nền tông rất nhạt → Ảnh tràn lề.
  - **CẤM** 3 section liên tiếp có cùng một màu nền.
  - Trang chủ phải có ít nhất 2 section dải màu thương hiệu đậm chữ trắng tương phản cao.

---

## 3. LƯỚI BỐ CỤC, KHOẢNG CÁCH & BO GÓC THỐNG NHẤT

- **Container chuẩn 1200px:** Mọi section căn thẳng tắp theo một lưới chung `max-w-[1200px]` (`.container-b2b` hoặc `max-w-7xl px-4 sm:px-6 lg:px-8 mx-auto`).
- **Khoảng cách Section (Section Spacing):** 
  - Desktop chuẩn 96px (`py-24` hoặc `.section-sp`) đối với các dải phân cách lớn, hoặc tinh tế theo quy chuẩn niche.
  - Mobile chuẩn 56px (`py-14`).
- **Bo góc thống nhất (Unified Border Radius):** 
  - Duy nhất **8px** (`rounded-[8px]`, `rounded-lg`, `--radius-base: 8px`) cho toàn bộ thẻ, nút bấm, ô input và bảng.
  - **CẤM** nút bo tròn kiểu pill (`rounded-full`).
- **Bóng đổ có kiểm soát (Controlled Elevation):** 
  - Chỉ dùng 1 mức bóng rất nhẹ (`0 10px 30px rgba(0, 0, 0, 0.08)`) khi hover thẻ sản phẩm hoặc khi header dính (`.header-scrolled`).
  - **CẤM** shadow mềm lớn (`shadow-md`/`lg`/`xl`) lan tràn mọi nơi.

---

## 4. HERO SECTION & DYNAMIC CAROUSEL SLIDER

- Dưới Header là Dynamic Carousel Slider nạp 100% dữ liệu động từ CSDL (`$sliders`).
- Thẻ `<picture>` linh hoạt hỗ trợ ảnh Mobile và Desktop (`image_mobile`, `image_desktop`).
- Lớp phủ gradient tối tương phản cao (`from-black/85 via-black/50 to-transparent`) bảo đảm chữ trắng luôn đọc sắc nét trên mọi hình ảnh.
- Tiêu đề slider: `text-2xl sm:text-4xl lg:text-[48px]`, font đậm, tối đa 2-3 dòng.
- Cặp nút CTA: 1 nút chính màu nhấn Accent + 1 nút phụ viền sáng/màu thương hiệu.
- Nút điều hướng mũi tên tròn mờ (`backdrop-blur-sm`), chấm phân trang mở rộng thành thanh màu nhấn khi active.
- Dưới Slider có **dải ngang 4 thông số hoạt động cốt lõi** của doanh nghiệp (Quy cách, Giao nhận/Triển khai, Kiểm định/Cam kết, Hỗ trợ) nằm trên nền tông màu rất nhạt.

---

## 5. THẺ SẢN PHẨM CÂN BẰNG CHIỀU CAO (EQUAL HEIGHT PRODUCT CARDS)

⛔ **CẤM TUYỆT ĐỐI** hiện tượng thẻ sản phẩm bị "cái cao cái thấp" do ảnh dọc/ngang không đồng đều hoặc tên dài ngắn:
- **Khung ảnh vuông chuẩn 1:1:** 
  - Khung bọc ảnh: `aspect-ratio: 1 / 1; flex-shrink: 0; position: relative; overflow: hidden;` (`aspect-square`).
  - Thẻ `<img>`: `position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: center;`.
  - Hiệu ứng: Hover zoom nhẹ 1.05 (`transition-transform duration-500 ease-out`).
- **Cấu trúc cân bằng lưới Grid:**
  - Lưới Grid: Thêm `items-stretch`.
  - Cột bọc: `w-full h-full flex flex-col`.
  - Thẻ `<article class="product-card-b2b">`: `h-full flex flex-col justify-between`.
  - Vùng tiêu đề: Khóa chiều cao tối thiểu `min-h-[3rem]` (vừa vặn 2 dòng chữ).
  - Vùng thông số kỹ thuật: Khóa chiều cao tối thiểu `min-h-[46px]`.
  - Vùng giá & Nút CTA: Đặt `mt-auto`, đảm bảo hàng giá và nút CTA luôn nằm thẳng tắp trên cùng một đường kẻ ngang đáy thẻ.

```html
<!-- Mẫu Thẻ Sản phẩm Chuẩn Base Equal-Height -->
<div class="h-full flex flex-col">
  <article class="h-full flex flex-col justify-between bg-white border border-slate-200 rounded-[8px] overflow-hidden hover:shadow-[0_10px_30px_rgba(0,0,0,0.08)] transition-all duration-300">
    <div class="aspect-square w-full relative overflow-hidden bg-slate-50 flex-shrink-0">
      <img src="{{ $product->image }}" alt="{{ $product->name }}" class="absolute inset-0 w-full h-full object-cover hover:scale-105 transition-transform duration-500">
    </div>
    <div class="p-4 flex flex-col flex-1">
      <h3 class="font-bold text-slate-800 text-sm md:text-base line-clamp-2 min-h-[3rem]">
        <a href="{{ route('web.resolve', $product->slug) }}">{{ $product->name }}</a>
      </h3>
      <div class="min-h-[46px] text-xs text-slate-500 mt-1 line-clamp-2">
        {{ $product->intro_vn }}
      </div>
      <div class="mt-auto pt-3 border-t border-slate-100 flex items-center justify-between">
        <span class="font-bold text-primary text-base">{{ number_format($product->price) }}đ</span>
        <a href="{{ route('web.resolve', $product->slug) }}" class="px-3 py-1.5 text-xs font-semibold rounded-[8px] bg-primary text-white hover:bg-primary-dark transition-colors">
          Chi tiết
        </a>
      </div>
    </div>
  </article>
</div>
```

---

## 6. CHUYỂN ĐỘNG CÓ KIỂM SOÁT (CONTROLLED TRANSITIONS)

- **Sticky Header:** Dính trên cùng, thu nhỏ chiều cao và có nền trắng + bóng nhẹ khi cuộn (`.header-scrolled`).
- **Scroll Reveal:** Trượt lên 20px + fade, 600ms ease-out, chỉ chạy 1 lần duy nhất khi cuộn tới (`.scroll-reveal.is-visible`), áp dụng độ trễ so le (`.stagger-1` đến `.stagger-4`).
- **Stat CountUp:** Hiệu ứng số liệu đếm lên 1 lần khi cuộn tới (`.stat-counter`).
- **Mũi tên trong link:** Dịch sang phải 4px khi hover (`.arrow-shift`).
- **Bắt buộc tuân thủ `@media (prefers-reduced-motion: reduce)`:** Tắt toàn bộ animation khi người dùng bật chế độ giảm chuyển động trong hệ điều hành:

```css
@media (prefers-reduced-motion: reduce) {
  *, ::before, ::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
    scroll-behavior: auto !important;
  }
}
```

---

## 7. FLOATING ACTION BUTTONS NGUYÊN BẢN

Cố định góc dưới bên phải (`fixed bottom-4 right-3 md:bottom-5 md:right-5 z-50 flex flex-col gap-3`):
1. **Hotline Call Button:** Tròn đỏ (`bg-red-600`), icon `fas fa-phone-volume`, chuông nhấp nháy (`animate-ping`), tooltip hiện số hotline.
2. **Zalo Chat Button:** Tròn xanh Zalo (`bg-[#0084FF]`), icon `fas fa-comment-dots`, tooltip "Chat Zalo tư vấn".
3. **Move to Top Button:** Tròn tối màu (`bg-slate-900`), icon `fas fa-arrow-up`, tự động hiện khi cuộn chuột > 250px.
*Toàn bộ 3 nút này phải nằm tập trung trong `resources/views/frontend/partials/contact_buttons.blade.php` (Rule 10).*

---

## 8. QUY CHUẨN XỬ LÝ MEDIA & CACHE-BUSTING

- **Khử Localhost URL:** 
  - Trong `AutoImagePathsTrait`, tự động parse và làm sạch mọi URL chứa `http://localhost/` hoặc `https://localhost/` thành đường dẫn asset tương đối chuẩn (`asset($path)`), bảo đảm hình ảnh hiển thị 100% trên domain thật khi triển khai staging / production.
- **Cache-Busting Assets:** 
  - Gắn query param timestamp (`?v={{ file_exists(public_path(...)) ? filemtime(public_path(...)) : time() }}`) vào các file CSS tĩnh trong `head.blade.php` để trình duyệt không lưu cache cũ khi developer cập nhật giao diện.
