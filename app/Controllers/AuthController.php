<?php

namespace App\Controllers;

class AuthController extends Controller
{
    public function showLogin()
    {
        $user = \auth_user();
        if ($user) {
            $this->redirect('/attendance');
        }
        $this->view('auth/login');
    }

    public function login()
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $stmt = $this->db()->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || (int)($user['is_active'] ?? 1) !== 1 || !password_verify($password, $user['password'])) {
            $_SESSION['error'] = 'Sai tài khoản đăng nhập hoặc mật khẩu';
            $this->redirect('/login');
        }
        $_SESSION['user_id'] = (int)$user['id'];
        $this->redirect('/attendance');
    }

    public function googleRedirect()
    {
        global $cfg;
        $g = $cfg['google'] ?? [];
        if (empty($g['client_id']) || empty($g['client_secret'])) {
            $_SESSION['error'] = 'Chưa cấu hình Google OAuth trong config/app.php';
            $this->redirect('/login');
        }

        $state = bin2hex(random_bytes(16));
        $_SESSION['oauth_state'] = $state;

        $params = http_build_query([
            'client_id' => $g['client_id'],
            'redirect_uri' => $g['redirect_uri'],
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'state' => $state,
            'access_type' => 'online',
            'prompt' => 'select_account'
        ]);
        $this->redirect('https://accounts.google.com/o/oauth2/v2/auth?' . $params);
    }

    public function googleCallback()
    {
        global $cfg;
        $g = $cfg['google'] ?? [];
        $state = $_GET['state'] ?? '';
        $code = $_GET['code'] ?? '';

        if (!$code || !$state || !hash_equals($_SESSION['oauth_state'] ?? '', $state)) {
            $_SESSION['error'] = 'Google OAuth state không hợp lệ.';
            $this->redirect('/login');
        }
        unset($_SESSION['oauth_state']);

        $postData = http_build_query([
            'code' => $code,
            'client_id' => $g['client_id'],
            'client_secret' => $g['client_secret'],
            'redirect_uri' => $g['redirect_uri'],
            'grant_type' => 'authorization_code',
        ]);

        $ch = curl_init('https://oauth2.googleapis.com/token');
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded']
        ]);
        $tokenRes = curl_exec($ch);
        curl_close($ch);

        $token = json_decode((string)$tokenRes, true);
        $accessToken = $token['access_token'] ?? null;
        if (!$accessToken) {
            $_SESSION['error'] = 'Không lấy được access token từ Google.';
            $this->redirect('/login');
        }

        $ch = curl_init('https://www.googleapis.com/oauth2/v2/userinfo');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $accessToken]
        ]);
        $profileRes = curl_exec($ch);
        curl_close($ch);

        $profile = json_decode((string)$profileRes, true);
        $email = trim((string)($profile['email'] ?? ''));
        $name = trim((string)($profile['name'] ?? 'Google User'));
        $avatar = trim((string)($profile['picture'] ?? ''));

        if ($email === '') {
            $_SESSION['error'] = 'Không lấy được email từ tài khoản Google.';
            $this->redirect('/login');
        }

        $stmt = $this->db()->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            $randomPass = password_hash(bin2hex(random_bytes(16)), PASSWORD_BCRYPT);
            $stmt = $this->db()->prepare('INSERT INTO users(full_name,email,password,role,is_active,department_id,position_id,avatar_url,last_seen_at) VALUES(?,?,?,?,1,NULL,NULL,?,NOW())');
            $stmt->execute([$name, $email, $randomPass, 'staff', $avatar]);
            $id = (int)$this->db()->lastInsertId();
        } else {
            $id = (int)$user['id'];
            if ((int)($user['is_active'] ?? 1) !== 1) {
                $_SESSION['error'] = 'Tài khoản đã bị khóa, vui lòng liên hệ Admin.';
                $this->redirect('/login');
            }
            $stmt = $this->db()->prepare('UPDATE users SET full_name = ?, avatar_url = ?, last_seen_at = NOW() WHERE id = ?');
            $stmt->execute([$name, $avatar, $id]);
        }

        $_SESSION['user_id'] = $id;
        $this->redirect('/attendance');
    }

    public function showLogout()
    {
        $user = \require_auth();
        $this->view('auth/logout', ['user' => $user]);
    }

    public function logout()
    {
        session_destroy();
        $this->redirect('/login');
    }
}
