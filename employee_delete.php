<?php
require_once __DIR__.'/auth.php';
require_login();
$pdo = db();
$id = (int)($_POST['id'] ?? 0);
if($id){
  $pdo->prepare("DELETE FROM employees WHERE id=?")->execute([$id]);
}
header('Location: employees.php'); exit;
