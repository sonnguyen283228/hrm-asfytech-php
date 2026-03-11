<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="card">
  <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;flex-wrap:wrap">
    <h2>Quản lý phòng ban</h2>
    <button class="btn btn-primary" type="button" onclick="openDeptModal()">+ Thêm phòng ban</button>
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
        <th style="text-align:left;border-bottom:1px solid #ddd;padding:8px">ID</th>
        <th style="text-align:left;border-bottom:1px solid #ddd;padding:8px">Tên phòng ban</th>
        <th style="text-align:left;border-bottom:1px solid #ddd;padding:8px">Mô tả</th>
        <th style="text-align:left;border-bottom:1px solid #ddd;padding:8px">Số nhân sự</th>
        <th style="text-align:left;border-bottom:1px solid #ddd;padding:8px">Thao tác</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($departments as $d): ?>
      <tr>
        <td style="padding:8px;border-bottom:1px solid #f1f1f1"><?= (int)$d['id'] ?></td>
        <td style="padding:8px;border-bottom:1px solid #f1f1f1"><?= htmlspecialchars($d['name']) ?></td>
        <td style="padding:8px;border-bottom:1px solid #f1f1f1"><?= htmlspecialchars($d['description'] ?? '--') ?></td>
        <td style="padding:8px;border-bottom:1px solid #f1f1f1"><?= (int)$d['total_users'] ?></td>
        <td style="padding:8px;border-bottom:1px solid #f1f1f1">
          <a class="btn" href="/departments/edit?id=<?= (int)$d['id'] ?>">Sửa</a>
          <form method="post" action="/departments/delete" style="display:inline" onsubmit="return confirm('Xóa phòng ban này?')">
            <input type="hidden" name="id" value="<?= (int)$d['id'] ?>">
            <button class="btn btn-danger" type="submit">Xóa</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<div id="deptModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:9999;">
  <div style="max-width:620px;margin:60px auto;background:#fff;border-radius:12px;padding:18px;max-height:90vh;overflow:auto">
    <div style="display:flex;justify-content:space-between;align-items:center">
      <h3 style="margin:0">Thêm phòng ban</h3>
      <button class="btn" type="button" onclick="closeDeptModal()">Đóng</button>
    </div>
    <form method="post" action="/departments/create">
      <label>Tên phòng ban</label>
      <input type="text" name="name" required placeholder="VD: Marketing">

      <label>Mô tả</label>
      <input type="text" name="description" placeholder="Mô tả chức năng phòng ban">

      <button class="btn btn-primary" type="submit">Lưu phòng ban</button>
    </form>
  </div>
</div>

<script>
function openDeptModal(){ document.getElementById('deptModal').style.display='block'; }
function closeDeptModal(){ document.getElementById('deptModal').style.display='none'; }
</script>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
