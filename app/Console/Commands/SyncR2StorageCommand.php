<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SyncR2StorageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'r2:sync {--overwrite : Ghi đè file nếu đã tồn tại trên Cloudflare R2}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Đồng bộ toàn bộ media từ public/uploads lên Cloudflare R2 Bucket';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $bucket = config('filesystems.disks.r2.bucket');
        if (empty($bucket)) {
            $this->error('Chưa cấu hình CLOUDFLARE_R2_BUCKET trong file .env!');
            return 1;
        }

        $uploadsDir = public_path('uploads');
        if (!File::isDirectory($uploadsDir)) {
            $this->error('Thư mục public/uploads không tồn tại.');
            return 1;
        }

        $this->info("Đang quét các tệp tin trong public/uploads...");
        $files = File::allFiles($uploadsDir);
        $total = count($files);

        if ($total === 0) {
            $this->warn('Không có tệp tin nào trong public/uploads.');
            return 0;
        }

        $this->info("Tìm thấy {$total} tệp tin. Bắt đầu đẩy lên Cloudflare R2 ({$bucket})...");

        $overwrite = $this->option('overwrite');
        $synced = 0;
        $skipped = 0;
        $failed = 0;

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $disk = Storage::disk('r2');

        foreach ($files as $file) {
            $fullPath = $file->getRealPath();
            $relativePath = 'uploads/' . str_replace('\\', '/', substr($fullPath, strlen($uploadsDir) + 1));

            try {
                if (!$overwrite && $disk->exists($relativePath)) {
                    $skipped++;
                    $bar->advance();
                    continue;
                }

                $stream = fopen($fullPath, 'r');
                $disk->put($relativePath, $stream, 'public');
                if (is_resource($stream)) {
                    fclose($stream);
                }

                $synced++;
            } catch (\Throwable $e) {
                $failed++;
                $this->newLine();
                $this->error("Lỗi tải lên {$relativePath}: " . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Hoàn tất đồng bộ Cloudflare R2!");
        $this->table(
            ['Trạng thái', 'Số lượng'],
            [
                ['Đã tải lên thành công', $synced],
                ['Bỏ qua (đã tồn tại)', $skipped],
                ['Thất bại', $failed],
                ['Tổng số file', $total],
            ]
        );

        return 0;
    }
}
