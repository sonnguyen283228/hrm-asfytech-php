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
    :root{
      --brand:#0f9d58;
      --brand-dark:#0a7a44;
      --bg:#f3f7f5;
      --text:#1f2937;
      --muted:#6b7280;
      --card:#ffffff;
      --border:#e5e7eb;
      --shadow:0 10px 25px rgba(2,22,12,.08);
      --radius:14px;
    }
    *{box-sizing:border-box}
    html,body{margin:0;padding:0}
    body{
      font-family:"Inter","Segoe UI",Arial,sans-serif;
      background:radial-gradient(circle at top right,#e9f8ef 0%,var(--bg) 40%,#f7faf8 100%);
      color:var(--text);
      max-width:1120px;
      margin:0 auto;
      padding:18px 14px 30px;
      line-height:1.45;
    }

    .topbar{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      margin-bottom:14px;
      padding:10px 12px;
      background:rgba(255,255,255,.75);
      border:1px solid #d9ece1;
      border-radius:12px;
      backdrop-filter: blur(4px);
    }
    .brand{display:flex;align-items:center;gap:10px;font-weight:700}
    .brand img{height:38px;width:auto;display:block}

    .card{
      background:var(--card);
      border:1px solid var(--border);
      border-radius:var(--radius);
      padding:18px;
      margin-bottom:14px;
      box-shadow:var(--shadow);
    }

    h2,h3{margin:0 0 10px}

    .btn{
      padding:10px 14px;
      border:1px solid #d1d5db;
      border-radius:10px;
      cursor:pointer;
      text-decoration:none;
      display:inline-block;
      color:#111827;
      background:#fff;
      transition:.2s ease;
      font-weight:600;
    }
    .btn:hover{transform:translateY(-1px);box-shadow:0 6px 14px rgba(2,22,12,.08)}
    .btn-primary{background:var(--brand);border-color:var(--brand);color:#fff}
    .btn-primary:hover{background:var(--brand-dark);border-color:var(--brand-dark)}
    .btn-danger{background:#dc2626;border-color:#dc2626;color:#fff}
    .btn-google{background:#fff;border:1px solid #d1d5db;color:#111827}

    input,select,textarea{
      padding:10px;
      width:100%;
      margin-top:6px;
      margin-bottom:12px;
      border:1px solid #d1d5db;
      border-radius:10px;
      background:#fff;
      font:inherit;
    }
    input:focus,select:focus,textarea:focus{
      outline:none;
      border-color:var(--brand);
      box-shadow:0 0 0 3px rgba(15,157,88,.15);
    }

    .muted{color:var(--muted)}
    .row{display:flex;gap:8px;flex-wrap:wrap}

    table{width:100%;border-collapse:collapse}
    th,td{padding:10px 8px;border-bottom:1px solid #edf1ef;text-align:left;vertical-align:top}
    th{background:#f8fbf9;color:#374151;font-weight:700}

    .badge{padding:3px 8px;border-radius:999px;font-size:12px;font-weight:700}
    .badge-online{background:#dcfce7;color:#166534}
    .badge-offline{background:#f3f4f6;color:#6b7280}

    @media (max-width: 768px){
      body{padding:10px}
      .card{padding:14px}
      .btn{width:100%;text-align:center}
      .topbar{flex-direction:column;align-items:flex-start}
    }
  </style>
</head>
<body>
  <div class="topbar">
    <div class="brand">
      <?php $logo = site_get('site_logo_url', ''); if ($logo): ?><img src="<?= htmlspecialchars($logo) ?>" alt="logo"><?php endif; ?>
      <span><?= htmlspecialchars(site_get('site_name', 'HRM APP')) ?></span>
    </div>
    <small class="muted">Tone: #0f9d58</small>
  </div>
  <?= site_get('header_html', '') ?>
