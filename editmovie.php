<?php
    require_once("templates/header.php");

    require_once("dao/UserDao.php");
    require_once("models/User.php");
    require_once("dao/MovieDao.php");

    $user = new User();

    $userDao = new UserDao($conn, $BASE_URL);
    $movieDao = new MovieDao($conn, $BASE_URL);

    $userData = $userDao->verifyToken(true);

    $id = filter_input(INPUT_GET, "id");

    $movie;

    if (empty($id)) {
        $message->setMessage("O filme não foi encotrado!", "error", "index.php");
    } else {
        $movie = $movieDao->findById($id);

        if (!$movie) {
            $message->setMessage("O filme não foi encotrado!", "error", "index.php");
        }
    }

    if ($movie->image == "") {
        $movie->image = "movie_cover.jpg";
    }

?>
    <div id="main-container" class="container-fluid">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6 offset-md-1">
                    <h1><?= $movie->title ?></h1> 
                    <p class="page-description">Altere os dados do Filme</p>
                    <form id="edit-movie-form" action="movie_process.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="type" value="update">
                        <input type="hidden" name="id" value="<?= $movie->id ?>">
                        <div class="mb-3">
                            <label for="title">Titulo:</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Digite o título do filme" value="<?= $movie->title ?>">
                        </div>
                        <div class="mb-3">
                            <label for="image">Imagem:</label>
                            <input type="file" class="form-control-file" name="image" id="image">
                        </div>
                        <div class="mb-3">
                            <label for="length">Dutação:</label>
                            <input type="text" class="form-control" id="length" name="length" placeholder="Digite a duração do filme" value="<?= $movie->length ?>">
                        </div>
                        <div class="mb-3">
                            <label for="category">Category:</label>
                            <select name="category" id="category" class="form-control">
                                <option value="">Selecione</option>
                                <option value="Ação" <?= $movie->category === "Ação" ? "selected" : "" ?>>Ação</option>
                     $userData           <option value="Drama" <?= $movie->category === "Drama" ? "selected" : "" ?>>Drama</option>
                                <option value="Comédia" <?= $movie->category === "Comédia" ? "selected" : "" ?>>Comédia</option>
                                <option value="Fantasia / Ficção" <?= $movie->category === "Fantasia / Ficção" ? "selected" : "" ?>>Fantas / Ficçãoia</option>
                                <option value="Terror" <?= $movie->category === "Terror" ? "selected" : "" ?>>Terror</option>
                                <option value="Romance" <?= $movie->category === "Romance" ? "selected" : "" ?>>Romance</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="trailer">Trailer:</label>
                            <input type="text" class="form-control" id="trailer" name="trailer" placeholder="Insira o link do trailer" value="<?= $movie->trailer ?>">
                        </div>
                        <div class="mb-3">
                            <label for="description">Descrição:</label>
                            <textarea name="description" id="description" class="form-control" rows="5"><?= $movie->description ?></textarea>
                        </div>
                        <input type="submit" class="btn card-btn" value="Salvar"> 
                    </form>
                </div>
                <div class="col-md-3">
                    <div class="movie-image-container" style="background-image: url('img/movies/<?= $movie->image ?>')"></div>
                </div>
            </div>
        </div>
    </div>
<?php
    require_once("templates/footer.php");
?>