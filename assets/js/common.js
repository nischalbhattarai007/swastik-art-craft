// Common JavaScript functions

// Mobile menu toggle
function toggleMobileMenu() {
    const mobileMenu = document.getElementById('mobile-menu');
    if (mobileMenu) {
        mobileMenu.classList.toggle('hidden');
    }
}

// Image preview function
function previewImage(input, previewId = 'preview', containerId = 'imagePreview') {
    const preview = document.getElementById(previewId);
    const previewContainer = document.getElementById(containerId);
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            if (preview) {
                preview.src = e.target.result;
                if (previewContainer) {
                    previewContainer.classList.remove('hidden');
                }
            }
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        if (previewContainer) {
            previewContainer.classList.add('hidden');
        }
    }
}

// Password toggle function
function togglePassword(fieldId = 'password', iconId = 'toggleIcon') {
    const passwordField = document.getElementById(fieldId);
    const toggleIcon = document.getElementById(iconId);
    
    if (passwordField && toggleIcon) {
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
}

// Modal functions
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

// Auto-focus first input in forms
document.addEventListener('DOMContentLoaded', function() {
    const firstInput = document.querySelector('form input[type="text"]:not([readonly]), form input[type="email"]:not([readonly])');
    if (firstInput) {
        firstInput.focus();
    }
});