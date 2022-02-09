<?php

if (!isset($_SESSION['check'])) {
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Área restrita, faça login para acessar.</div>";
    header("Location: login.php");
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $error = false;
    var_dump(
        $data
    );
    if(empty($data["id"]) || empty($data["nome"]) || empty($data["usuario"])){
        $error = true;
        $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Whoops! Necessário preencher todos os campos.</div>";
    }
    elseif ((strlen($data["nome"])) < 4) {
        $error = true;
        $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Whoops!&nbsp;</stron>"
            . "Digite seu nome completo!</div>";
    }

    if (!empty($data["senha"]) && (strlen($data["senha"])) < 6) {
        $error = true;
        $_SESSION["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Whoops!&nbsp;</stron>"
            . "A senha deve conter mais que 6 caracteres!!</div>";
    }

    if ($error) {
        //$_SESSION['dados'] = $dados;
        $url_return = pg . "/edit/edit_usuarios?id='" . $data["id"] . "'";
        header("Location: $url_return");
    } else {
        if(!empty($data["senha"])){
            $data["senha"] = password_hash($data["senha"], PASSWORD_DEFAULT);
            $sql_update = "UPDATE users SET
                       name = '" . $data["nome"] . "',
                       email = '" . $data["email"] . "',
                       user_name = '" . $data["usuario"] . "',
                       user_password = '" . $data["senha"] . "',
                       access_level = '" . $data["nva_id"] . "',
                       modified = CURRENT_TIMESTAMP WHERE id =:id";
        } else {
            $sql_update = "UPDATE users SET
                       name = '" . $data["nome"] . "',
                       email = '" . $data["email"] . "',
                       user_name = '" . $data["usuario"] . "',
                       access_level = '" . $data["nva_id"] . "',
                       modified = CURRENT_TIMESTAMP WHERE id =:id";
        }

        $res_update = $conn ->prepare($sql_update);
        $res_update ->bindValue(":id", $data["id"], PDO::PARAM_INT);
        $res_update ->execute();

        if ($res_update ->rowCount()) {
            unset($_SESSION["dados"]);
            $_SESSION ["msg"]= "<div class='alert alert-success alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "Usuário editado com sucesso</div>";
            $url_return = pg . "/list/list_usuarios";
            header("Location: $url_return");
        } else {
            //Criar log de tentativa de acesso e redirecinar
            $log = "[".date("d/m/Y H:i:s")."] [ERROR]: ".mysqli_error($conn)."\n";
            //Diretório onde os arquivos de log devem ser gravados
            $directory = "logs/";
            if(!is_dir($directory)){
                mkdir($directory, 0777, true);
                chmod($directory, 0777);
            }

            //Nome do arquivo de log
            $fileName = $directory . "PAS".date("dmY").".txt";
            $handle = fopen($fileName, "a+");
            fwrite($handle, $log);
            fclose($handle);

            $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible'> "
                . "<button type='button' class='close' data-dismiss='alert'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button>$log.</div>";
            $url_return = pg . "/list/list_usuarios";
            header("Location: $url_return");
        }
    }
} else {
    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Erro ao carregar a página!</div>";
    $url_return = pg . "/list/list_usuarios";
    header("Location: $url_return");
}
