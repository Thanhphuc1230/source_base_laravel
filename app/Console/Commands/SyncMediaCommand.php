<?php

namespace App\Console\Commands;

use App\Models\Media;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SyncMediaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:sync {--force : Re-scan and overwrite existing entries}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan existing media files in public/uploads and synchronize into tp_media table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $uploadsDir = public_path('uploads');
        if (!File::isDirectory($uploadsDir)) {
            $this->error('Directory public/uploads does not exist.');
            return 1;
        }

        $this->info('Scanning files in public/uploads...');

        $files = File::allFiles($uploadsDir);
        $total = count($files);
        $this->info("Found {$total} files. Starting synchronization...");

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'mp4', 'webm', 'mov', 'ogg', 'pdf', 'ico'];
        $syncedCount = 0;
        $skippedCount = 0;

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        foreach ($files as $file) {
            $extension = strtolower($file->getExtension());
            if (!in_array($extension, $allowedExtensions)) {
                $skippedCount++;
                $bar->advance();
                continue;
            }

            // Relative path e.g. uploads/product/2026/10/xyz.webp
            $fullPath = $file->getRealPath();
            $relativePath = 'uploads/' . str_replace('\\', '/', substr($fullPath, strlen($uploadsDir) + 1));

            // Extract module from path
            $parts = explode('/', str_replace('\\', '/', substr($fullPath, strlen($uploadsDir) + 1)));
            $module = !empty($parts[0]) ? $parts[0] : 'general';

            $existing = Media::where('path', $relativePath)->first();
            if ($existing && !$this->option('force')) {
                $skippedCount++;
                $bar->advance();
                continue;
            }

            $fileName = $file->getFilename();
            $fileSize = $file->getSize();
            $mimeType = File::mimeType($fullPath) ?: 'application/octet-stream';

            // Calculate dimensions for image files
            $dimensions = null;
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                try {
                    $imgSize = @getimagesize($fullPath);
                    if ($imgSize && isset($imgSize[0]) && isset($imgSize[1])) {
                        $dimensions = "{$imgSize[0]}x{$imgSize[1]}";
                    }
                } catch (\Throwable $e) {
                    // Ignore dimension failure
                }
            }

            if ($existing) {
                $existing->update([
                    'name' => pathinfo($fileName, PATHINFO_FILENAME),
                    'file_name' => $fileName,
                    'module' => $module,
                    'mime_type' => $mimeType,
                    'extension' => $extension,
                    'size' => $fileSize,
                    'dimensions' => $dimensions,
                ]);
            } else {
                Media::create([
                    'uuid' => (string) Str::uuid(),
                    'name' => pathinfo($fileName, PATHINFO_FILENAME),
                    'file_name' => $fileName,
                    'path' => $relativePath,
                    'module' => $module,
                    'mime_type' => $mimeType,
                    'extension' => $extension,
                    'size' => $fileSize,
                    'dimensions' => $dimensions,
                    'created_by' => null,
                ]);
            }

            $syncedCount++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Synchronization finished. Synced: {$syncedCount}, Skipped: {$skippedCount}.");

        return 0;
    }
}
