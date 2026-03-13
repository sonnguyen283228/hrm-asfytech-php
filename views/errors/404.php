<?php
// Standalone 404 Error layout (Phoenix Style)
$siteName = function_exists('site_get') ? site_get('site_name', 'HRM APP') : 'HRM APP';
$favicon = function_exists('site_get') ? site_get('site_favicon_url', '') : '';
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>404 Không tìm thấy trang - <?= htmlspecialchars($siteName) ?></title>
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
    <div class="row vh-100 g-0 flex-center">
      <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 d-flex justify-content-center align-items-center">
        <div class="w-100 px-4 px-sm-6 px-md-8 px-lg-6 px-xl-8 py-6 bg-white shadow-lg rounded-4 border border-200 text-center position-relative overflow-hidden">
          <div class="bg-primary position-absolute top-0 start-0 w-100" style="height: 120px; z-index: 1; opacity: 0.1;"></div>
          
          <div class="position-relative" style="z-index: 2; margin-top: 20px;">
            <div class="d-flex justify-content-center mb-4">
                <img src="/phoenix/assets/img/spot-illustrations/404-illustration.png" alt="404 Error" class="img-fluid" style="max-height: 200px;" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjMzhiMmFjIiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCI+PHBhdGggZD0iTTEwLjI5IDMuODZsLTYuNiAxMS40MWMtMS4wNSAxLjgyLS4yNCA0LjEzIDEuNzggNC4xM2gxMy4yYzIuMDMgMCAyLjgzLTIuMzEgMS43OC00LjEzTDExLjcgMy44NmMtLjc5LTEuMzYtMi43Ni0xLjM2LTMuNDEgMHoiLz48cGF0aCBkPSJNMTIgOWYwIDQiLz48cGF0aCBkPSJNMTIgMTdoLjAxIi8+PC9zdmc+'" />
            </div>
            
            <h1 class="text-1000 mb-2 fw-bolder" style="font-size: 3rem;">404</h1>
            <h3 class="text-800 mb-3 fw-bold">Trang bạn tìm kiếm không tồn tại!</h3>
            <p class="text-700 fs--1 mb-5">Liên kết có thể đã hỏng, bị xóa hoặc bạn đã gõ nhầm địa chỉ URL. Đừng lo lắng, hãy quay lại trang chủ.</p>

            <div class="d-grid">
              <a class="btn btn-primary btn-lg w-100 d-flex align-items-center justify-content-center" href="/attendance">
                 <span class="fas fa-home me-2"></span>Trở về trang chủ
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <script src="/phoenix/vendors/bootstrap/bootstrap.min.js"></script>
</body>
</html>
