<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RedirectRequest;
use App\Services\RedirectService;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    protected RedirectService $redirectService;

    public function __construct(RedirectService $redirectService)
    {
        $this->redirectService = $redirectService;
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => $request->get('search'),
            'type' => $request->get('type'),
        ];
        $redirects = $this->redirectService->paginate($filters, 20)->withQueryString();

        return view('admin.modules.redirect.index', compact('redirects', 'filters'));
    }

    public function create()
    {
        return view('admin.modules.redirect.create');
    }

    public function store(RedirectRequest $request)
    {
        $this->redirectService->create($request->validated());

        return redirect()->route('admin.redirect.index')->with('success', 'Thêm cấu hình chuyển hướng thành công.');
    }

    public function edit(string $uuid)
    {
        $redirect = $this->redirectService->findByUuid($uuid);
        if (! $redirect) {
            return redirect()->route('admin.redirect.index')->with('error', 'Không tìm thấy bản ghi chuyển hướng.');
        }

        return view('admin.modules.redirect.edit', compact('redirect'));
    }

    public function update(RedirectRequest $request, string $uuid)
    {
        $updated = $this->redirectService->update($uuid, $request->validated());
        if (! $updated) {
            return redirect()->route('admin.redirect.index')->with('error', 'Không thể cập nhật bản ghi.');
        }

        return redirect()->route('admin.redirect.index')->with('success', 'Cập nhật chuyển hướng thành công.');
    }

    public function status(string $uuid, int $status)
    {
        $this->redirectService->toggleStatus($uuid, $status);

        return response()->json(['success' => true]);
    }

    public function destroy(string $uuid)
    {
        $this->redirectService->delete($uuid);

        return redirect()->route('admin.redirect.index')->with('success', 'Đã xoá chuyển hướng.');
    }

    public function destroyAll(Request $request)
    {
        $uuids = (array) $request->input('uuids', []);
        if (! empty($uuids)) {
            $this->redirectService->destroyAll($uuids);
        }

        return response()->json(['success' => true]);
    }
}
