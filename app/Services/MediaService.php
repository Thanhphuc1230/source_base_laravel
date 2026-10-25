<?php

namespace App\Services;

use App\Models\Media;
use App\Repositories\Interfaces\MediaRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class MediaService
{
    /**
     * @var MediaRepositoryInterface
     */
    protected $mediaRepo;

    /**
     * Allowed extensions.
     */
    protected $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    protected $videoExtensions = ['mp4', 'webm', 'mov', 'ogg'];
    protected $docExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'svg', 'ico'];

    public function __construct(MediaRepositoryInterface $mediaRepo)
    {
        $this->mediaRepo = $mediaRepo;
    }

    /**
     * Get paginated media items.
     */
    public function paginate(array $filters = [], int $perPage = 24)
    {
        return $this->mediaRepo->paginateWithFilters($filters, $perPage);
    }

    /**
     * Upload and process a single file.
     *
     * @param UploadedFile $file
     * @param string $module
     * @param int|null $userId
     * @return Media|null
     */
    public function upload(UploadedFile $file, string $module = 'general', ?int $userId = null): ?Media
    {
        @ini_set('memory_limit', '1024M');

        $originalName = $file->getClientOriginalName();
        $rawExtension = strtolower($file->getClientOriginalExtension());
        $cleanModule = Str::slug($module ?: 'general');
        $year = date('Y');
        $month = date('m');

        // Path structure: uploads/{module}/{Y}/{m}/
        $subFolder = "uploads/{$cleanModule}/{$year}/{$month}";
        $targetDirectory = public_path($subFolder);

        if (!File::exists($targetDirectory)) {
            File::makeDirectory($targetDirectory, 0755, true);
        }

        $baseName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME));
        if (empty($baseName)) {
            $baseName = 'media_' . time();
        }
        $uniqueSuffix = time() . '_' . Str::random(4);

        $dimensions = null;
        $isImage = in_array($rawExtension, $this->imageExtensions);

        try {
            if ($isImage) {
                // Auto WebP conversion with max 1920px dimensions
                $targetFileName = "{$baseName}_{$uniqueSuffix}.webp";
                $fullFilePath = "{$targetDirectory}/{$targetFileName}";

                $img = Image::make($file);

                // Resize if exceeding 1920x1920
                if ($img->width() > 1920 || $img->height() > 1920) {
                    $img->resize(1920, 1920, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                }

                $dimensions = "{$img->width()}x{$img->height()}";
                $img->encode('webp', 80)->save($fullFilePath);
                $img->destroy();

                $storedSize = File::size($fullFilePath);
                $finalMime = 'image/webp';
                $finalExt = 'webp';
            } else {
                // Direct store for SVG, videos, documents
                $targetFileName = "{$baseName}_{$uniqueSuffix}.{$rawExtension}";
                $fullFilePath = "{$targetDirectory}/{$targetFileName}";

                $file->move($targetDirectory, $targetFileName);

                $storedSize = File::size($fullFilePath);
                $finalMime = File::mimeType($fullFilePath) ?: $file->getClientMimeType();
                $finalExt = $rawExtension;
            }

            $relativePath = "{$subFolder}/{$targetFileName}";

            return Media::create([
                'uuid' => (string) Str::uuid(),
                'name' => pathinfo($originalName, PATHINFO_FILENAME),
                'file_name' => $targetFileName,
                'path' => $relativePath,
                'module' => $cleanModule,
                'mime_type' => $finalMime,
                'extension' => $finalExt,
                'size' => $storedSize,
                'dimensions' => $dimensions,
                'created_by' => $userId,
            ]);

        } catch (\Throwable $e) {
            Log::error('MediaService upload error: ' . $e->getMessage());

            // Fallback: Store original file without modification
            try {
                $targetFileName = "{$baseName}_{$uniqueSuffix}.{$rawExtension}";
                $fullFilePath = "{$targetDirectory}/{$targetFileName}";
                $file->move($targetDirectory, $targetFileName);

                $relativePath = "{$subFolder}/{$targetFileName}";

                return Media::create([
                    'uuid' => (string) Str::uuid(),
                    'name' => pathinfo($originalName, PATHINFO_FILENAME),
                    'file_name' => $targetFileName,
                    'path' => $relativePath,
                    'module' => $cleanModule,
                    'mime_type' => $file->getClientMimeType() ?: 'application/octet-stream',
                    'extension' => $rawExtension,
                    'size' => File::size($fullFilePath),
                    'dimensions' => null,
                    'created_by' => $userId,
                ]);
            } catch (\Throwable $fallbackErr) {
                Log::error('MediaService fallback upload error: ' . $fallbackErr->getMessage());
                return null;
            }
        }
    }

    /**
     * Upload multiple files.
     */
    public function uploadMultiple(array $files, string $module = 'general', ?int $userId = null): array
    {
        $uploaded = [];
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $item = $this->upload($file, $module, $userId);
                if ($item) {
                    $uploaded[] = $item;
                }
            }
        }
        return $uploaded;
    }

    /**
     * Delete media item.
     */
    public function delete(int $id): bool
    {
        $media = Media::find($id);
        if (!$media) {
            return false;
        }

        // Delete physical file
        $physicalPath = public_path($media->path);
        if (File::exists($physicalPath)) {
            File::delete($physicalPath);
        }

        return (bool) $media->delete();
    }
}
