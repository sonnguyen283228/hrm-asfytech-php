<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="card-modern" style="max-width:980px;margin:0 auto;">
  <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:10px">
    <h2 style="margin:0">Tạo dự án mới</h2>
    <a class="btn" href="/projects">← Quay lại danh sách</a>
  </div>

  <?php if (!empty($_SESSION['error'])): ?>
    <p style="color:#d73a49;font-weight:600"><?= htmlspecialchars($_SESSION['error']) ?></p>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>

  <form method="post" action="/projects/create">
    <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px;align-items:end">
      <div>
        <label>Tên dự án</label>
        <input type="text" name="name" required placeholder="VD: HRM ASFY 2026">
      </div>

      <div>
        <label>Ngày bắt đầu</label>
        <input type="date" name="start_date" required>
      </div>

      <div style="grid-column:1/-1">
        <label>Mô tả dự án</label>
        <textarea name="description" rows="3" style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:10px" placeholder="Mô tả ngắn về mục tiêu và phạm vi dự án"></textarea>
      </div>

      <div style="grid-column:1/-1;display:flex;gap:8px;flex-wrap:wrap">
        <button class="btn btn-asfy" type="submit">Tạo dự án</button>
        <a class="btn" href="/projects">Hủy</a>
      </div>
    </div>
  </form>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
