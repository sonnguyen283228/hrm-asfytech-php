<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php $activePage='projects'; require __DIR__ . '/../layouts/sidebar.php'; ?>

<div class="px-sm-4 px-md-5 mt-4">
  <!-- Breadcrumb & Header -->
    <nav class="mb-2" aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="/projects">Dự án</a></li>
        <li class="breadcrumb-item active" aria-current="page">Chi tiết Dự án</li>
      </ol>
    </nav>
    <div class="d-flex align-items-end justify-content-between mb-4">
      <div>
        <h2 class="mb-2"><?= htmlspecialchars($project['name']) ?></h2>
        <div class="d-flex align-items-center text-700">
          <span data-feather="calendar" class="me-2" style="width: 14px; height: 14px;"></span>
          <span class="me-3">Bắt đầu: <strong><?= date('d/m/Y', strtotime($project['start_date'])) ?></strong></span>
          <span data-feather="clock" class="me-2" style="width: 14px; height: 14px;"></span>
          <span>Dự kiến: <strong><?= (float)($project['duration_months'] ?? 0) ?> tháng</strong></span>
        </div>
      </div>
      <div>
        <a class="btn btn-outline-primary" href="/projects"><span data-feather="arrow-left" class="me-2"></span>Quay lại</a>
      </div>
    </div>

    <!-- Alert Messages -->
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

    <div class="row g-4 mb-4">
      <div class="col-12 col-xl-4">
        <div class="card h-100 shadow-sm border-0">
          <div class="card-body">
            <h5 class="card-title text-900 border-bottom pb-2 mb-3">Thông tin mô tả</h5>
            <p class="text-700 fs--1 mb-0"><?= nl2br(htmlspecialchars($project['description'] ?? 'Không có mô tả chi tiết.')) ?></p>
          </div>
        </div>
      </div>
      
      <div class="col-12 col-xl-8">
        <div class="card h-100 shadow-sm border-0">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
               <h5 class="card-title text-900 mb-0">Thành viên đội dự án</h5>
               <?php if (in_array((string)(auth_user()['role'] ?? ''), ['admin', 'manager'])): ?>
               <button class="btn btn-sm btn-subtle-primary" type="button" data-bs-toggle="collapse" data-bs-target="#addMemberForm"><span data-feather="user-plus" class="me-2"></span>Thêm thành viên</button>
               <?php endif; ?>
            </div>
            
            <div class="collapse mb-4" id="addMemberForm">
              <div class="p-3 bg-light rounded-2 border">
                <form method="post" action="/projects/members/add" class="row g-2 align-items-end needs-validation" novalidate>
                  <input type="hidden" name="project_id" value="<?= (int)$project['id'] ?>">
                  <div class="col-md-5">
                    <label class="form-label fs--1 fw-bold">Nhân sự <span class="text-danger">*</span></label>
                    <select class="form-select form-select-sm" name="user_id" required>
                      <option value="">-- Chọn nhân sự --</option>
                      <?php foreach ($users as $u): ?>
                        <option value="<?= (int)$u['id'] ?>"><?= htmlspecialchars($u['full_name']) ?> (<?= htmlspecialchars($u['email']) ?>)</option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-5">
                    <label class="form-label fs--1 fw-bold">Vai trò <span class="text-danger">*</span></label>
                    <input class="form-control form-control-sm" type="text" name="project_role" placeholder="VD: Backend, PM, Tester..." required>
                  </div>
                  <div class="col-md-2">
                    <button class="btn btn-primary btn-sm w-100" type="submit">Thêm</button>
                  </div>
                </form>
              </div>
            </div>

            <div class="table-responsive scrollbar">
              <table class="table table-sm fs--1 mb-0 overflow-hidden text-nowrap">
                <thead class="bg-200">
                  <tr>
                    <th class="sort pe-1 align-middle white-space-nowrap">Thành viên</th>
                    <th class="sort pe-1 align-middle white-space-nowrap">Phòng ban</th>
                    <th class="sort pe-1 align-middle white-space-nowrap">Vai trò dự án</th>
                  </tr>
                </thead>
                <tbody class="list">
                  <?php foreach ($members as $m): ?>
                  <tr>
                    <td class="align-middle py-2">
                      <div class="d-flex align-items-center">
                        <div class="avatar avatar-m me-2">
                          <div class="avatar-name rounded-circle"><span><?= mb_substr($m['full_name'], 0, 2) ?></span></div>
                        </div>
                        <div>
                           <h6 class="mb-0 fw-semi-bold"><?= htmlspecialchars($m['full_name']) ?></h6>
                           <a class="text-500 fs--2" href="mailto:<?= htmlspecialchars($m['email']) ?>"><?= htmlspecialchars($m['email']) ?></a>
                        </div>
                      </div>
                    </td>
                    <td class="align-middle py-2"><?= htmlspecialchars($m['department_name'] ?? '--') ?></td>
                    <td class="align-middle py-2"><span class="badge badge-phoenix badge-phoenix-info fs--2"><?= htmlspecialchars($m['project_role']) ?></span></td>
                  </tr>
                  <?php endforeach; ?>
                  <?php if (empty($members)): ?>
                    <tr><td colspan="3" class="text-center py-3 text-700">Chưa có thành viên nào trong dự án này.</td></tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
            
          </div>
        </div>
      </div>
    </div> <!-- .row -->

    <!-- Modules Section -->
    <div class="card shadow-sm border-0">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
            <h5 class="card-title text-900 mb-0">Các hạng mục (Modules) triển khai</h5>
            <?php if (in_array((string)(auth_user()['role'] ?? ''), ['admin', 'manager'])): ?>
            <button class="btn btn-sm btn-subtle-primary" type="button" data-bs-toggle="collapse" data-bs-target="#addModuleForm"><span data-feather="layers" class="me-2"></span>Thêm Module</button>
            <?php endif; ?>
        </div>

        <div class="collapse mb-4" id="addModuleForm">
          <div class="p-3 bg-light rounded-2 border">
            <form method="post" action="/projects/modules/create" class="row g-2 align-items-end needs-validation" novalidate>
              <input type="hidden" name="project_id" value="<?= (int)$project['id'] ?>">
              <div class="col-md-6">
                <label class="form-label fs--1 fw-bold">Tên Module / Công việc <span class="text-danger">*</span></label>
                <input class="form-control form-control-sm" type="text" name="name" required placeholder="VD: Xây dựng Database, Thiết kế UI...">
              </div>
              <div class="col-md-4">
                <label class="form-label fs--1 fw-bold">Thời gian ước tính (Tháng) <span class="text-danger">*</span></label>
                <input class="form-control form-control-sm" type="number" step="0.5" min="0.5" name="planned_months" required>
              </div>
              <div class="col-md-2">
                <button class="btn btn-primary btn-sm w-100" type="submit">Lưu Module</button>
              </div>
            </form>
          </div>
        </div>

        <div class="table-responsive scrollbar">
          <table class="table fs--1 mb-0 overflow-hidden text-nowrap">
            <thead class="bg-200">
              <tr>
                <th class="align-middle white-space-nowrap">Tên Module (Công việc)</th>
                <th class="align-middle text-center white-space-nowrap">Thời gian (Tháng)</th>
                <th class="align-middle white-space-nowrap" style="min-width: 200px">Tiến độ (%)</th>
                <th class="align-middle text-center white-space-nowrap">Trạng thái</th>
                <th class="align-middle text-end white-space-nowrap">Cập nhật lúc</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($modules as $mo): ?>
              <tr>
                <td class="align-middle py-3">
                   <h6 class="mb-0 text-900 fw-semi-bold"><?= htmlspecialchars($mo['name']) ?></h6>
                </td>
                <td class="align-middle py-3 text-center fw-bold text-700"><?= (float)$mo['planned_months'] ?></td>
                <td class="align-middle py-3">
                   <!-- Progress Form Inline -->
                   <form method="post" action="/projects/modules/progress" class="d-flex align-items-center gap-2 m-0 p-0">
                     <input type="hidden" name="project_id" value="<?= (int)$project['id'] ?>">
                     <input type="hidden" name="module_id" value="<?= (int)$mo['id'] ?>">
                     
                     <div class="flex-grow-1 d-flex align-items-center gap-2">
                       <input class="form-range flex-grow-1" type="range" name="progress_percent" min="0" max="100" value="<?= (int)$mo['progress_percent'] ?>" oninput="this.nextElementSibling.value = this.value + '%'">
                       <output class="fw-bold fs--2 text-primary" style="width: 35px;"><?= (int)$mo['progress_percent'] ?>%</output>
                     </div>
                </td>
                <td class="align-middle py-3 text-center">
                    <select class="form-select form-select-sm" name="status" style="width: 130px; display: inline-block;">
                      <option value="pending" <?= $mo['status']==='pending'?'selected':'' ?>>Đang chờ</option>
                      <option value="in_progress" <?= $mo['status']==='in_progress'?'selected':'' ?>>Đang làm</option>
                      <option value="done" <?= $mo['status']==='done'?'selected':'' ?>>Hoàn thành</option>
                    </select>
                </td>
                <td class="align-middle py-3 text-end">
                    <button class="btn btn-sm btn-phoenix-primary px-3" type="submit"><span data-feather="save" class="me-1" style="width: 12px; height: 12px"></span>Lưu</button>
                   </form>
                </td>
              </tr>
              <?php endforeach; ?>
              <?php if (empty($modules)): ?>
                <tr><td colspan="5" class="text-center py-4 text-700">Chưa có hạng mục mô-đun nào được lập.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

      </div>
    </div> <!-- .card modules -->

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
    })
  })()
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
