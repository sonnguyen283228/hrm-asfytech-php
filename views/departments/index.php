<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="dashboard-shell">
  <?php $activePage='departments'; require __DIR__ . '/../layouts/sidebar.php'; ?>
  <section class="mainpanel">
    <div class="topline">
      <h2 style="margin:0">Quản lý phòng ban</h2>
      <button class="btn btn-asfy" type="button" onclick="openDeptModal()">+ Thêm phòng ban</button>
    </div>

    <?php if (!empty($_SESSION['success'])): ?><p style="color:#0a7d34;font-weight:600"><?= htmlspecialchars($_SESSION['success']) ?></p><?php unset($_SESSION['success']); endif; ?>
    <?php if (!empty($_SESSION['error'])): ?><p style="color:#d73a49;font-weight:600"><?= htmlspecialchars($_SESSION['error']) ?></p><?php unset($_SESSION['error']); endif; ?>

    <div class="table-wrap">
      <table class="table-clean" style="width:100%;min-width:760px">
        <thead><tr><th>ID</th><th>Tên phòng ban</th><th>Mô tả</th><th>Số nhân sự</th><th>Thao tác</th></tr></thead>
        <tbody>
        <?php foreach ($departments as $d): ?>
          <tr>
            <td><?= (int)$d['id'] ?></td>
            <td><?= htmlspecialchars($d['name']) ?></td>
            <td><?= htmlspecialchars($d['description'] ?? '--') ?></td>
            <td><?= (int)$d['total_users'] ?></td>
            <td>
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
  </section>
</div>

<div id="deptModal" class="modal-mask" style="display:none">
  <div class="modal-panel" style="max-width:620px">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
      <h3 style="margin:0">Thêm phòng ban</h3>
      <button class="btn" type="button" onclick="closeDeptModal()">Đóng</button>
    </div>
    <form method="post" action="/departments/create">
      <label>Tên phòng ban</label>
      <input type="text" name="name" required>
      <label>Mô tả</label>
      <input type="text" name="description">
      <button class="btn btn-asfy" type="submit">Lưu phòng ban</button>
    </form>
  </div>
</div>
<script>
function openDeptModal(){document.getElementById('deptModal').style.display='block';}
function closeDeptModal(){document.getElementById('deptModal').style.display='none';}
</script>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
