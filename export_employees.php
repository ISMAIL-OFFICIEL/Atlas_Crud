<?php
require_once __DIR__ . '/auth.php';
require_login();
$pdo = db();

$filters = [
    'name'      => trim($_GET['name'] ?? ''),
    'positions' => trim($_GET['positions'] ?? ''),
    'min_salary'=> trim($_GET['min_salary'] ?? ''),
];
$where = []; $params = [];
if ($filters['name'] !== '')      { $where[] = "name LIKE ?";       $params[] = '%'.$filters['name'].'%'; }
if ($filters['positions'] !== '') { $where[] = "positions LIKE ?";  $params[] = '%'.$filters['positions'].'%'; }
if ($filters['min_salary'] !== ''){ $where[] = "salary >= ?";       $params[] = $filters['min_salary']; }
$whereSql = $where ? ('WHERE '.implode(' AND ',$where)) : '';

$stmt = $pdo->prepare("SELECT id,name,tel,adress,positions,contract,salary FROM employees $whereSql ORDER BY id DESC");
$stmt->execute($params);
$rows = $stmt->fetchAll();

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="employees.csv"');
$out = fopen('php://output', 'w');
fputcsv($out, ['id','name','tel','adress','positions', 'contract','salary']);
foreach ($rows as $r) fputcsv($out, $r);
fclose($out);
exit;
