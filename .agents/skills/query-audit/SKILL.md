---
name: query-audit
description: >-
  Kỹ năng rà soát và kiểm toán hiệu năng truy vấn CSDL, phát hiện lỗi N+1 queries,
  câu lệnh select * bị cấm hoặc SQL thô trong Controller (vi phạm Rule 2 & Rule 19).
---

# Query Audit Skill

## 1. Mục tiêu
Đảm bảo 100% truy vấn dữ liệu đạt hiệu năng tối đa, không làm nghẽn CSDL MySQL và triệt tiêu các lỗi kiến trúc:
- Phát hiện Controller viết `DB::table(...)` hoặc `DB::select(...)` trực tiếp thay vì qua Repository.
- Phát hiện vấn đề **N+1 queries** khi duyệt danh sách liên quan mà không dùng Eager Loading (`with()`).
- Phát hiện truy vấn danh sách lấy cột thừa (`content_vn`, `content_en`, `image_detail`, `select *`).

---

## 2. Các Dấu Hiệu Vi Phạm Cần Rà Soát

### 2.1. SQL Thô Trong Controller (Vi phạm Rule 2)
```bash
# Quét tìm DB facade được gọi trong thư mục Controllers:
npm run ast -- -p 'DB::$METHOD($$$)' app/Http/Controllers
```
- **Khắc phục**: Chuyển logic query sang `app/Repositories/Eloquent/` và gọi qua Interface.

### 2.2. Lấy Cột Văn Bản Lớn Trong Query Danh Sách (Vi phạm Rule 19)
- Khi hiển thị danh sách sản phẩm, tin tức, card preview:
  - ❌ **Sai**: `Product::where('status', true)->get();` (lấy cả `content_vn`, `content_en` hàng chục KB mỗi dòng).
  - ✅ **Đúng**: `Product::select('id_product', 'name_vn', 'slug_vn', 'price', 'avatar', 'category_id')->get();`

### 2.3. Lỗi N+1 Queries (Missing Eager Loading)
- ❌ **Sai**:
  ```php
  $products = $this->productRepo->all();
  foreach ($products as $p) {
      echo $p->cate->name; // Gây thêm 1 truy vấn riêng cho mỗi sản phẩm!
  }
  ```
- ✅ **Đúng**:
  ```php
  $products = $this->productRepo->with(['cate'])->all();
  ```

---

## 3. Lệnh Thực Thi Rà Soát Tự Động
```bash
# 1. Quét Controllers chứa DB query thô:
git grep -n "DB::table" app/Http/Controllers/
git grep -n "DB::raw" app/Http/Controllers/

# 2. Quét các hàm Repository thiếu select cột:
git grep -n "all(\['\*'\])" app/Repositories/Eloquent/
```
