<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= htmlspecialchars(site_get('site_name', 'HRM PHP')) ?></title>
  <?php $favicon = site_get('site_favicon_url', ''); if ($favicon): ?>
    <link rel="icon" href="<?= htmlspecialchars($favicon) ?>">
  <?php endif; ?>
  <style>
    body{font-family:Arial,sans-serif;background:#f5f7fb;max-width:980px;margin:24px auto;padding:0 12px;color:#1f2937}
    .card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:20px;margin-bottom:16px;box-shadow:0 2px 8px rgba(0,0,0,.04)}
    .btn{padding:10px 14px;border:0;border-radius:8px;cursor:pointer;text-decoration:none;display:inline-block}
    .btn-primary{background:#1f6feb;color:#fff}
    .btn-danger{background:#d73a49;color:#fff}
    .btn-google{background:#fff;border:1px solid #d1d5db;color:#111827}
    input{padding:10px;width:100%;margin-top:6px;margin-bottom:12px;border:1px solid #d1d5db;border-radius:8px}
    .muted{color:#6b7280}
    .row{display:flex;gap:8px;flex-wrap:wrap}
  </style>
</head>
<body>
  <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px">
    <?php $logo = site_get('site_logo_url', ''); if ($logo): ?><img src="<?= htmlspecialchars($logo) ?>" alt="logo" style="height:40px"><?php endif; ?>
    <strong><?= htmlspecialchars(site_get('site_name', 'HRM APP')) ?></strong>
  </div>
  <?= site_get('header_html', '') ?>
