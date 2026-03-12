<nav class="navbar navbar-vertical navbar-expand-lg">
  <script>
    var navbarStyle = window.config.config.phoenixNavbarStyle;
    if (navbarStyle && navbarStyle !== 'transparent') {
      document.querySelector('body').classList.add(`navbar-${navbarStyle}`);
    }
  </script>
  <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
    <div class="navbar-vertical-content">
      <ul class="navbar-nav flex-column" id="navbarVerticalNav">
        
        <li class="nav-item">
          <!-- label-->
          <p class="navbar-vertical-label">Tổng Quan</p>
          <hr class="navbar-vertical-line" />
          <a class="nav-link <?= ($activePage ?? '') === 'home' ? 'active' : '' ?>" href="/attendance" role="button" data-bs-toggle="" aria-expanded="false">
            <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="pie-chart"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Trang chủ</span></span></div>
          </a>
        </li>
        
        <li class="nav-item">
          <!-- label-->
          <p class="navbar-vertical-label">Quản Lý Nguồn Lực</p>
          <hr class="navbar-vertical-line" />
          
          <a class="nav-link <?= ($activePage ?? '') === 'employees' ? 'active' : '' ?>" href="/employees" role="button" data-bs-toggle="" aria-expanded="false">
            <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="users"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Nhân sự</span></span></div>
          </a>
          
          <a class="nav-link <?= ($activePage ?? '') === 'departments' ? 'active' : '' ?>" href="/departments" role="button" data-bs-toggle="" aria-expanded="false">
            <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="grid"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Phòng ban</span></span></div>
          </a>
          
          <a class="nav-link <?= ($activePage ?? '') === 'projects' ? 'active' : '' ?>" href="/projects" role="button" data-bs-toggle="" aria-expanded="false">
            <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="briefcase"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Dự án</span></span></div>
          </a>
        </li>
        
        <li class="nav-item">
          <!-- label-->
          <p class="navbar-vertical-label">Hệ Thống</p>
          <hr class="navbar-vertical-line" />
          
          <a class="nav-link <?= ($activePage ?? '') === 'reports' ? 'active' : '' ?>" href="/attendance/reports" role="button" data-bs-toggle="" aria-expanded="false">
            <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="file-text"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Báo cáo công</span></span></div>
          </a>
          
          <a class="nav-link <?= ($activePage ?? '') === 'settings' ? 'active' : '' ?>" href="/settings/site" role="button" data-bs-toggle="" aria-expanded="false">
            <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="settings"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Tùy biến</span></span></div>
          </a>
        </li>
        
      </ul>
    </div>
  </div>
  <div class="navbar-vertical-footer"><button class="btn navbar-vertical-toggle border-0 fw-semi-bold w-100 white-space-nowrap d-flex align-items-center"><span class="uil uil-left-arrow-to-left fs-0"></span><span class="uil uil-arrow-from-right lh-1 fs-0"></span><span class="navbar-vertical-footer-text ms-2">Thu gọn</span></button></div>
</nav>
