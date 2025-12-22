<?php

    require_once("globals.php");
    require_once("db.php");
    require_once("models/User.php");
    require_once("models/Message.php");
    require_once("dao/UserDao.php");

    $message = new Message($BASE_URL);

    $userDao = new UserDao($conn, $BASE_URL);

    $type = filter_input(INPUT_POST, "type");

    if($type === "login") {

        
    } else if ($type === "register") {
        $name = filter_input(INPUT_POST, "name");
        $lastname = filter_input(INPUT_POST, "lastname");
        $email = filter_input(INPUT_POST, "email");
        $password = filter_input(INPUT_POST, "password");
        $confirpassword = filter_input(INPUT_POST, "confirpassword");

        if($name && $lastname && $email && $password) {
            
            if ($password === $confirpassword) {

                if ($userDao->findByEmail($email) === false) {
                    
                    $user = new User();

                    $userToken = $user->generateToken();
                    $finalPassword = $user->generatePassword($password);

                    $user->name = $name;
                    $user->lastname = $lastname;
                    $user->email = $email;
                    $user->password = $finalPassword;
                    $user->token = $userToken;

                    $auth = true;

                    $userDao->create($user, $auth);

                } else {
                    $message->setMessage("Usuário já cadastrado, tente outro e-mail.", "error", "back");
                }
            } else {
                $message->setMessage("As senhas não são iguais.", "error", "back");
            }
        } else {
            $message->setMessage("Por favor, preencha todos os campos.", "error", "back");
        }
    }