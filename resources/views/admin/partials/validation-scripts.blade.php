@if ($errors->any())
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Hiển thị popup SweetAlert2 thông báo lỗi tổng quan
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'error',
            title: 'Dữ liệu không hợp lệ!',
            text: 'Vui lòng kiểm tra các trường được đánh dấu đỏ hoặc banner thông báo.',
            confirmButtonColor: '#2563eb',
            confirmButtonText: 'Đã hiểu'
        });
    }

    // 2. Tìm phần tử có lỗi đầu tiên (.is-invalid hoặc .invalid-feedback)
    setTimeout(function() {
        const firstInvalid = document.querySelector('.is-invalid, .invalid-feedback, .is-invalid-media');
        if (!firstInvalid) return;

        // Nếu phần tử lỗi nằm trong một tab-pane đang không active
        const tabPane = firstInvalid.closest('.tab-pane');
        if (tabPane && !tabPane.classList.contains('active')) {
            const paneId = tabPane.getAttribute('id');
            if (paneId) {
                // Tìm trigger tab bằng href hoặc data-bs-target
                const tabTrigger = document.querySelector(`[data-bs-toggle="tab"][href="#${paneId}"], [data-bs-toggle="tab"][data-bs-target="#${paneId}"]`);
                if (tabTrigger) {
                    if (typeof bootstrap !== 'undefined' && bootstrap.Tab) {
                        const tabInstance = bootstrap.Tab.getOrCreateInstance(tabTrigger);
                        tabInstance.show();
                    } else {
                        tabTrigger.click();
                    }
                }
            }
        }

        // Tương tự nếu nằm trong accordion hoặc collapse đang đóng
        const collapsePane = firstInvalid.closest('.collapse:not(.show)');
        if (collapsePane) {
            const collapseId = collapsePane.getAttribute('id');
            const collapseTrigger = document.querySelector(`[data-bs-toggle="collapse"][href="#${collapseId}"], [data-bs-toggle="collapse"][data-bs-target="#${collapseId}"]`);
            if (collapseTrigger) {
                if (typeof bootstrap !== 'undefined' && bootstrap.Collapse) {
                    const collapseInstance = bootstrap.Collapse.getOrCreateInstance(collapsePane);
                    collapseInstance.show();
                } else {
                    collapseTrigger.click();
                }
            }
        }

        // Cuộn mượt mà tới phần tử lỗi đầu tiên
        setTimeout(function() {
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            if (typeof firstInvalid.focus === 'function' && firstInvalid.tagName !== 'DIV') {
                firstInvalid.focus();
            }
        }, 150);
    }, 200);
});
</script>
@endif
