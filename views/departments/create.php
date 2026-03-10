<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="card" style="max-width:680px">
  <h2>Thêm phòng ban</h2>

  <?php if (!empty($_SESSION['error'])): ?>
    <p style="color:#d73a49"><?= htmlspecialchars($_SESSION['error']) ?></p>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>

  <form method="post" action="/departments/create">
    <label>Tên phòng ban</label>
    <input type="text" name="name" required placeholder="VD: Marketing">

    <button class="btn btn-primary" type="submit">Lưu phòng ban</button>
    <a class="btn" href="/departments">Quay lại</a>
  </form>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
