@props([
    'model' => $page ?? null,
    'col' => 'col-md-6'
])

<div class="{{ $col }} mb-3">
    <label for="stt" class="form-label font-weight-semibold">Số thứ tự</label>
    <input type="number" id="stt" name="stt" class="form-control @error('stt') is-invalid @enderror"
        placeholder="Nhập số thứ tự" value="{{ old('stt', $model->stt ?? 1) }}">
    @error('stt')
        <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
    @enderror
</div>

<div class="{{ $col }} mb-3">
    <label for="created_at" class="form-label font-weight-semibold">Ngày đăng</label>
    <input type="datetime-local" id="created_at" name="created_at"
        class="form-control @error('created_at') is-invalid @enderror"
        value="{{ old('created_at', isset($model->created_at) ? \Carbon\Carbon::parse($model->created_at)->format('Y-m-d\TH:i') : '') }}">
    @error('created_at')
        <div class="invalid-feedback d-block fw-semibold">{{ $message }}</div>
    @enderror
</div>
