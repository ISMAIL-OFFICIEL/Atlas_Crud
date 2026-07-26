<?php 
require_once __DIR__ . '/auth.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (login($_POST['email'], $_POST['password'])) { 
        header('Location: employees.php'); 
        exit; 
    }
    $error = "Identifiants invalides";
}
?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>AtlasTech | Gestion RH</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

<style>
body{
    font-family:'Poppins',sans-serif;
    background:linear-gradient(135deg,#0f2027,#203a43,#2c5364);
    min-height:100vh;
    overflow-x:hidden;
    position:relative;
}

/* Floating shapes */
body::before, body::after{
    content:'';
    position:absolute;
    width:400px;
    height:400px;
    background:rgba(255,255,255,0.05);
    border-radius:50%;
    z-index:0;
}

body::before{
    top:-100px;
    left:-100px;
}

body::after{
    bottom:-150px;
    right:-150px;
}

/* Header */
.hero-header{
    padding:40px 0 20px 0;
    text-align:center;
    color:white;
    position:relative;
    z-index:2;
}

.logo-text{
    font-size:2rem;
    font-weight:700;
    letter-spacing:1px;
}

.logo-text span{
    color:#00e5ff;
}

/* Glass Card */
.login-wrapper{
    backdrop-filter:blur(20px);
    background:rgba(255,255,255,0.08);
    border-radius:20px;
    box-shadow:0 25px 60px rgba(0,0,0,0.4);
    overflow:hidden;
    position:relative;
    z-index:2;
    animation:fadeUp 1s ease forwards;
    opacity:0;
}

@keyframes fadeUp{
    to{
        opacity:1;
        transform:translateY(0);
    }
}

.right-side{
    padding:50px;
    color:white;
}

.left-side{
    background:rgba(255,255,255,0.03);
    display:flex;
    align-items:center;
    justify-content:center;
}

.left-side img{
    max-width:80%;
}

.form-control{
    background:rgba(255,255,255,0.1);
    border:none;
    color:white;
    border-radius:50px;
    padding:12px 20px;
}

.form-control::placeholder{
    color:rgba(255,255,255,0.7);
}

.form-control:focus{
    background:rgba(255,255,255,0.15);
    box-shadow:none;
    color:white;
}

.btn-login{
    background:#00e5ff;
    border:none;
    border-radius:50px;
    padding:12px;
    font-weight:600;
    width:100%;
    transition:0.3s;
}

.btn-login:hover{
    background:white;
    color:#000;
}

.subtitle{
    font-size:0.95rem;
    opacity:0.8;
}

</style>
</head>

<body>

<header class="hero-header">
    <div style="display: flex; align-items: center; justify-content: center; gap: 0px;">
            <img src="logoo.PNG"  style="width: 200px; height: 150px; object-fit: contain;">
            <h3 class="fw-bold text-white m-0" style="font-family: sans-serif;">
                AtlasTech Solutions
            </h3>
        </div>
    <div class="subtitle"> Application de Gestion des Ressources Humaines</div>
</header>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="row login-wrapper">

                <!-- LEFT -->
                <div class="col-md-6 left-side d-none d-md-flex">
                    <img src="login.webp" alt="login illustration">
                </div>

                <!-- RIGHT -->
                <div class="col-md-6 right-side text-center">

                    <h3 class="mb-4">Connexion</h3>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger">
                            <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <form method="post">
                        <div class="mb-3">
                            <input type="email" name="email" class="form-control" placeholder="Email" required>
                        </div>

                        <div class="mb-3">
                            <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
                        </div>

                        <button class="btn btn-login">
                            Se connecter
                        </button>
                    </form>

                </div>

            </div>
        </div>
    </div>
</div>

</body>
</html>

