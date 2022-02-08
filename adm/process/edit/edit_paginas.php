<?php
// OBS: Falta criar a validação para caso o usuário apague algum campo que é obrigatório.
if (!isset($_SESSION['check'])) {
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Área restrita, faça login para acessar.</div>";
    header("Location: login.php");
}

//$editpagina = filter_input(INPUT_POST, 'btnEditar', FILTER_SANITIZE_STRING);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    $erro = false;
    //$dados_validos = vdados($dados);
    //var_dump($dados_validos);
    if ($erro) {
        $_SESSION['dados'] = $dados;
        $url_destino = pg . "/register/reg_paginas";
        header("Location: $url_destino");
    } else {
        $sql = "UPDATE paginas SET
                nome = '" . $dados['nome'] . "',
                endereco = '" . $dados['endereco'] . "',
                descricao = '" . $dados['descricao'] . "',
                modified = NOW() WHERE id = '".$dados['id']."'";
        mysqli_set_charset($conn, "utf8");
        $result = mysqli_query($conn, $sql);
        //var_dump($sql);
        //var_dump($result);
        if (mysqli_affected_rows($conn)) {
            unset($_SESSION['dados']);
            $_SESSION ['msg'] = "<div class='alert alert-success alert-dismissible text-center'> "
                    . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                    . "<span aria-hidden='true'>&times;</span>"
                    . "</button><strong>Aviso!&nbsp;</stron>"
                    . "Página atualizada com sucesso!</div>";
            $url_destino = pg . "/list/list_paginas";
            header("Location: $url_destino");
        } else {
            $_SESSION['dados'] = $dados;
            $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                    . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                    . "<span aria-hidden='true'>&times;</span>"
                    . "</button><strong>Aviso!&nbsp;</stron>"
                    . "Erro ao atualizar página!</div>";
            $url_destino = pg . "/edit/edit_paginas";
            header("Location: $url_destino");
        }
    }
} else {
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Erro ao carregar a página!</div>";
    $url_destino = pg . "/list/list_paginas";
    header("Location: $url_destino");
}

