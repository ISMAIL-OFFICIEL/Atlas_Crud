<?php
require_once __DIR__ . '/auth.php';
require_login('admin');
$pdo = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id   = $_POST['id'] ?? '';
    $name = trim($_POST['name'] ?? '');
    $email= trim($_POST['email'] ?? '');
    $role = $_POST['role'] ?? 'rh';
    $pwd  = $_POST['password'] ?? '';

    if ($id === '') {
        $hash = password_hash($pwd, PASSWORD_DEFAULT);
        $pdo->prepare("INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)")
            ->execute([$name,$email,$hash,$role]);
    } else {
        if ($pwd !== '') {
            $hash = password_hash($pwd, PASSWORD_DEFAULT);
            $pdo->prepare("UPDATE users SET name=?, email=?, role=?, password=? WHERE id=?")
                ->execute([$name,$email,$role,$hash,$id]);
        } else {
            $pdo->prepare("UPDATE users SET name=?, email=?, role=? WHERE id=?")
                ->execute([$name,$email,$role,$id]);
        }
    }
    header('Location: users.php'); exit;
}

if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM users WHERE id=?")->execute([$_GET['delete']]);
    header('Location: users.php'); exit;
}

$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $edit = $stmt->fetch();
}
$users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des utilisateurs</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6fc;
            min-height: 100vh;
        }
        .card {
            border-radius: 16px;
            border: none;
            box-shadow: 0 10px 25px rgba(0,0,0,.08);
        }
        .btn-main {
            background: #4f46e5;
            color: white;
        }
        .btn-main:hover {
            background: #4338ca;
            color: white;
        }
        .table thead {
            background: #4f46e5;
            color: white;
        }
    </style>
</head>

<body>

<div class="container my-4">

    <!-- TOP BAR -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Gestion des utilisateurs</h4>
        <div>
            <strong><?= $_SESSION['user']['name'] ?></strong>
            <span class="badge bg-primary"><?= $_SESSION['user']['role'] ?></span>
            <a href="logout.php" class="btn btn-sm btn-outline-danger ms-2">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="mb-3">
                <?= $edit ? "Modifier utilisateur #{$edit['id']}" : "Ajouter un utilisateur" ?>
            </h5>

            <form method="post" class="row g-3">
                <input type="hidden" name="id" value="<?=htmlspecialchars($edit['id'] ?? '')?>">

                <div class="col-md-6">
                    <label class="form-label">Nom</label>
                    <input name="name" class="form-control"
                           value="<?=htmlspecialchars($edit['name'] ?? '')?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input name="email" type="email" class="form-control"
                           value="<?=htmlspecialchars($edit['email'] ?? '')?>" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Rôle</label>
                    <select name="role" class="form-select">
                        <?php foreach (['Admin','Rh','Ceo'] as $r): ?>
                            <option value="<?=$r?>" <?=($edit['role'] ?? '')===$r?'selected':''?>>
                                <?=$r?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-8">
                    <label class="form-label">Mot de passe</label>
                    <input
                        name="password"
                        type="password"
                        class="form-control"
                        placeholder="<?= $edit ? 'Laisser vide pour conserver' : 'Mot de passe' ?>"
                        <?= $edit ? '' : 'required' ?>
                    >
                </div>

                <div class="col-12 d-grid">
                    <button class="btn btn-main">
                        <?= $edit ? 'Mettre à jour' : 'Créer utilisateur' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- USERS TABLE -->
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td class="fw-semibold"><?= $u['name'] ?></td>
                        <td><?= $u['email'] ?></td>
                        <td>
                            <span class="badge bg-info"><?= $u['role'] ?></span>
                        </td>
                        <td>
                            <a href="?edit=<?= $u['id'] ?>" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="?delete=<?= $u['id'] ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Supprimer ?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="text-center mt-3">
        <a href="employees.php" class="text-decoration-none">
            ← Retour aux employés
        </a>
    </div>

</div>

</body>
</html>

