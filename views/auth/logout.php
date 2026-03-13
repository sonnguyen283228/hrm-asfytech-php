<?php
// Standalone logout layout (Phoenix Style)
$siteName = function_exists('site_get') ? site_get('site_name', 'HRM APP') : 'HRM APP';
$favicon = function_exists('site_get') ? site_get('site_favicon_url', '') : '';
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Đăng xuất - <?= htmlspecialchars($siteName) ?></title>
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
    .avatar-logout {
      width: 100px;
      height: 100px;
      margin: 0 auto;
    }
  </style>
</head>
<body>
  <main class="main" id="top">
    <div class="row vh-100 g-0 flex-center">
      <div class="col-sm-10 col-md-8 col-lg-5 col-xl-5 d-flex justify-content-center align-items-center">
        <div class="w-100 px-4 px-sm-6 px-md-8 px-lg-6 px-xl-8 py-6 bg-white shadow-lg rounded-4 border border-200 text-center position-relative overflow-hidden">
          <div class="bg-primary position-absolute top-0 start-0 w-100" style="height: 120px; z-index: 1; opacity: 0.1;"></div>
          
          <div class="position-relative" style="z-index: 2; margin-top: 20px;">
            <div class="avatar avatar-xl avatar-logout mb-4 shadow-sm border border-4 border-white bg-white">
                <?php if (!empty($user['avatar_url'])): ?>
                   <img class="rounded-circle object-fit-cover w-100 h-100" src="<?= htmlspecialchars($user['avatar_url']) ?>" alt="Avatar" referrerpolicy="no-referrer" />
                <?php else: ?>
                   <div class="avatar-name rounded-circle w-100 h-100 fs-2 bg-200 text-700 border-0 d-flex align-items-center justify-content-center"><span><?= mb_substr($user['full_name'] ?? 'U', 0, 1) ?></span></div>
                <?php endif; ?>
            </div>
            
            <h3 class="text-1000 mb-2 fw-bolder">Đăng xuất hệ thống?</h3>
            <p class="text-700 fs--1 mb-5">Xin chào <strong><?= htmlspecialchars($user['full_name'] ?? '') ?></strong>, bạn có chắc chắn muốn đăng xuất khỏi <?= htmlspecialchars($siteName) ?>?</p>

            <form method="post" action="/logout" class="d-grid gap-3">
              <button class="btn btn-phoenix-danger btn-lg w-100 d-flex align-items-center justify-content-center" type="submit">
                 <span class="fas fa-sign-out-alt me-2"></span>Xác nhận đăng xuất
              </button>
              <a class="btn btn-outline-primary btn-lg w-100 d-flex align-items-center justify-content-center" href="/attendance">
                 <span class="fas fa-arrow-left me-2"></span>Hủy, trở lại trang chủ
              </a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>
  <script src="/phoenix/vendors/bootstrap/bootstrap.min.js"></script>
</body>
</html>
