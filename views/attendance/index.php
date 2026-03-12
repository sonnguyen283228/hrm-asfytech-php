<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="kpi-grid">
  <div class="kpi-card">
    <div class="kpi-title">Tổng nhân sự</div>
    <div class="kpi-value">124</div>
  </div>
  <div class="kpi-card">
    <div class="kpi-title">Dự án đang triển khai</div>
    <div class="kpi-value">18</div>
  </div>
</div>

<div class="card-modern">
  <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;flex-wrap:wrap">
    <h2 style="margin:0">Xin chào, <?= htmlspecialchars($user['full_name']) ?></h2>
    <div class="muted">Hôm nay: <strong><?= htmlspecialchars($today) ?></strong></div>
  </div>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:12px;margin-bottom:12px">
    <div><span class="muted">Check-in:</span> <strong><?= htmlspecialchars($row['check_in'] ?? 'Chưa') ?></strong></div>
    <div><span class="muted">Check-out:</span> <strong><?= htmlspecialchars($row['check_out'] ?? 'Chưa') ?></strong></div>
  </div>

  <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:10px">
    <?php if (($user['role'] ?? 'staff') === 'admin'): ?>
      <a class="btn" href="/employees">Nhân sự</a>
      <a class="btn" href="/departments">Phòng ban</a>
      <a class="btn" href="/projects">Dự án</a>
      <a class="btn" href="/attendance/reports">Báo cáo công</a>
      <a class="btn" href="/settings/site">Tùy biến site</a>
    <?php endif; ?>

    <form method="post" action="/attendance/check-in">
      <button class="btn btn-asfy" type="submit">Check-in</button>
    </form>

    <form method="post" action="/attendance/check-out">
      <button class="btn btn-danger" type="submit">Check-out</button>
    </form>

    <form method="post" action="/logout">
      <button class="btn" type="submit">Đăng xuất</button>
    </form>
  </div>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
