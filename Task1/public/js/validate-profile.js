// public/js/validate-profile.js

(function () {
    const form = document.getElementById('profileForm');
    if (!form) return;

    const showError = (id, msg) => {
        let el = document.getElementById('err_' + id);
        const input = document.getElementById(id);
        if (!el) {
            el = document.createElement('span');
            el.id = 'err_' + id;
            el.className = 'field-error';
            if (input) input.after(el);
        }
        el.textContent = msg;
        input?.closest('.form-group')?.classList.add('has-error');
    };

    const clearError = (id) => {
        const el = document.getElementById('err_' + id);
        if (el) el.textContent = '';
        document.getElementById(id)?.closest('.form-group')?.classList.remove('has-error');
    };

    const validateEmail = (v) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);
    const ALLOWED_TYPES  = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    const MAX_SIZE       = 2 * 1024 * 1024;

    // Real-time
    form.name.addEventListener('input', () => {
        form.name.value.trim().length < 2
            ? showError('name', 'Name must be at least 2 characters.')
            : clearError('name');
    });

    form.email.addEventListener('input', () => {
        validateEmail(form.email.value.trim()) ? clearError('email') : showError('email', 'Enter a valid email address.');
    });

    form.profile_picture.addEventListener('change', () => {
        const file = form.profile_picture.files[0];
        if (!file) { clearError('profile_picture'); return; }
        if (!ALLOWED_TYPES.includes(file.type)) {
            showError('profile_picture', 'Only JPEG, PNG, GIF or WEBP images allowed.');
        } else if (file.size > MAX_SIZE) {
            showError('profile_picture', 'Image must be under 2 MB.');
        } else {
            clearError('profile_picture');
        }
    });

    form.new_password.addEventListener('input', () => {
        if (!form.new_password.value) { clearError('new_password'); clearError('confirm_password'); return; }
        form.new_password.value.length < 8
            ? showError('new_password', 'New password must be at least 8 characters.')
            : clearError('new_password');
        if (form.confirm_password.value) {
            form.confirm_password.value !== form.new_password.value
                ? showError('confirm_password', 'Passwords do not match.')
                : clearError('confirm_password');
        }
    });

    form.confirm_password.addEventListener('input', () => {
        form.confirm_password.value !== form.new_password.value
            ? showError('confirm_password', 'Passwords do not match.')
            : clearError('confirm_password');
    });

    form.addEventListener('submit', (e) => {
        let valid = true;

        if (form.name.value.trim().length < 2) {
            showError('name', 'Name must be at least 2 characters.'); valid = false;
        }
        if (!validateEmail(form.email.value.trim())) {
            showError('email', 'Enter a valid email address.'); valid = false;
        }

        const file = form.profile_picture.files[0];
        if (file) {
            if (!ALLOWED_TYPES.includes(file.type)) {
                showError('profile_picture', 'Only JPEG, PNG, GIF or WEBP images allowed.'); valid = false;
            } else if (file.size > MAX_SIZE) {
                showError('profile_picture', 'Image must be under 2 MB.'); valid = false;
            }
        }

        const hasPasswordChange = form.current_password.value || form.new_password.value || form.confirm_password.value;
        if (hasPasswordChange) {
            if (!form.current_password.value) {
                showError('current_password', 'Current password is required.'); valid = false;
            }
            if (form.new_password.value.length < 8) {
                showError('new_password', 'New password must be at least 8 characters.'); valid = false;
            }
            if (form.confirm_password.value !== form.new_password.value) {
                showError('confirm_password', 'Passwords do not match.'); valid = false;
            }
        }

        if (!valid) e.preventDefault();
    });
})();
