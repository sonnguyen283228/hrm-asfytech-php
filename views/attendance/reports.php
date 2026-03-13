<?php $activePage='reports'; require __DIR__ . '/../layouts/header.php'; ?>

<div class="px-sm-4 px-md-5 mt-4">
  <!-- Page Header -->
  <div class="page-header mb-4 fade-in">
    <div class="row align-items-center justify-content-between g-3">
      <div class="col-auto flex-grow-1">
        <h2 class="mb-0 text-900">Báo cáo chấm công</h2>
        <p class="text-700 mb-0">Theo dõi thông tin chấm công chi tiết của tất cả nhân sự theo tháng.</p>
      </div>
    </div>
  </div>

  <!-- Filter Card -->
  <div class="card shadow-sm border-0 mb-4 fade-in fade-in-delay-1">
    <div class="card-body p-4">
      <form method="get" action="/attendance/reports" class="filter-bar">
        <div>
          <label class="form-label fs--1 fw-bold mb-1">Tháng báo cáo</label>
          <input class="form-control form-control-sm" type="month" name="month" value="<?= htmlspecialchars($month) ?>" required style="min-width:180px" />
        </div>
        <div class="d-flex align-items-end gap-2" style="padding-top: 22px;">
          <button class="btn btn-primary btn-sm" type="submit">
            <span data-feather="search" class="me-1" style="width:14px;height:14px"></span>Xem báo cáo
          </button>
          <a class="btn btn-outline-success btn-sm" href="/attendance/export/excel?month=<?= urlencode($month) ?>">
            <span data-feather="file-text" class="me-1" style="width:14px;height:14px"></span>Xuất Excel
          </a>
          <a class="btn btn-outline-danger btn-sm" target="_blank" href="/attendance/export/pdf?month=<?= urlencode($month) ?>">
            <span data-feather="printer" class="me-1" style="width:14px;height:14px"></span>Xuất PDF
          </a>
        </div>
      </form>
    </div>
  </div>

  <!-- Data Table -->
  <div class="card shadow-sm border-0 mb-3 fade-in fade-in-delay-2">
    <div class="card-body p-3">
      <div class="table-responsive scrollbar">
        <table class="table table-sm table-hrm fs--1 mb-0 overflow-hidden text-nowrap">
          <thead>
            <tr>
              <th class="align-middle white-space-nowrap pb-2 pt-3 text-center" style="width:50px">STT</th>
              <th class="pe-1 align-middle white-space-nowrap pb-2 pt-3">Họ tên</th>
              <th class="pe-1 align-middle white-space-nowrap pb-2 pt-3">Email</th>
              <th class="pe-1 align-middle white-space-nowrap pb-2 pt-3 text-center">Ngày có mặt</th>
              <th class="pe-1 align-middle white-space-nowrap pb-2 pt-3 text-center">Tổng giờ làm</th>
            </tr>
          </thead>
          <tbody class="list">
            <?php $stt = 1; foreach ($rows as $r): ?>
            <tr class="hover-actions-trigger position-static">
              <td class="align-middle white-space-nowrap py-2 text-center fw-bold text-700"><?= $stt++ ?></td>
              <td class="align-middle white-space-nowrap py-3">
                <div class="d-flex align-items-center gap-2">
                  <div class="avatar avatar-s">
                    <div class="avatar-name rounded-circle"><span><?= mb_substr($r['full_name'], 0, 2) ?></span></div>
                  </div>
                  <h6 class="mb-0 text-900 fw-semi-bold"><?= htmlspecialchars($r['full_name']) ?></h6>
                </div>
              </td>
              <td class="align-middle white-space-nowrap py-3">
                <a class="text-500 fs--1 fw-semi-bold" href="mailto:<?= htmlspecialchars($r['email']) ?>"><?= htmlspecialchars($r['email']) ?></a>
              </td>
              <td class="align-middle white-space-nowrap py-3 text-center">
                <span class="badge badge-phoenix fs--2 badge-phoenix-primary"><span class="badge-label"><?= (int)$r['present_days'] ?> ngày</span></span>
              </td>
              <td class="align-middle white-space-nowrap py-3 text-center">
                <?php $hours = round(((int)$r['worked_minutes'])/60, 1); ?>
                <span class="fw-bold text-900"><?= $hours ?></span>
                <span class="text-500 fs--2">giờ</span>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($rows)): ?>
            <tr>
              <td colspan="5" class="text-center py-5">
                <div class="empty-state">
                  <div class="empty-state-icon"><span data-feather="inbox" class="text-400" style="width:28px;height:28px"></span></div>
                  <h6 class="text-700 fw-semi-bold mb-1">Không có dữ liệu</h6>
                  <p class="text-500 mb-0 fs--1">Chọn tháng và nhấn "Xem báo cáo" để hiển thị dữ liệu chấm công.</p>
                </div>
              </td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
