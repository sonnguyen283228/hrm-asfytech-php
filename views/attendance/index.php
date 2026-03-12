<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="dashboard-shell">
  <?php $activePage='home'; require __DIR__ . '/../layouts/sidebar.php'; ?>

  <section class="mainpanel p-4">
    <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-2">
      <div>
        <h2 class="mb-1 text-900">Tổng quan Hệ thống</h2>
        <div class="text-700 fw-semi-bold">Cập nhật lúc <?= date('H:i d/m/Y') ?></div>
      </div>
      <div class="text-800 fw-bold bg-white px-3 py-2 border rounded-3 shadow-sm">Hôm nay: <?= htmlspecialchars($today) ?></div>
    </div>

    <!-- KPI Cards -->
    <div class="row g-3 mb-4">
      <div class="col-12 col-sm-6 col-md-3">
        <div class="card h-100 border-0 shadow-sm">
          <div class="card-body d-flex flex-column justify-content-between">
            <div class="d-flex align-items-center mb-3">
              <div class="icon-item shadow-sm text-primary bg-primary-100 me-2" style="width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center"><span data-feather="users" style="width:16px;height:16px"></span></div>
              <h6 class="mb-0 text-700">Tổng nhân sự</h6>
            </div>
            <h3 class="mb-0 text-900"><?= (int)($stats['employees'] ?? 0) ?></h3>
          </div>
        </div>
      </div>
      
      <div class="col-12 col-sm-6 col-md-3">
        <div class="card h-100 border-0 shadow-sm">
          <div class="card-body d-flex flex-column justify-content-between">
            <div class="d-flex align-items-center mb-3">
              <div class="icon-item shadow-sm text-info bg-info-100 me-2" style="width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center"><span data-feather="briefcase" style="width:16px;height:16px"></span></div>
              <h6 class="mb-0 text-700">Tổng dự án</h6>
            </div>
            <h3 class="mb-0 text-900"><?= (int)($stats['projects_total'] ?? 0) ?></h3>
          </div>
        </div>
      </div>
      
      <div class="col-12 col-sm-6 col-md-3">
        <div class="card h-100 border-0 shadow-sm">
          <div class="card-body d-flex flex-column justify-content-between">
            <div class="d-flex align-items-center mb-3">
              <div class="icon-item shadow-sm text-success bg-success-100 me-2" style="width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center"><span data-feather="play-circle" style="width:16px;height:16px"></span></div>
              <h6 class="mb-0 text-700">Đang triển khai</h6>
            </div>
            <h3 class="mb-0 text-success"><?= (int)($stats['projects_in_progress'] ?? 0) ?></h3>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-md-3">
        <div class="card h-100 border-0 shadow-sm">
          <div class="card-body d-flex flex-column justify-content-between">
            <div class="d-flex align-items-center mb-3">
              <div class="icon-item shadow-sm text-warning bg-warning-100 me-2" style="width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center"><span data-feather="check-circle" style="width:16px;height:16px"></span></div>
              <h6 class="mb-0 text-700">Hoàn thành</h6>
            </div>
            <h3 class="mb-0 text-warning"><?= (int)($stats['projects_done'] ?? 0) ?></h3>
          </div>
        </div>
      </div>
    </div>

    <div class="row g-3">
        <!-- Project Progress Chart -->
        <div class="col-12 col-lg-8">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-4 text-900 border-bottom pb-2">Tiến độ dự án đang triển khai</h5>
                    <div style="height: 300px; display: flex; align-items: center; justify-content: center; background: #f8f9fa; border-radius: 8px; border: 1px dashed #e2e8f0; color: #94a3b8">
                        (Biểu đồ tỷ lệ hoàn thành dự án)
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="col-12 col-lg-4">
            <div class="card h-100 border-0 shadow-sm bg-light">
                <div class="card-body">
                    <h5 class="card-title mb-4 text-900 border-bottom pb-2">Lối tắt thao tác</h5>
                    <div class="d-grid gap-2 mt-3">
                        <?php $u = auth_user(); ?>
                        
                        <!-- Chấm công (cho mọi user) -->
                        <div class="d-flex gap-2 mb-2">
                           <form method="post" action="/attendance/check-in" class="flex-grow-1"><button class="btn btn-primary w-100 shadow-sm" type="submit"><span data-feather="log-in" class="me-2"></span>Check-in</button></form>
                           <form method="post" action="/attendance/check-out" class="flex-grow-1"><button class="btn btn-outline-danger w-100 shadow-sm bg-white" type="submit"><span data-feather="log-out" class="me-2"></span>Check-out</button></form>
                        </div>
                        
                        <!-- Links (phân quyền) -->
                        <?php if ($u && in_array((string)$u['role'], ['admin', 'manager'])): ?>
                        <a class="btn btn-subtle-secondary text-start fw-semi-bold bg-white shadow-sm" href="/employees"><span data-feather="users" class="me-3 text-primary"></span>Quản lý nhân sự</a>
                        <?php endif; ?>
                        
                        <a class="btn btn-subtle-secondary text-start fw-semi-bold bg-white shadow-sm" href="/projects"><span data-feather="briefcase" class="me-3 text-info"></span>Quản lý dự án</a>
                        
                        <?php if ($u && (string)$u['role'] === 'admin'): ?>
                        <a class="btn btn-subtle-secondary text-start fw-semi-bold bg-white shadow-sm" href="/attendance/reports"><span data-feather="file-text" class="me-3 text-warning"></span>Báo cáo chấm công</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

  </section>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
