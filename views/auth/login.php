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
      <input type="email" name="email" required placeholder="you@gmail.com">

      <label>Mật khẩu</label>
      <input type="password" name="password" required placeholder="******">

      <button class="btn btn-asfy auth-btn" type="submit">Đăng nhập</button>
    </form>

    <div style="margin:12px 0;text-align:center" class="muted">hoặc</div>
    <a class="auth-google" href="/auth/google">
      <span style="display:inline-flex;align-items:center;justify-content:center;gap:8px">
        <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true">
          <path fill="#EA4335" d="M12 10.2v3.9h5.5c-.2 1.3-1.5 3.9-5.5 3.9-3.3 0-6-2.7-6-6s2.7-6 6-6c1.9 0 3.2.8 3.9 1.5l2.7-2.6C16.9 3.3 14.7 2.4 12 2.4 6.8 2.4 2.6 6.6 2.6 11.8S6.8 21.2 12 21.2c6.9 0 9.2-4.8 9.2-7.3 0-.5 0-.9-.1-1.2H12z"/>
          <path fill="#34A853" d="M3.7 7.2l3.2 2.3C7.8 7.7 9.7 6.4 12 6.4c1.9 0 3.2.8 3.9 1.5l2.7-2.6C16.9 3.3 14.7 2.4 12 2.4 8.4 2.4 5.2 4.5 3.7 7.2z"/>
          <path fill="#4A90E2" d="M12 21.2c2.6 0 4.8-.8 6.4-2.3l-3-2.5c-.8.6-1.9 1-3.4 1-3.9 0-5.2-2.6-5.5-3.9l-3.3 2.5c1.5 2.8 4.5 5.2 8.8 5.2z"/>
          <path fill="#FBBC05" d="M3.2 11.8c0-1 .2-1.9.5-2.8L.4 6.5C-.2 7.8-.6 9.2-.6 10.8s.4 3 1 4.3l3.3-2.5c-.3-.8-.5-1.7-.5-2.8z"/>
        </svg>
        Đăng nhập bằng Google
      </span>
    </a>
  </div>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
