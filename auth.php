<?php
session_start();
require_once __DIR__ . '/db.php';

function login(string $email, string $password): bool {
    $email = trim($email);
    $stmt = db()->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if (!$user) return false;

    $storedPassword = (string) $user['password'];
    if (!password_verify($password, $storedPassword)) {
        $isLegacyPlaintext = hash_equals($storedPassword, $password);
        $isLegacySha1 = preg_match('/\A[a-f0-9]{40}\z/i', $storedPassword) === 1
            && hash_equals(strtolower($storedPassword), sha1($password));
        if (!$isLegacyPlaintext && !$isLegacySha1) return false;

        $newHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = db()->prepare('UPDATE users SET password = ? WHERE id = ?');
        $stmt->execute([$newHash, $user['id']]);
    }

    $_SESSION['user'] = ['id'=>$user['id'],'name'=>$user['name'],'role'=>$user['role']];
    return true;
}
function require_login(string $role = null): void {
    if (empty($_SESSION['user'])) { header('Location: login.php'); exit; }
    if ($role && $_SESSION['user']['role'] !== $role) { http_response_code(403); exit('Accès refusé'); }
}
function logout(): void { session_destroy(); header('Location: login.php'); exit; }
