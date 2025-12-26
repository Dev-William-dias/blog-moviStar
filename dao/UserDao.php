<?php

require_once("globals.php");
require_once("models/User.php");
require_once("models/Message.php");

class UserDao implements UserDaoInterface {

    private $conn;
    private $url;
    private $message;

    public function __construct(PDO $conn, $url) {
        $this->conn = $conn;
        $this->url = $url;
        $this->message = new Message($url);
    }

    public function buildUser($data) {

        $user = new User();

        $user->id = $data["id"];
        $user->name = $data["name"];
        $user->lastname = $data["lastname"];
        $user->email = $data["email"];
        $user->password = $data["password"];
        $user->image = $data["image"];
        $user->bio = $data["bio"];
        $user->token = $data["token"];

        return $user;
    }

    public function create(User $user, $authUser = false) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO users (name, lastname, email, password, token) VALUES (:name, :lastname, :email, :password, :token)");

            $stmt->bindParam(":name", $user->name);
            $stmt->bindParam(":lastname", $user->lastname);
            $stmt->bindParam(":email", $user->email);
            $stmt->bindParam(":password", $user->password);
            $stmt->bindParam(":token", $user->token);

            $stmt->execute();

            if ($authUser) {
                $this->setTokenToSession($user->token);
            }

        } catch (PDOException $e) {
            Globals::logError("UserDao create: ".$e->getMessage());
            $this->message->setMessage("Erro ao criar usuário.","error","back");     
        }
    }

    public function update(User $user, $redirect = true) {
        try {
            $stmt = $this->conn->prepare("UPDATE users  SET name = :name, lastname = :lastname, email = :email,image = :image, bio = :bio, token = :token WHERE id = :id");

            $stmt->bindParam(":name", $user->name);
            $stmt->bindParam(":lastname", $user->lastname);
            $stmt->bindParam(":email", $user->email);
            $stmt->bindParam(":image", $user->image);
            $stmt->bindParam(":bio", $user->bio);
            $stmt->bindParam(":token", $user->token);
            $stmt->bindParam(":id", $user->id);

            $stmt->execute();

            if ($redirect) {
                $this->message->setMessage("Dados atualizados com sucesso","success","editprofile.php");
            }
        } catch (PDOException $e) {
            Globals::logError("UserDao update: ".$e->getMessage());
            $this->message->setMessage("Erro ao atualizar dados.","error","back");
        }
    }

    public function verifyToken($protected = false) {
        try {
            if (!empty($_SESSION["token"])) {

                $token = $_SESSION["token"];

                $user = $this->findByToken($token);

                if ($user) {
                    return $user;
                } else if ($protected) {
                    $this->message->setMessage("Faça a autenticação para acessar esta página.","error","index.php");
                }

            } elseif ($protected) {
                $this->message->setMessage("Faça a autenticação para acessar esta página!", "error", "index.php");
            }

            return false;
        } catch (Exception $e) {
            Globals::logError("UserDao verifyToken: ".$e->getMessage());
            return false;
        }
    }

    public function setTokenToSession($token, $redirect = true) {
        $_SESSION["token"] = $token;

        if ($redirect) {
            $this->message->setMessage("Seja bem-vinda!", "success", "editprofile.php");
        }
    }

    public function authenticateUser($email, $password) {
        try {
            $user = $this->findByEmail($email);

            if ($user) {
                
                if (password_verify($password, $user->password)) {

                    $token = $user->generateToken();

                    $this->setTokenToSession($token, false);

                    $user->token = $token;
                    $this->update($user, false);

                    return true;
                }
                
                return false;
            }

            return false;
        } catch (Exception $e) {
            Globals::logError("UserDao authenticateUser: ".$e->getMessage());
            return false;
        }
    }


    public function findByEmail($email) {
        try {
            if ($email == "") {
                return false;
            }

            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");

            $stmt->bindParam(":email", $email);
            $stmt->execute();

            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($data) {
                return $this->buildUser($data);
            }

            return false;
        } catch (PDOException $e) {
            Globals::logError("UserDao findByEmail: ".$e->getMessage());
            return false;
        }
    }


    public function findById($id) {

    }

    public function findByToken($token) {
        try {
            if ($token == "") {
                return false;
            }

            $stmt = $this->conn->prepare("SELECT * FROM users WHERE token = :token");

            $stmt->bindParam(":token", $token);
            $stmt->execute();

            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($data) {
                return $this->buildUser($data);
            }

            return false;
        } catch (PDOException $e) {
            Globals::logError("UserDao findByToken: ".$e->getMessage());
            return false;
        }
    }


    public function destroyToken() {
        $_SESSION["token"] = "";

        $this->message->setMessage("Você fez o logout com sucesso!", "success", "index.php");
    }

    public function changePassword(User $user) {

    }

}