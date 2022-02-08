<?php

if (!isset($_SESSION['check'])) {
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible'> "
            . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Área restrita, faça login para acessar.</div>";
    header("Location: login.php");
}

//$Update = filter_input(INPUT_POST, 'btnUpdate', FILTER_SANITIZE_STRING);

if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
    $erro = false;
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    if (empty($dados['title'])) {
        $erro = TRUE;
        $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "Preencha o campo TÍTULO!</div>";
    } elseif (empty($dados['color'])) {
        $erro = TRUE;
        $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "Escolha a cor do EVENTO!</div>";
    } elseif (empty($dados['start'])) {
        $erro = TRUE;
        $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "Preencha a data de início do evento!</div>";
    } elseif (empty($dados['end'])) {
        $erro = TRUE;
        $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "Preencha a data de encerramento do evento!</div>";
    } elseif (empty($dados['descricao'])) {
        $erro = TRUE;
        $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "Preencha a descrição do evento!</div>";
    }

    if ($erro) {
        $_SESSION['dados'] = $dados;
        $url_destino = pg . "/viewer/view_agenda";
        header("Location: $url_destino");
    } else {
        //Converter a data de iníco para o padrão do 
        $data = explode(" ", $dados['start']);
        list($date, $hora) = $data;
        $data_sem_barra = array_reverse(explode("/", $date));
        $data_sem_barra = implode("-", $data_sem_barra);
        $start_sem_barra = $data_sem_barra . " " . $hora;

        $data = explode(" ", $dados['end']);
        list($date, $hora) = $data;
        $data_sem_barra = array_reverse(explode("/", $date));
        $data_sem_barra = implode("-", $data_sem_barra);
        $end_sem_barra = $data_sem_barra . " " . $hora;

        //Removendo as quebras de linhas para salvar no banco de dados.
        $descricao = preg_replace('/[\n|\r|\n\r|\r\n]{2,}/',' ', $dados['descricao']);

        $sql = "UPDATE events SET 
                title = '" . $dados['title'] . "',
                color = '" . $dados['color'] . "',
                start = '" . $start_sem_barra . "',
                end = '" . $end_sem_barra . "',
                descricao = '$descricao',
                modified = NOW() WHERE id = '" . $dados['id'] . "'";
        $result = mysqli_query($conn, $sql);
         if (mysqli_affected_rows($conn)) {
            unset($_SESSION['dados']);
            $_SESSION ['msg'] = "<div class='alert alert-success alert-dismissible text-center'> "
                    . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                    . "<span aria-hidden='true'>&times;</span>"
                    . "</button><strong>Aviso!&nbsp;</stron>"
                    . "Evento atualizado com sucesso!</div>";
            $url_destino = pg . "/viewer/view_agenda";
            header("Location: $url_destino");
            //
        } else {
            $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                    . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                    . "<span aria-hidden='true'>&times;</span>"
                    . "</button><strong>Aviso!&nbsp;</stron>"
                    . "Evento não atualizado!</div>";
            $url_destino = pg . "/viewer/view_agenda";
            header("Location: $url_destino");
        }
    }
} else {
    // Este esle é chamado caso o botão cadastrar evendo não seja clicado para acionar a página.
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Seu nível de acesso não permite a execução deste recurso!</div>";
    $url_destino = pg . "/home";
    header("Location: $url_destino");
}