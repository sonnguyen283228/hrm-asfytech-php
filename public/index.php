<?php

declare(strict_types=1);

require __DIR__ . '/../app/bootstrap.php';

$cfg = require __DIR__ . '/../config/app.php';
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($uri === '/' && $method === 'GET') {
    $user = auth_user();
    if ($user) redirect('/attendance');
    redirect('/login');
}

if ($uri === '/login' && $method === 'GET') { view('auth/login'); exit; }

if ($uri === '/login' && $method === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = db()->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || (int)($user['is_active'] ?? 1) !== 1 || !password_verify($password, $user['password'])) {
        $_SESSION['error'] = 'Sai tĂ i khoáº£n hoáº·c máº­t kháº©u';
        redirect('/login');
    }
    $_SESSION['user_id'] = (int)$user['id'];
    redirect('/attendance');
}

if ($uri === '/auth/google' && $method === 'GET') {
    $g = $cfg['google'];
    if (empty($g['client_id']) || empty($g['client_secret'])) {
        $_SESSION['error'] = 'ChÆ°a cáº¥u hĂ¬nh Google OAuth trong config/app.php';
        redirect('/login');
    }

    $state = bin2hex(random_bytes(16));
    $_SESSION['oauth_state'] = $state;

    $params = http_build_query([
        'client_id' => $g['client_id'],
        'redirect_uri' => $g['redirect_uri'],
        'response_type' => 'code',
        'scope' => 'openid email profile',
        'state' => $state,
        'access_type' => 'online',
        'prompt' => 'select_account'
    ]);
    redirect('https://accounts.google.com/o/oauth2/v2/auth?' . $params);
}

if ($uri === '/auth/google/callback' && $method === 'GET') {
    $g = $cfg['google'];
    $state = $_GET['state'] ?? '';
    $code = $_GET['code'] ?? '';

    if (!$code || !$state || !hash_equals($_SESSION['oauth_state'] ?? '', $state)) {
        $_SESSION['error'] = 'Google OAuth state khĂ´ng há»£p lá»‡.';
        redirect('/login');
    }
    unset($_SESSION['oauth_state']);

    $postData = http_build_query([
        'code' => $code,
        'client_id' => $g['client_id'],
        'client_secret' => $g['client_secret'],
        'redirect_uri' => $g['redirect_uri'],
        'grant_type' => 'authorization_code',
    ]);

    $ch = curl_init('https://oauth2.googleapis.com/token');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postData,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded']
    ]);
    $tokenRes = curl_exec($ch);
    curl_close($ch);

    $token = json_decode((string)$tokenRes, true);
    $accessToken = $token['access_token'] ?? null;
    if (!$accessToken) {
        $_SESSION['error'] = 'KhĂ´ng láº¥y Ä‘Æ°á»£c access token tá»« Google.';
        redirect('/login');
    }

    $ch = curl_init('https://www.googleapis.com/oauth2/v2/userinfo');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $accessToken]
    ]);
    $profileRes = curl_exec($ch);
    curl_close($ch);

    $profile = json_decode((string)$profileRes, true);
    $email = trim((string)($profile['email'] ?? ''));
    $name = trim((string)($profile['name'] ?? 'Google User'));
    $avatar = trim((string)($profile['picture'] ?? ''));

    if ($email === '') {
        $_SESSION['error'] = 'KhĂ´ng láº¥y Ä‘Æ°á»£c email tá»« tĂ i khoáº£n Google.';
        redirect('/login');
    }

    $stmt = db()->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        $randomPass = password_hash(bin2hex(random_bytes(16)), PASSWORD_BCRYPT);
        $stmt = db()->prepare('INSERT INTO users(full_name,email,password,role,is_active,department_id,position,avatar_url,last_seen_at) VALUES(?,?,?,?,1,NULL,?,?,NOW())');
        $stmt->execute([$name, $email, $randomPass, 'staff', 'NhĂ¢n viĂªn', $avatar]);
        $id = (int)db()->lastInsertId();
    } else {
        $id = (int)$user['id'];
        if ((int)($user['is_active'] ?? 1) !== 1) {
            $_SESSION['error'] = 'TĂ i khoáº£n Ä‘Ă£ bá»‹ khĂ³a, vui lĂ²ng liĂªn há»‡ Admin.';
            redirect('/login');
        }
        $stmt = db()->prepare('UPDATE users SET full_name = ?, avatar_url = ?, last_seen_at = NOW() WHERE id = ?');
        $stmt->execute([$name, $avatar, $id]);
    }

    $_SESSION['user_id'] = $id;
    redirect('/attendance');
}

if ($uri === '/logout' && $method === 'GET') { $user = require_auth(); view('auth/logout', ['user' => $user]); exit; }
if ($uri === '/logout' && $method === 'POST') { session_destroy(); redirect('/login'); }

if ($uri === '/attendance' && $method === 'GET') {
    $user = require_auth();
    $today = date('Y-m-d');
    $stmt = db()->prepare('SELECT * FROM attendance_logs WHERE user_id = ? AND work_date = ? LIMIT 1');
    $stmt->execute([$user['id'], $today]);
    $row = $stmt->fetch();

    $stats = [
        'employees' => 0,
        'projects_total' => 0,
        'projects_planning' => 0,
        'projects_in_progress' => 0,
        'projects_paused' => 0,
        'projects_done' => 0,
        'present_today' => 0,
        'my_today_hours' => 0,
    ];

    $stats['employees'] = (int)(db()->query("SELECT COUNT(*) c FROM users WHERE is_active = 1")->fetch()['c'] ?? 0);

    $projectRows = db()->query("SELECT status, COUNT(*) c FROM projects GROUP BY status")->fetchAll();
    foreach ($projectRows as $r) {
        $k = 'projects_' . $r['status'];
        if (array_key_exists($k, $stats)) $stats[$k] = (int)$r['c'];
        $stats['projects_total'] += (int)$r['c'];
    }

    $pStmt = db()->prepare("SELECT COUNT(*) c FROM attendance_logs WHERE work_date = ? AND check_in IS NOT NULL");
    $pStmt->execute([$today]);
    $stats['present_today'] = (int)($pStmt->fetch()['c'] ?? 0);

    $hStmt = db()->prepare("SELECT TIMESTAMPDIFF(MINUTE, check_in, COALESCE(check_out, NOW())) m FROM attendance_logs WHERE user_id = ? AND work_date = ? AND check_in IS NOT NULL LIMIT 1");
    $hStmt->execute([$user['id'], $today]);
    $mins = (int)($hStmt->fetch()['m'] ?? 0);
    $stats['my_today_hours'] = round($mins / 60, 2);

    view('attendance/index', ['user' => $user, 'row' => $row, 'today' => $today, 'stats' => $stats]);
    exit;
}

if ($uri === '/attendance/check-in' && $method === 'POST') {
    $user = require_auth();
    $today = date('Y-m-d');
    $ip = $_SERVER['REMOTE_ADDR'] ?? null;
    $stmt = db()->prepare('INSERT INTO attendance_logs(user_id, work_date, check_in, check_in_ip) VALUES (?, ?, NOW(), ?) ON DUPLICATE KEY UPDATE check_in = COALESCE(check_in, NOW()), check_in_ip = COALESCE(check_in_ip, VALUES(check_in_ip))');
    $stmt->execute([$user['id'], $today, $ip]);
    redirect('/attendance');
}

if ($uri === '/attendance/check-out' && $method === 'POST') {
    $user = require_auth();
    $today = date('Y-m-d');
    $ip = $_SERVER['REMOTE_ADDR'] ?? null;
    $stmt = db()->prepare('UPDATE attendance_logs SET check_out = NOW(), check_out_ip = ? WHERE user_id = ? AND work_date = ?');
    $stmt->execute([$ip, $user['id'], $today]);
    redirect('/attendance');
}


// ===== Attendance reports =====
if ($uri === '/attendance/reports' && $method === 'GET') {
    $user = require_admin();
    $month = trim((string)($_GET['month'] ?? date('Y-m')));
    if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
        $month = date('Y-m');
    }

    $rows = attendance_summary_by_month($month);

    view('attendance/reports', ['user' => $user, 'rows' => $rows, 'month' => $month]);
    exit;
}

if ($uri === '/attendance/export/excel' && $method === 'GET') {
    $user = require_admin();
    $month = trim((string)($_GET['month'] ?? date('Y-m')));
    if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
        $month = date('Y-m');
    }

    $rows = attendance_summary_by_month($month);

    header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
    header('Content-Disposition: attachment; filename="attendance_' . $month . '.xls"');

    echo "Há» tĂªn\tEmail\tNgĂ y cĂ³ máº·t\tTá»•ng giá» lĂ m\n";
    foreach ($rows as $r) {
        $hours = round(((int)$r['worked_minutes']) / 60, 2);
        echo $r['full_name'] . "\t" . $r['email'] . "\t" . (int)$r['present_days'] . "\t" . $hours . "\n";
    }
    exit;
}

if ($uri === '/attendance/export/pdf' && $method === 'GET') {
    $user = require_admin();
    $month = trim((string)($_GET['month'] ?? date('Y-m')));
    if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
        $month = date('Y-m');
    }

    $rows = attendance_summary_by_month($month);

    header('Content-Type: text/html; charset=UTF-8');
    echo '<!doctype html><html><head><meta charset="utf-8"><title>BĂ¡o cĂ¡o cháº¥m cĂ´ng ' . htmlspecialchars($month) . '</title>';
    echo '<style>body{font-family:Arial,sans-serif;padding:24px} table{width:100%;border-collapse:collapse} th,td{border:1px solid #ccc;padding:8px;text-align:left} h2{margin:0 0 16px} .note{margin-top:12px;color:#555}</style>';
    echo '</head><body>';
    echo '<h2>BĂ¡o cĂ¡o cháº¥m cĂ´ng thĂ¡ng ' . htmlspecialchars($month) . '</h2>';
    echo '<table><thead><tr><th>Há» tĂªn</th><th>Email</th><th>NgĂ y cĂ³ máº·t</th><th>Tá»•ng giá» lĂ m</th></tr></thead><tbody>';
    foreach ($rows as $r) {
        $hours = round(((int)$r['worked_minutes']) / 60, 2);
        echo '<tr><td>' . htmlspecialchars($r['full_name']) . '</td><td>' . htmlspecialchars($r['email']) . '</td><td>' . (int)$r['present_days'] . '</td><td>' . $hours . '</td></tr>';
    }
    echo '</tbody></table>';
    echo '<p class="note">Máº¹o: Nháº¥n Ctrl+P vĂ  chá»n Save as PDF Ä‘á»ƒ xuáº¥t file PDF.</p>';
    echo '<script>window.print();</script>';
    echo '</body></html>';
    exit;
}

// ===== Employee management =====
if ($uri === '/employees' && $method === 'GET') {
    $user = require_admin();
    $stmt = db()->query('SELECT u.*, d.name AS department_name FROM users u LEFT JOIN departments d ON d.id = u.department_id ORDER BY u.id DESC');
    $employees = $stmt->fetchAll();
    $departments = db()->query('SELECT * FROM departments WHERE is_active = 1 ORDER BY name')->fetchAll();
    view('employees/index', ['user' => $user, 'employees' => $employees, 'departments' => $departments]);
    exit;
}

if ($uri === '/employees/create' && $method === 'GET') {
    $user = require_admin();
    $departments = db()->query('SELECT * FROM departments ORDER BY name')->fetchAll();
    view('employees/create', ['user' => $user, 'departments' => $departments]);
    exit;
}

if ($uri === '/employees/create' && $method === 'POST') {
    $user = require_admin();
    $fullName = trim((string)($_POST['full_name'] ?? ''));
    $email = trim((string)($_POST['email'] ?? ''));
    $role = trim((string)($_POST['role'] ?? 'staff'));
    $departmentId = (int)($_POST['department_id'] ?? 0);
    $position = trim((string)($_POST['position'] ?? 'NhĂ¢n viĂªn'));

    $phone = trim((string)($_POST['phone'] ?? ''));
    $addressWard = trim((string)($_POST['address_ward'] ?? ''));
    $addressCity = trim((string)($_POST['address_city'] ?? ''));
    $startDate = trim((string)($_POST['start_date'] ?? ''));
    $birthDate = trim((string)($_POST['birth_date'] ?? ''));
    $baseSalary = (int)($_POST['base_salary'] ?? 0);

    if ($fullName === '' || $email === '') {
        $_SESSION['error'] = 'Vui lĂ²ng nháº­p Ä‘áº§y Ä‘á»§ há» tĂªn vĂ  email.';
        redirect('/employees');
    }

    if (!is_gmail($email)) {
        $_SESSION['error'] = 'Email pháº£i lĂ  @gmail.com';
        redirect('/employees');
    }
    if (!is_vn_phone($phone)) {
        $_SESSION['error'] = 'Sá»‘ Ä‘iá»‡n thoáº¡i chÆ°a Ä‘Ăºng chuáº©n VN.';
        redirect('/employees');
    }
    $age = age_from_birthdate($birthDate);
    if ($age === null || $age < 18) {
        $_SESSION['error'] = 'NhĂ¢n sá»± pháº£i tá»« 18 tuá»•i trá»Ÿ lĂªn.';
        redirect('/employees');
    }
    if ($baseSalary < 0) {
        $_SESSION['error'] = 'LÆ°Æ¡ng cÆ¡ báº£n khĂ´ng há»£p lá»‡.';
        redirect('/employees');
    }

    $randomPass = password_hash(bin2hex(random_bytes(16)), PASSWORD_BCRYPT);
    $stmt = db()->prepare('INSERT INTO users(full_name,email,phone,address_ward,address_city,start_date,birth_date,base_salary,password,role,is_active,department_id,position,avatar_url,last_seen_at) VALUES(?,?,?,?,?,?,?,?,?,?,1,?,?,?,?,NOW())');
    $stmt->execute([$fullName, $email, $phone, $addressWard, $addressCity, $startDate ?: null, $birthDate ?: null, $baseSalary, $randomPass, $role, $departmentId ?: null, $position, null]);

    $_SESSION['success'] = 'ÄĂ£ thĂªm nhĂ¢n sá»±. Avatar sáº½ tá»± Ä‘á»“ng bá»™ khi nhĂ¢n sá»± Ä‘Äƒng nháº­p Google láº§n Ä‘áº§u.';
    redirect('/employees');
}


// ===== Department management =====
if ($uri === '/departments' && $method === 'GET') {
    $user = require_admin();
    $stmt = db()->query('SELECT d.*, COUNT(u.id) AS total_users FROM departments d LEFT JOIN users u ON u.department_id = d.id GROUP BY d.id ORDER BY d.id DESC');
    $departments = $stmt->fetchAll();
    view('departments/index', ['user' => $user, 'departments' => $departments]);
    exit;
}

if ($uri === '/departments/create' && $method === 'GET') {
    $user = require_admin();
    view('departments/create', ['user' => $user]);
    exit;
}

if ($uri === '/departments/create' && $method === 'POST') {
    $user = require_admin();
    $name = trim((string)($_POST['name'] ?? ''));
    $description = trim((string)($_POST['description'] ?? ''));

    if ($name === '') {
        $_SESSION['error'] = 'TĂªn phĂ²ng ban khĂ´ng Ä‘Æ°á»£c Ä‘á»ƒ trá»‘ng.';
        redirect('/departments');
    }

    $stmt = db()->prepare('INSERT INTO departments(name, description, is_active) VALUES(?,?,1)');
    $stmt->execute([$name, $description ?: null]);

    $_SESSION['success'] = 'ÄĂ£ táº¡o phĂ²ng ban má»›i.';
    redirect('/departments');
}

if ($uri === '/departments/edit' && $method === 'GET') {
    $user = require_admin();
    $id = (int)($_GET['id'] ?? 0);
    $stmt = db()->prepare('SELECT * FROM departments WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    $department = $stmt->fetch();
    if (!$department) {
        $_SESSION['error'] = 'KhĂ´ng tĂ¬m tháº¥y phĂ²ng ban.';
        redirect('/departments');
    }
    view('departments/edit', ['user' => $user, 'department' => $department]);
    exit;
}

if ($uri === '/departments/edit' && $method === 'POST') {
    $user = require_admin();
    $id = (int)($_POST['id'] ?? 0);
    $name = trim((string)($_POST['name'] ?? ''));

    if ($id <= 0 || $name === '') {
        $_SESSION['error'] = 'Dá»¯ liá»‡u phĂ²ng ban khĂ´ng há»£p lá»‡.';
        redirect('/departments');
    }

    $stmt = db()->prepare('UPDATE departments SET name = ? WHERE id = ?');
    $stmt->execute([$name, $id]);

    $_SESSION['success'] = 'ÄĂ£ cáº­p nháº­t phĂ²ng ban.';
    redirect('/departments');
}

if ($uri === '/departments/delete' && $method === 'POST') {
    $user = require_admin();
    $id = (int)($_POST['id'] ?? 0);

    if ($id <= 0) {
        $_SESSION['error'] = 'PhĂ²ng ban khĂ´ng há»£p lá»‡.';
        redirect('/departments');
    }

    $stmt = db()->prepare('SELECT COUNT(*) AS total FROM users WHERE department_id = ?');
    $stmt->execute([$id]);
    $total = (int)($stmt->fetch()['total'] ?? 0);

    if ($total > 0) {
        $_SESSION['error'] = 'KhĂ´ng thá»ƒ xĂ³a phĂ²ng ban Ä‘ang cĂ³ nhĂ¢n sá»±.';
        redirect('/departments');
    }

    $stmt = db()->prepare('DELETE FROM departments WHERE id = ?');
    $stmt->execute([$id]);
    $_SESSION['success'] = 'ÄĂ£ xĂ³a phĂ²ng ban.';
    redirect('/departments');
}


// ===== Project management =====
if ($uri === '/projects' && $method === 'GET') {
    $user = require_admin();

    $projects = projects_overview();

    view('projects/index', ['user' => $user, 'projects' => $projects]);
    exit;
}

if ($uri === '/projects/create' && $method === 'GET') {
    $user = require_admin();
    view('projects/create', ['user' => $user]);
    exit;
}

if ($uri === '/projects/create' && $method === 'POST') {
    $user = require_admin();

    $name = trim((string)($_POST['name'] ?? ''));
    $startDate = trim((string)($_POST['start_date'] ?? ''));
    $description = trim((string)($_POST['description'] ?? ''));

    if ($name === '' || $startDate === '') {
        $_SESSION['error'] = 'Vui lĂ²ng nháº­p Ä‘áº§y Ä‘á»§ thĂ´ng tin dá»± Ă¡n.';
        redirect('/projects/create');
    }

    $stmt = db()->prepare('INSERT INTO projects(name,start_date,duration_months,description,status) VALUES(?,?,?,?,?)');
    $stmt->execute([$name, $startDate, null, $description, 'planning']);

    $_SESSION['success'] = 'ÄĂ£ táº¡o dá»± Ă¡n má»›i.';
    redirect('/projects');
}

if ($uri === '/projects/view' && $method === 'GET') {
    $user = require_admin();
    $projectId = (int)($_GET['id'] ?? 0);

    $stmt = db()->prepare('SELECT * FROM projects WHERE id = ? LIMIT 1');
    $stmt->execute([$projectId]);
    $project = $stmt->fetch();
    if (!$project) {
        $_SESSION['error'] = 'KhĂ´ng tĂ¬m tháº¥y dá»± Ă¡n.';
        redirect('/projects');
    }

    $modules = db()->prepare('SELECT * FROM project_modules WHERE project_id = ? ORDER BY id DESC');
    $modules->execute([$projectId]);
    $modules = $modules->fetchAll();

    $members = db()->prepare('SELECT pm.*, u.full_name, u.email, u.avatar_url, d.name AS department_name
                              FROM project_members pm
                              JOIN users u ON u.id = pm.user_id
                              LEFT JOIN departments d ON d.id = u.department_id
                              WHERE pm.project_id = ?
                              ORDER BY pm.id DESC');
    $members->execute([$projectId]);
    $members = $members->fetchAll();

    $users = db()->query('SELECT id, full_name, email FROM users ORDER BY full_name')->fetchAll();

    view('projects/view', [
        'user' => $user,
        'project' => $project,
        'modules' => $modules,
        'members' => $members,
        'users' => $users,
    ]);
    exit;
}

if ($uri === '/projects/modules/create' && $method === 'POST') {
    $user = require_admin();
    $projectId = (int)($_POST['project_id'] ?? 0);
    $name = trim((string)($_POST['name'] ?? ''));
    $plannedMonths = (float)($_POST['planned_months'] ?? 0);

    if ($projectId <= 0 || $name === '' || $plannedMonths <= 0) {
        $_SESSION['error'] = 'ThĂ´ng tin module khĂ´ng há»£p lá»‡.';
        redirect('/projects/view?id=' . $projectId);
    }

    $stmt = db()->prepare('INSERT INTO project_modules(project_id,name,planned_months,status,progress_percent) VALUES(?,?,?,?,?)');
    $stmt->execute([$projectId, $name, $plannedMonths, 'pending', 0]);

    $u = db()->prepare('UPDATE projects p SET duration_months = CEIL((SELECT COALESCE(SUM(duration_days),0) FROM project_details WHERE project_id = p.id)/30) WHERE p.id = ?');
    $u->execute([$projectId]);

    $_SESSION['success'] = 'ÄĂ£ thĂªm module cho dá»± Ă¡n.';
    redirect('/projects/view?id=' . $projectId);
}

if ($uri === '/projects/modules/progress' && $method === 'POST') {
    $user = require_admin();
    $projectId = (int)($_POST['project_id'] ?? 0);
    $moduleId = (int)($_POST['module_id'] ?? 0);
    $progress = (int)($_POST['progress_percent'] ?? 0);
    $status = trim((string)($_POST['status'] ?? 'pending'));

    if ($progress < 0) $progress = 0;
    if ($progress > 100) $progress = 100;
    if (!in_array($status, ['pending','in_progress','done'], true)) {
        $status = 'pending';
    }

    $stmt = db()->prepare('UPDATE project_modules SET progress_percent = ?, status = ? WHERE id = ? AND project_id = ?');
    $stmt->execute([$progress, $status, $moduleId, $projectId]);

    $_SESSION['success'] = 'ÄĂ£ cáº­p nháº­t tiáº¿n Ä‘á»™ module.';
    redirect('/projects/view?id=' . $projectId);
}

if ($uri === '/projects/members/add' && $method === 'POST') {
    $user = require_admin();
    $projectId = (int)($_POST['project_id'] ?? 0);
    $userId = (int)($_POST['user_id'] ?? 0);
    $projectRole = trim((string)($_POST['project_role'] ?? 'Developer'));

    if ($projectId <= 0 || $userId <= 0 || $projectRole === '') {
        $_SESSION['error'] = 'ThĂ´ng tin thĂ nh viĂªn dá»± Ă¡n khĂ´ng há»£p lá»‡.';
        redirect('/projects/view?id=' . $projectId);
    }

    $stmt = db()->prepare('INSERT INTO project_members(project_id,user_id,project_role) VALUES(?,?,?)
                           ON DUPLICATE KEY UPDATE project_role = VALUES(project_role)');
    $stmt->execute([$projectId, $userId, $projectRole]);

    $_SESSION['success'] = 'ÄĂ£ thĂªm/cáº­p nháº­t vai trĂ² nhĂ¢n sá»± trong dá»± Ă¡n.';
    redirect('/projects/view?id=' . $projectId);
}


// ===== Site settings (admin) =====
if ($uri === '/settings/site' && $method === 'GET') {
    $user = require_admin();
    $settings = site_settings();
    view('settings/site', ['user' => $user, 'settings' => $settings]);
    exit;
}

if ($uri === '/settings/site' && $method === 'POST') {
    $user = require_admin();
    $pairs = [
        'site_name' => trim((string)($_POST['site_name'] ?? 'HRM APP')),
        'site_logo_url' => trim((string)($_POST['site_logo_url'] ?? '')),
        'site_favicon_url' => trim((string)($_POST['site_favicon_url'] ?? '')),
        'header_html' => (string)($_POST['header_html'] ?? ''),
        'footer_html' => (string)($_POST['footer_html'] ?? ''),
        'footer_text' => trim((string)($_POST['footer_text'] ?? 'Â© HRM APP')),
    ];
    $stmt = db()->prepare('INSERT INTO site_settings(`key`,`value`) VALUES(?,?) ON DUPLICATE KEY UPDATE `value`=VALUES(`value`)');
    foreach ($pairs as $k => $v) $stmt->execute([$k, $v]);

    $_SESSION['success'] = 'ÄĂ£ cáº­p nháº­t giao diá»‡n site.';
    redirect('/settings/site');
}

http_response_code(404);
echo '404 Not Found';

