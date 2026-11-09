---
name: module-scaffolder
description: >-
  Kỹ năng tự động sinh trọn bộ 6 tầng kiến trúc cho một module mới (Migration, Model,
  Repository Interface, Eloquent Repository, Service, Controller, FormRequest, Blade).
  Sử dụng khi phát triển chức năng mới để đảm bảo 100% tuân thủ Layered Architecture.
---

# Module Scaffolder Skill

## 1. Mục tiêu
Chuẩn hóa việc tạo mới module hoặc chức năng, ngăn chặn tình trạng viết sai kiến trúc, bỏ sót tầng Repository, viết SQL thô trong Controller hoặc quên đăng ký Provider.

---

## 2. Quy trình 6 Tầng Bắt Buộc (Scaffolding Pipeline)

Khi tạo một module mới `{Module}` (Ví dụ: `Voucher`, `Review`, `Affiliate`):

### Tầng 1: Migration & CSDL
- Tạo file migration: `database/migrations/xxxx_create_tp_{table}_table.php`.
- Đồng thời ghi câu lệnh `CREATE TABLE` / `ALTER TABLE` tương ứng vào `database/patch_schema.sql`.

### Tầng 2: Eloquent Model (`app/Models/{Module}.php`)
- Sử dụng các trait tiêu chuẩn:
  ```php
  use App\Traits\AutoImagePathsTrait;
  use App\Traits\BaseLogsActivity;
  use GeneaLabs\LaravelModelCaching\Traits\Cachable;
  use Illuminate\Database\Eloquent\Factories\HasFactory;
  use Illuminate\Database\Eloquent\Model;
  ```
- Khai báo rõ ràng: `$table`, `$primaryKey`, `$fillable`, `$casts`.

### Tầng 3: Repository Interface (`app/Repositories/Interfaces/{Module}RepositoryInterface.php`)
- Kế thừa `RepositoryInterface`:
  ```php
  namespace App\Repositories\Interfaces;

  interface {Module}RepositoryInterface extends RepositoryInterface
  {
      public function getListPaginated(array $filters = [], int $perPage = 10);
      public function findByUuid(string $uuid);
  }
  ```

### Tầng 4: Eloquent Repository (`app/Repositories/Eloquent/{Module}Repository.php`)
- Kế thừa `BaseRepository` và implement Interface:
  ```php
  namespace App\Repositories\Eloquent;

  use App\Models\{Module};
  use App\Repositories\Interfaces\{Module}RepositoryInterface;

  class {Module}Repository extends BaseRepository implements {Module}RepositoryInterface
  {
      public function __construct({Module} $model)
      {
          parent::__construct($model);
      }
  }
  ```

### Tầng 5: Service Layer (`app/Services/{Module}Service.php`)
- Chứa business logic, bọc `DB::transaction`, tự động xóa cache liên quan (`DashboardService::flushCache` hoặc `Cache::tags`):
  ```php
  namespace App\Services;

  use App\Repositories\Interfaces\{Module}RepositoryInterface;
  use Illuminate\Support\Facades\DB;

  class {Module}Service
  {
      protected $repo;

      public function __construct({Module}RepositoryInterface $repo)
      {
          $this->repo = $repo;
      }
  }
  ```

### Tầng 6: Controller & FormRequest (`app/Http/Controllers/Admin/{Module}Controller.php`)
- Sử dụng FormRequest xác thực input.
- Kế thừa `BaseController`, inject `{Module}RepositoryInterface` hoặc `{Module}Service`.
- Tuyệt đối cấm viết `DB::table(...)` hay query SQL thô trong Controller.
- Dùng `<x-admin.table-wrapper>` và chuẩn UI 38px controls cho giao diện Admin.

---

## 3. Đăng ký & Đồng bộ Bắt buộc
1. Đăng ký bind trong `app/Providers/RepositoryServiceProvider.php`:
   ```php
   $this->app->bind({Module}RepositoryInterface::class, {Module}Repository::class);
   ```
2. Thêm route trong `routes/admin/{module}.php` và include vào `routes/admin.php`.
3. Chạy cập nhật skeleton map ngay lập tức:
   ```bash
   php tools/generate-codebase-skeleton.php
   php artisan optimize:clear
   ```
