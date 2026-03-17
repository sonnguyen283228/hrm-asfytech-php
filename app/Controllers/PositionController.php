<?php

namespace App\Controllers;

class PositionController extends Controller
{
    public function index()
    {
        $user = \require_admin();

        $stmt = $this->db()->query('SELECT p.*, COUNT(u.id) AS total_users FROM positions p LEFT JOIN users u ON u.position_id = p.id GROUP BY p.id ORDER BY p.id DESC');
        $positions = $stmt->fetchAll();
        $this->view('positions/index', ['user' => $user, 'positions' => $positions]);
    }

    public function store()
    {
        $user = \require_admin();
        $name = trim((string)($_POST['name'] ?? ''));
        $description = trim((string)($_POST['description'] ?? ''));

        if ($name === '') {
            $_SESSION['error'] = 'Tên chức vụ không được để trống.';
            $this->redirect('/positions');
        }

        $stmt = $this->db()->prepare('INSERT INTO positions(name, description, is_active) VALUES(?,?,1)');
        $stmt->execute([$name, $description ?: null]);

        $_SESSION['success'] = 'Đã tạo chức vụ mới thành công.';
        $this->redirect('/positions');
    }

    public function update()
    {
        $user = \require_admin();
        $id = (int)($_POST['id'] ?? 0);
        $name = trim((string)($_POST['name'] ?? ''));
        $description = trim((string)($_POST['description'] ?? ''));

        if ($id <= 0 || $name === '') {
            $_SESSION['error'] = 'Dữ liệu chức vụ không hợp lệ.';
            $this->redirect('/positions');
        }

        $stmt = $this->db()->prepare('UPDATE positions SET name = ?, description = ? WHERE id = ?');
        $stmt->execute([$name, $description ?: null, $id]);

        $_SESSION['success'] = 'Đã lưu thay đổi chức vụ.';
        $this->redirect('/positions');
    }

    public function toggle()
    {
        $user = \require_admin();
        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['error'] = 'Chức vụ không hợp lệ.';
            $this->redirect('/positions');
        }

        $stmt = $this->db()->prepare('UPDATE positions SET is_active = 1 - COALESCE(is_active, 1) WHERE id = ?');
        $stmt->execute([$id]);
        $_SESSION['success'] = 'Đã thay đổi trạng thái hoạt động của chức vụ.';
        $this->redirect('/positions');
    }

    public function destroy()
    {
        $user = \require_admin();
        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['error'] = 'Chức vụ không hợp lệ.';
            $this->redirect('/positions');
        }

        $stmt = $this->db()->prepare('SELECT COUNT(*) AS total FROM users WHERE position_id = ?');
        $stmt->execute([$id]);
        $total = (int)($stmt->fetch()['total'] ?? 0);

        if ($total > 0) {
            $_SESSION['error'] = 'Không thể xóa vĩnh viễn chức vụ đang có nhân sự đảm nhiệm.';
            $this->redirect('/positions');
        }

        $stmt = $this->db()->prepare('DELETE FROM positions WHERE id = ?');
        $stmt->execute([$id]);
        $_SESSION['success'] = 'Đã xóa vĩnh viễn chức vụ khỏi hệ thống.';
        $this->redirect('/positions');
    }
}
