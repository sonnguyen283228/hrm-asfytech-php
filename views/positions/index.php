<?php $activePage='positions'; require __DIR__ . '/../layouts/header.php'; ?>

<div class="px-sm-4 px-md-5 mt-4">
  <div class="mb-4">
      <div class="row align-items-center justify-content-between g-3">
        <div class="col-auto flex-grow-1">
          <h2 class="mb-0 text-900">Quản lý chức vụ</h2>
          <p class="text-700 mb-0">Quản lý danh sách các chức vụ, vị trí làm việc trong hệ thống.</p>
        </div>
        <div class="col-auto">
          <div class="d-flex align-items-center gap-2">
            <?php if (in_array(strtolower((string)(auth_user()['role'] ?? '')), ['admin', 'manager'])): ?>
            <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#positionModal"><span data-feather="plus" class="me-2"></span>Thêm chức vụ mới</button>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-soft-success d-flex align-items-center auto-dismiss-alert" role="alert">
      <span data-feather="check-circle" class="text-success fs-3 me-3"></span>
      <p class="mb-0 flex-1 fw-bold"><?= htmlspecialchars($_SESSION['success']) ?></p>
      <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['success']); endif; ?>
    
    <?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-soft-danger d-flex align-items-center auto-dismiss-alert" role="alert">
      <span data-feather="alert-circle" class="text-danger fs-3 me-3"></span>
      <p class="mb-0 flex-1 fw-bold"><?= htmlspecialchars($_SESSION['error']) ?></p>
      <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['error']); endif; ?>

    <div class="card shadow-sm border-0 mb-3" data-list='{"valueNames":["id","name","desc","members","status"],"page":10,"pagination":true}'>
      <div class="card-body p-3">
        
        <div class="row align-items-center justify-content-between g-3 mb-4">
          <div class="col-12 col-md-auto d-flex align-items-center">
            <div class="search-box">
              <form class="position-relative" data-bs-toggle="search" data-bs-display="static">
                <input class="form-control form-control-sm search-input search" type="search" placeholder="Tìm bằng tên chức vụ..." aria-label="Search" />
                <span class="fas fa-search search-box-icon"></span>
              </form>
            </div>
          </div>
        </div>

        <div class="table-responsive scrollbar">
          <table class="table table-sm fs--1 mb-0 overflow-hidden text-nowrap">
            <thead class="bg-200 text-900 border-bottom">
              <tr>
                <th class="sort pe-1 align-middle white-space-nowrap pb-2 pt-3" data-sort="id">ID</th>
                <th class="sort pe-1 align-middle white-space-nowrap pb-2 pt-3" data-sort="name">Tên chức vụ</th>
                <th class="sort pe-1 align-middle white-space-nowrap pb-2 pt-3" data-sort="desc">Mô tả</th>
                <th class="sort pe-1 align-middle white-space-nowrap pb-2 pt-3 text-center" data-sort="members">Số nhân sự</th>
                <th class="sort pe-1 align-middle white-space-nowrap pb-2 pt-3" data-sort="status">Trạng thái</th>
                <th class="text-end align-middle pb-2 pt-3">Thao tác</th>
              </tr>
            </thead>
            <tbody class="list" id="table-position-body">
            <?php foreach (($positions ?? []) as $p): ?>
              <tr class="hover-actions-trigger btn-reveal-trigger position-static">
                <td class="id align-middle white-space-nowrap py-3 fw-bold">#<?= (int)$p['id'] ?></td>
                <td class="name align-middle white-space-nowrap py-3">
                    <h6 class="mb-0 text-900 fw-semi-bold"><?= htmlspecialchars($p['name']) ?></h6>
                </td>
                <td class="desc align-middle py-3 text-700" style="min-width: 200px; max-width: 300px; white-space: normal;">
                    <?= htmlspecialchars($p['description'] ?? '--') ?>
                </td>
                <td class="members align-middle white-space-nowrap py-3 text-center">
                    <span class="badge badge-phoenix fs--2 badge-phoenix-primary"><span class="badge-label"><?= (int)($p['total_users'] ?? 0) ?></span></span>
                </td>
                <td class="status align-middle white-space-nowrap py-3">
                  <?php if ((int)($p['is_active'] ?? 1) === 1): ?>
                    <span class="badge badge-phoenix fs--2 badge-phoenix-success"><span class="badge-label">Hoạt động</span></span>
                  <?php else: ?>
                    <span class="badge badge-phoenix fs--2 badge-phoenix-secondary"><span class="badge-label">Ngừng hoạt động</span></span>
                  <?php endif; ?>
                </td>
                <td class="align-middle white-space-nowrap text-end py-3">
                  <?php if (in_array(strtolower((string)(auth_user()['role'] ?? '')), ['admin', 'manager'])): ?>
                  <div class="font-sans-serif btn-reveal-trigger position-static">
                    <button class="btn btn-sm dropdown-toggle dropdown-caret-none transition-none btn-reveal fs--2" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2"></span></button>
                    <div class="dropdown-menu dropdown-menu-end py-2">
                        <a class="dropdown-item cursor-pointer" onclick='editPosition(<?= json_encode([
                          "id" => $p["id"], 
                          "name" => $p["name"], 
                          "description" => $p["description"] ?? "",
                          "is_active" => $p["is_active"] ?? 1
                        ]) ?>)' data-bs-toggle="modal" data-bs-target="#editPositionModal">Chỉnh sửa</a>
                        <div class="dropdown-divider"></div>
                        <form method="post" action="/positions/toggle" onsubmit="return confirm('Bạn muốn thay đổi trạng thái hoạt động của chức vụ này?');">
                            <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                            <button class="dropdown-item text-warning" type="submit"><?php echo ((int)($p['is_active'] ?? 1) !== 1) ? 'Kích hoạt lại' : 'Tạm dừng chức vụ'; ?></button>
                        </form>
                        <form method="post" action="/positions/delete" class="mt-1" onsubmit="return confirm('CẢNH BÁO: Bạn có chắc chắn muốn XÓA VĨNH VIỄN chức vụ này không? Không thể thực hiện nếu đang có nhân sự giữ chức vụ này.');">
                            <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                            <button class="dropdown-item text-danger" type="submit">Xóa vĩnh viễn</button>
                        </form>
                    </div>
                  </div>
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

<!-- Modal Thêm Chức Vụ -->
<div class="modal fade" id="positionModal" tabindex="-1" aria-labelledby="positionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-light border-bottom border-200">
        <h5 class="modal-title text-1000" id="positionModalLabel">Thêm Chức Vụ Mới</h5>
        <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times fs--1"></span></button>
      </div>
      <form method="post" action="/positions/create" class="needs-validation" novalidate>
        <div class="modal-body p-4 bg-white">
          <div class="mb-3">
            <label class="form-label" for="pos_name">Tên chức danh/vị trí <span class="text-danger">*</span></label>
            <input class="form-control" id="pos_name" name="name" type="text" placeholder="VD: Lập trình viên, Giám đốc..." required />
            <div class="invalid-feedback">Vui lòng nhập tên chức vụ.</div>
          </div>
          <div class="mb-0">
            <label class="form-label" for="pos_desc">Mô tả thêm</label>
            <textarea class="form-control" id="pos_desc" name="description" rows="3" placeholder="Mô tả tóm tắt về vị trí này..."></textarea>
          </div>
        </div>
        <div class="modal-footer border-top-0 px-4 pb-4">
          <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Hủy</button>
          <button class="btn btn-primary px-4" type="submit">Lưu Chức Vụ</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Sửa Chức Vụ -->
<div class="modal fade" id="editPositionModal" tabindex="-1" aria-labelledby="editPositionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-light border-bottom border-200">
        <h5 class="modal-title text-1000" id="editPositionModalLabel">Chỉnh Sửa Chức Vụ</h5>
        <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times fs--1"></span></button>
      </div>
      <form method="post" action="/positions/edit" class="needs-validation" novalidate>
        <input type="hidden" id="edit_pos_id" name="id" value="" />
        <div class="modal-body p-4 bg-white">
          <div class="mb-3">
            <label class="form-label">Tên chức danh/vị trí <span class="text-danger">*</span></label>
            <input class="form-control" id="edit_pos_name" name="name" type="text" required />
            <div class="invalid-feedback">Vui lòng nhập tên chức vụ.</div>
          </div>
          <div class="mb-0">
            <label class="form-label">Mô tả thêm</label>
            <textarea class="form-control" id="edit_pos_desc" name="description" rows="3"></textarea>
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
    
    // Auto hide alerts after 4s
    setTimeout(function() {
      document.querySelectorAll('.auto-dismiss-alert').forEach(function(alertNode) {
        var alert = new bootstrap.Alert(alertNode)
        alert.close()
      })
    }, 4000);
  })();

  function editPosition(data) {
      document.getElementById('edit_pos_id').value = data.id || '';
      document.getElementById('edit_pos_name').value = data.name || '';
      document.getElementById('edit_pos_desc').value = data.description || '';
  }
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
