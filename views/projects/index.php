<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="card">
  <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;flex-wrap:wrap">
    <h2>Quản lý dự án</h2>
    <a class="btn btn-primary" href="/projects/create">+ Tạo dự án</a>
  </div>

  <?php if (!empty($_SESSION['success'])): ?>
    <p style="color:#0a7d34"><?= htmlspecialchars($_SESSION['success']) ?></p>
    <?php unset($_SESSION['success']); ?>
  <?php endif; ?>
  <?php if (!empty($_SESSION['error'])): ?>
    <p style="color:#d73a49"><?= htmlspecialchars($_SESSION['error']) ?></p>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>

  <table style="width:100%;border-collapse:collapse;margin-top:10px">
    <thead>
      <tr>
        <th style="text-align:left;border-bottom:1px solid #ddd;padding:8px">Dự án</th>
        <th style="text-align:left;border-bottom:1px solid #ddd;padding:8px">Ngày bắt đầu</th>
        <th style="text-align:left;border-bottom:1px solid #ddd;padding:8px">Thời gian dự kiến</th>
        <th style="text-align:left;border-bottom:1px solid #ddd;padding:8px">Số module</th>
        <th style="text-align:left;border-bottom:1px solid #ddd;padding:8px">Hoàn thành</th>
        <th style="text-align:left;border-bottom:1px solid #ddd;padding:8px">Thao tác</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($projects as $p): ?>
      <tr>
        <td style="padding:8px;border-bottom:1px solid #f1f1f1"><?= htmlspecialchars($p['name']) ?></td>
        <td style="padding:8px;border-bottom:1px solid #f1f1f1"><?= htmlspecialchars($p['start_date']) ?></td>
        <td style="padding:8px;border-bottom:1px solid #f1f1f1"><?= (int)($p['duration_months'] ?? 0) ?> tháng (tính từ chi tiết)</td>
        <td style="padding:8px;border-bottom:1px solid #f1f1f1"><?= (int)$p['total_modules'] ?></td>
        <td style="padding:8px;border-bottom:1px solid #f1f1f1"><?= round((float)$p['progress_avg']) ?>%</td>
        <td style="padding:8px;border-bottom:1px solid #f1f1f1"><a class="btn" href="/projects/view?id=<?= (int)$p['id'] ?>">Chi tiết</a></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
