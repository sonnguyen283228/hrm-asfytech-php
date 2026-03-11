<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="card" style="max-width:860px">
  <h2>Tùy biến giao diện App (Admin)</h2>
  <?php if (!empty($_SESSION['success'])): ?><p style="color:#0a7d34"><?= htmlspecialchars($_SESSION['success']) ?></p><?php unset($_SESSION['success']); endif; ?>

  <form method="post" action="/settings/site">
    <label>Tên site</label>
    <input type="text" name="site_name" value="<?= htmlspecialchars($settings['site_name'] ?? 'HRM APP') ?>">

    <label>Logo URL</label>
    <input type="text" name="site_logo_url" value="<?= htmlspecialchars($settings['site_logo_url'] ?? '') ?>" placeholder="https://...logo.png">

    <label>Favicon URL</label>
    <input type="text" name="site_favicon_url" value="<?= htmlspecialchars($settings['site_favicon_url'] ?? '') ?>" placeholder="https://...favicon.ico">

    <label>Footer text</label>
    <input type="text" name="footer_text" value="<?= htmlspecialchars($settings['footer_text'] ?? '© HRM APP') ?>">

    <label>Header HTML</label>
    <textarea name="header_html" style="width:100%;min-height:100px;margin-bottom:12px"><?= htmlspecialchars($settings['header_html'] ?? '') ?></textarea>

    <label>Footer HTML</label>
    <textarea name="footer_html" style="width:100%;min-height:100px;margin-bottom:12px"><?= htmlspecialchars($settings['footer_html'] ?? '') ?></textarea>

    <button class="btn btn-primary" type="submit">Lưu tùy biến</button>
    <a class="btn" href="/attendance">Quay lại</a>
  </form>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
