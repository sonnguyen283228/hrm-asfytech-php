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
                <th class="align-middle white-space-nowrap pb-2 pt-3 text-center" style="width:50px">STT</th>
                <th class="pe-1 align-middle white-space-nowrap pb-2 pt-3">Tên phòng ban</th>
                <th class="pe-1 align-middle white-space-nowrap pb-2 pt-3">Mô tả chức năng</th>
                <th class="pe-1 align-middle white-space-nowrap pb-2 pt-3 text-center">Số nhân sự</th>
                <th class="text-end align-middle pb-2 pt-3">Thao tác</th>
              </tr>
            </thead>
            <tbody class="list" id="table-department-body">
            <?php $stt = 1; foreach ($departments as $d): ?>
              <tr class="hover-actions-trigger btn-reveal-trigger position-static">
                <td class="align-middle white-space-nowrap py-2 text-center fw-bold text-700"><?= $stt++ ?></td>
                <td class="name align-middle white-space-nowrap py-3">
                    <h6 class="mb-0 text-900 fw-semi-bold"><?= htmlspecialchars($d['name']) ?></h6>
                </td>
                <td class="desc align-middle py-3 text-700" style="min-width: 250px; white-space: normal;">
                    <?= htmlspecialchars($d['description'] ?? '--') ?>
                </td>
                <td class="members align-middle white-space-nowrap py-3 text-center">
                    <span class="badge badge-phoenix fs--2 badge-phoenix-primary"><span class="badge-label"><?= (int)$d['total_users'] ?></span></span>
                </td>
                <td class="align-middle white-space-nowrap text-end py-2">
                  <?php if (in_array(strtolower((string)(auth_user()['role'] ?? '')), ['admin', 'manager'])): ?>
                  <div class="d-flex justify-content-end gap-1">
                    <button class="btn btn-phoenix-primary btn-icon btn-icon-xs btn-sm" title="Ch\u1ec9nh s\u1eeda" onclick='editDept(<?= json_encode([
                      "id" => $d["id"],
                      "name" => $d["name"],
                      "description" => $d["description"] ?? ""
                    ]) ?>); (function(){ var m = new bootstrap.Modal(document.getElementById("editDeptModal")); m.show(); })();'>
                      <span data-feather="edit"></span>
                    </button>
                    <form method="post" action="/departments/delete" class="mb-0 d-inline-block" onsubmit="return confirm('B\u1ea1n c\u00f3 ch\u1eafc ch\u1eafn mu\u1ed1n x\u00f3a ph\u00f2ng ban n\u00e0y kh\u00f4ng?')">
                      <input type="hidden" name="id" value="<?= (int)$d['id'] ?>">
                      <button type="submit" class="btn btn-phoenix-danger btn-icon btn-icon-xs btn-sm" title="X\u00f3a">
                        <span data-feather="trash-2"></span>
                      </button>
                    </form>
                  </div>
                  <?php endif; ?>
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
