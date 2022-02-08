<?php

if (!isset($_SESSION['check'])) {
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible'> "
            . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Área restrita, faça login para acessar.</div>";
    header("Location: login.php");
}

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if (!empty($id)) {
        $sql = "SELECT stu_id FROM usuarios WHERE id = '$id'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        if ($row['stu_id'] == 1) {
            $stu_id = 2;
            $msg = "<div class='alert alert-success alert-dismissible text-center'> "
                    . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                    . "<span aria-hidden='true'>&times;</span>"
                    . "</button><strong>Aviso!&nbsp;</stron>"
                    . "Usuário bloqueado com sucesso!</div>";
        } else {
            $stu_id = 1;
            $msg = "<div class='alert alert-success alert-dismissible text-center'> "
                    . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                    . "<span aria-hidden='true'>&times;</span>"
                    . "</button><strong>Aviso!&nbsp;</stron>"
                    . "Usuário liberado com sucesso!</div>";
        }
        $update = "UPDATE usuarios SET
                   stu_id = '$stu_id',
                   modified = NOW()
                   WHERE id = '$id'";
        $result_update = mysqli_query($conn, $update);
        if (mysqli_affected_rows($conn)) {
            $_SESSION ['msg'] = $msg;
            $url_destino = pg . "/list/list_usuarios?id='".$row['nva_id']."'";
            header("Location: $url_destino");
        } else {
            $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                    . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                    . "<span aria-hidden='true'>&times;</span>"
                    . "</button><strong>Aviso!&nbsp;</stron>"
                    . "Usuário não encontrado!</div>";
            $url_destino = pg . "/list/list_usuarios?id='".$row['nva_id']."'";
            header("Location: $url_destino");
        }
}