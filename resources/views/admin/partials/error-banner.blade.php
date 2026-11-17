@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4 border-2 shadow-sm" role="alert" id="admin-validation-error-banner">
        <h6 class="alert-heading fw-bold mb-2 d-flex align-items-center">
            <i class="ri-error-warning-fill me-2 fs-18"></i> Vui lòng kiểm tra lại thông tin:
        </h6>
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
