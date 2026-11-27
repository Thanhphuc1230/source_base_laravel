<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HomeLayoutRequest;
use App\Services\HomeLayoutService;
use Illuminate\Http\Request;

class HomeLayoutController extends Controller
{
    public function __construct(
        protected HomeLayoutService $homeLayoutService
    ) {}

    public function index()
    {
        $data = $this->homeLayoutService->getBuilderData();

        return view('admin.modules.home-layout.index', $data);
    }

    public function store(HomeLayoutRequest $request)
    {
        $count = $this->homeLayoutService->addSections($request->validated());

        if ($count === 0) {
            return redirect()->route('admin.home-layout.index')->with('error', 'Vui lòng chọn ít nhất một khối để thêm vào trang chủ.');
        }

        return redirect()->route('admin.home-layout.index')->with('success', "Đã thêm {$count} khối vào bố cục trang chủ.");
    }

    public function update(Request $request, string $uuid)
    {
        $validated = $request->validate([
            'title_vn' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'limit' => ['required', 'integer', 'min:1', 'max:50'],
        ]);

        $updated = $this->homeLayoutService->updateSection($uuid, $validated);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => $updated]);
        }

        return redirect()->route('admin.home-layout.index')->with(
            $updated ? 'success' : 'error',
            $updated ? 'Cập nhật khối trang chủ thành công.' : 'Không tìm thấy khối cần cập nhật.'
        );
    }

    public function status(string $uuid, int $status)
    {
        $updated = $this->homeLayoutService->toggleStatus($uuid, $status);

        return response()->json(['success' => $updated]);
    }

    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'order' => ['required', 'array', 'min:1'],
            'order.*' => ['required', 'string'],
        ]);

        $this->homeLayoutService->reorder($validated['order']);

        return response()->json(['success' => true]);
    }

    public function destroy(Request $request, string $uuid)
    {
        $deleted = $this->homeLayoutService->deleteSection($uuid);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => $deleted]);
        }

        return redirect()->route('admin.home-layout.index')->with(
            $deleted ? 'success' : 'error',
            $deleted ? 'Đã xóa khối khỏi trang chủ.' : 'Không tìm thấy khối cần xóa.'
        );
    }
}
