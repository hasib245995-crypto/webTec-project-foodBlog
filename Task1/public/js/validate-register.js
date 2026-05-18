// public/js/validate-register.js

(function () {
    const form = document.getElementById('registerForm');
    if (!form) return;

    const showError = (id, msg) => {
        let el = document.getElementById('err_' + id);
        const input = document.getElementById(id) || form.querySelector('[name="' + id + '"]');
        if (!el) {
            el = document.createElement('span');
            el.id = 'err_' + id;
            el.className = 'field-error';
            if (input) input.after(el);
        }
        el.textContent = msg;
        if (input) input.closest('.form-group')?.classList.add('has-error');
    };

    const clearError = (id) => {
        const el = document.getElementById('err_' + id);
        if (el) el.textContent = '';
        const input = document.getElementById(id) || form.querySelector('[name="' + id + '"]');
        if (input) input.closest('.form-group')?.classList.remove('has-error');
    };

    const validateEmail = (v) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);

    // Real-time
    form.name.addEventListener('input', () => {
        form.name.value.trim().length < 2
            ? showError('name', 'Name must be at least 2 characters.')
            : clearError('name');
    });

    form.email.addEventListener('input', () => {
        validateEmail(form.email.value.trim())
            ? clearError('email')
            : showError('email', 'Enter a valid email address.');
    });

    form.password.addEventListener('input', () => {
        form.password.value.length < 8
            ? showError('password', 'Password must be at least 8 characters.')
            : clearError('password');
    });

    form.confirm.addEventListener('input', () => {
        form.confirm.value !== form.password.value
            ? showError('confirm', 'Passwords do not match.')
            : clearError('confirm');
    });

    // On submit
    form.addEventListener('submit', (e) => {
        let valid = true;

        if (form.name.value.trim().length < 2) {
            showError('name', 'Name must be at least 2 characters.');
            valid = false;
        }
        if (!validateEmail(form.email.value.trim())) {
            showError('email', 'Enter a valid email address.');
            valid = false;
        }
        if (form.password.value.length < 8) {
            showError('password', 'Password must be at least 8 characters.');
            valid = false;
        }
        if (form.confirm.value !== form.password.value) {
            showError('confirm', 'Passwords do not match.');
            valid = false;
        }

        if (!valid) e.preventDefault();
    });
})();
