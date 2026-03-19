<?php $activePage='settings'; require __DIR__ . '/../layouts/header.php'; ?>

<div class="px-sm-4 px-md-5 mt-4">
  <div class="mb-5">
      <h2 class="mb-0 text-900">Tùy biến giao diện App</h2>
      <p class="text-700 mb-0">Thiết lập cấu hình giao diện chung và thông tin hiển thị của hệ thống.</p>
    </div>

    <div class="card shadow-sm border-0">
      <div class="card-body p-4">
        <form method="post" action="/settings/site" enctype="multipart/form-data">
          
          <h5 class="text-900 mb-4 border-bottom pb-2">1. Thông tin cơ bản</h5>
          <div class="row g-3 mb-4">
            <div class="col-12 col-xl-6">
              <label class="form-label" for="site_name">Tên hệ thống (Site Name)</label>
              <input class="form-control" id="site_name" name="site_name" type="text" value="<?= htmlspecialchars($settings['site_name'] ?? 'HRM APP') ?>">
            </div>
            <div class="col-12 col-xl-6">
              <label class="form-label" for="footer_text">Nội dung Footer (Bản quyền)</label>
              <input class="form-control" id="footer_text" name="footer_text" type="text" value="<?= htmlspecialchars($settings['footer_text'] ?? '© HRM APP') ?>">
            </div>
          </div>

          <h5 class="text-900 mb-4 border-bottom pb-2 mt-5">2. Nhận diện thương hiệu (Logo & Favicon)</h5>
          <div class="row g-4 mb-4">
            <div class="col-12">
              <div class="alert alert-info border-info-subtle border-start border-4 bg-white d-flex align-items-center" role="alert">
                <span class="fas fa-info-circle fs-3 text-info me-3"></span>
                <div>
                  <h5 class="mb-1 text-900 fw-bold">Cách thay đổi Logo và Favicon</h5>
                  <p class="mb-0 text-700">
                    Để chống lỗi và tăng tính ổn định, hệ thống lấy ảnh tĩnh thủ công thay vì upload tại đây.<br>
                    Hãy mở <strong>File Manager</strong> của máy chủ Host, tìm vào thư mục `<span class="fw-bold px-1 bg-200 rounded">public/brand</span>` của dự án.<br>
                    - Bạn tải file ảnh logo của bạn vào đó với tên là <strong>logo.png</strong> (hoặc jpg/svg/webp).<br>
                    - Đổi Favicon bằng cách tải file <strong>favicon.ico</strong> (hoặc png) vào cùng thư mục.<br>
                    Trình duyệt sẽ lập tức áp dụng mà không cần thiết lập lại.
                  </p>
                </div>
              </div>
            </div>
          </div>

          <h5 class="text-900 mb-4 border-bottom pb-2 mt-5">3. Mã nhúng HTML / Scripts tùy chọn</h5>
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label">Header HTML (Thêm vào thẻ &lt;head&gt;)</label>
              <textarea class="form-control font-monospace fs--1" name="header_html" rows="4" placeholder="Ví dụ: Google Analytics script, link CSS ngoài..."><?= htmlspecialchars($settings['header_html'] ?? '') ?></textarea>
              <div class="form-text">Cẩn thận khi chèn mã JavaScript tại đây vì có thể ảnh hưởng đến giao diện.</div>
            </div>
            <div class="col-12">
              <label class="form-label">Footer HTML (Thêm trước thẻ đóng &lt;/body&gt;)</label>
              <textarea class="form-control font-monospace fs--1" name="footer_html" rows="4" placeholder="Ví dụ: Live chat widget, Script tiện ích..."><?= htmlspecialchars($settings['footer_html'] ?? '') ?></textarea>
            </div>
          </div>

          <h5 class="text-900 mb-4 border-bottom pb-2 mt-5">4. Quản lý tính năng (Feature Flags)</h5>
          <div class="row g-4">
            <div class="col-12">
              <div class="alert alert-warning border-warning-subtle border-start border-4 bg-white d-flex align-items-center" role="alert">
                <span class="fas fa-exclamation-triangle fs-3 text-warning me-3"></span>
                <div>
                  <h6 class="mb-1 text-900 fw-bold">Lưu ý khi bật/tắt module</h6>
                  <p class="mb-0 text-700 fs--1">Khi tắt một module, nó sẽ bị ẩn hoàn toàn với các nhân viên và quản lý thông thường, nhưng <b>vẫn hiển thị bình thường</b> đối với tài khoản Admin để tiện cho việc bảo trì, sửa lỗi.</p>
                </div>
              </div>
            </div>
            
            <div class="col-md-6 col-xl-4 mt-2">
              <div class="form-check form-switch cursor-pointer">
                <input class="form-check-input cursor-pointer" id="ff_module_employees" name="ff_module_employees" type="checkbox" value="1" <?= (\site_get('ff_module_employees', '1') === '1') ? 'checked' : '' ?> />
                <label class="form-check-label cursor-pointer fs-0 text-900" for="ff_module_employees">Module Nhân sự</label>
              </div>
            </div>
            
            <div class="col-md-6 col-xl-4 mt-2">
              <div class="form-check form-switch cursor-pointer">
                <input class="form-check-input cursor-pointer" id="ff_module_positions" name="ff_module_positions" type="checkbox" value="1" <?= (\site_get('ff_module_positions', '1') === '1') ? 'checked' : '' ?> />
                <label class="form-check-label cursor-pointer fs-0 text-900" for="ff_module_positions">Module Chức vụ</label>
              </div>
            </div>
            
            <div class="col-md-6 col-xl-4 mt-2">
              <div class="form-check form-switch cursor-pointer">
                <input class="form-check-input cursor-pointer" id="ff_module_departments" name="ff_module_departments" type="checkbox" value="1" <?= (\site_get('ff_module_departments', '1') === '1') ? 'checked' : '' ?> />
                <label class="form-check-label cursor-pointer fs-0 text-900" for="ff_module_departments">Module Phòng ban</label>
              </div>
            </div>
            
            <div class="col-md-6 col-xl-4 mt-2">
              <div class="form-check form-switch cursor-pointer">
                <input class="form-check-input cursor-pointer" id="ff_module_projects" name="ff_module_projects" type="checkbox" value="1" <?= (\site_get('ff_module_projects', '1') === '1') ? 'checked' : '' ?> />
                <label class="form-check-label cursor-pointer fs-0 text-900" for="ff_module_projects">Module Dự án</label>
              </div>
            </div>
            
            <div class="col-md-6 col-xl-4 mt-2 mb-4">
              <div class="form-check form-switch cursor-pointer">
                <input class="form-check-input cursor-pointer" id="ff_module_attendance" name="ff_module_attendance" type="checkbox" value="1" <?= (\site_get('ff_module_attendance', '1') === '1') ? 'checked' : '' ?> />
                <label class="form-check-label cursor-pointer fs-0 text-900" for="ff_module_attendance">Module Chấm công</label>
              </div>
            </div>
          </div>

          <hr class="my-4">
          <div class="d-flex justify-content-end">
            <button class="btn btn-primary px-5" type="submit"><span data-feather="save" class="me-2"></span>Lưu tùy biến</button>
          </div>
          
        </form>
      </div>
    </div>
</div>



<?php require __DIR__ . '/../layouts/footer.php'; ?>
