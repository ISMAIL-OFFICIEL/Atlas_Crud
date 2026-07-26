<?php
require_once __DIR__.'/auth.php';
require_login();
$pdo = db();
if($_SERVER['REQUEST_METHOD']==='POST'){
  $stmt = $pdo->prepare("INSERT INTO employees (name,tel,adress,positions,contract,salary) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->execute([
    $_POST['name'],
    $_POST['tel'],
    $_POST['adress'],
    $_POST['positions'],
    $_POST['contract'],
    $_POST['salary']
  ]);
  header('Location: employees.php'); exit;
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un employé</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6fc;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .card {
            border-radius: 16px;
            border: none;
            box-shadow: 0 15px 35px rgba(0,0,0,.1);
        }

        .btn-main {
            background: #6c63ff;
            color: white;
        }

        .btn-main:hover {
            background: #554eea;
            color: white;
        }
    </style>
</head>

<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">

            <div class="card">
                <div class="card-body p-4">
                    <h4 class="mb-4 text-center">Ajouter un employé</h4>

                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Nom</label>
                            <input name="name" class="form-control" placeholder="Nom complet" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Téléphone</label>
                            <input name="tel" class="form-control" placeholder="+212..." required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Adresse</label>
                            <input name="adress" class="form-control" placeholder="Adresse" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Poste</label>
                            <input name="positions" class="form-control" placeholder="Poste" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Contract</label>
                            </label>
                            <input name="contract" class="form-control" placeholder="Contract" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Salaire (DH)</label>
                            <input name="salary" type="number" step="0.01" class="form-control" placeholder="Salaire" required>
                        </div>

                        <div class="d-grid">
                            <button class="btn btn-main">
                                Créer l’employé
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="text-center mt-3">
                <a href="employees.php" class="text-decoration-none">
                    ← Retour à la liste
                </a>
            </div>

        </div>
    </div>
</div>

</body>
</html>
