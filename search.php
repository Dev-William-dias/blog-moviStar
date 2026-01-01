<?php
    require_once("templates/header.php");
    require_once("dao/MovieDao.php");

    $movieDao = new MovieDao($conn, $BASE_URL);

    $q = filter_input(INPUT_GET, "q");

    $movies = $movieDao->findByTitle($q);

?>
    <div id="main-container" class="container-fluid">
        <h2 class="section-title" id="search-title">Você estã buscando por: <span id="search-result"><?= $q?></span></h2>
        <p class="section-description">Resuldados da busca.</p>
        <div class="movies-container">
            <?php foreach($movies as $movie): ?>
                <?php require("templates/movie_card.php"); ?>
            <?php endforeach; ?>
            <?php if(count($movies) === 0): ?>
                <p class="empty-list">Filme não encontrado, <a href="index.php" class="back-link">Voltar</a>.</p>
            <?php endif; ?>
        </div>
<?php
    require_once("templates/footer.php");
?>
