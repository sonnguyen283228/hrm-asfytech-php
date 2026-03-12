<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="auth-wrap">
  <div class="auth-card">
    <h2 class="auth-title">Đăng nhập HRM</h2>
    <p class="auth-sub">Quản lý nhân sự và dự án tập trung</p>

    <?php if (!empty($_SESSION['error'])): ?>
      <p style="color:#d73a49;font-weight:600"><?= htmlspecialchars($_SESSION['error']) ?></p>
      <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form method="post" action="/login">
      <label>Email</label>
      <input type="email" name="email" required placeholder="admin@company.local">

      <label>Mật khẩu</label>
      <input type="password" name="password" required placeholder="******">

      <button class="btn btn-asfy auth-btn" type="submit">Đăng nhập</button>
    </form>

    <div style="margin:12px 0;text-align:center" class="muted">hoặc</div>
    <a class="auth-google" href="/auth/google">Đăng nhập bằng Google</a>
  </div>
  <p class="muted" style="text-align:center;margin-top:10px">Demo: admin@company.local / 123456</p>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
