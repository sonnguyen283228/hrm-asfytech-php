<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars(site_get('site_name', 'HRM APP')) ?></title>
  <?php $favicon = site_get('site_favicon_url', ''); if ($favicon): ?>
    <link rel="icon" href="<?= htmlspecialchars($favicon) ?>">
  <?php endif; ?>
  <link href="/theme-phoenix/assets/css/theme.min.css" rel="stylesheet" />
  <link href="/theme-phoenix/assets/css/user.min.css" rel="stylesheet" />
  <style>
    :root{--phoenix-primary:#4285f4}
    .navbar-brand img{height:32px}
    .app-shell{max-width:1200px;margin:0 auto;padding:16px}
    .nav-pill{padding:.45rem .8rem;border-radius:999px}
    .nav-pill.active{background:#4285f4;color:#fff!important}
  </style>
</head>
<body>
  <main class="main" id="top">
    <div class="app-shell">
      <nav class="navbar navbar-expand-lg navbar-light bg-white border rounded-3 px-3 py-2 mb-3">
        <a class="navbar-brand d-flex align-items-center gap-2" href="/attendance">
          <?php $logo = site_get('site_logo_url', ''); if ($logo): ?><img src="<?= htmlspecialchars($logo) ?>" alt="logo"><?php endif; ?>
          <span class="fw-bold text-1100"><?= htmlspecialchars(site_get('site_name', 'HRM APP')) ?></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNav"><span class="navbar-toggler-icon"></span></button>
        <?php $u = auth_user(); $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/'; ?>
        <div class="collapse navbar-collapse" id="topNav">
          <?php if ($u): ?>
          <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3 gap-2">
            <li class="nav-item"><a class="nav-link nav-pill <?= ($path==='/'||strpos($path,'/attendance')===0)?'active':'' ?>" href="/attendance">Home</a></li>
            <li class="nav-item"><a class="nav-link nav-pill <?= (strpos($path,'/employees')===0||strpos($path,'/departments')===0)?'active':'' ?>" href="/employees">Nhân sự</a></li>
            <li class="nav-item"><a class="nav-link nav-pill <?= (strpos($path,'/projects')===0)?'active':'' ?>" href="/projects">Dự án</a></li>
          </ul>
          <?php endif; ?>
          <small class="text-700">ASFY HRM</small>
        </div>
      </nav>

      <?= site_get('header_html', '') ?>
