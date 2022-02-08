<?php

session_start();

unset($_SESSION["credentials"], $_SESSION["check"]);
      $_SESSION ['msg'] = "<div class='alert alert-success alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "Logout realizado com sucesso.</div>";
header ("Location:login.php");