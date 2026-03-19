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
          <table class="table table-sm table-hrm fs--1 mb-0 overflow-hidden text-nowrap">
            <thead>
              <tr>
                <th class="align-middle white-space-nowrap pb-2 pt-3 text-center" style="width:50px">STT</th>
                <th class="pe-1 align-middle white-space-nowrap pb-2 pt-3">Tên chức vụ</th>
                <th class="pe-1 align-middle white-space-nowrap pb-2 pt-3">Mô tả</th>
                <th class="pe-1 align-middle white-space-nowrap pb-2 pt-3 text-center">Số nhân sự</th>
                <th class="pe-1 align-middle white-space-nowrap pb-2 pt-3">Trạng thái</th>
                <th class="text-end align-middle pb-2 pt-3">Thao tác</th>
              </tr>
            </thead>
            <tbody class="list" id="table-position-body">
            <?php $stt = 1; foreach (($positions ?? []) as $p): ?>
              <tr class="hover-actions-trigger btn-reveal-trigger position-static">
                <td class="align-middle white-space-nowrap py-2 text-center fw-bold text-700"><?= $stt++ ?></td>
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
                <td class="align-middle white-space-nowrap text-end py-2">
                  <?php if (in_array(strtolower((string)(auth_user()['role'] ?? '')), ['admin', 'manager'])): ?>
                  <div class="d-flex justify-content-end gap-1">
                    <button class="btn btn-phoenix-primary btn-icon btn-icon-xs btn-sm" title="Ch\u1ec9nh s\u1eeda" onclick='editPosition(<?= json_encode([
                        "id" => $p["id"],
                        "name" => $p["name"],
                        "description" => $p["description"] ?? "",
                        "is_active" => $p["is_active"] ?? 1
                    ]) ?>); (function(){ var m = new bootstrap.Modal(document.getElementById("editPositionModal")); m.show(); })();'>
                      <span data-feather="edit"></span>
                    </button>
                    <form method="post" action="/positions/toggle" class="mb-0 d-inline-block" onsubmit="return confirm('Thay \u0111\u1ed5i tr\u1ea1ng th\u00e1i ch\u1ee9c v\u1ee5?')">
                      <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                      <button type="submit" class="btn btn-icon btn-icon-xs btn-sm <?= ((int)($p['is_active'] ?? 1) === 1) ? 'btn-phoenix-warning' : 'btn-phoenix-success' ?>" title="<?= ((int)($p['is_active'] ?? 1) === 1) ? 'T\u1ea1m d\u1eebng' : 'K\u00edch ho\u1ea1t' ?>">
                        <span data-feather="<?= ((int)($p['is_active'] ?? 1) === 1) ? 'pause-circle' : 'play-circle' ?>"></span>
                      </button>
                    </form>
                    <form method="post" action="/positions/delete" class="mb-0 d-inline-block" onsubmit="return confirm('X\u00f3a v\u0129nh vi\u1ec5n ch\u1ee9c v\u1ee5 n\u00e0y?')">
                      <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
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
