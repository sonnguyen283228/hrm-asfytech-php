<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="card-modern">
  <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:8px">
    <h2 style="margin:0">Quản lý dự án</h2>
    <a class="btn btn-asfy" href="/projects/create">+ Tạo dự án</a>
  </div>

  <?php if (!empty($_SESSION['success'])): ?>
    <p style="color:#0a7d34;font-weight:600"><?= htmlspecialchars($_SESSION['success']) ?></p>
    <?php unset($_SESSION['success']); ?>
  <?php endif; ?>
  <?php if (!empty($_SESSION['error'])): ?>
    <p style="color:#d73a49;font-weight:600"><?= htmlspecialchars($_SESSION['error']) ?></p>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>

  <div style="overflow:auto">
    <table class="table-clean" style="width:100%;border-collapse:collapse;min-width:860px">
      <thead>
        <tr>
          <th>Dự án</th>
          <th>Ngày bắt đầu</th>
          <th>Thời gian dự kiến</th>
          <th>Số module</th>
          <th>Hoàn thành</th>
          <th>Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($projects as $p): ?>
        <tr>
          <td><?= htmlspecialchars($p['name']) ?></td>
          <td><?= htmlspecialchars($p['start_date']) ?></td>
          <td><?= (int)($p['duration_months'] ?? 0) ?> tháng (tính từ chi tiết)</td>
          <td><?= (int)$p['total_modules'] ?></td>
          <td><span class="badge-online"><?= round((float)$p['progress_avg']) ?>%</span></td>
          <td><a class="btn" href="/projects/view?id=<?= (int)$p['id'] ?>">Chi tiết</a></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
