<?php

namespace App\Controllers;

class ProjectController extends Controller
{
    public function index()
    {
        $user = \require_admin();

        $projects = \projects_overview();

        $this->view('projects/index', ['user' => $user, 'projects' => $projects]);
    }

    public function create()
    {
        $this->redirect('/projects');
    }

    public function store()
    {
        $user = \require_admin();

        $name = trim((string)($_POST['name'] ?? ''));
        $startDate = trim((string)($_POST['start_date'] ?? ''));
        $description = trim((string)($_POST['description'] ?? ''));

        if ($name === '' || $startDate === '') {
            $_SESSION['error'] = 'Vui lòng nhập đầy đủ thông tin dự án.';
            $this->redirect('/projects/create');
        }

        $stmt = $this->db()->prepare('INSERT INTO projects(name,start_date,duration_months,description,status) VALUES(?,?,?,?,?)');
        $stmt->execute([$name, $startDate, null, $description, 'planning']);

        $_SESSION['success'] = 'Đã tạo dự án mới.';
        $this->redirect('/projects');
    }

    public function update()
    {
        $user = \require_admin();
        $id = (int)($_POST['id'] ?? 0);
        $name = trim((string)($_POST['name'] ?? ''));
        $startDate = trim((string)($_POST['start_date'] ?? ''));
        $status = trim((string)($_POST['status'] ?? ''));

        if ($id <= 0 || $name === '' || $startDate === '') {
            $_SESSION['error'] = 'Thông tin dự án không hợp lệ.';
            $this->redirect('/projects');
        }

        $stmt = $this->db()->prepare('UPDATE projects SET name = ?, start_date = ?, status = ? WHERE id = ?');
        $stmt->execute([$name, $startDate, $status, $id]);

        $_SESSION['success'] = 'Cập nhật thông tin dự án thành công.';
        $this->redirect('/projects');
    }

    public function destroy()
    {
        $user = \require_admin();
        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['error'] = 'ID Dự án không hợp lệ.';
            $this->redirect('/projects');
        }

        // Xóa cascade
        $this->db()->prepare('DELETE FROM project_details WHERE project_id = ?')->execute([$id]);
        $this->db()->prepare('DELETE FROM project_modules WHERE project_id = ?')->execute([$id]);
        $this->db()->prepare('DELETE FROM project_members WHERE project_id = ?')->execute([$id]);
        $this->db()->prepare('DELETE FROM projects WHERE id = ?')->execute([$id]);

        $_SESSION['success'] = 'Đã xóa toàn bộ dữ liệu dự án.';
        $this->redirect('/projects');
    }

    public function show()
    {
        $user = \require_admin();
        $projectId = (int)($_GET['id'] ?? 0);

        $stmt = $this->db()->prepare('SELECT * FROM projects WHERE id = ? LIMIT 1');
        $stmt->execute([$projectId]);
        $project = $stmt->fetch();
        if (!$project) {
            $_SESSION['error'] = 'Không tìm thấy dự án.';
            $this->redirect('/projects');
        }

        $modules = $this->db()->prepare('SELECT * FROM project_modules WHERE project_id = ? ORDER BY id DESC');
        $modules->execute([$projectId]);
        $modules = $modules->fetchAll();

        $details = $this->db()->prepare('SELECT pd.*, m.name AS module_name, u.full_name AS assignee_name
                                   FROM project_details pd
                                   LEFT JOIN project_modules m ON m.id = pd.module_id
                                   LEFT JOIN users u ON u.id = pd.assigned_to
                                   WHERE pd.project_id = ? ORDER BY pd.id DESC');
        $details->execute([$projectId]);
        $details = $details->fetchAll();

        // Get free users for assignment
        $assignedSql = "SELECT user_id FROM project_members WHERE project_id = ?";
        $assignedStmt = $this->db()->prepare($assignedSql);
        $assignedStmt->execute([$projectId]);
        $assignedIds = $assignedStmt->fetchAll(\PDO::FETCH_COLUMN);

        $members = [];
        if (!empty($assignedIds)) {
            $inQuery = implode(',', array_fill(0, count($assignedIds), '?'));
            $memStmt = $this->db()->prepare("SELECT * FROM users WHERE id IN ($inQuery)");
            $memStmt->execute($assignedIds);
            $members = $memStmt->fetchAll();
        }

        $allUsers = $this->db()->query('SELECT * FROM users WHERE is_active = 1')->fetchAll();

        $this->view('projects/view', [
            'user' => $user,
            'project' => $project,
            'modules' => $modules,
            'details' => $details,
            'members' => $members,
            'allUsers' => $allUsers
        ]);
    }

    public function storeModule()
    {
        $user = \require_admin();
        $projectId = (int)($_POST['project_id'] ?? 0);
        $name = trim((string)($_POST['name'] ?? ''));
        $plannedMonths = (float)($_POST['planned_months'] ?? 0);

        if ($projectId <= 0 || $name === '' || $plannedMonths <= 0) {
            $_SESSION['error'] = 'Thông tin module không hợp lệ.';
            $this->redirect('/projects/view?id=' . $projectId);
        }

        $stmt = $this->db()->prepare('INSERT INTO project_modules(project_id,name,planned_months,status,progress_percent) VALUES(?,?,?,?,?)');
        $stmt->execute([$projectId, $name, $plannedMonths, 'pending', 0]);

        $u = $this->db()->prepare('UPDATE projects p SET duration_months = CEIL((SELECT COALESCE(SUM(duration_days),0) FROM project_details WHERE project_id = p.id)/30) WHERE p.id = ?');
        $u->execute([$projectId]);

        $_SESSION['success'] = 'Đã thêm module cho dự án.';
        $this->redirect('/projects/view?id=' . $projectId);
    }

    public function updateModuleProgress()
    {
        $user = \require_admin();
        $projectId = (int)($_POST['project_id'] ?? 0);
        $moduleId = (int)($_POST['module_id'] ?? 0);
        $progress = (int)($_POST['progress_percent'] ?? 0);
        $status = trim((string)($_POST['status'] ?? 'pending'));

        if ($progress < 0) $progress = 0;
        if ($progress > 100) $progress = 100;
        if (!in_array($status, ['pending','in_progress','done'], true)) {
            $status = 'pending';
        }

        $stmt = $this->db()->prepare('UPDATE project_modules SET progress_percent = ?, status = ? WHERE id = ? AND project_id = ?');
        $stmt->execute([$progress, $status, $moduleId, $projectId]);

        $_SESSION['success'] = 'Đã cập nhật tiến độ module.';
        $this->redirect('/projects/view?id=' . $projectId);
    }

    public function addMember()
    {
        $user = \require_admin();
        $projectId = (int)($_POST['project_id'] ?? 0);
        $userId = (int)($_POST['user_id'] ?? 0);
        $projectRole = trim((string)($_POST['project_role'] ?? 'Developer'));

        if ($projectId <= 0 || $userId <= 0 || $projectRole === '') {
            $_SESSION['error'] = 'Thông tin thành viên dự án không hợp lệ.';
            $this->redirect('/projects/view?id=' . $projectId);
        }

        $stmt = $this->db()->prepare('INSERT INTO project_members(project_id,user_id,project_role) VALUES(?,?,?)
                               ON DUPLICATE KEY UPDATE project_role = VALUES(project_role)');
        $stmt->execute([$projectId, $userId, $projectRole]);

        $_SESSION['success'] = 'Đã thêm/cập nhật vai trò nhân sự trong dự án.';
        $this->redirect('/projects/view?id=' . $projectId);
    }
}
