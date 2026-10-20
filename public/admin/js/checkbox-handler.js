/**
 * Checkbox Handler - Xử lý chức năng check all cho các trang list
 */
document.addEventListener('DOMContentLoaded', function() {
    initCheckboxHandler();
});

function initCheckboxHandler() {
    const checkAll = document.getElementById('checkAll');
    const checkboxes = document.querySelectorAll('.form-check-all input[type="checkbox"][name="uuids[]"]');
    const deleteSelectedBtn = document.getElementById('deleteSelectedItems');
    
    // Xử lý check all
    if (checkAll) {
        checkAll.addEventListener('change', function() {
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = checkAll.checked;
                // Thêm/bỏ class highlight cho row
                const row = checkbox.closest('tr');
                if (checkAll.checked) {
                    row.classList.add('table-active');
                } else {
                    row.classList.remove('table-active');
                }
            });
        });
    }
    
    // Xử lý khi click vào từng checkbox
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const row = this.closest('tr');
            if (this.checked) {
                row.classList.add('table-active');
            } else {
                row.classList.remove('table-active');
            }
            
            // Cập nhật trạng thái checkAll
            const checkedBoxes = document.querySelectorAll('.form-check-all input[type="checkbox"][name="uuids[]"]:checked');
            if (checkAll) {
                checkAll.checked = checkedBoxes.length === checkboxes.length;
                checkAll.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < checkboxes.length;
            }
        });
    });
    
    // Xử lý nút xóa nhiều
    if (deleteSelectedBtn) {
        deleteSelectedBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const checkedBoxes = document.querySelectorAll('.form-check-all input[type="checkbox"][name="uuids[]"]:checked');
            
            if (checkedBoxes.length === 0) {
                alert('Vui lòng chọn ít nhất một mục để xóa!');
                return;
            }
            
            if (confirm('Bạn có chắc chắn muốn xóa ' + checkedBoxes.length + ' mục đã chọn?')) {
                const existingForm = document.getElementById('delete-form-all');
                const actionUrl = existingForm ? existingForm.getAttribute('action') : null;
                const tokenInput = existingForm ? existingForm.querySelector('input[name="_token"]') : document.querySelector('meta[name="csrf-token"]');
                const csrfToken = tokenInput ? (tokenInput.value || tokenInput.getAttribute('content')) : '';

                if (existingForm) {
                    // Xóa các input uuids cũ nếu có
                    existingForm.querySelectorAll('input[name="uuids[]"]').forEach(el => el.remove());

                    // Append các UUIDs được chọn vào form
                    checkedBoxes.forEach(function(cb) {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'uuids[]';
                        input.value = cb.value;
                        existingForm.appendChild(input);
                    });

                    existingForm.submit();
                } else if (actionUrl) {
                    // Fallback tạo form động nếu form không tìm thấy trong DOM
                    const dynamicForm = document.createElement('form');
                    dynamicForm.method = 'POST';
                    dynamicForm.action = actionUrl;

                    const tokenField = document.createElement('input');
                    tokenField.type = 'hidden';
                    tokenField.name = '_token';
                    tokenField.value = csrfToken;
                    dynamicForm.appendChild(tokenField);

                    checkedBoxes.forEach(function(cb) {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'uuids[]';
                        input.value = cb.value;
                        dynamicForm.appendChild(input);
                    });

                    document.body.appendChild(dynamicForm);
                    dynamicForm.submit();
                } else {
                    alert('Không tìm thấy đường dẫn xử lý xóa!');
                }
            }
        });
    }
} 