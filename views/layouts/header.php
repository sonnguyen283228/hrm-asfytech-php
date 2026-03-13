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

  <script src="/phoenix/assets/js/config.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
  <link href="/phoenix/assets/css/theme.min.css" rel="stylesheet" id="style-default">
  <link href="/phoenix/assets/css/user.min.css" rel="stylesheet" id="user-style-default">
  <link href="/phoenix/assets/css/asfy-custom.css" rel="stylesheet" />
  
  <style>
    body { font-family: 'Nunito Sans', sans-serif; }
    .navbar-brand img { height: 36px; }

    /* ===== HRM Striped Table ===== */
    .table-hrm thead tr {
      background: linear-gradient(135deg, #2c3e50 0%, #3d5a80 100%) !important;
      color: #fff !important;
    }
    .table-hrm thead th {
      color: #fff !important;
      font-weight: 700;
      letter-spacing: 0.03em;
      font-size: .78rem;
      text-transform: uppercase;
      border-bottom: none !important;
      padding-top: 12px;
      padding-bottom: 12px;
    }
    .table-hrm tbody tr:nth-child(even) {
      background-color: #f3f6fb;
    }
    .table-hrm tbody tr:nth-child(odd) {
      background-color: #fff;
    }
    .table-hrm tbody tr:hover {
      background-color: #e8f0fe !important;
      transition: background-color 0.15s ease;
    }
    .table-hrm td, .table-hrm th {
      vertical-align: middle;
      border-color: #e2e8ef;
    }
    /* Nút icon nhỏ cho cột Thao tác */
    .btn-icon-xs {
      width: 28px !important;
      height: 28px !important;
      padding: 0 !important;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }
    .btn-icon-xs [data-feather], .btn-icon-xs svg {
      width: 13px !important;
      height: 13px !important;
    }
  </style>
  
  <?= site_get('header_html', '') ?>
</head>
<body class="navbar-top">
  <main class="main" id="top">
    <!-- Top Navbar -->
    <nav class="navbar navbar-top navbar-expand-lg bg-white mb-4 border-bottom px-0 sticky-top shadow-sm" id="navbarTop" style="z-index: 1020;">
      <div class="container-fluid px-3 px-xl-4 px-xxl-6">
        
        <div class="d-flex align-items-center">
          <button class="btn btn-link navbar-toggler me-1 px-2 d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTopCollapse" aria-controls="navbarTopCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span data-feather="menu"></span>
          </button>
          <a class="navbar-brand me-1 me-sm-3 d-flex align-items-center gap-2" href="/attendance">
            <?php $logo = site_get('site_logo_url', ''); if ($logo): ?>
              <img src="<?= htmlspecialchars($logo) ?>" alt="logo" style="height: 28px;">
            <?php else: ?>
              <div class="d-flex align-items-center justify-content-center bg-primary text-white rounded-2" style="width: 28px; height: 28px; font-weight: 800; font-size: 14px;">A</div>
            <?php endif; ?>
            <span class="d-none d-sm-block fw-bold text-1000 fs-1 tracking-tight"><?= htmlspecialchars(site_get('site_name', 'HRM APP')) ?></span>
          </a>
        </div>

        <div class="collapse navbar-collapse justify-content-center" id="navbarTopCollapse">
          <ul class="navbar-nav gap-2 gap-lg-4 mb-2 mb-lg-0 py-2 py-lg-0">
            <li class="nav-item">
              <a class="nav-link fw-bold text-uppercase fs--1 d-flex align-items-center gap-2 px-3 py-2 rounded-3 transition-base <?= ($activePage ?? '') === 'home' ? 'active bg-primary-100 text-primary' : 'text-700 hover-bg-200' ?>" href="/attendance">
                <span data-feather="pie-chart" style="width: 16px; height: 16px;"></span> Trang chủ
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link fw-bold text-uppercase fs--1 d-flex align-items-center gap-2 px-3 py-2 rounded-3 transition-base <?= ($activePage ?? '') === 'employees' ? 'active bg-primary-100 text-primary' : 'text-700 hover-bg-200' ?>" href="/employees">
                <span data-feather="users" style="width: 16px; height: 16px;"></span> Nhân sự
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link fw-bold text-uppercase fs--1 d-flex align-items-center gap-2 px-3 py-2 rounded-3 transition-base <?= ($activePage ?? '') === 'departments' ? 'active bg-primary-100 text-primary' : 'text-700 hover-bg-200' ?>" href="/departments">
                <span data-feather="grid" style="width: 16px; height: 16px;"></span> Phòng ban
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link fw-bold text-uppercase fs--1 d-flex align-items-center gap-2 px-3 py-2 rounded-3 transition-base <?= ($activePage ?? '') === 'positions' ? 'active bg-primary-100 text-primary' : 'text-700 hover-bg-200' ?>" href="/positions">
                <span data-feather="award" style="width: 16px; height: 16px;"></span> Chức vụ
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link fw-bold text-uppercase fs--1 d-flex align-items-center gap-2 px-3 py-2 rounded-3 transition-base <?= ($activePage ?? '') === 'projects' ? 'active bg-primary-100 text-primary' : 'text-700 hover-bg-200' ?>" href="/projects">
                <span data-feather="briefcase" style="width: 16px; height: 16px;"></span> Dự án
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link fw-bold text-uppercase fs--1 d-flex align-items-center gap-2 px-3 py-2 rounded-3 transition-base <?= ($activePage ?? '') === 'settings' ? 'active bg-primary-100 text-primary' : 'text-700 hover-bg-200' ?>" href="/settings/site">
                <span data-feather="settings" style="width: 16px; height: 16px;"></span> Tùy biến
              </a>
            </li>
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
                  <img class="rounded-circle w-100 h-100 object-fit-cover" src="<?= htmlspecialchars($u['avatar_url']) ?>" alt="" referrerpolicy="no-referrer" />
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
                        <img class="rounded-circle object-fit-cover w-100 h-100" src="<?= htmlspecialchars($u['avatar_url']) ?>" alt="" referrerpolicy="no-referrer" />
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
