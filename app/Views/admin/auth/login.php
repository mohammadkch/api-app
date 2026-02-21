<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ورود به پنل مدیریت</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Vazirmatn Font -->
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Vazirmatn', sans-serif;
            height: 100vh;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-box {
            width: 100%;
            max-width: 400px;
            background: #ffffff;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .form-control {
            border-radius: 10px;
        }

        .btn-primary {
            border-radius: 10px;
            padding: 10px;
        }

        .password-toggle {
            cursor: pointer;
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .position-relative input {
            padding-left: 40px;
        }
    </style>
</head>
<body>

<div class="login-box">

    <h4 class="text-center mb-4 fw-bold">ورود به پنل مدیریت</h4>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('admin/login') ?>">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label class="form-label">نام کاربری</label>
            <input type="text" name="username" class="form-control" required>
        </div>

        <div class="mb-3 position-relative">
            <label class="form-label">رمز عبور</label>
            <input type="password" name="password" id="password" class="form-control" required>
            <span class="password-toggle" onclick="togglePassword()">👁</span>
        </div>

        <button type="submit" class="btn btn-primary w-100 mt-2">
            ورود
        </button>

    </form>

</div>

<script>
    function togglePassword() {
        const input = document.getElementById('password');

        if (input.type === "password") {
            input.type = "text";
        } else {
            input.type = "password";
        }
    }
</script>

</body>
</html>
