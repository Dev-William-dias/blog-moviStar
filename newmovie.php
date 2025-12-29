<?php
    require_once("templates/header.php");

    require_once("dao/UserDao.php");
    require_once("models/User.php");

    $user = new User();

    $userDao = new UserDao($conn, $BASE_URL);

    $userData = $userDao->verifyToken(true);
?>
    <div id="main-container" class="container-fluid">
        <div class="offset-md-4 col-md-4 new-movie-container">
            <h1 class="page-title">Adicionar Filme</h1>
            <p class="page-description">Adicione sua crítica.</p>
            <form action="movie_process.php" id="add-movie-form" method="post" enctype="multipart/form-data">
                <input type="hidden" name="type" value="create">
                <div class="mb-3">
                    <label for="title">Titulo:</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Digite o título do filme">
                </div>
                <div class="mb-3">
                    <label for="image">Imagem:</label>
                    <input type="file" class="form-control-file" name="image" id="image">
                </div>
                <div class="mb-3">
                    <label for="length">Dutação:</label>
                    <input type="text" class="form-control" id="length" name="length" placeholder="Digite a duração do filme">
                </div>
                <div class="mb-3">
                    <label for="category">Category:</label>
                    <select name="category" id="category" class="form-control">
                        <option value="">Selecione</option>
                        <option value="Ação">Ação</option>
                        <option value="Drama">Drama</option>
                        <option value="Comédia">Comédia</option>
                        <option value="Fantasia / Ficção">Fantas / Ficçãoia</option>
                        <option value="Terror">Terror</option>
                        <option value="Romance">Romance</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="trailer">Trailer:</label>
                    <input type="text" class="form-control" id="trailer" name="trailer" placeholder="Insira o link do trailer">
                </div>
                <div class="mb-3">
                    <label for="description">Descrição:</label>
                    <textarea name="description" id="description" class="form-control" rows="5"></textarea>
                </div>
                <input type="submit" class="btn card-btn" value="Alterar">    
            </form>
        </div>
    </div>
<?php
    require_once("templates/footer.php");
?>