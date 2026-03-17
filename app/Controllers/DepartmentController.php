<?php

namespace App\Controllers;

class DepartmentController extends Controller
{
    public function index()
    {
        $user = \require_admin();
        $stmt = $this->db()->query('SELECT d.*, COUNT(u.id) AS total_users FROM departments d LEFT JOIN users u ON u.department_id = d.id GROUP BY d.id ORDER BY d.id DESC');
        $departments = $stmt->fetchAll();
        $this->view('departments/index', ['user' => $user, 'departments' => $departments]);
    }

    public function create()
    {
        $user = \require_admin();
        $this->redirect('/departments');
    }

    public function store()
    {
        $user = \require_admin();
        $name = trim((string)($_POST['name'] ?? ''));
        $description = trim((string)($_POST['description'] ?? ''));

        if ($name === '') {
            $_SESSION['error'] = 'Tên phòng ban không được để trống.';
            $this->redirect('/departments');
        }

        $stmt = $this->db()->prepare('INSERT INTO departments(name, description, is_active) VALUES(?,?,1)');
        $stmt->execute([$name, $description ?: null]);

        $_SESSION['success'] = 'Đã tạo phòng ban mới.';
        $this->redirect('/departments');
    }

    public function edit()
    {
        $user = \require_admin();
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $this->db()->prepare('SELECT * FROM departments WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $department = $stmt->fetch();
        if (!$department) {
            $_SESSION['error'] = 'Không tìm thấy phòng ban.';
        }
        $this->redirect('/departments');
    }

    public function update()
    {
        $user = \require_admin();
        $id = (int)($_POST['id'] ?? 0);
        $name = trim((string)($_POST['name'] ?? ''));

        if ($id <= 0 || $name === '') {
            $_SESSION['error'] = 'Dữ liệu phòng ban không hợp lệ.';
            $this->redirect('/departments');
        }

        $stmt = $this->db()->prepare('UPDATE departments SET name = ? WHERE id = ?');
        $stmt->execute([$name, $id]);

        $_SESSION['success'] = 'Đã cập nhật phòng ban.';
        $this->redirect('/departments');
    }

    public function destroy()
    {
        $user = \require_admin();
        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['error'] = 'Phòng ban không hợp lệ.';
            $this->redirect('/departments');
        }

        $stmt = $this->db()->prepare('SELECT COUNT(*) AS total FROM users WHERE department_id = ?');
        $stmt->execute([$id]);
        $total = (int)($stmt->fetch()['total'] ?? 0);

        if ($total > 0) {
            $_SESSION['error'] = 'Không thể xóa phòng ban đang có nhân sự.';
            $this->redirect('/departments');
        }

        $stmt = $this->db()->prepare('DELETE FROM departments WHERE id = ?');
        $stmt->execute([$id]);
        $_SESSION['success'] = 'Đã xóa phòng ban.';
        $this->redirect('/departments');
    }
}
