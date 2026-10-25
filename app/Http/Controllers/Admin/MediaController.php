<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Services\MediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MediaController extends Controller
{
    /**
     * @var MediaService
     */
    protected $mediaService;

    public function __construct(MediaService $mediaService)
    {
        $this->middleware('auth');
        $this->mediaService = $mediaService;
    }

    /**
     * Display standalone media manager page
     */
    public function index(Request $request)
    {
        return view('admin.modules.media.index');
    }

    /**
     * Get paginated media items as JSON for modal.
     */
    public function list(Request $request): JsonResponse
    {
        $filters = [
            'search' => $request->get('search'),
            'module' => $request->get('module'),
            'type' => $request->get('type'),
        ];
        $perPage = (int) $request->get('per_page', 24);

        $media = $this->mediaService->paginate($filters, $perPage);

        return response()->json([
            'success' => true,
            'data' => $media->items(),
            'current_page' => $media->currentPage(),
            'last_page' => $media->lastPage(),
            'total' => $media->total(),
            'has_more' => $media->hasMorePages(),
        ]);
    }

    /**
     * Upload one or multiple media files via AJAX.
     */
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'files' => 'nullable|array',
            'files.*' => 'file|max:51200', // 50MB
            'file' => 'nullable|file|max:51200',
            'module' => 'nullable|string|max:50',
        ]);

        $module = $request->input('module', 'general');
        $userId = Auth::id();
        $results = [];

        if ($request->hasFile('files')) {
            $results = $this->mediaService->uploadMultiple($request->file('files'), $module, $userId);
        } elseif ($request->hasFile('file')) {
            $item = $this->mediaService->upload($request->file('file'), $module, $userId);
            if ($item) {
                $results[] = $item;
            }
        }

        if (empty($results)) {
            return response()->json([
                'success' => false,
                'message' => 'Không có file nào được tải lên hoặc định dạng không hỗ trợ',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Tải lên thành công ' . count($results) . ' tệp tin',
            'files' => $results,
        ]);
    }

    /**
     * Delete media item.
     */
    public function delete(Request $request, $id): JsonResponse
    {
        $success = $this->mediaService->delete((int) $id);

        if (!$success) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy tệp hoặc không thể xóa',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa tệp tin thành công',
        ]);
    }

    /**
     * Get distinct module list for dropdown filter.
     */
    public function modules(): JsonResponse
    {
        $modules = Media::select('module')
            ->distinct()
            ->whereNotNull('module')
            ->where('module', '!=', '')
            ->orderBy('module')
            ->pluck('module');

        return response()->json([
            'success' => true,
            'modules' => $modules,
        ]);
    }
}
