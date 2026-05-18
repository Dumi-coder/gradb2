(function () {
    function attachPasswordModal(form, options) {
        if (!form) return;

        var modal = form.querySelector('.change-password-modal');
        var openBtn = form.querySelector('.js-open-password-modal');
        var closeBtns = form.querySelectorAll('.js-close-password-modal');
        var doneBtns = form.querySelectorAll('.js-done-password-modal');
        var changePasswordFlag = form.querySelector('input[name="change_password"]');
        var currentField = form.querySelector('input[name="password_current"]');
        var newField = form.querySelector('input[name="password_new"]');
        var confirmField = form.querySelector('input[name="password_confirm"]');
        var indicatorContainer = form.querySelector('.js-password-strength-container');

        if (!modal || !openBtn || !currentField || !newField || !confirmField || !changePasswordFlag) {
            return;
        }

        var hasPasswordErrors = modal.getAttribute('data-has-password-errors') === '1';

        function setModalVisible(isVisible) {
            if (isVisible) {
                modal.classList.add('is-open');
                modal.setAttribute('aria-hidden', 'false');
                openBtn.setAttribute('aria-expanded', 'true');
                if (changePasswordFlag) {
                    changePasswordFlag.value = '1';
                }
                window.setTimeout(function () {
                    currentField.focus();
                }, 30);
            } else {
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');
                openBtn.setAttribute('aria-expanded', 'false');
            }
        }

        function clearPasswordFields() {
            currentField.value = '';
            newField.value = '';
            confirmField.value = '';
            if (changePasswordFlag) {
                changePasswordFlag.value = '0';
            }
        }

        function hasAnyPasswordInput() {
            return currentField.value.trim() !== '' || newField.value.trim() !== '' || confirmField.value.trim() !== '';
        }

        openBtn.addEventListener('click', function () {
            setModalVisible(true);
        });

        closeBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                setModalVisible(false);
                if (!hasPasswordErrors) {
                    clearPasswordFields();
                }
            });
        });

        doneBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                if (changePasswordFlag) {
                    changePasswordFlag.value = hasAnyPasswordInput() ? '1' : '0';
                }
                setModalVisible(false);
            });
        });

        modal.addEventListener('click', function (event) {
            if (event.target === modal) {
                setModalVisible(false);
                if (!hasPasswordErrors) {
                    clearPasswordFields();
                }
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && modal.classList.contains('is-open')) {
                setModalVisible(false);
                if (!hasPasswordErrors) {
                    clearPasswordFields();
                }
            }
        });

        if (typeof PasswordValidator === 'function' && indicatorContainer && indicatorContainer.id) {
            var validator = new PasswordValidator();
            validator.initPasswordValidation(newField.id, confirmField.id, indicatorContainer.id, {
                allowEmpty: true
            });
        }

        if (hasPasswordErrors) {
            setModalVisible(true);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        var forms = document.querySelectorAll('form[data-password-modal="true"]');
        forms.forEach(function (form) {
            attachPasswordModal(form);
        });
    });
})();
