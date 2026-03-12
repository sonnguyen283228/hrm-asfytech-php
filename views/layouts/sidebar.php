<aside class="sidepanel">
  <div class="brand">Trang quản trị</div>
  <div class="sub">Ever.vn</div>

  <a class="side-link <?= ($activePage ?? '') === 'home' ? 'active' : '' ?>" href="/attendance">Trang chủ</a>
  <a class="side-link <?= ($activePage ?? '') === 'employees' ? 'active' : '' ?>" href="/employees">Nhân sự</a>
  <a class="side-link <?= ($activePage ?? '') === 'departments' ? 'active' : '' ?>" href="/departments">Phòng ban</a>
  <a class="side-link <?= ($activePage ?? '') === 'projects' ? 'active' : '' ?>" href="/projects">Dự án</a>
  <a class="side-link <?= ($activePage ?? '') === 'reports' ? 'active' : '' ?>" href="/attendance/reports">Báo cáo công</a>
  <a class="side-link <?= ($activePage ?? '') === 'settings' ? 'active' : '' ?>" href="/settings/site">Tùy biến</a>
</aside>
