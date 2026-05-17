// public/js/validate-login.js

(function () {
    const form = document.getElementById('loginForm');
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

    form.email.addEventListener('input', () => {
        validateEmail(form.email.value.trim()) ? clearError('email') : showError('email', 'Enter a valid email address.');
    });

    form.password.addEventListener('input', () => {
        form.password.value.length > 0 ? clearError('password') : showError('password', 'Password is required.');
    });

    form.addEventListener('submit', (e) => {
        let valid = true;
        if (!validateEmail(form.email.value.trim())) {
            showError('email', 'Enter a valid email address.');
            valid = false;
        }
        if (!form.password.value) {
            showError('password', 'Password is required.');
            valid = false;
        }
        if (!valid) e.preventDefault();
    });
})();
