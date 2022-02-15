<?php
if (!isset($_SESSION["check"])) {
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Área restrita, faça login para acessar.</div>";
    header("Location: login.php");
}
$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
if (!empty($id)) {

    $sql = "UPDATE posts SET 
    modified = CURRENT_TIMESTAMP WHERE id =:id";
    $res = $conn ->prepare($sql);
    $res ->bindValue(":id", $id, PDO::PARAM_INT);
    $res ->execute();

    if($res ->rowCount()){
        $_SESSION ["msg"] = "<div class='alert alert-success alert-dismissible text-center'> "
        . "<button type='button' class='close' data-dismiss='alert'>"
        . "<span aria-hidden='true'>&times;</span>"
        . "</button><strong>Aviso!&nbsp;</stron>"
        . "Leitura confirmada com sucesso!</div>";
        $url_return= pg . "/list/list_recados";
        header("Location: $url_return");
    }
} else {
    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
    . "<button type='button' class='close' data-dismiss='alert'>"
    . "<span aria-hidden='true'>&times;</span>"
    . "</button><strong>Aviso!&nbsp;</stron>"
    . "Registro de mensagem não encontrado!</div>";
    $url_return = pg . "/list/list_recados";
    header("Location: $url_return");
}