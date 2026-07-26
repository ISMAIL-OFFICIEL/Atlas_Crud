<?php
require_once __DIR__.'/auth.php';
require_login();
$pdo = db();

$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10; $offset = ($page-1)*$perPage;

$filters = [
    'name'=>trim($_GET['name'] ?? ''),
    'positions'=>trim($_GET['positions'] ?? ''),
    'min_salary'=>trim($_GET['min_salary'] ?? '')
];
$where=[]; $params=[];
if($filters['name']!==''){ $where[]="name LIKE ?"; $params[]='%'.$filters['name'].'%'; }
if($filters['positions']!==''){ $where[]="positions LIKE ?"; $params[]='%'.$filters['positions'].'%'; }
if($filters['min_salary']!==''){ $where[]="salary>=?"; $params[]=$filters['min_salary']; }
$whereSql = $where ? 'WHERE '.implode(' AND ',$where) : '';

$count = $pdo->prepare("SELECT COUNT(*) FROM employees $whereSql"); $count->execute($params);
$total = (int)$count->fetchColumn(); $pages = max(1, (int)ceil($total/$perPage));

$list = $pdo->prepare("SELECT * FROM employees $whereSql ORDER BY id ASC LIMIT $perPage OFFSET $offset");
$list->execute($params); $employees = $list->fetchAll();

$queryNoPage = $_GET; unset($queryNoPage['page']);
$csvLink = 'export_employees.php?'.http_build_query($queryNoPage);
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>RH Dashboard</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6fc;
            overflow-x: hidden;
        }

        /* SIDEBAR */

                    @keyframes slideIn {
                from {
                    transform: translateX(-40px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }

            .sidebar {
                animation: slideIn .6s ease forwards;
            }

            .content {
                animation: fadeUp .7s ease forwards;
            }

            @keyframes fadeUp {
                from {
                    transform: translateY(20px);
                    opacity: 0;
                }
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }

                .sidebar {
            width: 260px;
            height: 100vh;
            background: linear-gradient(180deg, #46bbe5, #6366f1);
            position: fixed;
            left: 0;
            top: 0;
            color: white;
            display: flex;
            flex-direction: column;
        }

        .sidebar .logo {
            font-size: 1.4rem;
            font-weight: bold;
            padding: 25px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,.2);
        }

        .sidebar .menu {
            flex: 1;
            padding-top: 20px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 22px;
            color: rgba(255,255,255,.9);
            text-decoration: none;
            font-size: 0.95rem;
            transition: all .3s ease;
            border-left: 4px solid transparent;
        }

        .sidebar a i {
            font-size: 1.2rem;
        }

        .sidebar a:hover {
            background: rgba(255,255,255,.15);
            padding-left: 28px;
        }

        .sidebar a.active {
            background: rgba(255,255,255,.2);
            border-left: 4px solid #facc15;
            font-weight: 600;
        }

        .sidebar-footer {
            padding: 15px;
            border-top: 1px solid rgba(255,255,255,.2);
        }

        .sidebar-footer a {
            color: #fde68a;
            display: flex;
            align-items: center;    
            gap: 8px;
            text-decoration: none;
        }


        /* CONTENT */
        .content {
            margin-left: 260px;
            padding: 20px;
        }

        .topbar {
            background: white;
            border-radius: 12px;
            padding: 15px 20px;
            box-shadow: 0 10px 20px rgba(0,0,0,.08);
            margin-bottom: 20px;
        }

        .card {
            border-radius: 16px;
            border: none;
            box-shadow: 0 10px 25px rgba(0,0,0,.08);
        }

        .table thead {
            background: #4f46e5;
            color: white;
        }

        
    </style>
</head>

<body>
    <header class="hero-header">
        
    <div class="mb-4 text-center" style="background: linear-gradient(90deg, #87cfea, #dfdada); padding: 15px; border-radius: 0 0 10px 10px;">
        
        <div style="display: flex; align-items: center; justify-content: center; gap: 0px;">
            <h3 class="fw-bold text-dark m-0" style="font-family: sans-serif;">
                AtlasTech Solutions
            </h3>
        </div>
            <h4 class="text-secondary text-uppercase fs-6 mt-1" style="letter-spacing: 2px; font-weight: 500;">
                Gestion des Ressources Humaines
            </h4>
           
        
    </div>
    </header>

<!-- SIDEBAR -->
<?php $role = $_SESSION['user']['role']; ?>
<div class="sidebar">
    <div class="logo" style="padding: 30px">
        Dashboard
    </div>


    <div class="menu">
        <a href="employees.php" class="active">
            <i class="bi bi-people"></i>
            <span>Employés</span>
        </a>

        <?php if($role === 'admin' || $role === 'rh'): ?>
        <a href="employee_create.php">
            <i class="bi bi-person-plus"></i> Ajouter employé
        </a>
        <?php endif; ?>

        <?php if($role === 'admin'): ?>
            <a href="users.php">
                <i class="bi bi-gear"></i> Gestion utilisateurs
            </a>
        <?php endif; ?>
    </div>

    <div class="sidebar-footer">
        <a href="logout.php">
            <i class="bi bi-box-arrow-right"></i>
            <span>Déconnexion</span>
        </a>
    </div>
</div>





<!-- CONTENT -->
<div class="content">

    <!-- TOPBAR -->
    <div class="topbar d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Gestion des employés</h5>
        <div>
            <strong><?= $_SESSION['user']['name'] ?></strong>
            <span class="badge bg-primary"><?= $_SESSION['user']['role'] ?></span>
        </div>
    </div>

    <!-- FILTERS -->
    <div class="card mb-4">
        <div class="card-body">
            <h6 class="mb-3">Filtrer</h6>
            <form method="get" class="row g-3">
                <div class="col-md-4">
                    <input class="form-control" name="name" placeholder="Nom"
                           value="<?=htmlspecialchars($filters['name'])?>">
                </div>
                <div class="col-md-4">
                    <input class="form-control" name="positions" placeholder="Poste"
                           value="<?=htmlspecialchars($filters['positions'])?>">
                </div>
                <div class="col-md-3">
                    <input class="form-control" type="number" step="0.01" name="min_salary"
                           placeholder="Salaire min"
                           value="<?=htmlspecialchars($filters['min_salary'])?>">
                </div>
                <div class="col-md-1 d-grid">
                    <button class="btn btn-primary">
                        <i class="bi bi-funnel"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ACTIONS -->
  
        <div class="d-flex justify-content-between mb-3">
            <?php if($role === 'admin' || $role === 'rh'): ?>
                <a href="employee_create.php" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Ajouter
                </a>
            <?php endif; ?>
            <?php if($role === 'admin'): ?>        
                <a href="<?=$csvLink?>" class="btn btn-outline-primary">
                    <i class="bi bi-download"></i> Export CSV
                </a>
            <?php endif; ?>
        </div>
    

    <!-- TABLE -->
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Tél</th>
                    <th>Adresse</th>
                    <th>Poste</th>
                    <th>Contract</th>
                    <th>Salaire</th>
                    <?php if($role === 'admin' || $role === 'rh'): ?>
                        <th>Actions                        
                    </th><?php endif; ?>
                </tr>
                </thead>
                <tbody>
                <?php foreach($employees as $emp): ?>
                    <tr>
                        <td><?= $emp['id'] ?></td>
                        <td class="fw-semibold"><?= $emp['name'] ?></td>
                        <td>+212 <?= $emp['tel'] ?></td>
                        <td><?= $emp['adress'] ?></td>
                        <td>
                            <span class="badge bg-info">
                                <?= $emp['positions'] ?>
                            </span>
                        </td>
                        <td><?= $emp['contract'] ?></td>
                        <td><?= $emp['salary'] ?> DH</td>
                        <?php if($role === 'admin' || $role === 'rh'): ?>
                            <td>
                                <a href="employee_show.php?id=<?=$emp['id']?>" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="employee_edit.php?id=<?=$emp['id']?>" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="employee_delete.php" method="post" class="d-inline"
                                    onsubmit="return confirm('Supprimer ?');">
                                    <input type="hidden" name="id" value="<?=$emp['id']?>">
                                    <button class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- PAGINATION -->
    <nav class="mt-4">
        <ul class="pagination justify-content-center">
            <?php for($p=1;$p<=$pages;$p++):
                $qs = http_build_query(array_merge($_GET,['page'=>$p])); ?>
                <li class="page-item <?= $p==$page?'active':'' ?>">
                    <a class="page-link" href="?<?=$qs?>"><?=$p?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>

</div>

</body>
</html>
