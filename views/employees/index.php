<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="card">
  <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;flex-wrap:wrap">
    <h2>Quản lý nhân sự</h2>
    <button class="btn btn-primary" type="button" onclick="openEmployeeModal()">+ Thêm nhân sự</button>
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
        <th style="text-align:left;border-bottom:1px solid #ddd;padding:8px">Avatar</th>
        <th style="text-align:left;border-bottom:1px solid #ddd;padding:8px">Họ tên</th>
        <th style="text-align:left;border-bottom:1px solid #ddd;padding:8px">Email</th>
        <th style="text-align:left;border-bottom:1px solid #ddd;padding:8px">Phòng ban</th>
        <th style="text-align:left;border-bottom:1px solid #ddd;padding:8px">Vị trí</th>
        <th style="text-align:left;border-bottom:1px solid #ddd;padding:8px">Vai trò</th>
        <th style="text-align:left;border-bottom:1px solid #ddd;padding:8px">Trạng thái</th>
        <th style="text-align:left;border-bottom:1px solid #ddd;padding:8px">Thời gian làm việc</th>
        <th style="text-align:left;border-bottom:1px solid #ddd;padding:8px">Lương cơ bản</th>
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
        <td style="padding:8px;border-bottom:1px solid #f1f1f1">
          <?php if ((int)($e['is_active'] ?? 1) !== 1): ?>
            <span style="color:#d73a49">Khóa</span>
          <?php else: ?>
            <?= is_user_online($e['last_seen_at'] ?? null, 5) ? '<span style="color:#0a7d34">Online</span>' : '<span class="muted">Offline</span>' ?>
          <?php endif; ?>
        </td>
        <td style="padding:8px;border-bottom:1px solid #f1f1f1"><?= htmlspecialchars(working_tenure_text($e['start_date'] ?? null)) ?></td>
        <td style="padding:8px;border-bottom:1px solid #f1f1f1"><?= isset($e['base_salary']) && $e['base_salary'] !== null ? number_format((int)$e['base_salary']) . ' đ' : '--' ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<div id="employeeModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:9999;">
  <div style="max-width:760px;margin:40px auto;background:#fff;border-radius:12px;padding:18px;max-height:90vh;overflow:auto">
    <div style="display:flex;justify-content:space-between;align-items:center">
      <h3 style="margin:0">Thêm nhân sự mới</h3>
      <button class="btn" type="button" onclick="closeEmployeeModal()">Đóng</button>
    </div>
    <form method="post" action="/employees/create">
      <label>Họ tên</label>
      <input type="text" name="full_name" required>

      <label>Email công việc (chỉ @gmail.com)</label>
      <input type="email" name="email" required placeholder="name@gmail.com">

      <label>Điện thoại (VN)</label>
      <input type="text" name="phone" required placeholder="09xxxxxxxx">

      <label>Địa chỉ - Phường/Xã</label>
      <input type="text" name="address_ward" placeholder="VD: Phường 7">

      <label>Địa chỉ - Tỉnh/Thành phố</label>
      <input type="text" name="address_city" placeholder="VD: TP.HCM">

      <label>Ngày bắt đầu làm việc</label>
      <input type="date" name="start_date">

      <label>Ngày sinh</label>
      <input type="date" name="birth_date">

      <label>Lương cơ bản (VND)</label>
      <input type="number" min="0" step="1" name="base_salary" placeholder="10000000">

      <label>Phòng ban</label>
      <select name="department_id" style="padding:10px;width:100%;margin-top:6px;margin-bottom:12px;border:1px solid #d1d5db;border-radius:8px">
        <option value="">-- Chọn phòng ban --</option>
        <?php foreach (($departments ?? []) as $d): ?>
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
    </form>
  </div>
</div>

<script>
function openEmployeeModal(){ document.getElementById('employeeModal').style.display='block'; }
function closeEmployeeModal(){ document.getElementById('employeeModal').style.display='none'; }
</script>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
