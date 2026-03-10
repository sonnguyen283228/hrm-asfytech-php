<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="card">
  <h2><?= htmlspecialchars($project['name']) ?></h2>
  <p><strong>Bắt đầu:</strong> <?= htmlspecialchars($project['start_date']) ?> | <strong>Thời gian dự kiến:</strong> <?= (int)$project['duration_months'] ?> tháng</p>
  <p class="muted"><?= htmlspecialchars($project['description'] ?? '') ?></p>
  <p><a class="btn" href="/projects">← Quay lại danh sách dự án</a></p>

  <?php if (!empty($_SESSION['success'])): ?>
    <p style="color:#0a7d34"><?= htmlspecialchars($_SESSION['success']) ?></p>
    <?php unset($_SESSION['success']); ?>
  <?php endif; ?>
  <?php if (!empty($_SESSION['error'])): ?>
    <p style="color:#d73a49"><?= htmlspecialchars($_SESSION['error']) ?></p>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>
</div>

<div class="card">
  <h3>Nhân sự trong dự án</h3>
  <form method="post" action="/projects/members/add" style="margin-bottom:12px">
    <input type="hidden" name="project_id" value="<?= (int)$project['id'] ?>">
    <label>Nhân sự</label>
    <select name="user_id" style="padding:10px;width:100%;margin:6px 0 12px;border:1px solid #d1d5db;border-radius:8px" required>
      <option value="">-- Chọn nhân sự --</option>
      <?php foreach ($users as $u): ?>
        <option value="<?= (int)$u['id'] ?>"><?= htmlspecialchars($u['full_name']) ?> (<?= htmlspecialchars($u['email']) ?>)</option>
      <?php endforeach; ?>
    </select>

    <label>Vai trò trong dự án</label>
    <input type="text" name="project_role" placeholder="VD: PM, Backend, Frontend, QA" required>

    <button class="btn btn-primary" type="submit">Thêm vào dự án</button>
  </form>

  <table style="width:100%;border-collapse:collapse">
    <thead>
      <tr>
        <th style="text-align:left;border-bottom:1px solid #ddd;padding:8px">Nhân sự</th>
        <th style="text-align:left;border-bottom:1px solid #ddd;padding:8px">Phòng ban</th>
        <th style="text-align:left;border-bottom:1px solid #ddd;padding:8px">Vai trò dự án</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($members as $m): ?>
      <tr>
        <td style="padding:8px;border-bottom:1px solid #f1f1f1"><?= htmlspecialchars($m['full_name']) ?> (<?= htmlspecialchars($m['email']) ?>)</td>
        <td style="padding:8px;border-bottom:1px solid #f1f1f1"><?= htmlspecialchars($m['department_name'] ?? '--') ?></td>
        <td style="padding:8px;border-bottom:1px solid #f1f1f1"><?= htmlspecialchars($m['project_role']) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<div class="card">
  <h3>Module dự án & tiến độ</h3>
  <form method="post" action="/projects/modules/create" style="margin-bottom:12px">
    <input type="hidden" name="project_id" value="<?= (int)$project['id'] ?>">
    <label>Tên module</label>
    <input type="text" name="name" required placeholder="VD: Module Đăng nhập">

    <label>Thời gian dự kiến (tháng)</label>
    <input type="number" step="0.5" min="0.5" name="planned_months" required>

    <button class="btn btn-primary" type="submit">Thêm module</button>
  </form>

  <table style="width:100%;border-collapse:collapse">
    <thead>
      <tr>
        <th style="text-align:left;border-bottom:1px solid #ddd;padding:8px">Module</th>
        <th style="text-align:left;border-bottom:1px solid #ddd;padding:8px">Dự kiến</th>
        <th style="text-align:left;border-bottom:1px solid #ddd;padding:8px">Tiến độ</th>
        <th style="text-align:left;border-bottom:1px solid #ddd;padding:8px">Trạng thái</th>
        <th style="text-align:left;border-bottom:1px solid #ddd;padding:8px">Cập nhật</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($modules as $mo): ?>
      <tr>
        <td style="padding:8px;border-bottom:1px solid #f1f1f1"><?= htmlspecialchars($mo['name']) ?></td>
        <td style="padding:8px;border-bottom:1px solid #f1f1f1"><?= (float)$mo['planned_months'] ?> tháng</td>
        <td style="padding:8px;border-bottom:1px solid #f1f1f1"><?= (int)$mo['progress_percent'] ?>%</td>
        <td style="padding:8px;border-bottom:1px solid #f1f1f1"><?= htmlspecialchars($mo['status']) ?></td>
        <td style="padding:8px;border-bottom:1px solid #f1f1f1">
          <form method="post" action="/projects/modules/progress" style="display:flex;gap:6px;align-items:center;flex-wrap:wrap">
            <input type="hidden" name="project_id" value="<?= (int)$project['id'] ?>">
            <input type="hidden" name="module_id" value="<?= (int)$mo['id'] ?>">
            <input type="number" name="progress_percent" min="0" max="100" value="<?= (int)$mo['progress_percent'] ?>" style="width:90px;padding:6px">
            <select name="status" style="padding:6px">
              <option value="pending" <?= $mo['status']==='pending'?'selected':'' ?>>pending</option>
              <option value="in_progress" <?= $mo['status']==='in_progress'?'selected':'' ?>>in_progress</option>
              <option value="done" <?= $mo['status']==='done'?'selected':'' ?>>done</option>
            </select>
            <button class="btn" type="submit">Lưu</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
