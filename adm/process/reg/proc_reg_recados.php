<?php

if (!isset($_SESSION['check'])) {
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible'> "
            . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Área restrita, faça login para acessar.</div>";
    header("Location: login.php");
}

if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    //Validar se o recado deve ser em broadcast
    if($dados['destinatario_id'] == '*' && $dados['setor_id'] == '*'){
    
        $sql = "SELECT id FROM usuarios WHERE id != '".$_SESSION['id']."' AND stu_id != 2";
        $res = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($res)) {

        	$sqlRecado = "INSERT INTO recados VALUES (
            DEFAULT, '".$_SESSION['id']."', '".$row['id']."', '".$dados['mensagem']."', NOW(), NULL)";
    		$resRecado = mysqli_query($conn, $sqlRecado);
        }
        
        $_SESSION ['msg'] = "<div class='alert alert-success alert-dismissible text-center'> "
        . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
        . "<span aria-hidden='true'>&times;</span>"
        . "</button><strong>Aviso!&nbsp;</stron>"
        . "O recado enviado com sucesso!</div>";
        $url_destino = pg . "/list/list_recados";
        header("Location: $url_destino");

    } elseif($dados['destinatario_id'] == '*' && !empty($dados['setor_id'])){
        $sql = "SELECT id FROM usuarios WHERE id != '".$_SESSION['id']."' AND stu_id != 2 AND nva_id = '".$dados['setor_id']."'";
        $res = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($res)) {

            $sqlRecado = "INSERT INTO recados VALUES (
            DEFAULT, '".$_SESSION['id']."', '".$row['id']."', '".$dados['mensagem']."', NOW(), NULL)";
            $resRecado = mysqli_query($conn, $sqlRecado);
        }
        $_SESSION ['msg'] = "<div class='alert alert-success alert-dismissible text-center'> "
        . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
        . "<span aria-hidden='true'>&times;</span>"
        . "</button><strong>Aviso!&nbsp;</stron>"
        . "O recado enviado com sucesso!</div>";
        $url_destino = pg . "/list/list_recados";
        header("Location: $url_destino");
    } else {

        if(!empty($dados['idRecado'])){
            $sqlUpdate = "UPDATE recados SET 
            modified = NOW() WHERE id = '".$dados['idRecado']."'";
            $resUpdate = mysqli_query($conn, $sqlUpdate);
        }
        
        $sqlRecado = "INSERT INTO recados VALUES (
        DEFAULT, '".$_SESSION['id']."', '".$dados['destinatario_id']."', '".$dados['mensagem']."', NOW(), NULL)";
    	$resRecado = mysqli_query($conn, $sqlRecado);
    	if(mysqli_insert_id($conn)){
    		$_SESSION ['msg'] = "<div class='alert alert-success alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "O recado enviado com sucesso!</div>";
		    $url_destino = pg . "/list/list_recados";
		    header("Location: $url_destino");
    	}
    }
} else {
	$_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "O recado não pode ser enviado, entrem em contato com o Administrador!</div>";
    $url_destino = pg . "/register/reg_recados";
    header("Location: $url_destino");
}
