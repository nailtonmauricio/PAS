<?php

session_start();

if($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once("config/config.php");
    $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
    $_SESSION["check"] = true;

    if (!empty($data["user_name"])) {
        $sql = "SELECT users.id, users.name, users.email, users.user_name, users.user_password, users.situation, users.access_level, al.position FROM users  JOIN access_level AS al ON users.access_level = al.id WHERE users.user_name =:user AND users.situation = 1";
        $res = $conn->prepare($sql);
        $res->bindValue(":user", $data["user_name"]);
        $res->execute();
        #$res ->debugDumpParams();

        if ($res->rowCount()) {
            $row = $res->fetch(PDO::FETCH_ASSOC, PDO::PARAM_STR);
            if (password_verify($data["user_password"], $row["user_password"])) {
                $_SESSION["credentials"] = [
                    "id" => $row["id"],
                    "name" => $row["name"],
                    "email" => $row["email"],
                    "user_name" => $row["user_name"],
                    "situation" => $row["situation"],
                    "access_level" => $row["access_level"],
                    "position" => $row["position"]
                ];
                $_SESSION["check"] = true;
                header("Location: index.php");
            } else {
                $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
                    . "<button type='button' class='close' data-dismiss='alert'>"
                    . "<span aria-hidden='true'>&times;</span>"
                    . "</button><strong>Aviso!&nbsp;</stron>"
                    . "Whoops! Credenciais inválidas!</div>";
                header("Location: login.php");
            }
        } else {
            $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "Whoops! Credenciais inválidas!</div>";
            header("Location: login.php");
        }
    } else {
        $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Whoops! Credenciais inválidas!</div>";
        header("Location: login.php");
    }
}