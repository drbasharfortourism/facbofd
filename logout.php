<?php
/**
 * ملف تسجيل الخروج من لوحة التحكم
 */

session_start();

// إنهاء الجلسة
session_unset();
session_destroy();

// إعادة التوجيه إلى صفحة تسجيل الدخول
header('Location: admin.php');
exit;
?>
