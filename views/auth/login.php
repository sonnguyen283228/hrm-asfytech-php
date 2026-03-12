<?php
// Standalone login layout (Phoenix Style)
$siteName = function_exists('site_get') ? site_get('site_name', 'HRM APP') : 'HRM APP';
$logo = function_exists('site_get') ? site_get('site_logo_url', '') : '';
$favicon = function_exists('site_get') ? site_get('site_favicon_url', '') : '';
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($siteName) ?> - Đăng nhập</title>
  <?php if ($favicon): ?><link rel="icon" href="<?= htmlspecialchars($favicon) ?>"><?php endif; ?>
  
  <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
  <link href="/phoenix/assets/css/theme.min.css" rel="stylesheet" id="style-default">
  <link href="/phoenix/assets/css/user.min.css" rel="stylesheet" id="user-style-default">
  
  <style>
    body { font-family: 'Nunito Sans', sans-serif; background-color: #f5f7fa; }
    .bg-auth {
      background-image: url('/phoenix/assets/img/bg/bg-11.png');
      background-size: cover;
      background-position: center;
    }
  </style>
</head>
<body>
  <main class="main" id="top">
    <div class="row vh-100 g-0">
      <div class="col-lg-6 position-relative d-none d-lg-block">
        <div class="bg-auth position-absolute top-0 left-0 w-100 h-100"></div>
        <div class="position-absolute w-100 h-100" style="background: rgba(15, 23, 42, 0.7);"></div>
        <div class="position-relative h-100 d-flex flex-column justify-content-center px-6">
          <div class="mb-4">
            <?php if ($logo): ?>
              <img src="<?= htmlspecialchars($logo) ?>" alt="logo" width="64" class="bg-white p-2 rounded-3 shadow-sm mb-4">
            <?php else: ?>
              <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-3 shadow-sm mb-4" style="width: 64px; height: 64px; font-size: 28px; font-weight: 800;">A</div>
            <?php endif; ?>
            <h1 class="text-white fw-bolder mb-3 display-4">ASFY HRM System</h1>
            <p class="text-300 fs-1 leading-normal mb-0" style="max-width: 500px">
              Hệ thống Quản trị Nhân sự và Quản lý Dự án toàn diện, giúp tối ưu hóa luồng công việc và năng suất của đội ngũ.
            </p>
          </div>
        </div>
      </div>
      
      <div class="col-lg-6 d-flex justify-content-center align-items-center">
        <div class="w-100 px-4 px-sm-6 px-md-8 px-lg-6 px-xl-8 px-xxl-10" style="max-width: 500px">
          <div class="text-center mb-5 d-lg-none">
            <?php if ($logo): ?>
              <img src="<?= htmlspecialchars($logo) ?>" alt="logo" width="48" class="mb-3 rounded">
            <?php else: ?>
              <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mb-3" style="width: 48px; height: 48px; font-size: 22px; font-weight: 800;">A</div>
            <?php endif; ?>
            <h3 class="text-1000">Hệ thống HRM</h3>
          </div>
          
          <div class="mb-5 text-center text-lg-start">
            <h3 class="text-1000">Đăng nhập</h3>
            <p class="text-700">Truy cập vào không gian làm việc của bạn</p>
          </div>

          <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-soft-danger d-flex align-items-center mb-4" role="alert">
              <span class="fas fa-exclamation-triangle me-3 fs-3"></span>
              <p class="mb-0 flex-1"><?= htmlspecialchars($_SESSION['error']) ?></p>
            </div>
            <?php unset($_SESSION['error']); ?>
          <?php endif; ?>

          <form method="post" action="/login">
            <div class="mb-3 text-start">
              <label class="form-label" for="email">Tài khoản Email</label>
              <div class="form-icon-container">
                <input class="form-control form-icon-input" id="email" name="email" type="email" placeholder="name@gmail.com" required />
                <span class="fas fa-envelope text-900 fs--1 form-icon"></span>
              </div>
            </div>
            
            <div class="mb-3 text-start">
              <label class="form-label" for="password">Mật khẩu</label>
              <div class="form-icon-container">
                <input class="form-control form-icon-input" id="password" name="password" type="password" placeholder="Mật khẩu của bạn" required />
                <span class="fas fa-key text-900 fs--1 form-icon"></span>
              </div>
            </div>
            
            <div class="row flex-between-center mb-4">
              <div class="col-auto">
                <div class="form-check mb-0">
                  <input class="form-check-input" id="basic-checkbox" type="checkbox" checked="checked" />
                  <label class="form-check-label mb-0" for="basic-checkbox">Ghi nhớ đăng nhập</label>
                </div>
              </div>
              <div class="col-auto"><a class="fs--1 fw-semi-bold" href="#!">Quên mật khẩu?</a></div>
            </div>
            
            <button class="btn btn-primary w-100 mb-3" type="submit">Đăng nhập</button>
            <div class="text-center"><a class="fs--1 fw-bold" href="/auth/google">Tiếp tục đăng nhập bằng Google</a></div>
          </form>
        </div>
      </div>
    </div>
  </main>

  <script src="/phoenix/vendors/bootstrap/bootstrap.min.js"></script>
</body>
</html>
