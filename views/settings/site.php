<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="card" style="max-width:920px">
  <h2>Tùy biến giao diện App (Admin)</h2>
  <?php if (!empty($_SESSION['success'])): ?><p style="color:#0a7d34"><?= htmlspecialchars($_SESSION['success']) ?></p><?php unset($_SESSION['success']); endif; ?>

  <form method="post" action="/settings/site" enctype="multipart/form-data">
    <label>Tên site</label>
    <input type="text" name="site_name" value="<?= htmlspecialchars($settings['site_name'] ?? 'HRM APP') ?>">

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
      <div>
        <label>Logo URL (tuỳ chọn)</label>
        <input type="text" name="site_logo_url" value="<?= htmlspecialchars($settings['site_logo_url'] ?? '') ?>" placeholder="https://...logo.png">
        <label>Kéo thả / chọn file Logo</label>
        <div id="logoDrop" style="border:2px dashed #b9cdfa;border-radius:12px;padding:16px;text-align:center;background:#f6f9ff;cursor:pointer">
          Kéo thả logo vào đây hoặc bấm để chọn file
          <input id="logoInput" type="file" name="site_logo_file" accept=".png,.jpg,.jpeg,.webp,.svg,.gif" style="display:none">
        </div>
      </div>

      <div>
        <label>Favicon URL (tuỳ chọn)</label>
        <input type="text" name="site_favicon_url" value="<?= htmlspecialchars($settings['site_favicon_url'] ?? '') ?>" placeholder="https://...favicon.ico">
        <label>Kéo thả / chọn file Favicon</label>
        <div id="favDrop" style="border:2px dashed #b9cdfa;border-radius:12px;padding:16px;text-align:center;background:#f6f9ff;cursor:pointer">
          Kéo thả favicon vào đây hoặc bấm để chọn file
          <input id="favInput" type="file" name="site_favicon_file" accept=".ico,.png,.svg" style="display:none">
        </div>
      </div>
    </div>

    <label style="margin-top:12px">Footer text</label>
    <input type="text" name="footer_text" value="<?= htmlspecialchars($settings['footer_text'] ?? '© HRM APP') ?>">

    <label>Header HTML</label>
    <textarea name="header_html" style="width:100%;min-height:100px;margin-bottom:12px"><?= htmlspecialchars($settings['header_html'] ?? '') ?></textarea>

    <label>Footer HTML</label>
    <textarea name="footer_html" style="width:100%;min-height:100px;margin-bottom:12px"><?= htmlspecialchars($settings['footer_html'] ?? '') ?></textarea>

    <button class="btn btn-primary" type="submit">Lưu tùy biến</button>
    <a class="btn" href="/attendance">Quay lại</a>
  </form>
</div>

<script>
function setupDrop(zoneId, inputId){
  const zone=document.getElementById(zoneId);
  const input=document.getElementById(inputId);
  zone.addEventListener('click',()=>input.click());
  zone.addEventListener('dragover',(e)=>{e.preventDefault(); zone.style.background='#eaf2ff';});
  zone.addEventListener('dragleave',()=>{zone.style.background='#f6f9ff';});
  zone.addEventListener('drop',(e)=>{
    e.preventDefault();
    zone.style.background='#f6f9ff';
    if(e.dataTransfer.files && e.dataTransfer.files[0]){
      input.files=e.dataTransfer.files;
      zone.firstChild.textContent='Đã chọn: '+e.dataTransfer.files[0].name+' ';
    }
  });
  input.addEventListener('change',()=>{
    if(input.files && input.files[0]) zone.firstChild.textContent='Đã chọn: '+input.files[0].name+' ';
  });
}
setupDrop('logoDrop','logoInput');
setupDrop('favDrop','favInput');
</script>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
