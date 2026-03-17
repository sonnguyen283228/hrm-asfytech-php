<?php

namespace App\Controllers;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = \require_auth();
        $today = date('Y-m-d');
        $stmt = $this->db()->prepare('SELECT * FROM attendance_logs WHERE user_id = ? AND work_date = ? LIMIT 1');
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

        $stats['employees'] = (int)($this->db()->query("SELECT COUNT(*) c FROM users WHERE is_active = 1")->fetch()['c'] ?? 0);

        $projectRows = $this->db()->query("SELECT status, COUNT(*) c FROM projects GROUP BY status")->fetchAll();
        foreach ($projectRows as $r) {
            $k = 'projects_' . $r['status'];
            if (array_key_exists($k, $stats)) $stats[$k] = (int)$r['c'];
            $stats['projects_total'] += (int)$r['c'];
        }

        $pStmt = $this->db()->prepare("SELECT COUNT(*) c FROM attendance_logs WHERE work_date = ? AND check_in IS NOT NULL");
        $pStmt->execute([$today]);
        $stats['present_today'] = (int)($pStmt->fetch()['c'] ?? 0);

        $hStmt = $this->db()->prepare("SELECT TIMESTAMPDIFF(MINUTE, check_in, COALESCE(check_out, NOW())) m FROM attendance_logs WHERE user_id = ? AND work_date = ? AND check_in IS NOT NULL LIMIT 1");
        $hStmt->execute([$user['id'], $today]);
        $mins = (int)($hStmt->fetch()['m'] ?? 0);
        $stats['my_today_hours'] = round($mins / 60, 2);

        $this->view('attendance/index', ['user' => $user, 'row' => $row, 'today' => $today, 'stats' => $stats]);
    }

    public function checkIn()
    {
        $user = \require_auth();
        $today = date('Y-m-d');
        $ip = $_SERVER['REMOTE_ADDR'] ?? null;
        $stmt = $this->db()->prepare('INSERT INTO attendance_logs(user_id, work_date, check_in, check_in_ip) VALUES (?, ?, NOW(), ?) ON DUPLICATE KEY UPDATE check_in = COALESCE(check_in, NOW()), check_in_ip = COALESCE(check_in_ip, VALUES(check_in_ip))');
        $stmt->execute([$user['id'], $today, $ip]);
        $this->redirect('/attendance');
    }

    public function checkOut()
    {
        $user = \require_auth();
        $today = date('Y-m-d');
        $ip = $_SERVER['REMOTE_ADDR'] ?? null;
        $stmt = $this->db()->prepare('UPDATE attendance_logs SET check_out = NOW(), check_out_ip = ? WHERE user_id = ? AND work_date = ?');
        $stmt->execute([$ip, $user['id'], $today]);
        $this->redirect('/attendance');
    }

    public function reports()
    {
        $user = \require_admin();
        $month = trim((string)($_GET['month'] ?? date('Y-m')));
        if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
            $month = date('Y-m');
        }

        $rows = \attendance_summary_by_month($month);

        $this->view('attendance/reports', ['user' => $user, 'rows' => $rows, 'month' => $month]);
    }

    public function exportExcel()
    {
        $user = \require_admin();
        $month = trim((string)($_GET['month'] ?? date('Y-m')));
        if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
            $month = date('Y-m');
        }

        $rows = \attendance_summary_by_month($month);

        header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment; filename="attendance_' . $month . '.xls"');

        echo "Họ tên\tEmail\tNgày có mặt\tTổng giờ làm\n";
        foreach ($rows as $r) {
            $hours = round(((int)$r['worked_minutes']) / 60, 2);
            echo $r['full_name'] . "\t" . $r['email'] . "\t" . (int)$r['present_days'] . "\t" . $hours . "\n";
        }
    }

    public function exportPdf()
    {
        $user = \require_admin();
        $month = trim((string)($_GET['month'] ?? date('Y-m')));
        if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
            $month = date('Y-m');
        }

        $rows = \attendance_summary_by_month($month);

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
    }
}
