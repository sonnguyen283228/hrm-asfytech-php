<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php $activePage='settings'; require __DIR__ . '/../layouts/sidebar.php'; ?>

<div class="px-sm-4 px-md-5 mt-4">
  <div class="mb-5">
      <h2 class="mb-0 text-900">Tùy biến giao diện App</h2>
      <p class="text-700 mb-0">Thiết lập cấu hình giao diện chung và thông tin hiển thị của hệ thống.</p>
    </div>

    <?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-soft-success d-flex align-items-center mb-4" role="alert">
      <span data-feather="check-circle" class="text-success fs-3 me-3"></span>
      <p class="mb-0 flex-1"><?= htmlspecialchars($_SESSION['success']) ?></p>
      <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['success']); endif; ?>

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
            <!-- Logo Section -->
            <div class="col-12 col-xl-6 border-end-xl">
              <div class="mb-3">
                <label class="form-label">Logo URL hiện tại (nếu có)</label>
                <input class="form-control form-control-sm mb-2" type="text" name="site_logo_url" value="<?= htmlspecialchars($settings['site_logo_url'] ?? '') ?>" placeholder="https://.../logo.png">
              </div>
              <label class="form-label">Tải lên Logo mới (Kéo thả hoặc Click)</label>
              <div class="border-dashed border-2 rounded-3 text-center p-4 cursor-pointer align-items-center d-flex flex-column justify-content-center transition-base hover-bg-100" id="logoDrop" style="min-height: 140px; border-color: #cbd0dd;">
                <span data-feather="image" class="text-400 mb-2" style="width: 32px; height: 32px;"></span>
                <p class="fs--1 text-600 mb-0 fw-semi-bold">Kéo thả logo vào đây hoặc <span class="text-primary">chọn file</span></p>
                <p class="fs--2 text-500 mb-0 mt-1">Hỗ trợ: PNG, JPG, SVG, WEBP (Tối đa 2MB)</p>
                <input id="logoInput" type="file" name="site_logo_file" accept=".png,.jpg,.jpeg,.webp,.svg,.gif" style="display:none">
              </div>
            </div>

            <!-- Favicon Section -->
            <div class="col-12 col-xl-6">
              <div class="mb-3">
                <label class="form-label">Favicon URL hiện tại (nếu có)</label>
                <input class="form-control form-control-sm mb-2" type="text" name="site_favicon_url" value="<?= htmlspecialchars($settings['site_favicon_url'] ?? '') ?>" placeholder="https://.../favicon.ico">
              </div>
              <label class="form-label">Tải lên Favicon mới</label>
              <div class="border-dashed border-2 rounded-3 text-center p-4 cursor-pointer align-items-center d-flex flex-column justify-content-center transition-base hover-bg-100" id="favDrop" style="min-height: 140px; border-color: #cbd0dd;">
                <span data-feather="sun" class="text-400 mb-2" style="width: 32px; height: 32px;"></span>
                <p class="fs--1 text-600 mb-0 fw-semi-bold">Kéo thả favicon vào đây hoặc <span class="text-primary">chọn file</span></p>
                <p class="fs--2 text-500 mb-0 mt-1">Hỗ trợ: ICO, PNG, SVG (Khuyến nghị 32x32px)</p>
                <input id="favInput" type="file" name="site_favicon_file" accept=".ico,.png,.svg" style="display:none">
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

          <hr class="my-4">
          <div class="d-flex justify-content-end">
            <button class="btn btn-primary px-5" type="submit"><span data-feather="save" class="me-2"></span>Lưu tùy biến</button>
          </div>
          
        </form>
      </div>
    </div>
</div>

<script>
function setupDrop(zoneId, inputId){
  const zone = document.getElementById(zoneId), 
        input = document.getElementById(inputId),
        originalHtml = zone.innerHTML;
        
  zone.addEventListener('click', () => input.click());
  
  zone.addEventListener('dragover', (e) => {
    e.preventDefault(); 
    zone.classList.add('bg-200', 'border-primary');
  });
  
  zone.addEventListener('dragleave', () => {
    zone.classList.remove('bg-200', 'border-primary');
  });
  
  zone.addEventListener('drop', (e) => {
    e.preventDefault(); 
    zone.classList.remove('bg-200', 'border-primary');
    
    if(e.dataTransfer.files?.[0]) {
      input.files = e.dataTransfer.files; 
      updateZoneUI(zone, e.dataTransfer.files[0].name);
    }
  });
  
  input.addEventListener('change', () => {
    if(input.files?.[0]) updateZoneUI(zone, input.files[0].name);
  });
  
  function updateZoneUI(zoneElement, fileName) {
      zoneElement.innerHTML = `
        <div class="text-success mb-2"><span data-feather="check-circle" style="width: 32px; height: 32px;"></span></div>
        <p class="fs--1 text-800 fw-bold mb-0">Đã chọn file:</p>
        <p class="fs--1 text-primary mb-0 text-truncate px-3 w-100">${fileName}</p>
      `;
      feather.replace();
  }
}

document.addEventListener("DOMContentLoaded", function() {
    setupDrop('logoDrop', 'logoInput'); 
    setupDrop('favDrop', 'favInput');
});
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
