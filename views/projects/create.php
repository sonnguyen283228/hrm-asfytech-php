<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="card" style="max-width:760px">
  <h2>Tạo dự án mới</h2>
  <?php if (!empty($_SESSION['error'])): ?>
    <p style="color:#d73a49"><?= htmlspecialchars($_SESSION['error']) ?></p>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>

  <form method="post" action="/projects/create">
    <label>Tên dự án</label>
    <input type="text" name="name" required>

    <label>Ngày bắt đầu</label>
    <input type="date" name="start_date" required>

    <label>Thời gian triển khai dự kiến (tháng)</label>
    <input type="number" min="1" name="duration_months" required>

    <label>Mô tả dự án</label>
    <input type="text" name="description" placeholder="Mô tả ngắn về dự án">

    <button class="btn btn-primary" type="submit">Tạo dự án</button>
    <a class="btn" href="/projects">Quay lại</a>
  </form>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
