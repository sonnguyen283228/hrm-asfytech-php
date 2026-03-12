<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="card-modern">
  <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:8px">
    <h2 style="margin:0">Quản lý nhân sự</h2>
    <button class="btn btn-asfy" type="button" onclick="openEmployeeModal()">+ Thêm nhân sự</button>
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
    <table class="table-clean" style="width:100%;border-collapse:collapse;margin-top:8px;min-width:1020px">
      <thead>
        <tr>
          <th>Avatar</th>
          <th>Họ tên</th>
          <th>Email</th>
          <th>Phòng ban</th>
          <th>Vị trí</th>
          <th>Vai trò</th>
          <th>Trạng thái</th>
          <th>Thời gian làm việc</th>
          <th>Lương cơ bản</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($employees as $e): ?>
        <tr>
          <td>
            <?php if (!empty($e['avatar_url'])): ?>
              <img src="<?= htmlspecialchars($e['avatar_url']) ?>" alt="avatar" style="width:36px;height:36px;border-radius:50%">
            <?php else: ?>
              <span class="muted">--</span>
            <?php endif; ?>
          </td>
          <td><?= htmlspecialchars($e['full_name']) ?></td>
          <td><?= htmlspecialchars($e['email']) ?></td>
          <td><?= htmlspecialchars($e['department_name'] ?? '--') ?></td>
          <td><?= htmlspecialchars($e['position'] ?? '--') ?></td>
          <td><?= htmlspecialchars($e['role']) ?></td>
          <td>
            <?php if ((int)($e['is_active'] ?? 1) !== 1): ?>
              <span class="badge-offline">Khóa</span>
            <?php else: ?>
              <?= is_user_online($e['last_seen_at'] ?? null, 5) ? '<span class="badge-online">Online</span>' : '<span class="badge-offline">Offline</span>' ?>
            <?php endif; ?>
          </td>
          <td><?= htmlspecialchars(working_tenure_text($e['start_date'] ?? null)) ?></td>
          <td><?= isset($e['base_salary']) && $e['base_salary'] !== null ? number_format((int)$e['base_salary']) . ' đ' : '--' ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<div id="employeeModal" class="modal-mask" style="display:none">
  <div class="modal-panel" style="max-width:920px">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
      <h3 style="margin:0">Thêm nhân sự mới</h3>
      <button class="btn" type="button" onclick="closeEmployeeModal()">Đóng</button>
    </div>

    <form method="post" action="/employees/create">
      <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px">
        <div>
          <label>Họ tên</label>
          <input type="text" name="full_name" required>
        </div>

        <div>
          <label>Email công việc (chỉ @gmail.com)</label>
          <input type="email" name="email" required placeholder="name@gmail.com">
        </div>

        <div>
          <label>Điện thoại (VN)</label>
          <input type="text" name="phone" required placeholder="09xxxxxxxx">
        </div>

        <div>
          <label>Địa chỉ - Phường/Xã</label>
          <input type="text" name="address_ward" placeholder="VD: Phường 7">
        </div>

        <div>
          <label>Địa chỉ - Tỉnh/Thành phố</label>
          <input type="text" name="address_city" placeholder="VD: TP.HCM">
        </div>

        <div>
          <label>Ngày bắt đầu làm việc</label>
          <input type="date" name="start_date">
        </div>

        <div>
          <label>Ngày sinh</label>
          <input type="date" name="birth_date">
        </div>

        <div>
          <label>Lương cơ bản (VND)</label>
          <input type="number" min="0" step="1" name="base_salary" placeholder="10000000">
        </div>

        <div style="grid-column:1/-1">
          <label>Phòng ban</label>
          <select name="department_id">
            <option value="">-- Chọn phòng ban --</option>
            <?php foreach (($departments ?? []) as $d): ?>
              <option value="<?= (int)$d['id'] ?>"><?= htmlspecialchars($d['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label>Vị trí</label>
          <input type="text" name="position" placeholder="VD: Frontend Developer" required>
        </div>

        <div>
          <label>Vai trò hệ thống</label>
          <select name="role">
            <option value="staff">staff</option>
            <option value="manager">manager</option>
            <option value="admin">admin</option>
          </select>
        </div>

        <div style="grid-column:1/-1;display:flex;gap:8px;justify-content:flex-start">
          <button class="btn btn-asfy" type="submit">Lưu nhân sự</button>
          <button class="btn" type="button" onclick="closeEmployeeModal()">Hủy</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
function openEmployeeModal(){ document.getElementById('employeeModal').style.display='block'; }
function closeEmployeeModal(){ document.getElementById('employeeModal').style.display='none'; }
</script>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
