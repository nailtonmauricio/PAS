<?php

if (!isset($_SESSION['check'])) {
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible'> "
            . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Área restrita, faça login para acessar.</div>";
    header("Location: login.php");
}

$ordem = filter_input(INPUT_GET, 'ordem', FILTER_SANITIZE_NUMBER_INT);
if (!empty($ordem)) {
    if ($ordem > $_SESSION['ordem']+1) {
        //Pesquisar o nível de acesso a ser alterado
        $sql_nv_atual = "SELECT id, ordem FROM nv_acessos WHERE ordem = '$ordem' LIMIT 1";
        $result_atual = mysqli_query($conn, $sql_nv_atual);
        $row_atual = mysqli_fetch_assoc($result_atual);
        
        $ordem_sup = $ordem - 1;
        $sql_nv_sup = "SELECT id, ordem FROM nv_acessos WHERE ordem = '$ordem_sup' LIMIT 1";
        $result_nv_sup = mysqli_query($conn, $sql_nv_sup);
        $row_sup = mysqli_fetch_assoc($result_nv_sup);
        
        $sql_nv_down = "UPDATE nv_acessos SET ordem = '" . $ordem . "',
                          modified =  NOW()
                          WHERE id = '" . $row_sup['id'] . "'";
        $result_down = mysqli_query($conn, $sql_nv_down);

        $sql_nv_up = "UPDATE nv_acessos SET ordem = '" . $ordem_sup . "',
                          modified =  NOW()
                          WHERE id = '" . $row_atual['id'] . "'";
        $result_up = mysqli_query($conn, $sql_nv_up);

        if (mysqli_affected_rows($conn)) {
            $_SESSION ['msg'] = "<div class='alert alert-success alert-dismissible text-center'> "
                    . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                    . "<span aria-hidden='true'>&times;</span>"
                    . "</button><strong>Aviso!&nbsp;</stron>"
                    . "Ordem do nível de acesso alterado com sucesso!</div>";
            $url_destino = pg . "/list/list_niveis_acesso";
            header("Location: $url_destino");
        } else {
            $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                    . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                    . "<span aria-hidden='true'>&times;</span>"
                    . "</button><strong>Aviso!&nbsp;</stron>"
                    . "Ação não realizada!</div>";
            $url_destino = pg . "/list/list_niveis_acesso";
            header("Location: $url_destino");
        }
    }
} else {
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Ordem não encontrada!</div>";
    $url_destino = pg . "/list/list_niveis_acesso";
    header("Location: $url_destino");
}
