<?php

if (!isset($_SESSION['check'])) {
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Whoops!&nbsp;</stron>"
            . "Área restrita, faça login para acessar.</div>";
    header("Location: login.php");
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $error = false;
    var_dump(
        $error,
        json_encode($data)
    );
    if(empty($data["nome"])){
        $error = true;
        $_SESSION ["msg"] = "<div class='alert alert-success alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Nível de acesso cadastrado com sucesso.</div>";
        $url_return = pg . "/list/list_niveis_acesso";
    }
    if(!empty($data["position"])){
        $sql_position = "SELECT position FROM access_level ORDER BY position DESC LIMIT 1";
        $res_position = $conn ->prepare($sql_position);
        $res_position ->execute();
        $row_position = $res_position ->fetch(PDO::FETCH_ASSOC);

    } else {

    }
    /*if (!$dados_validos) {
        $erro = true;
        $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "Necessário preencher todos os campos.</div>";
    }
    $dados_validos["nome"] = mb_strtolower($dados_validos["nome"]);
    if ($erro) {
        $_SESSION['dados'] = $dados;
        $url_destino = pg . "/register/reg_niveis_acesso";
        header("Location: $url_destino");
    } else {
        $sql_ordem = "SELECT ordem FROM nv_acessos ORDER BY ordem DESC LIMIT 1";
        $result_ordem = mysqli_query($conn, $sql_ordem);
        $row_ordem = mysqli_fetch_assoc($result_ordem);
        $row_ordem['ordem']++;
        $sql = "INSERT INTO nv_acessos VALUES (DEFAULT, '" . $dados_validos['nome'] . "', '".$row_ordem['ordem']."', DEFAULT, NOW(), NULL)";
        mysqli_set_charset($conn, "utf8");
        $result = mysqli_query($conn, $sql);
        //Recupera o ultimo id cadastrado
        $lastId = mysqli_insert_id($conn);
        if ($result) {
            //Copiar as permissões do perfil escolhido
            $sqlPerfil = "SELECT pagina_id, permissao, menu, ordem FROM nva_paginas WHERE nva_id='".$dados['perfil']."'";
            $resPerfil = mysqli_query($conn, $sqlPerfil);
            //Criar o INSERT das permissões do novo perfil
            while($rowPerfil = mysqli_fetch_assoc($resPerfil)){
                $sqlNvaPagina = "INSERT INTO nva_paginas VALUES( 
                    DEFAULT,
                    '$lastId',
                    '".$rowPerfil['pagina_id']."',
                    '".$rowPerfil['permissao']."',
                    '".$rowPerfil['menu']."',
                    '".$rowPerfil['ordem']."',
                    NOW(),
                    NULL)";
                $resNvaPagina = mysqli_query($conn, $sqlNvaPagina);
            }
            unset($_SESSION['dados']);
            $_SESSION ['msg'] = "<div class='alert alert-success alert-dismissible text-center'> "
                    . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                    . "<span aria-hidden='true'>&times;</span>"
                    . "</button><strong>Aviso!&nbsp;</stron>"
                    . "Nível de acesso cadastrado com sucesso.</div>";
            $url_destino = pg . "/list/list_niveis_acesso";
            header("Location: $url_destino");
        } else {
            $_SESSION['dados'] = $dados;
            $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                    . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                    . "<span aria-hidden='true'>&times;</span>"
                    . "</button><strong>Aviso!&nbsp;</stron>"
                    . "Erro ao cadastrar nível de acesso, campos inválidos!</div>";
            $url_destino = pg . "/register/reg_niveis_acesso";
            header("Location: $url_destino");
        }
    }*/
} else {
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Whoops!&nbsp;</stron>"
            . "Erro ao carregar a página!</div>";
    $url_destino = pg . "/list/list_niveis_acesso";
    header("Location: $url_destino");
}

