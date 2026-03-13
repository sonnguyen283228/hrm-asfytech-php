<?php $activePage='home'; require __DIR__ . '/../layouts/header.php'; ?>

<div class="pb-5 pb-lg-7 px-sm-4 px-md-5 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2 fade-in">
      <div>
        <h2 class="mb-2 text-1100 fw-800">Tổng quan Hệ thống</h2>
        <p class="text-700 mb-0">Cập nhật lúc <?= date('H:i d/m/Y') ?></p>
      </div>
      <div class="d-flex align-items-center gap-2">
          <div class="bg-white px-4 py-2 border border-300 rounded-pill shadow-sm d-flex align-items-center gap-2 text-800 fw-semi-bold">
              <span data-feather="calendar" style="width: 16px; height: 16px;"></span>
              Hôm nay: <?= htmlspecialchars($today ?? date('Y-m-d')) ?>
          </div>
      </div>
    </div>

    <!-- KPI Cards -->
    <div class="row g-3 mb-5">
      <!-- Card 1 -->
      <div class="col-12 col-sm-6 col-xl-3 fade-in fade-in-delay-1">
        <div class="card h-100 shadow-sm border border-200 rounded-4 overflow-hidden kpi-primary border-accent-primary card-hover">
          <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <p class="text-600 fw-semi-bold mb-1 fs--1 text-uppercase">Tổng nhân sự</p>
                <h2 class="text-1100 mb-0 fw-bold stat-number"><?= (int)($stats['employees'] ?? 0) ?></h2>
              </div>
              <div class="bg-primary-100 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                <span data-feather="users" style="width: 24px; height: 24px;"></span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Card 2 -->
      <div class="col-12 col-sm-6 col-xl-3 fade-in fade-in-delay-2">
        <div class="card h-100 shadow-sm border border-200 rounded-4 overflow-hidden kpi-info border-accent-info card-hover">
          <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <p class="text-600 fw-semi-bold mb-1 fs--1 text-uppercase">Tổng dự án</p>
                <h2 class="text-1100 mb-0 fw-bold stat-number"><?= (int)($stats['projects_total'] ?? 0) ?></h2>
              </div>
              <div class="bg-info-100 text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                <span data-feather="briefcase" style="width: 24px; height: 24px;"></span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Card 3 -->
      <div class="col-12 col-sm-6 col-xl-3 fade-in fade-in-delay-3">
        <div class="card h-100 shadow-sm border border-200 rounded-4 overflow-hidden kpi-success border-accent-success card-hover">
          <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <p class="text-600 fw-semi-bold mb-1 fs--1 text-uppercase">Đang triển khai</p>
                <h2 class="text-success mb-0 fw-bold stat-number"><?= (int)($stats['projects_in_progress'] ?? 0) ?></h2>
              </div>
              <div class="bg-success-100 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                <span data-feather="play-circle" style="width: 24px; height: 24px;"></span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Card 4 -->
      <div class="col-12 col-sm-6 col-xl-3 fade-in fade-in-delay-4">
        <div class="card h-100 shadow-sm border border-200 rounded-4 overflow-hidden kpi-warning border-accent-warning card-hover">
          <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <p class="text-600 fw-semi-bold mb-1 fs--1 text-uppercase">Hoàn thành</p>
                <h2 class="text-warning mb-0 fw-bold stat-number"><?= (int)($stats['projects_done'] ?? 0) ?></h2>
              </div>
              <div class="bg-warning-100 text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                <span data-feather="check-circle" style="width: 24px; height: 24px;"></span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row g-4 mb-4 flex-xl-row-reverse">
        
        <!-- Project Progress Chart Panel -->
        <div class="col-12 col-xl-8">
            <div class="card shadow-sm border border-200 rounded-4 h-100 bg-white">
                <div class="card-header bg-white border-bottom border-200 p-4">
                    <h5 class="text-1000 mb-0 fw-bold d-flex align-items-center gap-2">
                        <span data-feather="bar-chart-2" class="text-primary" style="width: 20px; height: 20px;"></span> 
                        Tiến độ dự án đang triển khai
                    </h5>
                </div>
                <div class="card-body p-4 d-flex flex-column">
                    <div class="flex-grow-1 bg-light rounded-3 d-flex flex-column align-items-center justify-content-center p-5 text-center" style="min-height: 400px; border: 1px dashed #cbd5e1;">
                         <div class="bg-white p-3 rounded-circle shadow-sm mb-3">
                             <span data-feather="pie-chart" class="text-500" style="width: 48px; height: 48px;"></span>
                         </div>
                         <h5 class="text-700 fw-semi-bold mb-2">Chưa có dữ liệu biểu đồ</h5>
                         <p class="text-500 mb-0" style="max-width: 300px;">Hệ thống sẽ tổng hợp và hiển thị trực quan tiến độ dự án tại đây khi có dữ liệu.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Panel -->
        <div class="col-12 col-xl-4 d-flex flex-column gap-4">
            <!-- Chấm công Card -->
            <div class="card shadow-sm border border-200 rounded-4 bg-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4 pb-3 border-bottom border-200">
                        <div class="bg-primary-100 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 44px; height: 44px;">
                            <span data-feather="clock" style="width: 22px; height: 22px;"></span>
                        </div>
                        <h5 class="text-1000 mb-0 fw-bold">Chấm công hôm nay</h5>
                    </div>
                    
                    <div class="d-grid gap-3">
                        <form method="post" action="/attendance/check-in" class="m-0">
                            <button class="btn btn-primary btn-lg w-100 d-flex align-items-center justify-content-center gap-2 rounded-3 shadow-sm" type="submit">
                                <span data-feather="log-in" style="width: 20px; height: 20px;"></span>
                                <span class="fw-bold">Check-in vào ca</span>
                            </button>
                        </form>
                        <form method="post" action="/attendance/check-out" class="m-0">
                            <button class="btn btn-outline-danger bg-white btn-lg w-100 d-flex align-items-center justify-content-center gap-2 rounded-3 shadow-sm" type="submit">
                                <span data-feather="log-out" style="width: 20px; height: 20px;"></span>
                                <span class="fw-bold">Check-out tan ca</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Quick Links Card -->
            <div class="card shadow-sm border border-200 rounded-4 flex-grow-1 bg-white">
                <div class="card-body p-4">
                    <h6 class="text-800 mb-4 fw-bold text-uppercase fs--1 ls-1">Lối tắt thao tác</h6>
                    <div class="d-flex flex-column gap-3">
                        <?php $u = auth_user(); ?>
                        
                        <?php if ($u && in_array(strtolower((string)$u['role']), ['admin', 'manager'])): ?>
                        <a class="text-decoration-none d-flex align-items-center p-3 border border-200 rounded-3 bg-light quick-link-card" href="/employees">
                            <div class="bg-primary-100 text-primary rounded-2 d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 40px; height: 40px;">
                                <span data-feather="users" style="width: 20px; height: 20px;"></span>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 text-900 fw-bold">Quản lý nhân sự</h6>
                            </div>
                            <div class="text-500"><span data-feather="chevron-right"></span></div>
                        </a>
                        <?php endif; ?>
                        
                        <a class="text-decoration-none d-flex align-items-center p-3 border border-200 rounded-3 bg-light quick-link-card" href="/projects">
                            <div class="bg-info-100 text-info rounded-2 d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 40px; height: 40px;">
                                <span data-feather="briefcase" style="width: 20px; height: 20px;"></span>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 text-900 fw-bold">Quản lý dự án</h6>
                            </div>
                            <div class="text-500"><span data-feather="chevron-right"></span></div>
                        </a>
                        
                        <?php if ($u && (string)$u['role'] === 'admin'): ?>
                        <a class="text-decoration-none d-flex align-items-center p-3 border border-200 rounded-3 bg-light quick-link-card" href="/attendance/reports">
                            <div class="bg-warning-100 text-warning rounded-2 d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 40px; height: 40px;">
                                <span data-feather="file-text" style="width: 20px; height: 20px;"></span>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 text-900 fw-bold">Báo cáo chấm công</h6>
                            </div>
                            <div class="text-500"><span data-feather="chevron-right"></span></div>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
