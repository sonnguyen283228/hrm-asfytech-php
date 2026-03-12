<?php $activePage='reports'; require __DIR__ . '/../layouts/header.php'; ?>

<div class="px-sm-4 px-md-5 mt-4">
    <div class="mb-4">
        <h2 class="mb-1 text-900">Báo cáo chấm công theo tháng</h2>
        <p class="text-700 mb-0">Theo dõi thông tin chấm công chi tiết của tất cả nhân sự</p>
    </div>
    
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">

  <form method="get" action="/attendance/reports" style="display:flex;gap:8px;align-items:end;flex-wrap:wrap">
    <div>
      <label>Tháng</label>
      <input type="month" name="month" value="<?= htmlspecialchars($month) ?>" required>
    </div>
    <div>
      <button class="btn btn-primary" type="submit">Xem báo cáo</button>
      <a class="btn" href="/attendance/export/excel?month=<?= urlencode($month) ?>">Xuất Excel</a>
      <a class="btn" target="_blank" href="/attendance/export/pdf?month=<?= urlencode($month) ?>">Xuất PDF</a>
    </div>
  </form>

  <div class="table-responsive mt-4">
  <table class="table table-sm fs--1 mb-0">
    <thead>
      <tr>
        <th style="text-align:left;border-bottom:1px solid #ddd;padding:8px">Họ tên</th>
        <th style="text-align:left;border-bottom:1px solid #ddd;padding:8px">Email</th>
        <th style="text-align:left;border-bottom:1px solid #ddd;padding:8px">Ngày có mặt</th>
        <th style="text-align:left;border-bottom:1px solid #ddd;padding:8px">Tổng giờ làm</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
      <tr>
        <td style="padding:8px;border-bottom:1px solid #f1f1f1"><?= htmlspecialchars($r['full_name']) ?></td>
        <td style="padding:8px;border-bottom:1px solid #f1f1f1"><?= htmlspecialchars($r['email']) ?></td>
        <td style="padding:8px;border-bottom:1px solid #f1f1f1"><?= (int)$r['present_days'] ?></td>
        <td style="padding:8px;border-bottom:1px solid #f1f1f1"><?= round(((int)$r['worked_minutes'])/60, 2) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  </div>
</div>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
