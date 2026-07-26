<?php
require_once __DIR__.'/auth.php';
require_login();
$pdo = db();
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM employees WHERE id=?"); $stmt->execute([$id]);
$emp = $stmt->fetch(); if(!$emp){ http_response_code(404); exit('Introuvable'); }
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails employé</title>

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

        .badge-info-custom {
            background: #e0e7ff;
            color: #4338ca;
        }
    </style>
</head>

<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">

            <div class="card">
                <div class="card-body p-4">
                    <h4 class="mb-1 text-center">Détails de l’employé</h4>
                    <p class="text-center text-muted mb-4">
                        ID : <?= $emp['id'] ?>
                    </p>

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-semibold">Nom</span>
                            <span><?=htmlspecialchars($emp['name'])?></span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-semibold">Téléphone</span>
                            <span><?=htmlspecialchars($emp['tel'])?></span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-semibold">Adresse</span>
                            <span><?=htmlspecialchars($emp['adress'])?></span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-semibold">Poste</span>
                            <span class="badge badge-info-custom">
                                <?=htmlspecialchars($emp['positions'])?>
                            </span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-semibold">Contract</span>
                            <span class="fw-bold">
                                <?=htmlspecialchars($emp['contract'])?>
                            </span>
                        </li>
                           
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-semibold">Salaire</span>
                            <span class="fw-bold">
                                <?=htmlspecialchars($emp['salary'])?> DH
                            </span>
                        </li>
                    </ul>
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

