<?php

if (!isset($_SESSION['check'])) {
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible'> "
        . "<button type='button' class='close' data-dismiss='alert'>"
        . "<span aria-hidden='true'>&times;</span>"
        . "</button><strong>Aviso!&nbsp;</stron>"
        . "Área restrita, faça login para acessar.</div>";
    header("Location: login.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
    $error = false;

    if (empty($data["endereco"])) {
        $error = true;
        $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Campo endenreço não pode estar vazio</div>";
        $url_redirect = pg . "/register/reg_paginas";
    } else {
        $sql_verify = "SELECT COUNT(id) AS total FROM pages WHERE path =:endereco";
        $res_verify = $conn->prepare($sql_verify);
        $res_verify->bindValue(":endereco", $data["endereco"]);
        $res_verify->execute();
        $row_verify = $res_verify->fetch(PDO::FETCH_ASSOC);

        if ($row_verify["total"]) {
            $error = true;
            $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "Campo endenreço já está cadastrado na base de dados</div>";
            $url_redirect = pg . "/register/reg_paginas";

        } else {
            $sql = "INSERT INTO pages (name, path, description) VALUES (:name, :path, :description)";
            $res = $conn->prepare($sql);
            $res->bindValue(":name", empty($data["nome"])? null:$data["nome"]);
            $res->bindValue(":path", $data["endereco"]);
            $res->bindValue(":description", empty($data["descricao"]) ? null : $data["descricao"]);
            $res->execute();

            /*
             * Executa o insert de relacionamento entre a página cadastrada e os níveis de acesso existestes
             */
            if ($res->rowCount()) {
                $last_page_id = $conn->lastInsertId();
                if (isset($_SESSION["data"])) {
                    unset($_SESSION["data"]);
                }

                $sql_al = "SELECT al.id FROM access_level AS al";
                $res_al = $conn->prepare($sql_al);
                $res_al->execute();
                $row_al = $res_al->fetchAll(PDO::FETCH_ASSOC);

                foreach ($row_al as $al_id) {
                    if ($al_id["id"] == 1) {
                        $sql_page_access_level = "INSERT INTO page_access_level  VALUES (DEFAULT, :al_id, :page_id, :access, DEFAULT, DEFAULT, DEFAULT)";
                        $res_page_access_level = $conn->prepare($sql_page_access_level);
                        $res_page_access_level->bindValue(":al_id", $al_id["id"], PDO::PARAM_INT);
                        $res_page_access_level->bindValue(":page_id", $last_page_id, PDO::PARAM_INT);
                        $res_page_access_level->bindValue(":access", 1, PDO::PARAM_INT);
                    } else {
                        $sql_page_access_level = "INSERT INTO page_access_level  VALUES (DEFAULT, :al_id, :page_id, DEFAULT, DEFAULT, DEFAULT, DEFAULT)";
                        $res_page_access_level = $conn->prepare($sql_page_access_level);
                        $res_page_access_level->bindValue(":al_id", $al_id["id"], PDO::PARAM_INT);
                        $res_page_access_level->bindValue(":page_id", $last_page_id, PDO::PARAM_INT);
                    }
                    $res_page_access_level->execute();
                }

                $_SESSION ['msg'] = "<div class='alert alert-success alert-dismissible text-center'> "
                    . "<button type='button' class='close' data-dismiss='alert'>"
                    . "<span aria-hidden='true'>&times;</span>"
                    . "</button><strong>Aviso!&nbsp;</stron>"
                    . "Página cadastrada cadastrada com sucesso</div>";
                $url_redirect = pg . "/list/list_paginas";
                header("Location: $url_redirect");
            }
        }
    }

    if ($error) {
        /*
         * Cria uma sessão contendo os valores passados pelo formulário para que sejam preenchidos novamente o que estava correto
         */
        $_SESSION['data'] = $data;
        header("Location: $url_redirect");
    }

} else {
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
        . "<button type='button' class='close' data-dismiss='alert'>"
        . "<span aria-hidden='true'>&times;</span>"
        . "</button><strong>Aviso!&nbsp;</stron>"
        . "Erro ao carregar a página!</div>";
    $url_destino = pg . "/list/list_paginas";
    header("Location: $url_destino");
}

