<?php
session_start();
ob_start();
include_once ("config/config.php");
include_once ("config/conn.php");

$key = filter_input(INPUT_GET, 'key', FILTER_SANITIZE_STRING);

?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width= device-width, inital-scale=1"/>
        <title>Atualizar Senha</title>
        <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css"/>
        <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css"/>
        <link rel="shortcut icon" href="assets/img/logo-icone.ico"/>
    </head>
    <body>
        <div class="container-fluid">
            <div class="col-md-4"></div>
            <div class="col-md-4 well" style="margin-top: 5%;">
                <div>
                    <img src="assets/img/logo-fdiniz.png" class="img-responsive" alt="LAB FDINIZ"/><br>
		    <?php
		    if (isset($_SESSION['msg'])) {
			echo $_SESSION['msg'];
			unset($_SESSION['msg']);
		    }
		    ?>
                </div>
                <form name="formAtualizarSenha" method="post" action="validaNewSenha.php">
                	<input type="hidden" name="key" value="<?php echo $key; ?>">	    
                    <label for="senha" class="sr-only"></label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input type="password" name="senha" id="senha" class="form-control" placeholder="Insira a nova senha com o mínimo de 6 dígitos." required="required"/>
                    </div><br/>
                    <div class="form-group">
			<div class="pull-right">
			    <button class="btn btn-xs btn-success">
				<span class="glyphicon glyphicon-floppy-saved"></span> Atualizar
			    </button>
			</div>
		    </div>
                </form>
		<div style="margin-top: 10px;">
		    <p class="text-center"><a href="login.php">Retornar ao Login</a></p>
		</div>
                <script src="assets/js/bootstrap.js"></script>
                <script src="assets/js/jquery-3.2.1.min.js"></script>
                <script src="assets/js/bootstrap.min.js"></script>
            </div>
        </div>
    </body>
</html>

