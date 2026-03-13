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
            <a class="btn btn-primary" href="/projects/create"><span data-feather="plus" class="me-2"></span>Tạo dự án mới</a>
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
                <th class="sort pe-1 align-middle white-space-nowrap pb-2 pt-3" data-sort="name">Tên dự án</th>
                <th class="sort pe-1 align-middle white-space-nowrap pb-2 pt-3" data-sort="status">Trạng thái</th>
                <th class="sort pe-1 align-middle white-space-nowrap pb-2 pt-3 text-center" data-sort="progress">Tiến độ</th>
                <th class="sort pe-1 align-middle white-space-nowrap pb-2 pt-3" data-sort="start_date">Ngày bắt đầu</th>
                <th class="sort pe-1 align-middle white-space-nowrap pb-2 pt-3 text-center" data-sort="duration">Thời gian (Tháng)</th>
                <th class="sort pe-1 align-middle white-space-nowrap pb-2 pt-3 text-center" data-sort="members">Số module</th>
                <th class="text-end align-middle pb-2 pt-3">Thao tác</th>
              </tr>
            </thead>
            <tbody class="list" id="table-projects-body">
              <?php foreach ($projects as $p): ?>
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
                <td class="align-middle white-space-nowrap text-end py-3">
                  <?php if (in_array(strtolower((string)(auth_user()['role'] ?? '')), ['admin', 'manager'])): ?>
                  <div class="font-sans-serif btn-reveal-trigger position-static">
                    <button class="btn btn-sm dropdown-toggle dropdown-caret-none transition-none btn-reveal fs--2" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2"></span></button>
                    <div class="dropdown-menu dropdown-menu-end py-2">
                        <a class="dropdown-item" href="/projects/view?id=<?= (int)$p['id'] ?>">Xem chi tiết & Cập nhật Task</a>
                        <a class="dropdown-item cursor-pointer" onclick='editProject(<?= json_encode([
                          "id" => $p["id"], 
                          "name" => $p["name"], 
                          "status" => $p["status"] ?? "Kế hoạch",
                          "start_date" => $p["start_date"] ?? ""
                        ]) ?>)' data-bs-toggle="modal" data-bs-target="#editProjectModal">Sửa Tên & Trạng Thái</a>
                        <div class="dropdown-divider"></div>
                        <form method="post" action="/projects/delete" onsubmit="return confirm('CẢNH BÁO: Bạn có chắc chắn muốn XÓA DỰ ÁN NÀY? Toàn bộ Dữ liệu tiến độ, thành viên và các module sẽ bị xóa vĩnh viễn!')">
                            <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                            <button class="dropdown-item text-danger" type="submit">Hủy & Xóa Dự án</button>
                        </form>
                    </div>
                  </div>
                  <?php else: ?>
                  <a class="btn btn-sm btn-phoenix-primary" href="/projects/view?id=<?= (int)$p['id'] ?>">Xem chi tiết</a>
                  <?php endif; ?>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        
        <div class="row align-items-center justify-content-between pe-0 fs--1 mt-3">
          <div class="col-auto d-flex">
            <p class="mb-0 d-none d-sm-block me-3 fw-semi-bold text-900" data-list-info="data-list-info"></p>
          </div>
          <div class="col-auto d-flex">
            <button class="page-link" data-list-pagination="prev"><span class="fas fa-chevron-left"></span></button>
            <ul class="mb-0 pagination"></ul>
            <button class="page-link pe-0" data-list-pagination="next"><span class="fas fa-chevron-right"></span></button>
          </div>
        </div>

      </div>
    </div>
</div>

<!-- Modal Sửa Dự Án -->
<div class="modal fade" id="editProjectModal" tabindex="-1" aria-labelledby="editProjectModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-light border-bottom border-200">
        <h5 class="modal-title text-1000" id="editProjectModalLabel">Cập Nhật Dự Án</h5>
        <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times fs--1"></span></button>
      </div>
      <form method="post" action="/projects/edit" class="needs-validation" novalidate>
        <input type="hidden" id="edit_proj_id" name="id" value="" />
        <div class="modal-body p-4 bg-white">
          <div class="mb-3">
            <label class="form-label">Tên dự án <span class="text-danger">*</span></label>
            <input class="form-control" id="edit_proj_name" name="name" type="text" required />
            <div class="invalid-feedback">Vui lòng nhập tên dự án.</div>
          </div>
          <div class="mb-3">
            <label class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
            <input class="form-control" id="edit_proj_start" name="start_date" type="date" required />
            <div class="invalid-feedback">Ngày bắt đầu không được để trống.</div>
          </div>
          <div class="mb-0">
            <label class="form-label">Trạng thái tổng quan</label>
            <select class="form-select" id="edit_proj_status" name="status">
              <option value="Kế hoạch">Kế hoạch</option>
              <option value="Đang triển khai">Đang triển khai</option>
              <option value="Tạm dừng">Tạm dừng</option>
              <option value="Hoàn thành">Hoàn thành</option>
            </select>
          </div>
        </div>
        <div class="modal-footer border-top-0 px-4 pb-4">
          <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Hủy</button>
          <button class="btn btn-primary px-4" type="submit">Lưu Thay Đổi</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  (function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }
        form.classList.add('was-validated')
      }, false)
    });

    // Auto dismiss alerts
    setTimeout(function() {
      document.querySelectorAll('.auto-dismiss-alert').forEach(function(alertNode) {
        var alert = new bootstrap.Alert(alertNode)
        alert.close()
      })
    }, 4000);
  })();

  function editProject(data) {
      document.getElementById('edit_proj_id').value = data.id || '';
      document.getElementById('edit_proj_name').value = data.name || '';
      if(data.start_date) {
         // Cắt lấy format YYYY-MM-DD
         document.getElementById('edit_proj_start').value = data.start_date.substring(0, 10);
      } else {
         document.getElementById('edit_proj_start').value = '';
      }
      document.getElementById('edit_proj_status').value = data.status || 'Kế hoạch';
  }
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
