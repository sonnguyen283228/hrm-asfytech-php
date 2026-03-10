<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="card" style="max-width:520px;margin:40px auto;">
  <h2>Đăng nhập HRM</h2>
  <?php if (!empty($_SESSION['error'])): ?>
    <p style="color:#d73a49"><?= htmlspecialchars($_SESSION['error']) ?></p>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>

  <form method="post" action="/login">
    <label>Email</label>
    <input type="email" name="email" required placeholder="admin@company.local">

    <label>Mật khẩu</label>
    <input type="password" name="password" required placeholder="******">

    <button class="btn btn-primary" type="submit">Đăng nhập</button>
  </form>

  <div style="margin:12px 0;text-align:center" class="muted">hoặc</div>
  <a class="btn btn-google" href="/auth/google">Đăng nhập bằng Google</a>
</div>
<p class="muted" style="text-align:center">Demo: admin@company.local / 123456</p>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
