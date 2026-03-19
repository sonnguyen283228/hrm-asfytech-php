<?php

declare(strict_types=1);

// PSR-4 Autoloader cho namespace App\
spl_autoload_register(function ($class) {
    if (str_starts_with($class, 'App\\')) {
        $file = __DIR__ . '/' . str_replace('\\', '/', substr($class, 4)) . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});

$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
session_set_cookie_params([
    'lifetime' => 86400,
    'path' => '/',
    'secure' => $secure,
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

$config = require __DIR__ . '/../config/app.php';
date_default_timezone_set($config['timezone']);

function db(): PDO
{
    static $pdo;
    if ($pdo instanceof PDO) return $pdo;

    $cfg = require __DIR__ . '/../config/app.php';
    $db = $cfg['db'];
    $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', $db['host'], $db['port'], $db['database'], $db['charset']);

    $pdo = new PDO($dsn, $db['username'], $db['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_PERSISTENT => true,
    ]);

    return $pdo;
}

function view(string $file, array $data = []): void
{
    extract($data);
    require __DIR__ . '/../views/' . $file . '.php';
}

function redirect(string $path): void
{
    session_write_close();
    header('Location: ' . $path);
    exit;
}

function auth_user(): ?array
{
    if (empty($_SESSION['user_id'])) return null;
    $stmt = db()->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
    $stmt->execute([$_SESSION['user_id']]);
    $u = $stmt->fetch() ?: null;
    if ($u) {
        $up = db()->prepare('UPDATE users SET last_seen_at = NOW() WHERE id = ?');
        $up->execute([$u['id']]);
    }
    return $u;
}

function require_auth(): array
{
    $u = auth_user();
    if (!$u) redirect('/login');
    return $u;
}

function require_admin(): array
{
    $u = require_auth();
    if (strtolower($u['role'] ?? 'staff') !== 'admin') {
        http_response_code(403);
        exit('403 Forbidden');
    }
    return $u;
}

function attendance_summary_by_month(string $month): array
{
    static $cache = [];
    if (isset($cache[$month])) return $cache[$month];

    $sql = "SELECT u.id AS user_id, u.full_name, u.email,
                   SUM(CASE WHEN al.check_in IS NOT NULL THEN 1 ELSE 0 END) AS present_days,
                   SUM(CASE WHEN al.check_in IS NOT NULL AND al.check_out IS NOT NULL THEN TIMESTAMPDIFF(MINUTE, al.check_in, al.check_out) ELSE 0 END) AS worked_minutes
            FROM users u
            LEFT JOIN attendance_logs al ON al.user_id = u.id AND al.work_date >= CONCAT(?, '-01')
                AND al.work_date < DATE_ADD(CONCAT(?, '-01'), INTERVAL 1 MONTH)
            GROUP BY u.id, u.full_name, u.email
            ORDER BY u.full_name";

    $stmt = db()->prepare($sql);
    $stmt->execute([$month, $month]);
    $cache[$month] = $stmt->fetchAll();
    return $cache[$month];
}

function projects_overview(): array
{
    $sql = "SELECT p.*,
                   COALESCE(x.total_details,0) AS total_modules,
                   COALESCE(x.progress_weighted,0) AS progress_avg
            FROM projects p
            LEFT JOIN (
                SELECT pd.project_id,
                       COUNT(*) AS total_details,
                       CASE WHEN SUM(pd.duration_days) = 0 THEN 0
                            ELSE SUM(pd.progress_percent * pd.duration_days) / SUM(pd.duration_days)
                       END AS progress_weighted
                FROM project_details pd
                GROUP BY pd.project_id
            ) x ON x.project_id = p.id
            ORDER BY p.id DESC";

    return db()->query($sql)->fetchAll();
}


function alert_telegram(string $message): void
{
    try {
        $cfg = require __DIR__ . '/../config/app.php';
        $a = $cfg['alerts'] ?? [];
        if (empty($a['enabled']) || empty($a['telegram_bot_token']) || empty($a['telegram_chat_id'])) {
            return;
        }

        $url = 'https://api.telegram.org/bot' . $a['telegram_bot_token'] . '/sendMessage';
        $payload = http_build_query([
            'chat_id' => $a['telegram_chat_id'],
            'text' => $message,
            'disable_web_page_preview' => true,
        ]);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 3,
            CURLOPT_CONNECTTIMEOUT => 2,
        ]);
        curl_exec($ch);
        curl_close($ch);
    } catch (Throwable $e) {
        // avoid recursive failure
    }
}

set_exception_handler(function(Throwable $e): void {
    $uri = $_SERVER['REQUEST_URI'] ?? '-';
    $method = $_SERVER['REQUEST_METHOD'] ?? '-';
    $host = $_SERVER['HTTP_HOST'] ?? '-';
    $msg = "🚨 HRM Exception
Host: {$host}
{$method} {$uri}
" . get_class($e) . ': ' . $e->getMessage();
    alert_telegram($msg);
    http_response_code(500);
    echo '500 Internal Server Error';
});

set_error_handler(function(int $severity, string $message, string $file, int $line): bool {
    $uri = $_SERVER['REQUEST_URI'] ?? '-';
    $method = $_SERVER['REQUEST_METHOD'] ?? '-';
    $host = $_SERVER['HTTP_HOST'] ?? '-';
    $msg = "⚠️ HRM PHP Error
Host: {$host}
{$method} {$uri}
{$message}
{$file}:{$line}";
    alert_telegram($msg);
    return false; // keep default handling/logging
});

register_shutdown_function(function(): void {
    $last = error_get_last();
    if (!$last) return;
    if (!in_array($last['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) return;

    $uri = $_SERVER['REQUEST_URI'] ?? '-';
    $method = $_SERVER['REQUEST_METHOD'] ?? '-';
    $host = $_SERVER['HTTP_HOST'] ?? '-';
    $msg = "💥 HRM Fatal
Host: {$host}
{$method} {$uri}
{$last['message']}
{$last['file']}:{$last['line']}";
    alert_telegram($msg);
});


function is_gmail(string $email): bool
{
    return (bool)preg_match('/^[A-Za-z0-9._%+-]+@gmail\.com$/', $email);
}

function is_vn_phone(string $phone): bool
{
    return (bool)preg_match('/^(0[3|5|7|8|9])[0-9]{8}$/', $phone);
}

function age_from_birthdate(?string $birthDate): ?int
{
    if (!$birthDate) return null;
    try {
        $d = new DateTime($birthDate);
        $now = new DateTime();
        return (int)$now->diff($d)->y;
    } catch (Throwable $e) {
        return null;
    }
}

function working_tenure_text(?string $startDate): string
{
    if (!$startDate) return '--';
    try {
        $s = new DateTime($startDate);
        $n = new DateTime();
        
        // Nếu ngày bắt đầu ở tương lai
        if ($s > $n) return 'Chưa làm việc';
        
        $diff = $s->diff($n);
        
        $y = $diff->y;
        $m = $diff->m;
        $d = $diff->d;

        $total_months = ($y * 12) + $m;
        if ($total_months < 12) {
            if ($total_months == 0) return ($d > 0) ? $d . ' ngày' : 'Hôm nay';
            return $total_months . ' tháng';
        } else {
            $years = round($total_months / 12, 1);
            return str_replace('.0', '', (string)$years) . ' năm';
        }
    } catch (Throwable $e) {
        return '--';
    }
}

function is_user_online(?string $lastSeenAt, int $minutes = 5): bool
{
    if (!$lastSeenAt) return false;
    try {
        $last = new DateTime($lastSeenAt);
        $now = new DateTime();
        return ($now->getTimestamp() - $last->getTimestamp()) <= ($minutes * 60);
    } catch (Throwable $e) {
        return false;
    }
}


function site_settings(): array
{
    static $cache = null;
    if ($cache !== null) return $cache;
    try {
        $rows = db()->query('SELECT `key`,`value` FROM site_settings')->fetchAll();
        $cache = [];
        foreach ($rows as $r) $cache[$r['key']] = (string)($r['value'] ?? '');
    } catch (Throwable $e) {
        $cache = [];
    }
    return $cache;
}

function site_get(string $key, string $default = ''): string
{
    $s = site_settings();
    return isset($s[$key]) && $s[$key] !== '' ? $s[$key] : $default;
}

function is_feature_enabled(string $featureKey): bool
{
    $enabled = site_get($featureKey, '1') === '1';
    $u = auth_user();
    $isAdmin = $u && strtolower($u['role'] ?? '') === 'admin';
    return $enabled || $isAdmin;
}

function get_brand_logo_url(): string
{
    $baseUrl = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
    $brandDir = __DIR__ . '/../public/brand/';
    $extensions = ['png', 'svg', 'jpg', 'jpeg', 'webp', 'gif'];
    
    // Ưu tiên chuẩn tên gốc
    foreach ($extensions as $ext) {
        if (file_exists($brandDir . 'logo.' . $ext)) {
            return $baseUrl . '/brand/logo.' . $ext . '?v=' . filemtime($brandDir . 'logo.' . $ext);
        }
    }
    
    // Nếu không có tên chuẩn, quét tìm ảnh bất kỳ trong thư mục brand (ko chứa chữ favicon)
    if (is_dir($brandDir)) {
        $files = scandir($brandDir);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..' && $file !== '.keep' && stripos($file, 'favicon') === false) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (in_array($ext, $extensions)) {
                    // Dùng rawurlencode để tránh lỗi khoảng trắng trong tên file (VD: ASFY TECH FS (1).png)
                    return $baseUrl . '/brand/' . rawurlencode($file) . '?v=' . filemtime($brandDir . $file);
                }
            }
        }
    }

    $dbUrl = site_get('site_logo_url', '');
    if ($dbUrl) return strpos($dbUrl, 'http') === 0 ? $dbUrl : $baseUrl . '/' . ltrim($dbUrl, '/');
    return '';
}

function get_brand_favicon_url(): string
{
    $baseUrl = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
    $brandDir = __DIR__ . '/../public/brand/';
    $extensions = ['ico', 'png', 'svg', 'jpg', 'jpeg', 'webp', 'gif'];
    
    // Ưu tiên chuẩn tên gốc
    foreach ($extensions as $ext) {
        if (file_exists($brandDir . 'favicon.' . $ext)) {
            return $baseUrl . '/brand/favicon.' . $ext . '?v=' . filemtime($brandDir . 'favicon.' . $ext);
        }
    }
    
    // Nếu không có tên chuẩn, quét tìm file có chữ favicon trong tên
    if (is_dir($brandDir)) {
        $files = scandir($brandDir);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..' && stripos($file, 'favicon') !== false) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (in_array($ext, $extensions)) {
                    return $baseUrl . '/brand/' . rawurlencode($file) . '?v=' . filemtime($brandDir . $file);
                }
            }
        }
    }

    $dbUrl = site_get('site_favicon_url', '');
    if ($dbUrl) return strpos($dbUrl, 'http') === 0 ? $dbUrl : $baseUrl . '/' . ltrim($dbUrl, '/');
    return '';
}
