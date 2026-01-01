<?php

require_once("globals.php");
require_once("models/Review.php");
require_once("models/Message.php");
require_once("dao/UserDao.php");

class ReviewDao implements ReviewDaoInterface {

    private $conn;
    private $url;
    private $message;

    public function __construct(PDO $conn, $url) {
        $this->conn = $conn;
        $this->url = $url;
        $this->message = new Message($url);
    }

    public function buildReview($data) {

        $reviewObject = new Review();

        $reviewObject->id = $data["id"];
        $reviewObject->rating = $data["rating"];
        $reviewObject->review = $data["review"];
        $reviewObject->users_id = $data["users_id"];
        $reviewObject->movies_id = $data["movies_id"];

        return $reviewObject;
    }

    public function create(Review $review) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO reviews (rating, review, users_id, movies_id) VALUES (:rating, :review, :users_id, :movies_id)");
    
            $stmt->bindParam(":rating", $review->rating);
            $stmt->bindParam(":review", $review->review);
            $stmt->bindParam(":users_id", $review->users_id);
            $stmt->bindParam(":movies_id", $review->movies_id);
            
            $stmt->execute();

            $this->message->setMessage("Comentário salvo!","success","back");

        } catch (PDOException $e) {
            Globals::logError("ReviewDao create: ".$e->getMessage());
            $this->message->setMessage("Erro ao adicionar filme.","error","back");     
        }
    }

    public function getMoviesReview($id) {
        try {
            $reviews = [];

            $stmt = $this->conn->prepare("SELECT * FROM reviews WHERE movies_id = :movies_id");

            $stmt->bindParam(":movies_id", $id);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {

                $reviewsArray = $stmt->fetchAll();

                $userDao = new UserDao($this->conn, $this->url);

                foreach($reviewsArray as $review) {
                    $reviewObject = $this->buildReview($review);

                    $user = $userDao->findById($reviewObject->users_id);

                    $reviewObject->user = $user;

                    $reviews[] = $reviewObject;
                }
            }

            return $reviews;
        } catch (PDOException $e) {
            Globals::logError("ReviewDao getMoviesReview: ".$e->getMessage());
            return [];
        }
    }

    public function hasAlreadyReviewed($id, $users_id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM reviews WHERE movies_id = :movies_id AND users_id = :users_id");

            $stmt->bindParam(":movies_id", $id);
            $stmt->bindParam(":users_id", $users_id);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            Globals::logError("ReviewDao getMoviesReview: ".$e->getMessage());
            return false;
        }
    }

    public function getRating($id) {
        try {

            $stmt = $this->conn->prepare("SELECT * FROM reviews WHERE movies_id = :movies_id");

            $stmt->bindParam(":movies_id", $id);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $rating = 0;

                $reviews = $stmt->fetchAll();

                foreach($reviews as $review) {
                    
                    $rating += $review["rating"];
                }

                $rating = $rating / count($review);

            } else {
                $rating = "Não avalíado";
            }

            return $rating;
        } catch (PDOException $e) {
            Globals::logError("ReviewDao getRating: ".$e->getMessage());
            return "Não avaliado";
        }
    }

}