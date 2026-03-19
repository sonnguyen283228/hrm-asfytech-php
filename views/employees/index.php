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
            <div class="dropdown">
              <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span data-feather="download" class="me-2"></span>Export File
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="/employees/export?format=excel&<?= http_build_query($_GET) ?>">Excel (.xls)</a></li>
                <li><a class="dropdown-item" href="/employees/export?format=pdf&<?= http_build_query($_GET) ?>">PDF (.pdf)</a></li>
              </ul>
            </div>
            <?php if (in_array(strtolower((string)(auth_user()['role'] ?? '')), ['admin', 'manager'])): ?>
            <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#employeeModal"><span data-feather="plus" class="me-2"></span>Thêm nhân sự mới</button>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>



    <div class="card shadow-sm border-0 mb-3" data-list='{"valueNames":["fullName","email","department","position","role","status","baseSalary"],"page":10,"pagination":true}'>
      <div class="card-body p-3">
        
        <!-- Filter Row -->
        <form method="GET" action="/employees" class="d-flex flex-wrap align-items-center gap-2 mb-4">
          <div class="search-box">
            <div class="position-relative">
              <input name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" class="form-control form-control-sm search-input" type="search" placeholder="Tìm tên, email..." style="min-width:200px" />
              <span class="fas fa-search search-box-icon"></span>
            </div>
          </div>
          <select name="department_id" class="form-select form-select-sm w-auto">
            <option value="">Tất cả phòng ban</option>
            <?php foreach (($departments ?? []) as $d): ?>
              <option value="<?= $d['id'] ?>" <?= (isset($_GET['department_id']) && $_GET['department_id'] == $d['id']) ? 'selected' : '' ?>><?= htmlspecialchars($d['name']) ?></option>
            <?php endforeach; ?>
          </select>
          <button type="submit" class="btn btn-sm btn-primary">Lọc / Tìm kiếm</button>
          <?php if (!empty($_GET['q']) || !empty($_GET['department_id'])): ?>
            <a href="/employees" class="btn btn-sm btn-outline-secondary">Xóa lọc</a>
          <?php endif; ?>
        </form>

        <div class="table-responsive scrollbar">
          <table class="table table-sm table-hrm fs--1 mb-0 overflow-hidden text-nowrap">
            <thead>
              <tr>
                <th class="white-space-nowrap pb-2 pt-3 text-center" style="width:50px">STT</th>
                <th class="white-space-nowrap pb-2 pt-3" style="width:50px"></th>
                <th class="pe-1 align-middle white-space-nowrap pb-2 pt-3">Họ tên & Email</th>
                <th class="pe-1 align-middle white-space-nowrap pb-2 pt-3">Phòng ban & Vị trí</th>
                <th class="pe-1 align-middle white-space-nowrap pb-2 pt-3">Vai trò</th>
                <th class="pe-1 align-middle white-space-nowrap pb-2 pt-3">Trạng thái</th>
                <th class="pe-1 align-middle white-space-nowrap pb-2 pt-3">Thời gian làm việc</th>
                <th class="pe-1 align-middle white-space-nowrap pb-2 pt-3">Lương CB</th>
                <th class="text-center align-middle pb-2 pt-3">Thao tác</th>
              </tr>
            </thead>
            <tbody class="list" id="table-employees-body">
              <?php $stt = 1; foreach ($employees as $e): ?>
              <tr class="hover-actions-trigger btn-reveal-trigger position-static">
                <td class="align-middle white-space-nowrap py-2 text-center text-900 fw-semi-bold"><?= $stt++ ?></td>
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
                <td class="department align-middle white-space-nowrap py-2">
                    <h6 class="mb-1 text-900"><?= htmlspecialchars($e['department_name'] ?? '--') ?></h6>
                    <?php $posName = $e['position_name'] ?? $e['position'] ?? null; ?>
                    <?php if ($posName): ?>
                    <span class="badge badge-phoenix badge-phoenix-secondary fs--2"><?= htmlspecialchars($posName) ?></span>
                    <?php endif; ?>
                </td>
                <td class="role align-middle white-space-nowrap py-2">
                    <?php 
                      $r = strtolower($e['role']);
                      $badgeClass = $r === 'admin' ? 'badge-phoenix-danger' : ($r === 'manager' ? 'badge-phoenix-warning' : 'badge-phoenix-primary');
                    ?>
                    <span class="badge badge-phoenix fs--2 <?= $badgeClass ?>"><span class="badge-label"><?= htmlspecialchars($e['role']) ?></span></span>
                </td>
                <td class="status align-middle white-space-nowrap py-2">
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
                  <div class="d-flex justify-content-end gap-1">
                    <button class="btn btn-phoenix-secondary btn-icon btn-icon-xs btn-sm" title="Xem chi tiết" onclick='viewEmployeeDetail(<?= json_encode($e) ?>)'><span data-feather="eye"></span></button>
                    <?php if (in_array(strtolower((string)(auth_user()['role'] ?? '')), ['admin', 'manager'])): ?>
                    <button class="btn btn-phoenix-primary btn-icon btn-icon-xs btn-sm" title="Chỉnh sửa" onclick='editEmployee(<?= json_encode([
                        "id" => $e["id"], 
                        "full_name" => $e["full_name"], 
                        "email" => $e["email"], 
                        "phone" => $e["phone"] ?? "",
                        "birth_date" => $e["birth_date"] ?? "",
                        "address_city" => $e["address_city"] ?? "",
                        "address_ward" => $e["address_ward"] ?? "",
                        "department_id" => $e["department_id"] ?? "",
                        "position_id" => $e["position_id"] ?? "",
                        "start_date" => $e["start_date"] ?? "",
                        "base_salary" => $e["base_salary"] ?? "",
                        "role" => $e["role"] ?? "staff"
                    ]) ?>)'><span data-feather="edit"></span></button>
                    <form method="post" action="/employees/toggle-status" class="mb-0 d-inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn thay đổi trạng thái của nhân sự này không?');">
                      <input type="hidden" name="id" value="<?= $e['id'] ?>">
                      <button type="submit" class="btn btn-phoenix-danger btn-icon btn-icon-xs btn-sm" title="<?= ((int)$e['is_active'] === 1) ? 'Khóa tài khoản' : 'Mở khóa' ?>">
                        <span data-feather="<?= ((int)$e['is_active'] === 1) ? 'lock' : 'unlock' ?>"></span>
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
        
        <?php $show_all_btn = true; require __DIR__ . '/../components/list_pagination.php'; ?>
    </div>
</div>

<?php require __DIR__ . '/_modals.php'; ?>
<?php require __DIR__ . '/../components/common_scripts.php'; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
