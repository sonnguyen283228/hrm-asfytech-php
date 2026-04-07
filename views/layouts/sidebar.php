<nav class="navbar navbar-vertical navbar-expand-xl" style="display:inline-block;">
  <div class="d-flex align-items-center mb-4 navbar-brand-wrapper">
    <a class="navbar-brand d-flex align-items-center gap-2" href="/attendance" style="padding: 8px;">
      <?php 
        $logoUrl = get_brand_logo_url(); 
        if ($logoUrl): 
      ?>
        <img src="<?= htmlspecialchars($logoUrl) ?>" alt="logo" style="height: 48px; width: auto; object-fit: cover; border-radius: 6px;">
      <?php else: ?>
        <div class="d-flex align-items-center justify-content-center bg-primary text-white" style="width: 48px; height: 48px; border-radius: 6px; font-weight: 800; font-size: 24px;">A</div>
      <?php endif; ?>
      <span class="fw-bold text-1000 tracking-tight" style="font-size: 18px; line-height: 1;"><?= htmlspecialchars(site_get('site_name', 'HRM APP')) ?></span>
    </a>
  </div>
  
  <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
    <div class="navbar-vertical-content">
      <ul class="navbar-nav flex-column" id="navbarVerticalNav">
        
        <li class="nav-item">
          <!-- 1. Tổng quan -->
          <a class="nav-link <?= ($activePage ?? '') === 'home' ? 'active' : '' ?>" href="/attendance" role="button">
            <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="pie-chart"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Tổng quan</span></span></div>
          </a>
        </li>
        
        <li class="nav-item">
          <!-- 2. Nhân sự -->
          <?php if (\is_feature_enabled('ff_module_employees')): ?>
          <a class="nav-link <?= ($activePage ?? '') === 'employees' ? 'active' : '' ?>" href="/employees" role="button">
            <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="users"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Nhân sự</span></span></div>
          </a>
          <?php endif; ?>
        </li>
        
        <li class="nav-item">
          <!-- 3. Phòng ban & Chức vụ (Accordion) -->
          <?php 
            $isDeptOrPosActive = in_array(($activePage ?? ''), ['departments', 'positions']); 
            $collapseClass = $isDeptOrPosActive ? 'show' : '';
            $ariaExpanded = $isDeptOrPosActive ? 'true' : 'false';
          ?>
          <a class="nav-link dropdown-indicator <?= $isDeptOrPosActive ? '' : 'collapsed' ?>" href="#org-structure" role="button" data-bs-toggle="collapse" aria-expanded="<?= $ariaExpanded ?>" aria-controls="org-structure">
            <div class="d-flex align-items-center">
              <span class="nav-link-icon"><span data-feather="layers"></span></span>
              <span class="nav-link-text-wrapper"><span class="nav-link-text">Phòng ban & Chức vụ</span></span>
            </div>
          </a>
          <div class="collapse <?= $collapseClass ?>" id="org-structure">
            <ul class="nav collapse-nav flex-column ps-3">
              <?php if (\is_feature_enabled('ff_module_departments')): ?>
              <li class="nav-item">
                <a class="nav-link <?= ($activePage ?? '') === 'departments' ? 'active' : '' ?>" href="/departments">
                  <div class="d-flex align-items-center"><span class="nav-link-text-wrapper"><span class="nav-link-text">Phòng ban</span></span></div>
                </a>
              </li>
              <?php endif; ?>
              <?php if (\is_feature_enabled('ff_module_positions')): ?>
              <li class="nav-item">
                <a class="nav-link <?= ($activePage ?? '') === 'positions' ? 'active' : '' ?>" href="/positions">
                  <div class="d-flex align-items-center"><span class="nav-link-text-wrapper"><span class="nav-link-text">Chức vụ</span></span></div>
                </a>
              </li>
              <?php endif; ?>
            </ul>
          </div>
        </li>
        
        <li class="nav-item">
          <!-- 4. Chấm công -->
          <a class="nav-link <?= ($activePage ?? '') === 'timekeeping' ? 'active' : '' ?>" href="/timekeeping" role="button">
            <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="clock"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Chấm công</span></span></div>
          </a>
        </li>
        
        <li class="nav-item">
          <!-- 5. Nghỉ phép -->
          <a class="nav-link <?= ($activePage ?? '') === 'leave' ? 'active' : '' ?>" href="/leave" role="button">
            <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="calendar"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Nghỉ phép</span></span></div>
          </a>
        </li>
        
        <li class="nav-item">
          <!-- 6. Dự án -->
          <?php if (\is_feature_enabled('ff_module_projects')): ?>
          <a class="nav-link <?= ($activePage ?? '') === 'projects' ? 'active' : '' ?>" href="/projects" role="button">
            <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="briefcase"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Dự án</span></span></div>
          </a>
          <?php endif; ?>
        </li>
        
        <li class="nav-item">
          <!-- 7. Báo cáo -->
          <a class="nav-link <?= ($activePage ?? '') === 'reports' ? 'active' : '' ?>" href="/attendance/reports" role="button">
            <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="file-text"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Báo cáo</span></span></div>
          </a>
        </li>
        
        <li class="nav-item">
          <!-- 8. Cài đặt -->
          <a class="nav-link <?= ($activePage ?? '') === 'settings' ? 'active' : '' ?>" href="/settings/site" role="button">
            <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="settings"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Cài đặt</span></span></div>
          </a>
        </li>
        
      </ul>
    </div>
  </div>
</nav>
