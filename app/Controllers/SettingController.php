<?php

namespace App\Controllers;

class SettingController extends Controller
{
    public function showSiteSettings()
    {
        $user = \require_admin();
        $settings = \site_settings();
        $this->view('settings/site', ['user' => $user, 'settings' => $settings]);
    }

    public function updateSiteSettings()
    {
        $user = \require_admin();
        $pairs = [
            'site_name'        => trim((string)($_POST['site_name'] ?? 'HRM APP')),
            'site_logo_url'    => trim((string)($_POST['site_logo_url'] ?? '')),
            'site_favicon_url' => trim((string)($_POST['site_favicon_url'] ?? '')),
            'header_html'      => (string)($_POST['header_html'] ?? ''),
            'footer_html'      => (string)($_POST['footer_html'] ?? ''),
            'footer_text'      => trim((string)($_POST['footer_text'] ?? '\u00a9 HRM APP')),
            'ff_module_employees'   => (string)($_POST['ff_module_employees'] ?? '0'),
            'ff_module_positions'   => (string)($_POST['ff_module_positions'] ?? '0'),
            'ff_module_departments' => (string)($_POST['ff_module_departments'] ?? '0'),
            'ff_module_projects'    => (string)($_POST['ff_module_projects'] ?? '0'),
            'ff_module_attendance'  => (string)($_POST['ff_module_attendance'] ?? '0'),
        ];

        $stmt = $this->db()->prepare('INSERT INTO site_settings(`key`,`value`) VALUES(?,?) ON DUPLICATE KEY UPDATE `value`=VALUES(`value`)');
        foreach ($pairs as $k => $v) $stmt->execute([$k, $v]);

        $_SESSION['success'] = 'Đã cập nhật giao diện site.';
        $this->redirect('/settings/site');
    }
}
