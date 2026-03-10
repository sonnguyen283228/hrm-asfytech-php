<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="card" style="max-width:520px;margin:40px auto;">
  <h2>Đăng xuất</h2>
  <p>Bạn có chắc muốn đăng xuất khỏi hệ thống?</p>
  <div class="row">
    <form method="post" action="/logout">
      <button class="btn btn-danger" type="submit">Đăng xuất</button>
    </form>
    <a class="btn" href="/attendance">Quay lại</a>
  </div>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
