<?php

declare(strict_types=1);
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
    header('Location: ' . $path);
    exit;
}

function auth_user(): ?array
{
    if (empty($_SESSION['user_id'])) return null;
    $stmt = db()->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch() ?: null;
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
    if (($u['role'] ?? 'staff') !== 'admin') {
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
    $sql = "SELECT p.*, COALESCE(m.total_modules,0) AS total_modules, COALESCE(m.progress_avg,0) AS progress_avg
            FROM projects p
            LEFT JOIN (
                SELECT project_id, COUNT(*) AS total_modules, AVG(progress_percent) AS progress_avg
                FROM project_modules
                GROUP BY project_id
            ) m ON m.project_id = p.id
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
