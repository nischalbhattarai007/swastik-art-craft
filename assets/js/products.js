// Products Management JavaScript

// Select All functionality
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const productCheckboxes = document.querySelectorAll('.product-checkbox');
    const bulkForm = document.getElementById('bulkForm');

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            productCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }

    // Bulk form submission
    if (bulkForm) {
        bulkForm.addEventListener('submit', function(e) {
            const selectedProducts = document.querySelectorAll('.product-checkbox:checked');
            const action = this.querySelector('select[name="bulk_action"]').value;
            
            if (!action) {
                e.preventDefault();
                alert('Please select an action');
                return;
            }
            
            if (selectedProducts.length === 0) {
                e.preventDefault();
                alert('Please select at least one product');
                return;
            }
            
            if (action === 'delete') {
                if (!confirm(`Are you sure you want to delete ${selectedProducts.length} product(s)?`)) {
                    e.preventDefault();
                    return;
                }
            }
            
            // Add selected products to form
            selectedProducts.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_products[]';
                input.value = checkbox.value;
                this.appendChild(input);
            });
        });
    }
});