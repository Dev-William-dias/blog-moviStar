<?php
    require_once("templates/header.php");

    require_once("dao/UserDao.php");
    require_once("models/User.php");

    require_once("dao/MovieDao.php");

    $user = new User();

    $userDao = new UserDao($conn, $BASE_URL);
    $movieDao = new MovieDao($conn, $BASE_URL);

    $userData = $userDao->verifyToken(true);

    $userMovies = $movieDao->getMoviesByUserId($userData->id);
?>
    <div id="main-container" class="container-fluid">
        <h2 class="section-title">Dashboard</h2>
        <p class="section-description">Adicione ou atualize as informações dos filmes que você enviou</p>
        <div class="row">
            <div class="col-md-12" id="add-movie-container">
                <a href="newmovie.php" class="btn card-btn">
                    <i class="fas fa-plus"></i> Adicionar Filme
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" id="movies-dashboard">
                <table class="table">
                    <thead>
                        <th scope="col">#</th>
                        <th scope="col">Título</th>
                        <th scope="col">Nota</th>
                        <th scope="col" class="actions-column">Ações</th>
                    </thead>
                    <tbody>
                        <?php foreach($userMovies as $movie): ?>
                            <tr>
                                <td scope="row"><?= $movie->id ?></td>
                                <td><a href="movie.php?id=<?= $movie->id ?>" class="table-movie-title"><?= $movie->title ?></a></td>
                                <td><i class="fas fa-star"></i>9</td>
                                <td class="actions-column">
                                    <a href="editmovie.php?id=<?= $movie->id ?>" class="edit-btn">
                                        <i class="far fa-edit"></i> Editar
                                    </a>
                                    <form action="movie_process.php" method="post">
                                        <input type="hidden" name="type" value="delete">
                                        <input type="hidden" name="id" value="<?= $movie->id ?>">
                                        <button type="submit" class="delete-btn">
                                            <i class="fas fa-times"></i> Deletar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php
    require_once("templates/footer.php");
?>