<?php

require_once("globals.php");
require_once("models/Movie.php");
require_once("models/Message.php");

class MovieDao implements MovieDaoInterface {

    private $conn;
    private $url;
    private $message;

    public function __construct(PDO $conn, $url) {
        $this->conn = $conn;
        $this->url = $url;
        $this->message = new Message($url);
    }

    public function buildMovie($data) {
        $movie = new Movie();

        $movie->id = $data["id"];
        $movie->title = $data["title"];
        $movie->description = $data["description"];
        $movie->image = $data["image"];
        $movie->trailer = $data["trailer"];
        $movie->category = $data["category"];
        $movie->length = $data["length"];
        $movie->users_id = $data["users_id"];

        return $movie;
    }

    public function findAll() {

    }

    public function getLatestMovies() {
        try {
            $movies = [];

            $stmt = $this->conn->query("SELECT * FROM movies ORDER BY id DESC");

            $stmt->execute();

            if ($stmt->rowCount() > 0) {

                $moviesArray = $stmt->fetchAll();

                foreach($moviesArray as $movie) {
                    $movies[] = $this->buildMovie($movie);
                }
            }

            return $movies;
        } catch (PDOException $e) {
            Globals::logError("MovieDao getLatestMovies: ".$e->getMessage());
            return [];
        } 
    }

    public function getMoviesByCategory($category) {
        try {
            $movies = [];

            $stmt = $this->conn->prepare("SELECT * FROM movies WHERE category = :category ORDER BY id DESC");

            $stmt->bindParam(":category", $category);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {

                $moviesArray = $stmt->fetchAll();

                foreach($moviesArray as $movie) {
                    $movies[] = $this->buildMovie($movie);
                }
            }

            return $movies;
        } catch (PDOException $e) {
            Globals::logError("MovieDao getLatestMovies: ".$e->getMessage());
            return [];
        }
    }

    public function getMoviesByUserId($id) {
        try {
            $movies = [];

            $stmt = $this->conn->prepare("SELECT * FROM movies WHERE users_id = :users_id");

            $stmt->bindParam(":users_id", $id);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {

                $moviesArray = $stmt->fetchAll();

                foreach($moviesArray as $movie) {
                    $movies[] = $this->buildMovie($movie);
                }
            }

            return $movies;
        } catch (PDOException $e) {
            Globals::logError("MovieDao getLatestMovies: ".$e->getMessage());
            return [];
        }
    }

    public function findById($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM movies WHERE id = :id");

            $stmt->bindParam(":id", $id);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $movieData = $stmt->fetch();

                $movie = $this->buildMovie($movieData);

                return $movie;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            Globals::logError("MovieDao getLatestMovies: ".$e->getMessage());
            return false;
        }
    }

    public function findByTitle($title) {

    }

    public function create(Movie $movie) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO movies (title, description, image, trailer, category, length, users_id) VALUES (:title, :description, :image, :trailer, :category, :length, :users_id)");
    
            $stmt->bindParam(":title", $movie->title);
            $stmt->bindParam(":description", $movie->description);
            $stmt->bindParam(":image", $movie->image);
            $stmt->bindParam(":trailer", $movie->trailer);
            $stmt->bindParam(":category", $movie->category);
            $stmt->bindParam(":length", $movie->length);
            $stmt->bindParam(":users_id", $movie->users_id);
            
            $stmt->execute();

            $this->message->setMessage("Filme adicionado com sucesso.","success","index.php");

        } catch (PDOException $e) {
            Globals::logError("MovieDao create: ".$e->getMessage());
            $this->message->setMessage("Erro ao adicionar filme.","error","back");     
        }
    }

    public function update(Movie $movie) {
        try {
            $stmt = $this->conn->prepare("UPDATE movies SET title = :title, description = :description, image = :image, trailer = :trailer, category = :category, length = :length WHERE id = :id");
    
            $stmt->bindParam(":title", $movie->title);
            $stmt->bindParam(":description", $movie->description);
            $stmt->bindParam(":image", $movie->image);
            $stmt->bindParam(":trailer", $movie->trailer);
            $stmt->bindParam(":category", $movie->category);
            $stmt->bindParam(":length", $movie->length);
            $stmt->bindParam(":id", $movie->id);

            $stmt->execute();

            $this->message->setMessage("Filme atualizado com sucesso","success","back");

        } catch (PDOException $e) {
            Globals::logError("MovieDao update: ".$e->getMessage());
            $this->message->setMessage("Erro ao atualizar filme.","error","back");     
        }
    }

    public function destroy($id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM movies WHERE id = :id");

            $stmt->bindParam(":id", $id);

            $stmt->execute();

            $this->message->setMessage("Filme removido com sucesso.","success","back");     

        } catch (PDOException $e) {
            Globals::logError("MovieDao getLatestMovies: ".$e->getMessage());
            return false;
        }
    }

}