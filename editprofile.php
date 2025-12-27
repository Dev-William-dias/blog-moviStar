<?php
    require_once("templates/header.php");
    require_once("dao/UserDao.php");
    require_once("models/User.php");

    $user = new User();

    $userDao = new UserDao($conn, $BASE_URL);

    $userData = $userDao->verifyToken(true);

    $fullName = $user->getFullName($userData);

    if ($userData->image == "") {
        $userData->image = "user.png";
    }
?>
    <div id="main-container" class="container-fluid">
        <div class="col-md-12">
            <form action="user_process.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="type" value="update">
                <div class="row">
                    <div class="col-md-4">
                        <h1><?= $fullName ?></h1>
                        <p class="page-description">Altere seus dados no formulário abaixo:</p>
                        <div class="mb-3">
                            <label for="name">Nome:</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Digite seu nome" value="<?= $userData->name ?>">
                        </div>
                        <div class="mb-3">
                            <label for="lastname">Sobrenome:</label>
                            <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Digite seu sobrenome" value="<?= $userData->lastname ?>">
                        </div>
                        <div class="mb-3">
                            <label for="email">E-mail:</label>
                            <input type="email" readonly class="form-control disabled" id="email" name="email" placeholder="Digite seu e-mail" value="<?= $userData->email ?>">
                        </div>   
                        <input type="submit" class="btn card-btn" value="Alterar">      
                    </div>
                    <div class="col-md-4">
                        <div id="profile-image-container" style="background-image: url('img/users/<?= $userData->image?>')"></div>
                        <div class="mb-3">
                            <label for="image">Foto:</label>
                            <input type="file" class="form-control-file" name="image" id="image">
                        </div>
                        <div class="mb-3">
                            <label for="bio">Sobre voce:</label>
                            <textarea class="form-control" name="bio" id="bio" rows="5"><?= $userData->bio?></textarea>
                        </div>
                    </div>
                </div>
            </form>
            <div class="row" id="change-password-container">
                <div class="col-md-4">
                    <h2>Alterar a senha:</h2>
                    <p class="page-description">Digite a nova senha e confirme, para alterar a senha:</p>
                    <form action="user_process.php" method="post">
                        <input type="hidden" name="type" value="changepassword">
                        <input type="hidden" name="id" value="<?= $userData->id?>">
                        <div class="mb-3">
                            <label for="password">Nova senha:</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Digite sua nova senha">
                        </div>
                        <div class="mb-3">
                            <label for="confirmepassword">Confirmação de senha:</label>
                            <input type="password" class="form-control" id="confirmepassword" name="confirmepassword" placeholder="Confirme sua nova senha">
                        </div>   
                        <input type="submit" class="btn card-btn" value="Alterar senha"> 
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php
    require_once("templates/footer.php");
?>