<?php
ob_start();
if (!isset($_SESSION['check'])) {
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Área restrita, faça login para acessar.</div>";
    header("Location: login.php");
}
?>
<div class="well conteudo">
    <?php

        if (isset($_SESSION['msg'])) {
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
        }
    ?>
    <h1 class="text-center text-capitalize">Bem Vindo(a), <?php echo $_SESSION["credentials"]['name']."!"; ?></h1>
</div>
<div class="well conteudo col-sm-12">
    <?php
        $sqlMsg = "SELECT COUNT(id) AS totMsg FROM posts
        WHERE recipient_id =:user_id";
        $resMsg = $conn ->prepare($sqlMsg);
        $resMsg ->bindValue(":user_id", $_SESSION["credentials"]["id"], PDO::PARAM_INT);
        $resMsg ->execute();
        $rowMsg = $resMsg ->fetch(PDO::FETCH_ASSOC);

        if($rowMsg['totMsg'] == 1){
            $recado = "<div class='alert alert-warning alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><a href='".pg."/list/list_recados'><strong>Aviso!&nbsp;</stron>"
            . "Você possui ".$rowMsg['totMsg']." recado não lido.</a></div>";
            echo $recado;
            unset($recado);
        } elseif($rowMsg['totMsg'] > 1){
            $recado = "<div class='alert alert-warning alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><a href='".pg."/list/list_recados'><strong>Aviso!&nbsp;</stron>"
            . "Você possui ".$rowMsg['totMsg']." recados não lidos.</a></div>";
            echo $recado;
            unset($recado);
        } else {
            unset($recado);
        }

        var_dump(
                $_SESSION
        );
    ?>
<div class="col-sm-4">
    <h4 class="text-center">Novidades da Versão 1.0.9</h4>
    <ul>
        <li>Módulo de mensagens entre usuários.</li>
    </ul> 
</div>
<div class="col-sm-4">
    <h4 class="text-center">Novidades da Versão 1.0.8</h4>
    <ul>
        <li>Módulo de agenda para usuários.</li>
    </ul>
    
</div>
<div class="col-sm-4">
    <h4 class="text-center">Novidades da Versão 1.0.7</h4>
    <ul>
        <li>Logoff automático após 30 minutos de inatividade.</li>
    </ul> 
</div>
</div>