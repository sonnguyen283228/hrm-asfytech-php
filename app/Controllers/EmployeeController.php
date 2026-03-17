<?php

namespace App\Controllers;

class EmployeeController extends Controller
{
    public function index()
    {
        $user = \require_admin();
        
        $q = trim((string)($_GET['q'] ?? ''));
        $departmentId = trim((string)($_GET['department_id'] ?? ''));
        $month = trim((string)($_GET['month'] ?? ''));

        $sql = "SELECT u.*, d.name AS department_name, p.name AS position_name FROM users u LEFT JOIN departments d ON d.id = u.department_id LEFT JOIN positions p ON p.id = u.position_id WHERE 1=1";
        $params = [];
        
        if ($q !== '') {
            $sql .= " AND (u.full_name LIKE ? OR u.email LIKE ? OR u.phone LIKE ?)";
            $params[] = "%$q%";
            $params[] = "%$q%";
            $params[] = "%$q%";
        }
        
        if ($departmentId !== '') {
            $sql .= " AND u.department_id = ?";
            $params[] = $departmentId;
        }
        
        if ($month !== '') {
            $sql .= " AND DATE_FORMAT(u.start_date, '%Y-%m') = ?";
            $params[] = $month;
        }
        
        $sql .= " ORDER BY u.id DESC";
        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        $employees = $stmt->fetchAll();

        $departments = $this->db()->query('SELECT * FROM departments WHERE is_active = 1 ORDER BY name')->fetchAll();
        
        $positions = [];
        try {
            $positions = $this->db()->query('SELECT * FROM positions WHERE is_active = 1 ORDER BY name')->fetchAll();
        } catch (\Throwable $e) {}

        $this->view('employees/index', ['user' => $user, 'employees' => $employees, 'departments' => $departments, 'positions' => $positions]);
    }

    public function export()
    {
        $user = \require_admin();
        
        $format = trim((string)($_GET['format'] ?? 'excel'));
        $q = trim((string)($_GET['q'] ?? ''));
        $departmentId = trim((string)($_GET['department_id'] ?? ''));
        $month = trim((string)($_GET['month'] ?? ''));

        $sql = "SELECT u.*, d.name AS department_name FROM users u LEFT JOIN departments d ON d.id = u.department_id WHERE 1=1";
        $params = [];
        
        if ($q !== '') {
            $sql .= " AND (u.full_name LIKE ? OR u.email LIKE ? OR u.phone LIKE ?)";
            $params[] = "%$q%";
            $params[] = "%$q%";
            $params[] = "%$q%";
        }
        
        if ($departmentId !== '') {
            $sql .= " AND u.department_id = ?";
            $params[] = $departmentId;
        }
        
        if ($month !== '') {
            $sql .= " AND DATE_FORMAT(u.start_date, '%Y-%m') = ?";
            $params[] = $month;
        }
        
        $sql .= " ORDER BY u.id DESC";
        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        $employees = $stmt->fetchAll();

        if ($format === 'pdf') {
            header('Content-Type: text/html; charset=UTF-8');
            echo '<!doctype html><html><head><meta charset="utf-8"><title>Danh sách nhân sự</title>';
            echo '<style>body{font-family:Arial,sans-serif;padding:24px} table{width:100%;border-collapse:collapse;font-size:14px} th,td{border:1px solid #ccc;padding:8px;text-align:left} h2{margin:0 0 16px} .note{margin-top:12px;color:#555}</style>';
            echo '</head><body>';
            echo '<h2>Danh sách nhân sự</h2>';
            echo '<table><thead><tr><th>STT</th><th>Họ tên</th><th>Email</th><th>SĐT</th><th>Phòng ban</th><th>Chức vụ</th><th>Vai trò</th><th>Ngày bắt đầu</th></tr></thead><tbody>';
            $stt = 1;
            foreach ($employees as $r) {
                $pName = htmlspecialchars($r['position'] ?? '--');
                echo '<tr><td>' . $stt++ . '</td><td>' . htmlspecialchars($r['full_name']) . '</td><td>' . htmlspecialchars($r['email']) . '</td><td>' . htmlspecialchars($r['phone'] ?? '') . '</td><td>' . htmlspecialchars($r['department_name'] ?? '--') . '</td><td>' . $pName . '</td><td>' . htmlspecialchars($r['role']) . '</td><td>' . htmlspecialchars($r['start_date'] ?? '--') . '</td></tr>';
            }
            echo '</tbody></table>';
            echo '<p class="note">Mẹo: Nhấn Ctrl+P và chọn Save as PDF để tải về tệp PDF.</p>';
            echo '<script>window.print();</script>';
            echo '</body></html>';
            exit;
        }

        header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment; filename="employees_' . date('Y_m_d_H_i') . '.xls"');

        echo "STT\tHọ tên\tEmail\tPhòng ban\tVị trí/Chức vụ\tVai trò\tNgày bắt đầu\tLương cơ bản\n";
        $stt = 1;
        foreach ($employees as $r) {
            $pName = htmlspecialchars($r['position'] ?? '');
            echo $stt++ . "\t" . 
                 htmlspecialchars($r['full_name']) . "\t" . 
                 htmlspecialchars($r['email']) . "\t" . 
                 htmlspecialchars($r['department_name'] ?? '--') . "\t" . 
                 $pName . "\t" . 
                 htmlspecialchars($r['role']) . "\t" . 
                 htmlspecialchars($r['start_date'] ?? '--') . "\t" . 
                 ($r['base_salary'] ? number_format((int)$r['base_salary']) . ' ₫' : '--') . "\n";
        }
    }

    public function create()
    {
        $user = \require_admin();
        $departments = $this->db()->query('SELECT * FROM departments ORDER BY name')->fetchAll();
        $this->view('employees/create', ['user' => $user, 'departments' => $departments]);
    }

    public function store()
    {
        $user = \require_admin();
        $fullName = trim((string)($_POST['full_name'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $role = trim((string)($_POST['role'] ?? 'staff'));
        $departmentId = (int)($_POST['department_id'] ?? 0);
        $positionId = (int)($_POST['position_id'] ?? 0);
        $phone = trim((string)($_POST['phone'] ?? ''));
        $addressWard = trim((string)($_POST['address_ward'] ?? ''));
        $addressCity = trim((string)($_POST['address_city'] ?? ''));
        $startDate = trim((string)($_POST['start_date'] ?? ''));
        $birthDate = trim((string)($_POST['birth_date'] ?? ''));
        $baseSalary = (int)($_POST['base_salary'] ?? 0);

        if ($fullName === '' || $email === '') {
            $_SESSION['error'] = 'Vui lòng nhập đầy đủ họ tên và email.';
            $this->redirect('/employees');
        }

        if (!\is_gmail($email)) {
            $_SESSION['error'] = 'Email phải là @gmail.com';
            $this->redirect('/employees');
        }
        if (!\is_vn_phone($phone)) {
            $_SESSION['error'] = 'Số điện thoại chưa đúng chuẩn VN.';
            $this->redirect('/employees');
        }
        $age = \age_from_birthdate($birthDate);
        if ($age === null || $age < 18) {
            $_SESSION['error'] = 'Nhân sự phải từ 18 tuổi trở lên.';
            $this->redirect('/employees');
        }
        if ($baseSalary < 0) {
            $_SESSION['error'] = 'Lương cơ bản không hợp lệ.';
            $this->redirect('/employees');
        }

        $randomPass = password_hash(bin2hex(random_bytes(16)), PASSWORD_BCRYPT);
        $stmt = $this->db()->prepare('INSERT INTO users(full_name,email,phone,address_ward,address_city,start_date,birth_date,base_salary,password,role,is_active,department_id,position_id,avatar_url,last_seen_at) VALUES(?,?,?,?,?,?,?,?,?,?,1,?,?,?,NOW())');
        $stmt->execute([$fullName, $email, $phone, $addressWard, $addressCity, $startDate ?: null, $birthDate ?: null, $baseSalary, $randomPass, $role, $departmentId ?: null, $positionId ?: null, null]);
        $_SESSION['success'] = 'Đã thêm nhân sự. Avatar sẽ tự đồng bộ khi đăng nhập bằng Google lần đầu.';
        $this->redirect('/employees');
    }

    public function update()
    {
        $user = \require_admin();
        
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['error'] = 'Không tìm thấy dữ liệu nhân sự cần sửa.';
            $this->redirect('/employees');
        }
        
        $fullName = trim((string)($_POST['full_name'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $role = trim((string)($_POST['role'] ?? 'staff'));
        $departmentId = (int)($_POST['department_id'] ?? 0);
        $positionId = (int)($_POST['position_id'] ?? 0);

        $phone = trim((string)($_POST['phone'] ?? ''));
        $addressWard = trim((string)($_POST['address_ward'] ?? ''));
        $addressCity = trim((string)($_POST['address_city'] ?? ''));
        $startDate = trim((string)($_POST['start_date'] ?? ''));
        $birthDate = trim((string)($_POST['birth_date'] ?? ''));
        $baseSalary = (int)($_POST['base_salary'] ?? 0);

        if ($fullName === '' || $email === '') {
            $_SESSION['error'] = 'Vui lòng nhập đầy đủ họ tên và email.';
            $this->redirect('/employees');
        }

        if (!\is_gmail($email)) {
            $_SESSION['error'] = 'Email phải là @gmail.com';
            $this->redirect('/employees');
        }
        if (!\is_vn_phone($phone)) {
            $_SESSION['error'] = 'Số điện thoại chưa đúng chuẩn VN.';
            $this->redirect('/employees');
        }
        $age = \age_from_birthdate($birthDate);
        if ($age === null || $age < 18) {
            $_SESSION['error'] = 'Nhân sự phải từ 18 tuổi trở lên. Hãy kiểm tra ngày sinh.';
            $this->redirect('/employees');
        }
        if ($baseSalary < 0) {
            $_SESSION['error'] = 'Lương cơ bản không hợp lệ.';
            $this->redirect('/employees');
        }

        $stmt = $this->db()->prepare('UPDATE users SET full_name=?, email=?, phone=?, address_ward=?, address_city=?, start_date=?, birth_date=?, base_salary=?, role=?, department_id=?, position_id=? WHERE id=?');
        $stmt->execute([
            $fullName, $email, $phone, $addressWard, $addressCity, 
            $startDate ?: null, $birthDate ?: null, $baseSalary, 
            $role, $departmentId ?: null, $positionId ?: null, $id
        ]);

        $_SESSION['success'] = 'Cập nhật hồ sơ nhân sự thành công.';
        $this->redirect('/employees');
    }

    public function toggleStatus()
    {
        $user = \require_admin();
        
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['error'] = 'Yêu cầu không hợp lệ.';
            $this->redirect('/employees');
        }
        
        // Cannot lock self
        if ($id === (int)$user['id']) {
            $_SESSION['error'] = 'Bạn không thể tự khóa tài khoản của chính mình.';
            $this->redirect('/employees');
        }

        $stmt = $this->db()->prepare('UPDATE users SET is_active = 1 - COALESCE(is_active, 1) WHERE id = ?');
        $stmt->execute([$id]);

        $_SESSION['success'] = 'Đã thay đổi trạng thái hoạt động của tài khoản nhân sự.';
        $this->redirect('/employees');
    }
}
