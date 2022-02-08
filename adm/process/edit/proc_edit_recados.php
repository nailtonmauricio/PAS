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

    $sqlUpdate = "UPDATE recados SET 
    modified = NOW() WHERE id = '$id'";
    $resUpdate = mysqli_query($conn, $sqlUpdate);

    if(mysqli_affected_rows($conn)){
        $_SESSION ['msg'] = "<div class='alert alert-success alert-dismissible text-center'> "
        . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
        . "<span aria-hidden='true'>&times;</span>"
        . "</button><strong>Aviso!&nbsp;</stron>"
        . "Leitura confirmada com sucesso!</div>";
        if($_SESSION['nva_user_id'] == 8){
            $url_destino = pg . "/list/list_rotas";
        } else {
            $url_destino = pg . "/list/list_recados";
        }
        header("Location: $url_destino");
    }
} else {
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
    . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
    . "<span aria-hidden='true'>&times;</span>"
    . "</button><strong>Aviso!&nbsp;</stron>"
    . "Registro de mensagem não encontrado!</div>";
    $url_destino = pg . "/list/list_recados";
    header("Location: $url_destino");
}