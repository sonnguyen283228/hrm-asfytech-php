<?php

declare(strict_types=1);

require __DIR__ . '/../app/bootstrap.php';

// Áp dụng: Auto-setup DB Schema 1 lần duy nhất cho Table Positions
if (empty($_SESSION['auto_setup_db_positions'])) {
    try {
        db()->exec("CREATE TABLE IF NOT EXISTS positions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description TEXT DEFAULT NULL,
            is_active TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        
        $cols = db()->query("SHOW COLUMNS FROM users LIKE 'position_id'")->fetchAll();
        if (count($cols) == 0) {
            db()->exec("ALTER TABLE users ADD COLUMN position_id INT NULL AFTER department_id;");
            
            // Map old data from `position` string to `position_id`
            $usersWithPosition = db()->query("SELECT id, position FROM users WHERE position IS NOT NULL AND position != ''")->fetchAll();
            $posMap = [];
            foreach ($usersWithPosition as $u) {
                $pName = trim((string)$u['position']);
                if (!isset($posMap[$pName])) {
                    $cStmt = db()->prepare("SELECT id FROM positions WHERE name = ?");
                    $cStmt->execute([$pName]);
                    $row = $cStmt->fetch();
                    if ($row) {
                        $posMap[$pName] = (int)$row['id'];
                    } else {
                        $iStmt = db()->prepare("INSERT INTO positions(name, is_active) VALUES (?, 1)");
                        $iStmt->execute([$pName]);
                        $posMap[$pName] = (int)db()->lastInsertId();
                    }
                }
                $uStmt = db()->prepare("UPDATE users SET position_id = ? WHERE id = ?");
                $uStmt->execute([$posMap[$pName], $u['id']]);
            }
        }
        $_SESSION['auto_setup_db_positions'] = true;
    } catch (\Throwable $e) { }
}

$cfg = require __DIR__ . '/../config/app.php';
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$router = new \App\Router();

// Auth Routes
$router->get('/', [\App\Controllers\AuthController::class, 'showLogin']);
$router->get('/login', [\App\Controllers\AuthController::class, 'showLogin']);
$router->post('/login', [\App\Controllers\AuthController::class, 'login']);
$router->get('/auth/google', [\App\Controllers\AuthController::class, 'googleRedirect']);
$router->get('/auth/google/callback', [\App\Controllers\AuthController::class, 'googleCallback']);
$router->get('/logout', [\App\Controllers\AuthController::class, 'showLogout']);
$router->post('/logout', [\App\Controllers\AuthController::class, 'logout']);

// Attendance (Dashboard)
$router->get('/attendance', [\App\Controllers\AttendanceController::class, 'index']);
$router->post('/attendance/check-in', [\App\Controllers\AttendanceController::class, 'checkIn']);
$router->post('/attendance/check-out', [\App\Controllers\AttendanceController::class, 'checkOut']);
$router->get('/attendance/reports', [\App\Controllers\AttendanceController::class, 'reports']);
$router->get('/attendance/export/excel', [\App\Controllers\AttendanceController::class, 'exportExcel']);
$router->get('/attendance/export/pdf', [\App\Controllers\AttendanceController::class, 'exportPdf']);

// Timekeeping
$router->get('/timekeeping', [\App\Controllers\TimekeepingController::class, 'index']);

// Leave Management
$router->get('/leave', [\App\Controllers\LeaveController::class, 'index']);

// Employees
$router->get('/employees', [\App\Controllers\EmployeeController::class, 'index']);
$router->get('/employees/export', [\App\Controllers\EmployeeController::class, 'export']);
$router->get('/employees/create', [\App\Controllers\EmployeeController::class, 'create']);
$router->post('/employees/create', [\App\Controllers\EmployeeController::class, 'store']);
$router->post('/employees/edit', [\App\Controllers\EmployeeController::class, 'update']);
$router->post('/employees/toggle-status', [\App\Controllers\EmployeeController::class, 'toggleStatus']);

// Departments
$router->get('/departments', [\App\Controllers\DepartmentController::class, 'index']);
$router->get('/departments/create', [\App\Controllers\DepartmentController::class, 'create']);
$router->post('/departments/create', [\App\Controllers\DepartmentController::class, 'store']);
$router->get('/departments/edit', [\App\Controllers\DepartmentController::class, 'edit']);
$router->post('/departments/edit', [\App\Controllers\DepartmentController::class, 'update']);
$router->post('/departments/delete', [\App\Controllers\DepartmentController::class, 'destroy']);

// Positions
$router->get('/positions', [\App\Controllers\PositionController::class, 'index']);
$router->post('/positions/create', [\App\Controllers\PositionController::class, 'store']);
$router->post('/positions/edit', [\App\Controllers\PositionController::class, 'update']);
$router->post('/positions/toggle', [\App\Controllers\PositionController::class, 'toggle']);
$router->post('/positions/delete', [\App\Controllers\PositionController::class, 'destroy']);

// Projects
$router->get('/projects', [\App\Controllers\ProjectController::class, 'index']);
$router->get('/projects/create', [\App\Controllers\ProjectController::class, 'create']);
$router->post('/projects/create', [\App\Controllers\ProjectController::class, 'store']);
$router->post('/projects/edit', [\App\Controllers\ProjectController::class, 'update']);
$router->post('/projects/delete', [\App\Controllers\ProjectController::class, 'destroy']);
$router->get('/projects/view', [\App\Controllers\ProjectController::class, 'show']);
$router->post('/projects/modules/create', [\App\Controllers\ProjectController::class, 'storeModule']);
$router->post('/projects/modules/progress', [\App\Controllers\ProjectController::class, 'updateModuleProgress']);
$router->post('/projects/members/add', [\App\Controllers\ProjectController::class, 'addMember']);

// Settings
$router->get('/settings/site', [\App\Controllers\SettingController::class, 'showSiteSettings']);
$router->post('/settings/site', [\App\Controllers\SettingController::class, 'updateSiteSettings']);

$router->dispatch($method, $uri);
