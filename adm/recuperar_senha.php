<?php

session_start();
//$btnRecupera = filter_input(INPUT_POST, 'btnRecupera', FILTER_SANITIZE_STRING);
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    
    if(!empty($email)){

        include_once ("config/conn.php");
        $sql = "SELECT id, nome, email FROM usuarios WHERE email = '$email' LIMIT 1";
        $res = mysqli_query ($conn, $sql);
        $row = mysqli_fetch_assoc ($res);
       
        if (mysqli_num_rows($res)){
            $key= md5($row ['id'] . $row ['email'] . date("Y-m-d H:i:s"));
            $sqlUpdate = "UPDATE usuarios SET
                          recuperar_senha='$key',
                          modified=NOW()
                          WHERE id='".$row ['id']."'";
            $resUpdate = mysqli_query($conn, $sqlUpdate);

            if($resUpdate){

                include_once ("config/config.php");
                
                $url = pg."/atualizar_senha.php?key=".$key;

                //Variaveis de POST, Alterar somente se necessário 
                //====================================================
                $email = $row ['email'];
                $mensagem = "Para recuperar a senha acesse o link: ";
                $mensagem .= $url;
                //====================================================
                    
                //REMETENTE --> ESTE EMAIL TEM QUE SER VALIDO DO DOMINIO
                //==================================================== 
                $email_remetente = "ti@labfdiniz.com.br"; // deve ser uma conta de email do seu dominio 
                //====================================================
                //Configurações do email, ajustar conforme necessidade
                //==================================================== 
                $email_destinatario = $email; // pode ser qualquer email que receberá as mensagens
                $email_reply = "informatica@labfdiniz.com.br"; 
                $email_assunto = "Recuperar senha SIG"; // Este será o assunto da mensagem
                //====================================================

                //Monta o Corpo da Mensagem
                //====================================================
                $email_conteudo = "$mensagem"; 
                //====================================================
                    
                //Seta os Headers (Alterar somente caso necessario) 
                //==================================================== 
                $email_headers = implode ( "\r\n",array ( "From: $email_remetente", "Reply-To: $email_reply", "Return-Path: $email_remetente","MIME-Version: 1.0","X-Priority: 3") );
                //====================================================

                //Enviando o email 
                //==================================================== 
                if(mail ($email_destinatario, $email_assunto, nl2br($email_conteudo), $email_headers)){
                    $_SESSION ['msg'] = "<div class='alert alert-success alert-dismissible text-center'> "
                    . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                    . "<span aria-hidden='true'>&times;</span>"
                    . "</button><strong>Aviso!&nbsp;</stron>"
                    . "Chave para recuperação de senha enviada com sucesso.</div>";
                    $url_destino = pg . "/login.php";
                    header("Location: $url_destino");
                }
            }

       } else {
        $_SESSION ['msg_key'] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "E-mail para recuperação inválido.</div>";
       }

    } else {
    $_SESSION ['msg_key'] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "E-mail deve ser preenchido!</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width= device-width, inital-scale=1"/>
        <title>Recuperar Senha</title>
        <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css"/>
        <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css"/>
        <link rel="stylesheet" type="text/css" href="assets/css/personalizado.css"/>
        <link rel="shortcut icon" href="assets/img/logo.ico"/>
    </head>
    <body>
        <div class="container-fluid">
            <div class="col-md-4"></div>
            <div class="col-md-4 well" style="margin-top: 5%;">
                <div>
                    <img src="assets/img/logo.png" class="img-responsive displayed" alt="LAB FDINIZ"/><br>
                    <?php
                        if (isset($_SESSION['msg_key'])) {
                            echo $_SESSION['msg_key'];
                            unset($_SESSION['msg_key']);
                        }
                    ?>
                </div>
                <form name="formRecuperarSenha" method="post" action="recuperar_senha.php">
                    <label for="senha" class="sr-only"></label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                        <input type="email" name="email" id="email" class="form-control"
                               required="required" placeholder="usuario@dominio.com.br"/>
                    </div><br/>
                    <div class="form-group"> 
                        <div class="pull-right">   
                            <button class="btn btn-xs btn-success">
                                <span class='glyphicon glyphicon-floppy-saved'></span> 
                                Enviar
                            </button>
                        </div>
                    </div>

                    <div style="margin-top: 10px;">
                        <p class="text-center"><a href="login.php">Retornar ao Login</a></p>
                    </div>
                </form>
                <script src="assets/js/bootstrap.js"></script>
                <script src="assets/js/jquery-3.2.1.min.js"></script>
                <script src="assets/js/bootstrap.min.js"></script>
            </div>
        </div>
    </body>
</html>

