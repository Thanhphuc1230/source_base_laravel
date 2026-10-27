# Quy Chuẩn Thiết Kế Giao Diện Frontend (Universal Base Anti-AI Design System)

> Tài liệu này quy định toàn bộ Design System cho Frontend theo chuẩn **Universal Base Anti-AI Design System** (Chi tiết xem thêm tại: [`.agents/rules/03-anti-ai-design-system.md`](./rules/03-anti-ai-design-system.md)).

---

## 0. ĐỊNH HƯỚNG PHONG CÁCH & NGUYÊN TẮC TRÁNH "CHẤT AI"

- **Thiết kế từ sản phẩm & nghiệp vụ thật:** Giao diện phản ánh quy cách sản phẩm, thông số kỹ thuật, quy trình đặt hàng/dịch vụ của doanh nghiệp thật. Không dùng layout rỗng vô nghĩa.
- **Nền chủ đạo & Nhịp nền xen kẽ (Alternating Rhythm):**
  - Luân chuyển: Nền Trắng tinh khiết (`#FFFFFF`) hoặc Off-white (`#F9FAFB`) → Nền màu thương hiệu đậm chữ trắng (ít nhất 2 section trên trang chủ) → Nền tông rất nhạt (5-8% tint) → Ảnh tràn lề.
  - CẤM 3 section liên tiếp có cùng màu nền.
- **Tone màu thương hiệu (Niche Color Palette):** Xây dựng theo logo và đặc trưng ngành hàng (Xanh lá tự nhiên/y tế, Xanh navy công nghệ/logistics, Nâu đồng/Gold nội thất cao cấp, Đen than chì luxury). Dùng cho các mảng lớn có lực: dải CTA, Footer, Banner.
- **Màu nhấn (Accent Color):** Chỉ dùng cho nút CTA chính và tối đa 1 điểm nhấn mỗi viewport.
- **Bo góc thống nhất (Unified Radius):** Duy nhất **8px** (`rounded-[8px]`, `rounded-lg`). CẤM nút bo tròn dạng pill (`rounded-full`).
- **Thẻ sản phẩm cân bằng tuyệt đối (Equal Height):** 100% thẻ sản phẩm dùng khung ảnh vuông 1:1 (`aspect-square`), `min-h-[3rem]` cho tiêu đề 2 dòng, `min-h-[46px]` cho thông số, và `mt-auto` cho giá + CTA để mọi nút bấm nằm thẳng tắp một hàng đáy.
- **Typography & Phân cấp chữ:**
  - Tiêu đề (`h1`-`h6`): Sans-serif đậm hoặc Serif sang trọng theo Niche, tự đứng vững, KHÔNG dùng badge pill chữ hoa nhỏ dẫn đường bên trên.
  - Nội dung/Mô tả: Sử dụng màu xám đậm chuẩn mực (`text-slate-700`, `text-slate-600`), độ tương phản cao, dễ đọc.
- **CẤM tuyệt đối "Chất AI":** Không dùng khối 4 số liệu ảo, không dùng icon bọc nền pastel (hồng, tím, xanh lơ), không dùng pill chứa tên công ty trên Hero, không dùng emoji, không dùng gradient cầu vồng/neon.

---

## 1. TYPOGRAPHY & FONTS (WORDPRESS STYLE)

Tất cả các file CSS/Tailwind phải cấu hình font family theo chuẩn:

```css
/* public/frontend/css/fonts.css */
@font-face {
  font-family: 'Noto Sans';
  src: url('/frontend/fonts/NotoSans-Regular.woff2') format('woff2');
  font-weight: 400;
  font-display: swap;
}
@font-face {
  font-family: 'Roboto';
  src: url('/frontend/fonts/Roboto-Bold.woff2') format('woff2');
  font-weight: 700;
  font-display: swap;
}

/* Font mặc định tối ưu tiếng Việt chuẩn toàn hệ thống: */
body, h1, h2, h3, h4, h5, h6 {
  font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", sans-serif;
}
```

---

## 2. HIỆU ỨNG SCROLL (SCROLL ANIMATIONS)

Sử dụng thư viện Scroll Animation nhẹ lưu tại local (`public/frontend/js/scroll-animate.js`) hoặc **Intersection Observer API**:

### Quy chuẩn thêm class cho Blade Component khi cuộn:

```html
<!-- Khi cuộn tới, phần tử này sẽ trượt nhẹ từ dưới lên và mờ dần ra -->
<div class="scroll-anim fade-up delay-100">...</div>
<div class="scroll-anim fade-up delay-200">...</div>
<div class="scroll-anim zoom-in delay-100">...</div>
```

### CSS Hiệu ứng chuẩn (`public/frontend/css/animations.css`):

```css
.scroll-anim {
  opacity: 0;
  transform: translateY(30px);
  transition: opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1), transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
  will-change: opacity, transform;
}
.scroll-anim.is-visible {
  opacity: 1;
  transform: translateY(0);
}
.scroll-anim.zoom-in {
  transform: scale(0.9);
}
.scroll-anim.zoom-in.is-visible {
  transform: scale(1);
}
/* Stagger delays */
.delay-100 { transition-delay: 100ms; }
.delay-200 { transition-delay: 200ms; }
.delay-300 { transition-delay: 300ms; }
```

---

## 3. THIẾT KẾ COMPONENT CHUẨN ANTI-AI (EQUAL-HEIGHT & BO GÓC 8PX)

### A. Product Card (Thẻ sản phẩm cân bằng chiều cao 100%)

```html
<div class="h-full flex flex-col">
  <article class="h-full flex flex-col justify-between bg-white border border-slate-200 rounded-[8px] overflow-hidden hover:shadow-[0_10px_30px_rgba(0,0,0,0.08)] transition-all duration-300 group scroll-anim fade-up">
    <!-- Ảnh vuông 1:1 tuyệt đối -->
    <div class="aspect-square w-full relative overflow-hidden bg-slate-50 flex-shrink-0">
      <img src="{{ $product->image }}" alt="{{ $product->name }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
      @if($product->price_old > $product->price)
        <span class="absolute top-2 left-2 bg-red-600 text-white text-[11px] font-bold px-2 py-0.5 rounded-[4px]">-{{ round((($product->price_old - $product->price)/$product->price_old)*100) }}%</span>
      @endif
    </div>
    
    <!-- Nội dung thẻ -->
    <div class="p-4 flex flex-col flex-1">
      <p class="text-xs text-slate-400 mb-1 font-medium">{{ $product->cate->name }}</p>
      <!-- Khóa tối thiểu 3rem (2 dòng chữ) để thẻ không bị lệch cao thấp -->
      <h3 class="font-bold text-slate-800 text-sm md:text-base line-clamp-2 min-h-[3rem] group-hover:text-primary transition-colors">
        <a href="{{ route('web.resolve', $product->slug) }}">{{ $product->name }}</a>
      </h3>
      
      <!-- Khóa chiều cao thông số kỹ thuật tối thiểu 46px -->
      <div class="min-h-[46px] text-xs text-slate-500 mt-1 line-clamp-2">
        {{ $product->intro_vn }}
      </div>

      <!-- Đáy thẻ luôn bám sát chân nhờ mt-auto -->
      <div class="mt-auto pt-3 border-t border-slate-100 flex items-center justify-between">
        <div>
          <span class="text-base md:text-lg font-bold text-red-600">{{ number_format($product->price) }}đ</span>
          @if($product->price_old)
            <span class="text-xs text-slate-400 line-through ml-1">{{ number_format($product->price_old) }}đ</span>
          @endif
        </div>
        <a href="{{ route('web.resolve', $product->slug) }}" class="px-3 py-1.5 text-xs font-semibold rounded-[8px] bg-primary text-white hover:bg-primary-dark transition-colors">
          Chi tiết
        </a>
      </div>
    </div>
  </article>
</div>
```

### B. News Card (Thẻ tin tức)

```html
<div class="h-full flex flex-col">
  <article class="h-full flex flex-col justify-between bg-white rounded-[8px] border border-slate-200 overflow-hidden hover:shadow-[0_10px_30px_rgba(0,0,0,0.08)] transition-all duration-300 group scroll-anim fade-up">
    <div class="aspect-video w-full overflow-hidden bg-slate-100 relative">
      <img src="{{ $news->image }}" alt="{{ $news->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
    </div>
    <div class="p-4 md:p-5 flex flex-col flex-1">
      <span class="text-xs font-semibold text-primary mb-1">{{ $news->cate->name }}</span>
      <h3 class="font-bold text-slate-800 text-base mb-2 line-clamp-2 min-h-[3rem] group-hover:text-primary transition-colors">
        <a href="{{ route('web.resolve', $news->slug) }}">{{ $news->name }}</a>
      </h3>
      <p class="text-slate-500 text-xs md:text-sm line-clamp-2 mb-4 min-h-[40px]">{{ $news->intro_vn }}</p>
      <div class="mt-auto pt-3 border-t border-slate-100 text-xs text-slate-400 flex items-center gap-2">
        <i class="far fa-calendar-alt"></i>
        <span>{{ $news->created_at->format('d/m/Y') }}</span>
      </div>
    </div>
  </article>
</div>
```

---

## 4. QUY CHUẨN RESPONSIVE GRID (TAILWIND)

* **Container:** `container mx-auto px-4 max-w-7xl`
* **Sản phẩm (Products Grid):**
  * Mobile (`< 640px`): `grid grid-cols-2 gap-3` (2 cột theo đúng yêu cầu)
  * Tablet (`640px - 1024px`): `grid-cols-3 gap-4`
  * Desktop (`> 1024px`): `grid-cols-4 gap-6`

* **Tin tức (News Grid):**
  * Mobile (`< 640px`): `grid grid-cols-2 gap-3`
  * Desktop (`> 1024px`): `grid-cols-3 gap-6`

---

## 5. QUY CHUẨN KHOẢNG CÁCH SECTION (SECTION SPACING / PADDING)

* **Container chuẩn 1200px:** Mọi section căn thẳng tắp theo lưới `max-w-[1200px]` (`container mx-auto px-4 max-w-7xl` hoặc `.container-b2b`).
* **Khoảng cách linh hoạt:**
  - Dải ngăn cách lớn/B2B chuyên nghiệp: Desktop chuẩn 96px (`py-24` hoặc `.section-sp`), Mobile chuẩn 56px (`py-14`).
  - Dải nội dung liền kề hoặc khối tin/sản phẩm gọn gàng: Duy trì padding tinh tế `py-[15px]` hoặc `py-6`.
* **Bo góc thống nhất:** Toàn bộ thẻ, nút, input dùng duy nhất **8px** (`rounded-[8px]`).

---

## 6. QUY CHUẨN HEADER ICONS & LANGUAGE SWITCHER

* **Không hiển thị số điện thoại / Hotline ở Header:**
  - Không đặt nút SĐT / hotline trong header thanh điều hướng chính nhằm giữ UI header tối giản, thông thoáng.
  - Các nút gọi điện / Zalo đã được bố trí chuyên nghiệp tại floating action button góc dưới phải màn hình (`contact_buttons.blade.php`).
* **Icon Giỏ hàng (Cart Icon):**
  - Giữ icon tự nhiên, phẳng và mộc.
  - **KHÔNG dùng background hay border-radius** (không bọc trong thẻ tròn/vuông nền xám hay viền).
  - Chỉ hiển thị icon thuần túy (như `<i class="fa-solid fa-cart-shopping"></i>` hoặc `<i class="fa-solid fa-bag-shopping"></i>`) kèm badge đếm số lượng tinh tế.
* **Cụm cờ ngôn ngữ (Language Switcher Flags):**
  - **KHÔNG dùng border-radius hay background/nền** bọc quanh lá cờ.
  - **KHÔNG dùng dropdown ẩn**: Hiển thị trực tiếp **2 lá cờ nằm ngang cạnh nhau** (`flex items-center gap-2`), mộc, phẳng, click trực tiếp để chuyển ngôn ngữ (VI / EN).

---

## 7. CHUYỂN ĐỘNG CÓ KIỂM SOÁT & PREFERS-REDUCED-MOTION

- **Scroll Reveal:** Hiệu ứng trượt nhẹ 20px + fade 600ms, chỉ chạy 1 lần khi cuộn tới (`.scroll-reveal.is-visible`).
- **Stat CountUp:** Số liệu đếm lên 1 lần duy nhất (`.stat-counter`).
- **Tuân thủ @media (prefers-reduced-motion):** Tắt toàn bộ animation khi người dùng bật chế độ giảm chuyển động trong hệ điều hành.

---

## 8. QUY CHUẨN XỬ LÝ MEDIA & CACHE-BUSTING

- **Khử Localhost URL:** Mọi URL trong `AutoImagePathsTrait` tự động làm sạch `http://localhost/` thành `asset(...)` chuẩn domain.
- **Cache-Busting Assets:** File CSS tĩnh trong `head.blade.php` bắt buộc kèm timestamp `?v={{ filemtime(...) }}`.
