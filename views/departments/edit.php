<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="card" style="max-width:680px">
  <h2>Cập nhật phòng ban</h2>
  <form method="post" action="/departments/edit">
    <input type="hidden" name="id" value="<?= (int)$department['id'] ?>">

    <label>Tên phòng ban</label>
    <input type="text" name="name" required value="<?= htmlspecialchars($department['name']) ?>">

    <button class="btn btn-primary" type="submit">Cập nhật</button>
    <a class="btn" href="/departments">Quay lại</a>
  </form>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
