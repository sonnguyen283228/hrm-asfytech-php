<?php $activePage='projects'; require __DIR__ . '/../layouts/header.php'; ?>

<div class="px-sm-4 px-md-5 mt-4">
  <!-- Breadcrumb -->
  <nav class="breadcrumb-modern mb-2" aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
      <li class="breadcrumb-item"><a href="/projects">Dự án</a></li>
      <li class="breadcrumb-item active" aria-current="page">Tạo dự án mới</li>
    </ol>
  </nav>

  <!-- Page Header -->
  <div class="page-header mb-4 fade-in">
    <div class="row align-items-center justify-content-between g-3">
      <div class="col-auto flex-grow-1">
        <h2 class="mb-0 text-900">Tạo dự án mới</h2>
        <p class="text-700 mb-0">Nhập thông tin chi tiết để khởi tạo một dự án mới trong hệ thống.</p>
      </div>
      <div class="col-auto">
        <a class="btn btn-outline-secondary" href="/projects">
          <span data-feather="arrow-left" class="me-2" style="width:14px;height:14px"></span>Quay lại danh sách
        </a>
      </div>
    </div>
  </div>

  <!-- Form Card -->
  <div class="card shadow-sm border-0 fade-in fade-in-delay-1" style="max-width: 900px;">
    <div class="card-body p-4">
      <form method="post" action="/projects/create" class="needs-validation" novalidate>
        <div class="row g-3">
          <div class="col-12"><h6 class="text-700 fw-bold mb-0">Thông tin dự án</h6><hr class="mt-2 mb-3"/></div>
          
          <div class="col-md-6">
            <label class="form-label" for="proj_name">Tên dự án <span class="text-danger">*</span></label>
            <input class="form-control" id="proj_name" name="name" type="text" placeholder="VD: HRM ASFY 2026" required />
            <div class="invalid-feedback">Vui lòng nhập tên dự án.</div>
          </div>
          <div class="col-md-6">
            <label class="form-label" for="proj_start_date">Ngày bắt đầu <span class="text-danger">*</span></label>
            <input class="form-control" id="proj_start_date" name="start_date" type="date" required />
            <div class="invalid-feedback">Vui lòng chọn ngày bắt đầu.</div>
          </div>
          <div class="col-12">
            <label class="form-label" for="proj_desc">Mô tả dự án</label>
            <textarea class="form-control" id="proj_desc" name="description" rows="4" placeholder="Mô tả ngắn về mục tiêu và phạm vi dự án..."></textarea>
          </div>
        </div>

        <hr class="my-4">
        <div class="d-flex justify-content-end gap-2">
          <a class="btn btn-outline-secondary" href="/projects">Hủy</a>
          <button class="btn btn-primary px-4" type="submit">
            <span data-feather="plus" class="me-2" style="width:14px;height:14px"></span>Tạo dự án
          </button>
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
    })
  })()
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
