<?php

function security (){
    if ((!isset($_SESSION['check'])) && (!isset($_SESSION['nva_id']))){
        $_SESSION ['msg'] = "<div class='alert alert-warning alert-dismissible'> "
            . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Área restrita, faça login para acessar.</div>";
        $url_destino = pg."login.php";
        header("Location: login.php");
    } 
}
