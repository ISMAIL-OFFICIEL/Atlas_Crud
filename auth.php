<?php
session_start();
require_once __DIR__ . '/db.php';

function login(string $email, string $password): bool {
    $stmt = db()->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if (!$user) return false;
    if (!password_verify($password, $user['password'])) return false;
    $_SESSION['user'] = ['id'=>$user['id'],'name'=>$user['name'],'role'=>$user['role']];
    return true;
}
function require_login(string $role = null): void {
    if (empty($_SESSION['user'])) { header('Location: login.php'); exit; }
    if ($role && $_SESSION['user']['role'] !== $role) { http_response_code(403); exit('Accès refusé'); }
}
function logout(): void { session_destroy(); header('Location: login.php'); exit; }
