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


if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $erro = false;
    $dados = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

    var_dump(
        $dados,
        $erro,
        $_SESSION["user_edit"]
    );

    if(empty($dados['id']) || empty($dados['nome']) || empty($dados['usuario'])){
        $erro = true;
        $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "Whoops! Necessário preencher todos os campos.</div>";
    } 
    elseif ((strlen($dados['nome'])) < 6) {
        $erro = true;
        $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "Digite seu nome completo!</div>";
    }  
    elseif (($_SESSION['nva_user_id'] != 1) && ($_SESSION['id'] != $dados['id'])){
        $erro = true;
        $_SESSION['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "Operação não autorizada para o seu nível de acesso!</div>";
    }
    if (!empty($dados['senha']) && (strlen($dados['senha'])) < 6) {
        $erro = true;
        $_SESSION['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "A senha deve conter mais que 6 caracteres!!</div>";
    }

    if ($erro) {
        //$_SESSION['dados'] = $dados;
        $url_destino = pg . "/edit/edit_usuarios?id='" . $dados['id'] . "'";
        header("Location: $url_destino");
    } /*else {
        if(!empty($dados['senha'])){
            $dados['senha'] = password_hash($dados['senha'], PASSWORD_DEFAULT);
            $sql_update = "UPDATE usuarios SET
                       nome = '" . $dados['nome'] . "',
                       email = '" . $dados['email'] . "',
                       usuario = '" . $dados['usuario'] . "',
                       senha = '" . $dados['senha'] . "',
                       nva_id = '" . $dados['nva_id'] . "',
                       unidade_id = '" . $dados['unidade_id'] . "',
                       modified = NOW() WHERE id = '" . $dados['id'] . "'";
        } else {
            $sql_update = "UPDATE usuarios SET
                       nome = '" . $dados['nome'] . "',
                       email = '" . $dados['email'] . "',
                       usuario = '" . $dados['usuario'] . "',
                       nva_id = '" . $dados['nva_id'] . "',
                       unidade_id = '" . $dados['unidade_id'] . "',
                       modified = NOW() WHERE id = '" . $dados['id'] . "'";
        }
        
        mysqli_set_charset($conn, "utf8");
        $result = mysqli_query($conn, $sql_update);
        if (mysqli_affected_rows($conn)) {
            unset($_SESSION['dados']);
            $_SESSION ['msg'] = "<div class='alert alert-success alert-dismissible text-center'> "
                    . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                    . "<span aria-hidden='true'>&times;</span>"
                    . "</button><strong>Aviso!&nbsp;</stron>"
                    . "Usuário editado com sucesso</div>";
            if($_SESSION['nva_user_id'] == 1 || $_SESSION['nva_user_id'] == 2){
                $url_destino = pg . "/list/list_usuarios";
            } else {
                $url_destino = pg . "/home";
            }
            header("Location: $url_destino");
        } else {
            //Criar log de tentativa de acesso e redirecinar
            $log = "[".date('d/m/Y H:i:s')."] [ERROR]: ".mysqli_error($conn)."\n";
            //Diretório onde os arquivos de log devem ser gravados
            $directory = 'logs/';
            if(!is_dir($directory)){
                mkdir($directory, 0777, true);
                chmod($directory, 0777);
            }

            //Nome do arquivo de log
            $fileName = $directory . "SIG".date('dmY').'.txt';
            $handle = fopen($fileName, 'a+');
            fwrite($handle, $log);
            fclose($handle);

            $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible'> "
            . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button>$log.</div>";
            header("Location: $url_destino");
        }
    }*/
} else {
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Erro ao carregar a página!</div>";
    $url_destino = pg . "/list/list_usuarios";
    header("Location: $url_destino");
}
