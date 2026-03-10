<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="card">
  <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;flex-wrap:wrap">
    <h2>Quản lý nhân sự</h2>
    <a class="btn btn-primary" href="/employees/create">+ Thêm nhân sự</a>
  </div>

  <?php if (!empty($_SESSION['success'])): ?>
    <p style="color:#0a7d34"><?= htmlspecialchars($_SESSION['success']) ?></p>
    <?php unset($_SESSION['success']); ?>
  <?php endif; ?>

  <table style="width:100%;border-collapse:collapse;margin-top:10px">
    <thead>
      <tr>
        <th style="text-align:left;border-bottom:1px solid #ddd;padding:8px">Avatar</th>
        <th style="text-align:left;border-bottom:1px solid #ddd;padding:8px">Họ tên</th>
        <th style="text-align:left;border-bottom:1px solid #ddd;padding:8px">Email</th>
        <th style="text-align:left;border-bottom:1px solid #ddd;padding:8px">Phòng ban</th>
        <th style="text-align:left;border-bottom:1px solid #ddd;padding:8px">Vị trí</th>
        <th style="text-align:left;border-bottom:1px solid #ddd;padding:8px">Vai trò</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($employees as $e): ?>
      <tr>
        <td style="padding:8px;border-bottom:1px solid #f1f1f1">
          <?php if (!empty($e['avatar_url'])): ?>
            <img src="<?= htmlspecialchars($e['avatar_url']) ?>" alt="avatar" style="width:36px;height:36px;border-radius:50%">
          <?php else: ?>
            <span class="muted">--</span>
          <?php endif; ?>
        </td>
        <td style="padding:8px;border-bottom:1px solid #f1f1f1"><?= htmlspecialchars($e['full_name']) ?></td>
        <td style="padding:8px;border-bottom:1px solid #f1f1f1"><?= htmlspecialchars($e['email']) ?></td>
        <td style="padding:8px;border-bottom:1px solid #f1f1f1"><?= htmlspecialchars($e['department_name'] ?? '--') ?></td>
        <td style="padding:8px;border-bottom:1px solid #f1f1f1"><?= htmlspecialchars($e['position'] ?? '--') ?></td>
        <td style="padding:8px;border-bottom:1px solid #f1f1f1"><?= htmlspecialchars($e['role']) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
