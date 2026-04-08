<?php $activePage='projects'; require __DIR__ . '/../layouts/header.php'; ?>

<div class="px-sm-4 px-md-5 mt-4">
  <div class="mb-4">
      <div class="row align-items-center justify-content-between g-3">
        <div class="col-auto flex-grow-1">
          <h2 class="mb-0 text-900">Quản lý dự án</h2>
          <p class="text-700 mb-0">Theo dõi tiến độ và thông tin tổng quan của tất cả dự án.</p>
        </div>
        <div class="col-auto">
          <div class="d-flex align-items-center gap-2">
            <?php if (in_array(strtolower((string)(auth_user()['role'] ?? '')), ['admin', 'manager'])): ?>
            <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#createProjectModal"><span data-feather="plus" class="me-2"></span>Tạo dự án mới</button>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>



    <div class="card shadow-sm border-0 mb-3" data-list='{"valueNames":["name","status","start_date","duration","progress","members"],"page":10,"pagination":true}'>
      <div class="card-body p-3">
        
        <div class="row align-items-center justify-content-between g-3 mb-4">
          <div class="col-12 col-md-auto d-flex align-items-center">
            <div class="search-box me-2">
              <form class="position-relative" data-bs-toggle="search" data-bs-display="static">
                <input class="form-control form-control-sm search-input search" type="search" placeholder="Tìm tên dự án..." aria-label="Search" />
                <span class="fas fa-search search-box-icon"></span>
              </form>
            </div>
          </div>
          <div class="col-12 col-md-auto d-flex gap-2">
             <select class="form-select form-select-sm w-auto" aria-label="Lọc trạng thái">
                 <option value="">Tất cả trạng thái</option>
                 <option value="Kế hoạch">Kế hoạch</option>
                 <option value="Đang triển khai">Đang triển khai</option>
                 <option value="Tạm dừng">Tạm dừng</option>
                 <option value="Hoàn thành">Hoàn thành</option>
             </select>
          </div>
        </div>

        <div class="table-responsive scrollbar">
          <table class="table table-sm table-hrm fs--1 mb-0 overflow-hidden text-nowrap">
            <thead>
              <tr>
                <th class="align-middle white-space-nowrap pb-2 pt-3 text-center" style="width:50px">STT</th>
                <th class="pe-1 align-middle white-space-nowrap pb-2 pt-3">Tên dự án</th>
                <th class="pe-1 align-middle white-space-nowrap pb-2 pt-3">Trạng thái</th>
                <th class="pe-1 align-middle white-space-nowrap pb-2 pt-3 text-center">Tiến độ</th>
                <th class="pe-1 align-middle white-space-nowrap pb-2 pt-3">Ngày bắt đầu</th>
                <th class="pe-1 align-middle white-space-nowrap pb-2 pt-3 text-center">Thời gian (Tháng)</th>
                <th class="pe-1 align-middle white-space-nowrap pb-2 pt-3 text-center">Số module</th>
                <th class="text-end align-middle pb-2 pt-3">Thao tác</th>
              </tr>
            </thead>
            <tbody class="list" id="table-projects-body">
              <?php $stt = 1; foreach ($projects as $p): ?>
              <?php 
                 $st = $p['status'] ?? 'Đang triển khai';
                 $st_class = 'badge-phoenix-primary';
                 if ($st === 'Hoàn thành') $st_class = 'badge-phoenix-success';
                 if ($st === 'Tạm dừng') $st_class = 'badge-phoenix-warning';
                 if ($st === 'Kế hoạch') $st_class = 'badge-phoenix-info';
                 $progress = round((float)$p['progress_avg']);
                 $prog_color = $progress < 30 ? 'bg-danger' : ($progress < 70 ? 'bg-primary' : 'bg-success');
              ?>
              <tr class="hover-actions-trigger btn-reveal-trigger position-static">
                <td class="align-middle white-space-nowrap py-2 text-center fw-bold text-700"><?= $stt++ ?></td>
                <td class="name align-middle white-space-nowrap py-3">
                    <a class="text-decoration-none fw-bold fs-0" href="/projects/view?id=<?= (int)$p['id'] ?>">
                        <?= htmlspecialchars($p['name']) ?>
                    </a>
                </td>
                <td class="status align-middle white-space-nowrap py-3">
                    <span class="badge badge-phoenix fs--2 <?= $st_class ?>"><span class="badge-label"><?= htmlspecialchars($st) ?></span></span>
                </td>
                <td class="progress align-middle white-space-nowrap py-3 text-center">
                    <div class="d-flex align-items-center justify-content-center gap-2">
                      <div class="progress" style="height: 6px; width: 60px;">
                        <div class="progress-bar <?= $prog_color ?> rounded-pill" role="progressbar" style="width: <?= $progress ?>%" aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                      <span class="text-700 fw-semi-bold"><?= $progress ?>%</span>
                    </div>
                </td>
                <td class="start_date align-middle white-space-nowrap py-3 text-700">
                    <span data-feather="calendar" class="me-2 text-500" style="width: 14px; height: 14px;"></span>
                    <?= htmlspecialchars(date('d/m/Y', strtotime($p['start_date']))) ?>
                </td>
                <td class="duration align-middle white-space-nowrap py-3 text-center fw-semi-bold">
                    <?= (float)($p['duration_months'] ?? 0) ?>
                </td>
                <td class="members align-middle white-space-nowrap py-3 text-center">
                    <div class="avatar-group cursor-pointer mx-auto">
                        <div class="avatar avatar-s" title="<?= (int)$p['total_modules'] ?> modules">
                           <div class="avatar-name rounded-circle border border-2 border-white bg-200 text-700"><span><?= (int)$p['total_modules'] ?></span></div>
                        </div>
                    </div>
                </td>
                <td class="align-middle white-space-nowrap text-end py-2">
                  <div class="d-flex justify-content-end gap-1">
                    <a class="btn btn-phoenix-secondary btn-icon btn-icon-xs btn-sm" title="Xem chi tiết" href="/projects/view?id=<?= (int)$p['id'] ?>">
                      <span data-feather="eye"></span>
                    </a>
                    <?php if (in_array(strtolower((string)(auth_user()['role'] ?? '')), ['admin', 'manager'])): ?>
                    <button class="btn btn-phoenix-primary btn-icon btn-icon-xs btn-sm" title="Sửa tên & trạng thái" onclick='editProject(<?= json_encode([
                        "id" => $p["id"],
                        "name" => $p["name"],
                        "status" => $p["status"] ?? "Kế hoạch",
                        "start_date" => $p["start_date"] ?? ""
                    ]) ?>); (function(){ var m = new bootstrap.Modal(document.getElementById("editProjectModal")); m.show(); })();'>
                      <span data-feather="edit"></span>
                    </button>
                    <form method="post" action="/projects/delete" class="mb-0 d-inline-block confirm-action" data-message="CẢNH BÁO: Xóa dự án này? Không thể hồi phục!">
                      <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                      <button type="submit" class="btn btn-phoenix-danger btn-icon btn-icon-xs btn-sm" title="Xóa dự án">
                        <span data-feather="trash-2"></span>
                      </button>
                    </form>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        
        <?php require __DIR__ . '/../components/list_pagination.php'; ?>
    </div>
</div>

<?php require __DIR__ . '/_modals.php'; ?>
<?php require __DIR__ . '/../components/common_scripts.php'; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
