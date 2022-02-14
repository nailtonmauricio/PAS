<?php

if (!isset($_SESSION["check"])) {
    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Whoops!&nbsp;</stron>"
            . "Área restrita, faça login para acessar.</div>";
    header("Location: login.php");
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if(empty($data["nome"])){
        $error = true;
        $_SESSION ["msg"] = "<div class='alert alert-success alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Nível de acesso cadastrado com sucesso.</div>";
        $url_return = pg . "/list/list_niveis_acesso";
    }
    if(!empty($data["perfil"])){

        $sql_position = "SELECT position FROM access_level WHERE id =:id ORDER BY position";
        $res_position = $conn ->prepare($sql_position);
        $res_position ->bindValue(":id", $data["perfil"], PDO::PARAM_INT);
        $res_position ->execute();
        $row_position = $res_position ->fetch(PDO::FETCH_ASSOC);

        $sql = "INSERT INTO access_level (name, position) VALUES(:name, :position)";
        $res = $conn ->prepare($sql);
        $res ->bindValue(":name", $data["nome"]);
        $res ->bindValue(":position", empty($ata["position"])?$row_position["position"]+1:$data["position"], PDO::PARAM_INT);
        $res ->execute();
        $last_insert_id = $conn ->lastInsertId();

        if($res ->rowCount()){
            $sql_pal = "SELECT pal.page_id, pal.access, pal.menu FROM page_access_level AS pal WHERE pal.al_id =:id";
            $res_pal = $conn ->prepare($sql_pal);
            $res_pal ->bindValue(":id", $data["perfil"], PDO::PARAM_INT);
            $res_pal ->execute();
            $res_pal ->debugDumpParams();
            $row_pal = $res_pal ->fetchAll(PDO::FETCH_ASSOC);

            foreach ($row_pal as $pal){
                $sql_insert = "INSERT INTO page_access_level (id, al_id, page_id, access, menu, created, modified) VALUES (DEFAULT, :last_insert_id, :page_id, :access, :menu, DEFAULT, DEFAULT)";
                $res_insert = $conn ->prepare($sql_insert);
                $res_insert ->bindValue(":last_insert_id", $last_insert_id, PDO::PARAM_INT);
                $res_insert ->bindValue(":page_id", $pal["page_id"], PDO::PARAM_INT);
                $res_insert ->bindValue(":access", $pal["access"], PDO::PARAM_INT);
                $res_insert ->bindValue(":menu", $pal["menu"], PDO::PARAM_INT);
                $res_insert ->execute();

            }
            $_SESSION ["msg"] = "<div class='alert alert-success alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "Permissões para o nível de acesso registradas com sucesso!</div>";
            $url_return= pg . "/list/list_niveis_acesso";
            header("Location: $url_return");
        }
    }
} else {
    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Whoops!&nbsp;</stron>"
            . "Erro ao carregar a página!</div>";
    $url_return= pg . "/list/list_niveis_acesso";
    header("Location: $url_return");
}

