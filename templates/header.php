<?php
    require_once("globals.php");
    require_once("db.php");
    require_once("models/Message.php");
    require_once("dao/UserDao.php");

    $message = new Message($BASE_URL);

    $flassMessage = $message->getMessage();

    if (!empty($flassMessage["msg"])) {
        $message->clearMessage();
    }

    $userDao = new UserDao($conn, $BASE_URL);
                          
    $userData = $userDao->verifyToken(false);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieStar</title>
    <link rel="short icon" href="img/moviestar.ico">
    <!--bootstrap-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.8/css/bootstrap.css" integrity="sha512-zylce7fP6h4usg536JBTRj2rt7q22Z0qicHSlgSK53Irtfkz37ate3KCQ59du+aXZV6R3yyL2X1LyGKBEUMZaw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!--CSS-->
    <link rel="stylesheet" href="css/style.css">
    <!--font awesome-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <header>
        <nav id="main-navbar" class="navbar navbar-expand-lg">
            <a href="/" class="navbar-brand">
                <img src="img/logo.svg" alt="MovieStar" id="logo">
                <span id="moviestar-title">MovieStar</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
            <form action="search.php" method="GET" id="search-form" class="d-flex align-items-center my-2 my-lg-0">   
                <input type="search" name="q" id="search" class="form-control me-2" placeholder="Buscar Filmes" aria-label="Search">
                <button class="btn btn-outline-secondary" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </form>
            <div class="collapse navbar-collapse" id="navbar">
                <ul class="navbar-nav">
                    <?php if($userData): ?>
                        <li class="nav-item">
                            <a href="newmovie.php" class="nav-link">
                                <i class="far fa-plus-square"></i> Incluir Filme
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="dashboard.php" class="nav-link">Meus Filmes</a>
                        </li>
                        <li class="nav-item">
                            <a href="editprofile.php" class="nav-link bold">
                               <?= $userData->name?> 
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="logout.php" class="nav-link">Sair</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a href="auth.php" class="nav-link">Entrar / Cadastrar</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>
    <?php if (!empty($flassMessage["msg"])): ?>
        <div class="msg-container">
            <p class="msg msg-<?= $flassMessage["type"] ?>"><?= $flassMessage["msg"] ?></p>
        </div>
    <?php endif; ?>
    