---
name: hardcode-scanner
description: >-
  Kỹ năng tự động quét mã nguồn Blade Views để phát hiện vi phạm Rule 1 (Zero-Hardcode).
  Sử dụng trước khi hoàn tất bất kỳ task giao diện hoặc chuyển đổi theme nào.
---

# Hardcode Scanner Skill

## 1. Mục tiêu
Phát hiện các thông tin nhạy cảm bị ghi cứng trong file `.blade.php`:
- Tên công ty / thương hiệu cụ thể (ví dụ: tên khách hàng, tên domain).
- Số điện thoại / Hotline cứng (ví dụ: `09xx`, `08xx`, `1900...`).
- Email liên hệ cứng (ví dụ: `info@...`, `contact@...`).
- URL Google Maps cứng (`https://www.google.com/maps/embed...`).
- Link mạng xã hội cứng (Facebook, Zalo, YouTube).

## 2. Các Mẫu Cần Quét & Thay Thế

| Dấu hiệu vi phạm ❌ | Thay thế bắt buộc ✅ |
|---|---|
| Chữ cứng tên công ty | `{{ $web->name_vn }}` hoặc `{{ $web->name_en }}` |
| Số điện thoại tĩnh | `{{ $web->phone }}` |
| Email tĩnh | `{{ $web->email }}` |
| Địa chỉ tĩnh | `{{ $web->address_vn }}` |
| Thẻ iframe Google Maps tĩnh | `src="{{ $web->map }}"` |
| Link Zalo tĩnh | `href="https://zalo.me/{{ preg_replace('/[^0-9]/', '', $web->zalo ?? $web->phone) }}"` |
| Link Facebook tĩnh | `href="{{ $web->facebook }}"` |
| Thẻ logo ảnh tĩnh | `<img src="{{ asset($web->logo) }}" alt="{{ $web->name_vn }}">` |

## 3. Lệnh Quét Nhanh Bằng Terminal
```bash
# Quét tìm các số điện thoại cứng tiềm ẩn trong views:
git grep -E -n "0[0-9]{3}[\. -]?[0-9]{3}[\. -]?[0-9]{3}" resources/views/frontend/

# Quét tìm iframe google map cứng:
git grep -n "google.com/maps/embed" resources/views/frontend/
```
