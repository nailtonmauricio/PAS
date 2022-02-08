<?php

session_start();
var_dump(
        scandir("install")
);
if(!in_array("config.txt", scandir("install/config"))){
    $_SESSION ["msg"] = "<div class='alert alert-warning alert-dismissible text-center'> "
        . "<button type='button' class='close' data-dismiss='alert'>"
        . "<span aria-hidden='true'>&times;</span>"
        . "</button><strong>Aviso!&nbsp;</stron>"
        . "Whoops! Antes de inciar o projeto é necessário configurar a base de dados!</div>";
    header("Location: install/index.php");
}
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width= device-width, inital-scale=1"/>
        <title>SCD</title>
        <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css"/>
        <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css"/>
        <link rel="stylesheet" type="text/css" href="assets/css/personalizado.css"/>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
        <link rel="shortcut icon" href="assets/img/logo.ico"/>
    </head>
    <body>
        <div class="container-fluid">
            <div class="col-md-4"></div>
            <div class="col-md-4 well" style="margin-top: 5%;">
                <div class="row">
                    <img src="assets/img/logo.png" class="img-responsive displayed" alt="NMATEC"/><br/>
                    <?php
                    if (isset($_SESSION['msg'])) {
                        echo $_SESSION['msg'];
                        session_destroy();
                    }
                    ?>
                </div>
                <div class="row">
                    <form name="formLogin" method="post" action="validaLogin.php">
                        <label for="user_name" class="sr-only"></label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fas fa-user"></i></span>
                            <input type="text" name="user_name" id="user_name"
                                   class="form-control text-uppercase" placeholder="Nome de usuário"
                                   required="required" autofocus/>
                        </div><br/>
                        <label for="user_password" class="sr-only"></label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fas fa-key"></i></span>
                            <input type="password" name="user_password" id="user_password" class="form-control"
                                   required="required" placeholder="************************"/>
                        </div><br/>
                        <div class="form-group">
                            <div class="pull-right">
                                <button class="btn btn-xs btn-success">
                                    <span class="fas fa-sign-in-alt"></span> Acessar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row">
                        <p class="text-center"><a href="recuperar_senha.php">Recuperar senha!</a></p>
                </div>
            </div>
        </div>
        <script src="assets/js/bootstrap.js"></script>
        <script src="assets/js/jquery-3.2.1.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
    </body>
</html>

