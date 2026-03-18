// التحقق من صحة البيانات عند إرسال النموذج
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');

    // إضافة رسالة خطأ للنموذج
    const errorMessage = document.createElement('div');
    errorMessage.className = 'error-message';
    errorMessage.textContent = 'الرجاء إدخال بيانات صحيحة';
    loginForm.insertBefore(errorMessage, loginForm.firstChild);

    // التحقق من صحة البريد الإلكتروني
    function validateEmail(email) {
        const re = /^(([^<>()\[\]\.,;:\s@"]+(\.[^<>()\[\]\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }

    // التحقق من صحة رقم الهاتف
    function validatePhone(phone) {
        const re = /^[0-9]{8,15}$/;
        return re.test(phone);
    }

    // التحقق من صحة كلمة المرور
    function validatePassword(password) {
        return password.length >= 6;
    }

    // معالجة إرسال النموذج
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const username = usernameInput.value.trim();
        const password = passwordInput.value;

        // إعادة تعيين رسالة الخطأ
        errorMessage.style.display = 'none';
        errorMessage.textContent = '';

        // التحقق من صحة البيانات
        let isValid = true;

        if (!username) {
            errorMessage.textContent = 'الرجاء إدخال البريد الإلكتروني أو رقم الهاتف';
            errorMessage.style.display = 'block';
            isValid = false;
        } else if (!validateEmail(username) && !validatePhone(username)) {
            errorMessage.textContent = 'الرجاء إدخال بريد إلكتروني أو رقم هاتف صحيح';
            errorMessage.style.display = 'block';
            isValid = false;
        } else if (!password) {
            errorMessage.textContent = 'الرجاء إدخال كلمة المرور';
            errorMessage.style.display = 'block';
            isValid = false;
        } else if (!validatePassword(password)) {
            errorMessage.textContent = 'كلمة المرور يجب أن تحتوي على 6 أحرف على الأقل';
            errorMessage.style.display = 'block';
            isValid = false;
        }

        // إذا كانت البيانات صحيحة، إرسال النموذج
        if (isValid) {
            // إرسال النموذج بالطريقة التقليدية
            loginForm.submit();
        }
    });

    // إضافة تأثيرات بصرية عند التركيز على الحقول
    usernameInput.addEventListener('focus', function() {
        errorMessage.style.display = 'none';
    });

    passwordInput.addEventListener('focus', function() {
        errorMessage.style.display = 'none';
    });


});
