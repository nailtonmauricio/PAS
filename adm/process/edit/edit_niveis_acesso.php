<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if (!isset($_SESSION['check'])) {
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible'> "
            . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Área restrita, faça login para acessar.</div>";
    header("Location: login.php");
}

//$editNvAcesso = filter_input(INPUT_POST, 'btnEditar', FILTER_SANITIZE_STRING);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    $erro = false;
    $dados_validos = vdados($dados);
    if (!$dados_validos) {
        $erro = true;
        $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "Necessário preencher todos os campos.</div>";
    } elseif ((strlen($dados_validos['nome'])) < 6) {
        $erro = true;
        $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "Digite seu nome completo!</div>";
    }  else {
        //Bloquear cadastros repetidos usando como parâmetro o campo USUÁRIO
        $sql = "SELECT id FROM usuarios WHERE usuario = '" . $dados_validos['usuario'] . "' AND id <> '".$dados['id']."' LIMIT 1";
        $result = mysqli_query($conn, $sql);

         if (mysqli_num_rows($result) > 0){
          $erro = true;
          $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
          . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
          . "<span aria-hidden='true'>&times;</span>"
          . "</button><strong>Aviso!&nbsp;</stron>"
          . "Usuário já cadastrado no banco de dados!</div>";
          } 
    }

    if ($erro) {
        $_SESSION['dados'] = $dados;
         $url_destino = pg . "/edit/edit_usuarios?id='".$dados['id']."'";
        header("Location: $url_destino");
    } else {
        echo "update habilitado";

    }
} else {
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Erro ao carregar a página!</div>";
    $url_destino = pg . "/list/list_usuarios";
    header("Location: $url_destino");
}