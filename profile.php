<?php
    require_once("templates/header.php");

    require_once("dao/UserDao.php");
    require_once("models/User.php");
    require_once("dao/MovieDao.php");

    $user = new User();
    $userDao = new UserDao($conn, $BASE_URL);

    $id = filter_input(INPUT_GET, "id");

    if (empty($id)) {

        if (!empty($userData)) {
            $id = $userData->id;
        } else {
            $message->setMessage("Usuario não encontrado!", "error", "index.php");
        }
    } else {

        $userData = $userDao->findById($id);

        if (!$userData) {
            $message->setMessage("Usuario não encontrado!", "error", "index.php");
        }  
    }

    $fullName = $user->getFullName($userData);

    if ($userData->image == "") {
        $userData->image = "user.png";
    }

    $userMovies = $movieDao->getMoviesByUserId($id);

?>
    <div id="main-container" class="container-fluid">
        <div class="col-md-8 offset-md-2">
            <div class="row profile-container">
                <div class="col-md-12">
                    <h1 class="page-title"><?= $fullName ?></h1>
                    <div class="profile-image-container">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
    require_once("templates/footer.php");
?>