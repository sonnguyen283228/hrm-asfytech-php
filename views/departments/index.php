<?php $activePage='departments'; require __DIR__ . '/../layouts/header.php'; ?>

<div class="px-sm-4 px-md-5 mt-4">
  <div class="mb-4">
      <div class="row align-items-center justify-content-between g-3">
        <div class="col-auto flex-grow-1">
          <h2 class="mb-0 text-900">Quản lý phòng ban</h2>
          <p class="text-700 mb-0">Quản lý cơ cấu tổ chức và sơ đồ phòng ban của công ty.</p>
        </div>
        <div class="col-auto">
          <div class="d-flex align-items-center gap-2">
            <?php if (in_array(strtolower((string)(auth_user()['role'] ?? '')), ['admin', 'manager'])): ?>
            <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#deptModal"><span data-feather="plus" class="me-2"></span>Thêm phòng ban</button>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>



    <div class="card shadow-sm border-0 mb-3" data-list='{"valueNames":["id","name","desc","members"],"page":10,"pagination":true}'>
      <div class="card-body p-3">
        
        <div class="row align-items-center justify-content-between g-3 mb-4">
          <div class="col-12 col-md-auto d-flex align-items-center">
            <div class="search-box">
              <form class="position-relative" data-bs-toggle="search" data-bs-display="static">
                <input class="form-control form-control-sm search-input search" type="search" placeholder="Tìm phòng ban..." aria-label="Search" />
                <span class="fas fa-search search-box-icon"></span>
              </form>
            </div>
          </div>
        </div>

        <div class="table-responsive scrollbar">
          <table class="table table-sm table-hrm fs--1 mb-0 overflow-hidden text-nowrap">
            <thead>
              <tr>
                <th class="sort pe-1 align-middle white-space-nowrap pb-2 pt-3" data-sort="id">ID</th>
                <th class="sort pe-1 align-middle white-space-nowrap pb-2 pt-3" data-sort="name">Tên phòng ban</th>
                <th class="sort pe-1 align-middle white-space-nowrap pb-2 pt-3" data-sort="desc">Mô tả chức năng</th>
                <th class="sort pe-1 align-middle white-space-nowrap pb-2 pt-3 text-center" data-sort="members">Số nhân sự</th>
                <th class="text-end align-middle pb-2 pt-3">Thao tác</th>
              </tr>
            </thead>
            <tbody class="list" id="table-department-body">
            <?php foreach ($departments as $d): ?>
              <tr class="hover-actions-trigger btn-reveal-trigger position-static">
                <td class="id align-middle white-space-nowrap py-3 fw-bold">#<?= (int)$d['id'] ?></td>
                <td class="name align-middle white-space-nowrap py-3">
                    <h6 class="mb-0 text-900 fw-semi-bold"><?= htmlspecialchars($d['name']) ?></h6>
                </td>
                <td class="desc align-middle py-3 text-700" style="min-width: 250px; white-space: normal;">
                    <?= htmlspecialchars($d['description'] ?? '--') ?>
                </td>
                <td class="members align-middle white-space-nowrap py-3 text-center">
                    <span class="badge badge-phoenix fs--2 badge-phoenix-primary"><span class="badge-label"><?= (int)$d['total_users'] ?></span></span>
                </td>
                <td class="align-middle white-space-nowrap text-end py-3">
                  <?php if (in_array(strtolower((string)(auth_user()['role'] ?? '')), ['admin', 'manager'])): ?>
                  <div class="font-sans-serif btn-reveal-trigger position-static">
                    <button class="btn btn-sm dropdown-toggle dropdown-caret-none transition-none btn-reveal fs--2" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2"></span></button>
                    <div class="dropdown-menu dropdown-menu-end py-2">
                        <a class="dropdown-item cursor-pointer" onclick='editDept(<?= json_encode([
                          "id" => $d["id"], 
                          "name" => $d["name"], 
                          "description" => $d["description"] ?? ""
                        ]) ?>)' data-bs-toggle="modal" data-bs-target="#editDeptModal">Chỉnh sửa</a>
                        <div class="dropdown-divider"></div>
                        <form method="post" action="/departments/delete" onsubmit="return confirm('Bạn có chắc chắn muốn xóa phòng ban này không? Các nhân sự thuộc phòng ban này sẽ bị mất liên kết phòng.')">
                            <input type="hidden" name="id" value="<?= (int)$d['id'] ?>">
                            <button class="dropdown-item text-danger" type="submit">Xóa phòng ban</button>
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

<!-- Modal Thêm Phòng Ban -->
<div class="modal fade" id="deptModal" tabindex="-1" aria-labelledby="deptModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-light border-bottom border-200">
        <h5 class="modal-title text-1000" id="deptModalLabel">Thêm Phòng Ban Mới</h5>
        <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times fs--1"></span></button>
      </div>
      <form method="post" action="/departments/create" class="needs-validation" novalidate>
        <div class="modal-body p-4 bg-white">
          <div class="mb-3">
            <label class="form-label" for="dept_name">Tên phòng ban <span class="text-danger">*</span></label>
            <input class="form-control" id="dept_name" name="name" type="text" placeholder="VD: Phòng Hành chính, IT..." required />
            <div class="invalid-feedback">Vui lòng nhập tên phòng ban.</div>
          </div>
          <div class="mb-0">
            <label class="form-label" for="dept_desc">Mô tả chức năng</label>
            <textarea class="form-control" id="dept_desc" name="description" rows="3" placeholder="Ghi chú thêm về chức năng của phòng ban này..."></textarea>
          </div>
        </div>
        <div class="modal-footer border-top-0 px-4 pb-4">
          <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Hủy</button>
          <button class="btn btn-primary px-4" type="submit">Lưu Phòng Ban</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Sửa Phòng Ban -->
<div class="modal fade" id="editDeptModal" tabindex="-1" aria-labelledby="editDeptModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-light border-bottom border-200">
        <h5 class="modal-title text-1000" id="editDeptModalLabel">Chỉnh Sửa Phòng Ban</h5>
        <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times fs--1"></span></button>
      </div>
      <form method="post" action="/departments/edit" class="needs-validation" novalidate>
        <input type="hidden" id="edit_dept_id" name="id" value="" />
        <div class="modal-body p-4 bg-white">
          <div class="mb-3">
            <label class="form-label">Tên phòng ban <span class="text-danger">*</span></label>
            <input class="form-control" id="edit_dept_name" name="name" type="text" required />
            <div class="invalid-feedback">Vui lòng nhập tên phòng ban.</div>
          </div>
          <div class="mb-0">
            <label class="form-label">Mô tả chức năng</label>
            <textarea class="form-control" id="edit_dept_desc" name="description" rows="3"></textarea>
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

  function editDept(data) {
      document.getElementById('edit_dept_id').value = data.id || '';
      document.getElementById('edit_dept_name').value = data.name || '';
      document.getElementById('edit_dept_desc').value = data.description || '';
  }
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
