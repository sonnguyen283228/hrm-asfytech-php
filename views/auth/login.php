<?php
// Standalone login layout (clean, centered) to match provided reference style
$siteName = function_exists('site_get') ? site_get('site_name', 'HRM APP') : 'HRM APP';
$logo = function_exists('site_get') ? site_get('site_logo_url', '') : '';
$favicon = function_exists('site_get') ? site_get('site_favicon_url', '') : '';
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= htmlspecialchars($siteName) ?> - Đăng nhập</title>
  <?php if ($favicon): ?><link rel="icon" href="<?= htmlspecialchars($favicon) ?>"><?php endif; ?>
  <style>
    :root{--primary:#4f46e5;--primary-dark:#4338ca;--bg:#f1f3f5;--card:#ffffff;--text:#111827;--muted:#6b7280}
    *{box-sizing:border-box}
    body{margin:0;font-family:Inter,Segoe UI,Arial,sans-serif;background:var(--bg);color:var(--text)}
    .wrap{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
    .panel{width:100%;max-width:480px}
    .logo{display:flex;justify-content:center;margin-bottom:16px}
    .logo .holder{width:76px;height:76px;border-radius:50%;background:#fff;display:flex;align-items:center;justify-content:center;box-shadow:0 8px 20px rgba(0,0,0,.12)}
    .logo img{max-width:44px;max-height:44px}
    .card{background:var(--card);border:1px solid #e5e7eb;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,.08);overflow:hidden}
    .content{padding:24px 28px}
    h2{margin:0 0 14px;font-size:22px}
    label{display:block;font-weight:600;margin:12px 0 6px}
    input[type=email],input[type=password]{width:100%;height:46px;border:1px solid #d1d5db;border-radius:8px;padding:0 12px;font-size:15px}
    input:focus{outline:none;border-color:#818cf8;box-shadow:0 0 0 3px rgba(79,70,229,.15)}
    .row{display:flex;align-items:center;gap:8px;margin:12px 0}
    .captcha{height:78px;border:1px solid #d1d5db;border-radius:6px;background:#fff;display:flex;align-items:center;padding:12px;margin-top:8px}
    .captcha .box{width:28px;height:28px;border:2px solid #6b7280;margin-right:10px}
    .btn{width:100%;height:44px;border:0;border-radius:8px;font-weight:700;cursor:pointer}
    .btn-primary{background:linear-gradient(90deg,var(--primary),#5b43ea);color:#fff}
    .btn-primary:hover{background:linear-gradient(90deg,var(--primary-dark),#4f46e5)}
    .divider{display:flex;align-items:center;gap:10px;color:var(--muted);margin:14px 0}
    .divider:before,.divider:after{content:'';flex:1;height:1px;background:#e5e7eb}
    .btn-google{display:flex;align-items:center;justify-content:center;gap:8px;height:42px;border:1px solid #d1d5db;border-radius:8px;background:#fff;color:#1f2937;text-decoration:none;font-weight:600}
    .foot{padding:12px 16px;background:#f8fafc;border-top:1px solid #e5e7eb;text-align:center;color:#6b7280;font-size:13px}
    .error{background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;padding:10px;border-radius:8px;margin-bottom:10px}
  </style>
</head>
<body>
  <div class="wrap">
    <div class="panel">
      <div class="logo">
        <div class="holder">
          <?php if ($logo): ?>
            <img src="<?= htmlspecialchars($logo) ?>" alt="logo">
          <?php else: ?>
            <div style="font-weight:800;color:#4f46e5">A</div>
          <?php endif; ?>
        </div>
      </div>

      <div class="card">
        <div class="content">
          <h2>Đăng nhập HRM</h2>

          <?php if (!empty($_SESSION['error'])): ?>
            <div class="error"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
          <?php endif; ?>

          <form method="post" action="/login">
            <label>Tên đăng nhập</label>
            <input type="email" name="email" required placeholder="Nhập tên đăng nhập">

            <label>Mật khẩu</label>
            <input type="password" name="password" required placeholder="Nhập mật khẩu">

            <div class="row">
              <input id="remember" type="checkbox" checked>
              <label for="remember" style="margin:0;font-weight:500">Ghi nhớ đăng nhập</label>
            </div>

            <div class="captcha">
              <div class="box"></div>
              <div style="font-size:14px">Tôi không phải là người máy</div>
            </div>

            <div style="margin-top:12px">
              <button class="btn btn-primary" type="submit">Đăng nhập</button>
            </div>
          </form>

          <div class="divider">Hoặc kết nối với:</div>
          <a class="btn-google" href="/auth/google">Tiếp tục với Google</a>
        </div>
        <div class="foot">© 2025 Hệ thống quản trị. Phiên bản 1.0.0</div>
      </div>
    </div>
  </div>
</body>
</html>
