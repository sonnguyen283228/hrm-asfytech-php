<?php require __DIR__ . '/../layouts/header.php'; ?>
<style>
.dashboard-shell{display:grid;grid-template-columns:280px 1fr;gap:16px}
.sidepanel{background:#1f2a44;border-radius:14px;padding:14px;color:#dbe7ff;min-height:640px}
.sidepanel .brand{font-weight:800;font-size:20px;margin-bottom:2px;color:#fff}
.sidepanel .sub{font-size:12px;color:#9fb6e9;margin-bottom:14px}
.side-link{display:block;padding:11px 12px;border-radius:10px;color:#dbe7ff;text-decoration:none;margin-bottom:8px;border:1px solid transparent}
.side-link:hover{background:#2b3a5f}
.side-link.active{background:#0ea5a0;color:#fff}
.mainpanel{background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:16px;box-shadow:0 6px 18px rgba(15,23,42,.05)}
.kpi-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px;margin-top:10px}
.kpi{border:1px solid #eceff4;border-radius:12px;padding:14px;background:#fff}
.kpi .t{font-size:13px;color:#64748b}
.kpi .v{font-size:28px;font-weight:800;color:#0f172a;margin-top:5px}
.topline{display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap}
.quick{display:flex;gap:8px;flex-wrap:wrap;margin-top:14px}
@media (max-width:1100px){.kpi-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.dashboard-shell{grid-template-columns:1fr}}
</style>

<div class="dashboard-shell">
  <aside class="sidepanel">
    <div class="brand">Trang quản trị</div>
    <div class="sub">Ever.vn</div>

    <a class="side-link active" href="/attendance">Trang chủ</a>
    <a class="side-link" href="/employees">Nhân sự</a>
    <a class="side-link" href="/departments">Phòng ban</a>
    <a class="side-link" href="/projects">Dự án</a>
    <a class="side-link" href="/attendance/reports">Báo cáo công</a>
    <a class="side-link" href="/settings/site">Tùy biến</a>
  </aside>

  <section class="mainpanel">
    <div class="topline">
      <div>
        <h2 style="margin:0 0 4px">Trang quản trị</h2>
        <div class="muted">Thống kê toàn diện hệ thống - Cập nhật lúc <?= date('H:i d/m/Y') ?></div>
      </div>
      <div class="muted">Hôm nay: <strong><?= htmlspecialchars($today) ?></strong></div>
    </div>

    <div class="kpi-grid">
      <div class="kpi"><div class="t">Tổng nhân sự</div><div class="v"><?= (int)($stats['employees'] ?? 0) ?></div></div>
      <div class="kpi"><div class="t">Tổng dự án</div><div class="v"><?= (int)($stats['projects_total'] ?? 0) ?></div></div>
      <div class="kpi"><div class="t">Đang triển khai</div><div class="v"><?= (int)($stats['projects_in_progress'] ?? 0) ?></div></div>
      <div class="kpi"><div class="t">Đi làm hôm nay</div><div class="v"><?= (int)($stats['present_today'] ?? 0) ?></div></div>
      <div class="kpi"><div class="t">Kế hoạch</div><div class="v"><?= (int)($stats['projects_planning'] ?? 0) ?></div></div>
      <div class="kpi"><div class="t">Tạm dừng</div><div class="v"><?= (int)($stats['projects_paused'] ?? 0) ?></div></div>
      <div class="kpi"><div class="t">Hoàn thành</div><div class="v"><?= (int)($stats['projects_done'] ?? 0) ?></div></div>
      <div class="kpi"><div class="t">Giờ làm hôm nay (cá nhân)</div><div class="v"><?= (float)($stats['my_today_hours'] ?? 0) ?>h</div></div>
    </div>

    <div class="quick">
      <form method="post" action="/attendance/check-in"><button class="btn btn-asfy" type="submit">Check-in</button></form>
      <form method="post" action="/attendance/check-out"><button class="btn btn-danger" type="submit">Check-out</button></form>
      <a class="btn" href="/employees">Danh sách nhân sự</a>
      <a class="btn" href="/projects">Danh sách dự án</a>
      <a class="btn" href="/attendance/reports">Báo cáo tháng</a>
      <form method="post" action="/logout"><button class="btn" type="submit">Đăng xuất</button></form>
    </div>
  </section>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
