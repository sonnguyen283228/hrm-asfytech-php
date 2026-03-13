<?php
require __DIR__ . '/../app/bootstrap.php';

try {
    $db = db();
    
    // Tạo bảng positions
    $sql = "CREATE TABLE IF NOT EXISTS positions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT DEFAULT NULL,
        is_active TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    
    $db->exec($sql);
    echo "Tạo bảng positions thành công.<br>";

    // Thêm cột position_id vào users nếu chưa có
    $checkSql = "SHOW COLUMNS FROM users LIKE 'position_id'";
    $stmt = $db->query($checkSql);
    if ($stmt->rowCount() == 0) {
        $db->exec("ALTER TABLE users ADD COLUMN position_id INT NULL AFTER department_id;");
        echo "Đã thêm cột position_id vào bảng users.<br>";
        
        // Cố gắng migrate data cũ từ cột position (varchar) sang bảng positions
        // 1. Lấy tất cả các position distinct hiện có
        $positions = $db->query("SELECT DISTINCT position FROM users WHERE position IS NOT NULL AND position != ''")->fetchAll(PDO::FETCH_COLUMN);
        
        foreach ($positions as $posName) {
            $stmtInsert = $db->prepare("INSERT INTO positions (name) VALUES (?)");
            $stmtInsert->execute([$posName]);
            $newPosId = $db->lastInsertId();
            
            // Cập nhật lại vào users
            $stmtUpdate = $db->prepare("UPDATE users SET position_id = ? WHERE position = ?");
            $stmtUpdate->execute([$newPosId, $posName]);
        }
        echo "Đã migrate dữ liệu position cũ.<br>";
    } else {
        echo "Cột position_id đã tồn tại trong users.<br>";
    }

} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage();
}
