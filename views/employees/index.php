<?php $activePage='employees'; require __DIR__ . '/../layouts/header.php'; ?>

<div class="px-sm-4 px-md-5 mt-4">
    <div class="mb-4">
      <div class="row align-items-center justify-content-between g-3">
        <div class="col-auto flex-grow-1">
          <h2 class="mb-0 text-900">Quản lý nhân sự</h2>
          <p class="text-700 mb-0">Tìm kiếm, quản lý và thêm mới nhân sự vào hệ thống.</p>
        </div>
        <div class="col-auto">
          <div class="d-flex align-items-center gap-2">
            <button class="btn btn-outline-secondary btn-sm"><span data-feather="download" class="me-2"></span>Export File</button>
            <?php if (in_array(strtolower((string)(auth_user()['role'] ?? '')), ['admin', 'manager'])): ?>
            <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#employeeModal"><span data-feather="plus" class="me-2"></span>Thêm nhân sự mới</button>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-soft-success d-flex align-items-center" role="alert">
      <span data-feather="check-circle" class="text-success fs-3 me-3"></span>
      <p class="mb-0 flex-1"><?= htmlspecialchars($_SESSION['success']) ?></p>
      <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['success']); endif; ?>
    
    <?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-soft-danger d-flex align-items-center" role="alert">
      <span data-feather="alert-circle" class="text-danger fs-3 me-3"></span>
      <p class="mb-0 flex-1"><?= htmlspecialchars($_SESSION['error']) ?></p>
      <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['error']); endif; ?>

    <div class="card shadow-sm border-0 mb-3" data-list='{"valueNames":["fullName","email","department","position","role","status","baseSalary"],"page":10,"pagination":true}'>
      <div class="card-body p-3">
        
        <!-- Filter Row -->
        <div class="row align-items-center justify-content-between g-3 mb-4">
          <div class="col-12 col-md-auto d-flex align-items-center">
            <div class="search-box me-2">
              <form class="position-relative" data-bs-toggle="search" data-bs-display="static">
                <input class="form-control form-control-sm search-input search" type="search" placeholder="Tìm tên, email..." aria-label="Search" />
                <span class="fas fa-search search-box-icon"></span>
              </form>
            </div>
          </div>
          <div class="col-12 col-md-auto d-flex gap-2">
             <select class="form-select form-select-sm w-auto" aria-label="Lọc phòng ban">
                <option value="">Tất cả phòng ban</option>
                <?php foreach (($departments ?? []) as $d): ?>
                  <option value="<?= htmlspecialchars($d['name']) ?>"><?= htmlspecialchars($d['name']) ?></option>
                <?php endforeach; ?>
             </select>
             <input type="month" class="form-control form-control-sm w-auto" placeholder="Tháng bắt đầu" aria-label="Lọc thời gian">
          </div>
        </div>

        <div class="table-responsive scrollbar">
          <table class="table table-sm fs--1 mb-0 overflow-hidden text-nowrap">
            <thead class="bg-200 text-900 border-bottom">
              <tr>
                <th class="white-space-nowrap pb-2 pt-3" style="width:50px"></th>
                <th class="sort pe-1 align-middle white-space-nowrap pb-2 pt-3" data-sort="fullName">Họ tên & Email</th>
                <th class="sort pe-1 align-middle white-space-nowrap pb-2 pt-3" data-sort="department">Phòng ban</th>
                <th class="sort pe-1 align-middle white-space-nowrap pb-2 pt-3" data-sort="position">Vị trí</th>
                <th class="sort pe-1 align-middle white-space-nowrap pb-2 pt-3" data-sort="role">Vai trò</th>
                <th class="sort pe-1 align-middle white-space-nowrap pb-2 pt-3" data-sort="status">Trạng thái</th>
                <th class="sort pe-1 align-middle white-space-nowrap pb-2 pt-3" data-sort="start_date">Thời gian làm việc</th>
                <th class="sort pe-1 align-middle white-space-nowrap pb-2 pt-3" data-sort="baseSalary">Lương CB</th>
                <th class="text-end align-middle pb-2 pt-3"></th>
              </tr>
            </thead>
            <tbody class="list" id="table-employees-body">
              <?php foreach ($employees as $e): ?>
              <tr class="hover-actions-trigger btn-reveal-trigger position-static">
                <td class="align-middle white-space-nowrap py-2">
                  <div class="avatar avatar-m">
                    <?php if (!empty($e['avatar_url'])): ?>
                      <img class="rounded-circle" src="<?= htmlspecialchars($e['avatar_url']) ?>" alt="" />
                    <?php else: ?>
                      <div class="avatar-name rounded-circle"><span><?= mb_substr($e['full_name'], 0, 2) ?></span></div>
                    <?php endif; ?>
                  </div>
                </td>
                <td class="fullName align-middle white-space-nowrap py-2">
                    <h6 class="mb-0 text-900 fw-semi-bold"><?= htmlspecialchars($e['full_name']) ?></h6>
                    <a class="text-500 fs--2 fw-semi-bold email" href="mailto:<?= htmlspecialchars($e['email']) ?>"><?= htmlspecialchars($e['email']) ?></a>
                </td>
                <td class="department align-middle white-space-nowrap py-2"><?= htmlspecialchars($e['department_name'] ?? '--') ?></td>
                <td class="position align-middle white-space-nowrap py-2"><?= htmlspecialchars($e['position'] ?? '--') ?></td>
                <td class="role align-middle white-space-nowrap py-2">
                    <?php 
                      $r = strtolower($e['role']);
                      $badgeClass = $r === 'admin' ? 'badge-phoenix-danger' : ($r === 'manager' ? 'badge-phoenix-warning' : 'badge-phoenix-primary');
                    ?>
                    <span class="badge badge-phoenix fs--2 <?= $badgeClass ?>"><span class="badge-label"><?= htmlspecialchars($e['role']) ?></span></span>
                </td>
                <td class="status align-middle white-space-nowrap d-flex align-items-center py-3">
                  <?php if ((int)($e['is_active'] ?? 1) !== 1): ?>
                    <span class="badge badge-phoenix fs--2 badge-phoenix-secondary"><span class="badge-label" data-feather="lock"></span> Khóa</span>
                  <?php else: ?>
                    <?php if (is_user_online($e['last_seen_at'] ?? null, 5)): ?>
                      <div class="d-flex align-items-center gap-2"><div class="bg-success rounded-circle" style="width:8px;height:8px"></div><h6 class="mb-0 text-700">Online</h6></div>
                    <?php else: ?>
                      <div class="d-flex align-items-center gap-2"><div class="bg-400 rounded-circle" style="width:8px;height:8px"></div><h6 class="mb-0 text-500">Offline</h6></div>
                    <?php endif; ?>
                  <?php endif; ?>
                </td>
                <td class="start_date align-middle white-space-nowrap py-2">
                    <span class="fw-semi-bold text-700"><?= htmlspecialchars(working_tenure_text($e['start_date'] ?? null)) ?></span>
                </td>
                <td class="baseSalary align-middle white-space-nowrap py-2 fw-bold text-900">
                    <?= isset($e['base_salary']) && $e['base_salary'] !== null ? number_format((int)$e['base_salary']) . ' đ' : '--' ?>
                </td>
                <td class="align-middle white-space-nowrap text-end py-2">
                  <div class="font-sans-serif btn-reveal-trigger position-static">
                    <button class="btn btn-sm dropdown-toggle dropdown-caret-none transition-none btn-reveal fs--2" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs--2"></span></button>
                    <div class="dropdown-menu dropdown-menu-end py-2">
                      <a class="dropdown-item" href="#!">Xem chi tiết</a>
                      <a class="dropdown-item" href="#!">Chỉnh sửa</a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item text-danger" href="#!"><?php echo ((int)($e['is_active'] ?? 1) !== 1) ? 'Mở khóa tài khoản' : 'Khóa tài khoản'; ?></a>
                    </div>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        
        <div class="row align-items-center justify-content-between pe-0 fs--1 mt-3">
          <div class="col-auto d-flex">
            <p class="mb-0 d-none d-sm-block me-3 fw-semi-bold text-900" data-list-info="data-list-info"></p>
            <a class="fw-semi-bold" href="#!" data-list-view="*">Xem tất cả<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a>
            <a class="fw-semi-bold d-none" href="#!" data-list-view="less">Thu gọn<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a>
          </div>
          <div class="col-auto d-flex">
            <button class="page-link" data-list-pagination="prev"><span class="fas fa-chevron-left"></span></button>
            <ul class="mb-0 pagination"></ul>
            <button class="page-link pe-0" data-list-pagination="next"><span class="fas fa-chevron-right"></span></button>
          </div>
        </div>

    </div>
</div>

<!-- Modal Thêm Nhân Sự -->
<div class="modal fade" id="employeeModal" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-light border-bottom border-200">
        <h5 class="modal-title text-1000" id="employeeModalLabel">Thêm Nhân Sự Mới</h5>
        <button class="btn p-1" type="button" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times fs--1"></span></button>
      </div>
      <form method="post" action="/employees/create" class="needs-validation" novalidate>
        <div class="modal-body p-4 bg-white">
          <div class="row g-3">
            <div class="col-12"><h6 class="text-700 fw-bold mb-0">Hồ sơ cá nhân</h6><hr class="mt-2 mb-3"/></div>
            <div class="col-md-6">
              <label class="form-label" for="full_name">Họ và tên <span class="text-danger">*</span></label>
              <input class="form-control" id="full_name" name="full_name" type="text" required />
              <div class="invalid-feedback">Vui lòng nhập họ và tên.</div>
            </div>
            <div class="col-md-6">
              <label class="form-label" for="email">Tài khoản Google (Email) <span class="text-danger">*</span></label>
              <input class="form-control" id="email" name="email" type="email" pattern="^[a-zA-Z0-9._%+-]+@gmail\.com$" placeholder="name@gmail.com" required />
              <div class="invalid-feedback">Bắt buộc sử dụng @gmail.com</div>
            </div>
            <div class="col-md-6">
              <label class="form-label" for="phone">Số điện thoại <span class="text-danger">*</span></label>
              <input class="form-control" id="phone" name="phone" type="tel" pattern="^(0[3|5|7|8|9])+([0-9]{8})$" placeholder="09xxxxxxxx" required />
            </div>
            <div class="col-md-6">
              <label class="form-label" for="birth_date">Ngày sinh</label>
              <input class="form-control" id="birth_date" name="birth_date" type="date" />
            </div>
            <div class="col-md-6">
               <label class="form-label">Tỉnh / Thành phố </label>
               <input class="form-control" name="address_city" type="text" placeholder="Hà Nội, TP HCM..." />
            </div>
            <div class="col-md-6">
               <label class="form-label">Phường / Xã</label>
               <input class="form-control" name="address_ward" type="text" placeholder="..." />
            </div>

            <div class="col-12 mt-4"><h6 class="text-700 fw-bold mb-0">Thông tin công việc</h6><hr class="mt-2 mb-3"/></div>
            
            <div class="col-md-6">
              <label class="form-label" for="department_id">Phòng ban</label>
              <select class="form-select" id="department_id" name="department_id">
                <option value="" selected>-- Chọn phòng ban --</option>
                <?php foreach (($departments ?? []) as $d): ?>
                  <option value="<?= (int)$d['id'] ?>"><?= htmlspecialchars($d['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label" for="position">Vị trí chức danh <span class="text-danger">*</span></label>
              <input class="form-control" id="position" name="position" type="text" placeholder="VD: Lập trình viên, Kế toán..." required />
            </div>
            <div class="col-md-4">
              <label class="form-label" for="start_date">Ngày tính lương</label>
              <input class="form-control" id="start_date" name="start_date" type="date" />
            </div>
            <div class="col-md-4">
              <label class="form-label" for="base_salary">Lương cơ bản (VND)</label>
              <div class="input-group">
                <input class="form-control" id="base_salary" name="base_salary" type="number" min="0" step="1" />
                <span class="input-group-text">đ</span>
              </div>
            </div>
            <div class="col-md-4">
              <label class="form-label" for="role">Quyền hệ thống <span class="text-danger">*</span></label>
              <select class="form-select" id="role" name="role" required>
                <option value="staff" selected>Staff</option>
                <option value="manager">Manager / HR</option>
                <option value="admin">Admin</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer border-top-0 px-4 pb-4">
          <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Hủy</button>
          <button class="btn btn-primary px-5" type="submit">Lưu Hồ Sơ</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  // Bootstrap 5 form validation
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
    })
  })()
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
