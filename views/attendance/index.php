<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="card">
  <h2>Xin chĂ o, <?= htmlspecialchars($user['full_name']) ?></h2>
  <p>HĂ´m nay: <strong><?= htmlspecialchars($today) ?></strong></p>
  <p>Check-in: <strong><?= htmlspecialchars($row['check_in'] ?? 'ChÆ°a') ?></strong></p>
  <p>Check-out: <strong><?= htmlspecialchars($row['check_out'] ?? 'ChÆ°a') ?></strong></p>

  <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:10px">
    <?php if (($user['role'] ?? 'staff') === 'admin'): ?><a class="btn" href="/employees">Nhân sự</a><a class="btn" href="/departments">Phòng ban</a><a class="btn" href="/projects">Dự án</a><a class="btn" href="/attendance/reports">Báo cáo công</a><?php endif; ?>
    <form method="post" action="/attendance/check-in">
      <button class="btn btn-primary" type="submit">Check-in</button>
    </form>

    <form method="post" action="/attendance/check-out">
      <button class="btn btn-danger" type="submit">Check-out</button>
    </form>

    <form method="post" action="/logout">
      <button class="btn" type="submit">ÄÄƒng xuáº¥t</button>
    </form>
  </div>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>

