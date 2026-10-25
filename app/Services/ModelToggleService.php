<?php

namespace App\Services;

class ModelToggleService
{
    /**
     * Whitelist of toggleable attributes to prevent arbitrary column alteration
     */
    protected array $allowedToggleFields = [
        'status',
        'home',
        'hot',
        'sale',
        'active',
    ];

    public function toggleModelStatus($uuid, $status, $fieldName, $model)
    {
        // Enforce whitelist check
        if (!in_array($fieldName, $this->allowedToggleFields, true)) {
            return response()->json(['message' => 'Trường cập nhật không hợp lệ'], 403);
        }

        $sanitizedStatus = (int)(bool)$status;
        $updated = $model::where('uuid', $uuid)->update([$fieldName => $sanitizedStatus]);

        if ($updated) {
            $mess = $sanitizedStatus == 1 ? 'Kích hoạt' : 'Tắt';
            toast($mess.' thành công');

            return response()->json(['message' => $mess.' '.$fieldName.' thành công']);
        }

        toast('Không tìm thấy '.$fieldName, 'error');

        return response()->json(['message' => 'Không có bản ghi nào được cập nhật'], 400);
    }

    public function updateModelOrder($request, $uuid, $model)
    {
        $item = $model::where('uuid', $uuid)->first();

        if ($item) {
            $item->stt = max(0, (int) $request->input('stt', 1));
            $item->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }
}
