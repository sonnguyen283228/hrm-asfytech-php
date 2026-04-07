<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars(site_get('site_name', 'HRM APP')) ?></title>
  
  <?php 
    $baseUrl = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
    $favUrl = get_brand_favicon_url(); 
    if ($favUrl): 
  ?>
    <link rel="icon" href="<?= htmlspecialchars($favUrl) ?>">
  <?php endif; ?>

  <script src="/phoenix/assets/js/config.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="/phoenix/assets/css/theme.min.css" rel="stylesheet" id="style-default">
  <link href="/phoenix/assets/css/user.min.css" rel="stylesheet" id="user-style-default">
  <link href="/phoenix/assets/css/asfy-custom.css" rel="stylesheet" />
  

  
  <?= site_get('header_html', '') ?>
</head>
<body class="bg-light">
  <main class="main" id="top">
    <?php require __DIR__ . '/sidebar.php'; ?>
    
    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg bg-white mb-4 border-bottom px-0 sticky-top shadow-sm" id="navbarTop" style="z-index: 1020;">
      <div class="container-fluid px-3 px-lg-4">
        
        <div class="d-flex align-items-center">
          <button class="btn btn-link navbar-toggler me-1 px-2 d-xl-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalCollapse" aria-controls="navbarVerticalCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span data-feather="menu"></span>
          </button>
          <a class="navbar-brand me-1 me-sm-3 d-flex align-items-center gap-2 d-xl-none" href="/attendance">
            <?php 
              $logoUrl = get_brand_logo_url(); 
              if ($logoUrl): 
            ?>
              <img src="<?= htmlspecialchars($logoUrl) ?>" alt="logo" style="height: 36px; width: auto; object-fit: cover; border-radius: 4px;">
            <?php else: ?>
              <div class="d-flex align-items-center justify-content-center bg-primary text-white" style="width: 36px; height: 36px; border-radius: 4px; font-weight: 800; font-size: 18px;">A</div>
            <?php endif; ?>
            <span class="d-none d-sm-block fw-bold text-1000 tracking-tight" style="font-size: 18px; line-height: 1;"><?= htmlspecialchars(site_get('site_name', 'HRM APP')) ?></span>
          </a>
        </div>

        <div class="collapse navbar-collapse" id="navbarTopCollapse">
          <ul class="navbar-nav gap-2 gap-lg-4 mb-2 mb-lg-0 py-2 py-lg-0">
             <!-- Navigation links moved to sidebar -->
          </ul>
        </div>
        
        <div class="d-flex align-items-center d-none">
          <label class="form-check-label mb-0" for="themeControlToggle">...</label>
        </div>

        <?php $u = auth_user(); ?>
        <?php if ($u): ?>
        <ul class="navbar-nav navbar-nav-icons ms-auto flex-row align-items-center mb-0">
          <li class="nav-item dropdown">
            <a class="nav-link lh-1 pe-0" id="navbarDropdownUser" href="#!" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
              <div class="avatar avatar-l">
                <?php if (!empty($u['avatar_url'])): ?>
                  <?php $avtUrl = strpos($u['avatar_url'], 'http') === 0 ? $u['avatar_url'] : $baseUrl . '/' . ltrim($u['avatar_url'], '/'); ?>
                  <img class="rounded-circle w-100 h-100 object-fit-cover" src="<?= htmlspecialchars($avtUrl) ?>" alt="" referrerpolicy="no-referrer" />
                <?php else: ?>
                  <div class="avatar-name rounded-circle border border-2 border-white bg-200 text-700"><span><?= mb_substr($u['full_name'] ?? 'U', 0, 1) ?></span></div>
                <?php endif; ?>
              </div>
            </a>
            <div class="dropdown-menu dropdown-menu-end navbar-dropdown-caret py-0 dropdown-profile shadow border border-300" aria-labelledby="navbarDropdownUser">
              <div class="card position-relative border-0">
                <div class="card-body p-0">
                  <div class="text-center pt-4 pb-3">
                    <div class="avatar avatar-xl">
                      <?php if (!empty($u['avatar_url'])): ?>
                        <?php $avtUrl = strpos($u['avatar_url'], 'http') === 0 ? $u['avatar_url'] : $baseUrl . '/' . ltrim($u['avatar_url'], '/'); ?>
                        <img class="rounded-circle object-fit-cover w-100 h-100" src="<?= htmlspecialchars($avtUrl) ?>" alt="" referrerpolicy="no-referrer" />
                      <?php else: ?>
                        <div class="avatar-name rounded-circle border border-2 border-white bg-200 text-700"><span><?= mb_substr($u['full_name'] ?? 'U', 0, 1) ?></span></div>
                      <?php endif; ?>
                    </div>
                    <h6 class="mt-2 text-1000"><?= htmlspecialchars($u['full_name'] ?? '') ?></h6>
                    <p class="text-600 fs--2 mb-0"><?= htmlspecialchars($u['role'] ?? '') ?></p>
                  </div>
                </div>
                <div class="card-footer p-2 border-top">
                  <form method="post" action="/logout" class="mb-0">
                    <button type="submit" class="btn btn-phoenix-secondary d-flex flex-center w-100"><span class="me-2" data-feather="log-out"></span>Đăng xuất</button>
                  </form>
                </div>
              </div>
            </div>
          </li>
        </ul>
        <?php endif; ?>

      </div>
    </nav>
    
    <!-- Main Content Wrapper -->

    <!-- Main Content Wrapper -->
    <div class="content pt-0">
