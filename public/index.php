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

    if (!$user || !password_verify($password, $user['password'])) {
        $_SESSION['error'] = 'Sai tài khoản hoặc mật khẩu';
        redirect('/login');
    }
    $_SESSION['user_id'] = (int)$user['id'];
    redirect('/attendance');
}

if ($uri === '/auth/google' && $method === 'GET') {
    $g = $cfg['google'];
    if (empty($g['client_id']) || empty($g['client_secret'])) {
        $_SESSION['error'] = 'Chưa cấu hình Google OAuth trong config/app.php';
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
        $_SESSION['error'] = 'Google OAuth state không hợp lệ.';
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
        $_SESSION['error'] = 'Không lấy được access token từ Google.';
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
        $_SESSION['error'] = 'Không lấy được email từ tài khoản Google.';
        redirect('/login');
    }

    $stmt = db()->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        $randomPass = password_hash(bin2hex(random_bytes(16)), PASSWORD_BCRYPT);
        $stmt = db()->prepare('INSERT INTO users(full_name,email,password,role,department_id,position,avatar_url) VALUES(?,?,?,?,NULL,?,?)');
        $stmt->execute([$name, $email, $randomPass, 'staff', 'Nhân viên', $avatar]);
        $id = (int)db()->lastInsertId();
    } else {
        $id = (int)$user['id'];
        $stmt = db()->prepare('UPDATE users SET full_name = ?, avatar_url = ? WHERE id = ?');
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
    view('attendance/index', ['user' => $user, 'row' => $row, 'today' => $today]);
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

    echo "Họ tên\tEmail\tNgày có mặt\tTổng giờ làm\n";
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
    echo '<!doctype html><html><head><meta charset="utf-8"><title>Báo cáo chấm công ' . htmlspecialchars($month) . '</title>';
    echo '<style>body{font-family:Arial,sans-serif;padding:24px} table{width:100%;border-collapse:collapse} th,td{border:1px solid #ccc;padding:8px;text-align:left} h2{margin:0 0 16px} .note{margin-top:12px;color:#555}</style>';
    echo '</head><body>';
    echo '<h2>Báo cáo chấm công tháng ' . htmlspecialchars($month) . '</h2>';
    echo '<table><thead><tr><th>Họ tên</th><th>Email</th><th>Ngày có mặt</th><th>Tổng giờ làm</th></tr></thead><tbody>';
    foreach ($rows as $r) {
        $hours = round(((int)$r['worked_minutes']) / 60, 2);
        echo '<tr><td>' . htmlspecialchars($r['full_name']) . '</td><td>' . htmlspecialchars($r['email']) . '</td><td>' . (int)$r['present_days'] . '</td><td>' . $hours . '</td></tr>';
    }
    echo '</tbody></table>';
    echo '<p class="note">Mẹo: Nhấn Ctrl+P và chọn Save as PDF để xuất file PDF.</p>';
    echo '<script>window.print();</script>';
    echo '</body></html>';
    exit;
}

// ===== Employee management =====
if ($uri === '/employees' && $method === 'GET') {
    $user = require_admin();
    $stmt = db()->query('SELECT u.*, d.name AS department_name FROM users u LEFT JOIN departments d ON d.id = u.department_id ORDER BY u.id DESC');
    $employees = $stmt->fetchAll();
    view('employees/index', ['user' => $user, 'employees' => $employees]);
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
    $position = trim((string)($_POST['position'] ?? 'Nhân viên'));

    if ($fullName === '' || $email === '') {
        $_SESSION['error'] = 'Vui lòng nhập đầy đủ họ tên và email.';
        redirect('/employees/create');
    }

    $randomPass = password_hash(bin2hex(random_bytes(16)), PASSWORD_BCRYPT);
    $stmt = db()->prepare('INSERT INTO users(full_name,email,password,role,department_id,position,avatar_url) VALUES(?,?,?,?,?,?,NULL)');
    $stmt->execute([$fullName, $email, $randomPass, $role, $departmentId ?: null, $position]);

    $_SESSION['success'] = 'Đã thêm nhân sự. Avatar sẽ tự đồng bộ khi nhân sự đăng nhập Google lần đầu.';
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

    if ($name === '') {
        $_SESSION['error'] = 'Tên phòng ban không được để trống.';
        redirect('/departments/create');
    }

    $stmt = db()->prepare('INSERT INTO departments(name) VALUES(?)');
    $stmt->execute([$name]);

    $_SESSION['success'] = 'Đã tạo phòng ban mới.';
    redirect('/departments');
}

if ($uri === '/departments/edit' && $method === 'GET') {
    $user = require_admin();
    $id = (int)($_GET['id'] ?? 0);
    $stmt = db()->prepare('SELECT * FROM departments WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    $department = $stmt->fetch();
    if (!$department) {
        $_SESSION['error'] = 'Không tìm thấy phòng ban.';
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
        $_SESSION['error'] = 'Dữ liệu phòng ban không hợp lệ.';
        redirect('/departments');
    }

    $stmt = db()->prepare('UPDATE departments SET name = ? WHERE id = ?');
    $stmt->execute([$name, $id]);

    $_SESSION['success'] = 'Đã cập nhật phòng ban.';
    redirect('/departments');
}

if ($uri === '/departments/delete' && $method === 'POST') {
    $user = require_admin();
    $id = (int)($_POST['id'] ?? 0);

    if ($id <= 0) {
        $_SESSION['error'] = 'Phòng ban không hợp lệ.';
        redirect('/departments');
    }

    $stmt = db()->prepare('SELECT COUNT(*) AS total FROM users WHERE department_id = ?');
    $stmt->execute([$id]);
    $total = (int)($stmt->fetch()['total'] ?? 0);

    if ($total > 0) {
        $_SESSION['error'] = 'Không thể xóa phòng ban đang có nhân sự.';
        redirect('/departments');
    }

    $stmt = db()->prepare('DELETE FROM departments WHERE id = ?');
    $stmt->execute([$id]);
    $_SESSION['success'] = 'Đã xóa phòng ban.';
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
    $durationMonths = (int)($_POST['duration_months'] ?? 0);
    $description = trim((string)($_POST['description'] ?? ''));

    if ($name === '' || $startDate === '' || $durationMonths <= 0) {
        $_SESSION['error'] = 'Vui lòng nhập đầy đủ thông tin dự án.';
        redirect('/projects/create');
    }

    $stmt = db()->prepare('INSERT INTO projects(name,start_date,duration_months,description,status) VALUES(?,?,?,?,?)');
    $stmt->execute([$name, $startDate, $durationMonths, $description, 'planning']);

    $_SESSION['success'] = 'Đã tạo dự án mới.';
    redirect('/projects');
}

if ($uri === '/projects/view' && $method === 'GET') {
    $user = require_admin();
    $projectId = (int)($_GET['id'] ?? 0);

    $stmt = db()->prepare('SELECT * FROM projects WHERE id = ? LIMIT 1');
    $stmt->execute([$projectId]);
    $project = $stmt->fetch();
    if (!$project) {
        $_SESSION['error'] = 'Không tìm thấy dự án.';
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
        $_SESSION['error'] = 'Thông tin module không hợp lệ.';
        redirect('/projects/view?id=' . $projectId);
    }

    $stmt = db()->prepare('INSERT INTO project_modules(project_id,name,planned_months,status,progress_percent) VALUES(?,?,?,?,?)');
    $stmt->execute([$projectId, $name, $plannedMonths, 'pending', 0]);

    $_SESSION['success'] = 'Đã thêm module cho dự án.';
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

    $_SESSION['success'] = 'Đã cập nhật tiến độ module.';
    redirect('/projects/view?id=' . $projectId);
}

if ($uri === '/projects/members/add' && $method === 'POST') {
    $user = require_admin();
    $projectId = (int)($_POST['project_id'] ?? 0);
    $userId = (int)($_POST['user_id'] ?? 0);
    $projectRole = trim((string)($_POST['project_role'] ?? 'Developer'));

    if ($projectId <= 0 || $userId <= 0 || $projectRole === '') {
        $_SESSION['error'] = 'Thông tin thành viên dự án không hợp lệ.';
        redirect('/projects/view?id=' . $projectId);
    }

    $stmt = db()->prepare('INSERT INTO project_members(project_id,user_id,project_role) VALUES(?,?,?)
                           ON DUPLICATE KEY UPDATE project_role = VALUES(project_role)');
    $stmt->execute([$projectId, $userId, $projectRole]);

    $_SESSION['success'] = 'Đã thêm/cập nhật vai trò nhân sự trong dự án.';
    redirect('/projects/view?id=' . $projectId);
}

http_response_code(404);
echo '404 Not Found';
