<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="card" style="max-width:720px">
  <h2>Thêm nhân sự mới</h2>
  <?php if (!empty($_SESSION['error'])): ?>
    <p style="color:#d73a49"><?= htmlspecialchars($_SESSION['error']) ?></p>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>

  <form method="post" action="/employees/create">
    <label>Họ tên</label>
    <input type="text" name="full_name" required>

    <label>Email công việc</label>
    <input type="email" name="email" required placeholder="name@company.com">

    <label>Phòng ban</label> <a class="muted" href="/departments/create">(+ Thêm phòng ban mới)</a>
    <select name="department_id" style="padding:10px;width:100%;margin-top:6px;margin-bottom:12px;border:1px solid #d1d5db;border-radius:8px">
      <option value="">-- Chọn phòng ban --</option>
      <?php foreach ($departments as $d): ?>
        <option value="<?= (int)$d['id'] ?>"><?= htmlspecialchars($d['name']) ?></option>
      <?php endforeach; ?>
    </select>

    <label>Vị trí</label>
    <input type="text" name="position" placeholder="VD: Frontend Developer" required>

    <label>Vai trò hệ thống</label>
    <select name="role" style="padding:10px;width:100%;margin-top:6px;margin-bottom:12px;border:1px solid #d1d5db;border-radius:8px">
      <option value="staff">staff</option>
      <option value="manager">manager</option>
      <option value="admin">admin</option>
    </select>

    <button class="btn btn-primary" type="submit">Lưu nhân sự</button>
    <a class="btn" href="/employees">Quay lại</a>
  </form>

  <p class="muted" style="margin-top:10px">Avatar sẽ tự cập nhật từ tài khoản Google khi nhân sự đăng nhập bằng Google lần đầu.</p>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
