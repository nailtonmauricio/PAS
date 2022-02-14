<?php

if (!isset($_SESSION["check"])) {
    $_SESSION ["msg"] = "<div class='alert alert-danger alert-dismissible'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Área restrita, faça login para acessar.</div>";
    header("Location: login.php");
}
?>
<div class="well conteudo">
    <div class="pull-right">
        <a href="<?php echo pg . '/list/list_niveis_acesso'; ?>"><button type="button" class="btn btn-xs btn-primary"><span class='glyphicon glyphicon-list'></span> Listar</button></a>
    </div>
    <div class="page-header"></div>
    <?php
        if (isset($_SESSION["msg"])){
            echo $_SESSION["msg"];
            unset($_SESSION["msg"]);
        }
    ?>
    <form name="cadNvAcessos" method="post" action="<?php echo pg; ?>/process/reg/reg_niveis_acesso" class="form-horizontal" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nome" class="col-sm-2 control-label">Nome</label>
            <div class="col-sm-10">
                <input type="text" name="nome" class="form-control text-uppercase" id="nome" placeholder="Nome do Nível de Acesso" value="<?php if(isset($_SESSION["data"]["name"])){ echo $_SESSION["data"]["name"];}?>" autofocus pattern="[a-z 0-9]{4,}" required/>
            </div>
        </div>
        <div class="form-group">
            <label for="perfil" class="col-sm-2 control-label">Perfil</label>
            <div class="col-sm-10">
                <select name="perfil" id="perfil" class="form-control">
                    <option value="">[SELECIONE]</option>
                    <?php
                        $sql_perfil = "SELECT id, UPPER(name) AS nome, position FROM access_level WHERE id !=1 AND situation = 1";
                        $res_perfil = $conn->prepare($sql_perfil);
                        $res_perfil ->execute();
                        $row_perfil = $res_perfil ->fetchAll(PDO::FETCH_ASSOC);
                        foreach($row_perfil as $perfil):
                    ?>
                        <option value="<?=$perfil["id"]?>"><?=$perfil["nome"]?></option>
                    <?php
                        endforeach;
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="position" class="col-sm-2 control-label">Posição</label>
            <div class="col-sm-10">
                <select name="position" id="position" class="form-control">
                    <option value="">[SELECIONE]</option>
                    <?php
                    $sql_position = "SELECT position , UPPER(name) AS name FROM access_level WHERE id >=:id AND situation = 1";
                    $res_position = $conn->prepare($sql_position);
                    $res_position ->bindValue(":id", $_SESSION["credentials"]["position"], PDO::PARAM_INT);
                    $res_position->execute();
                    $row_position = $res_position ->fetchAll(PDO::FETCH_ASSOC);
                    foreach($row_position as $position):
                        ?>
                        <option value="<?=$position["position"]?>"><?=$position["name"]?></option>
                    <?php
                    endforeach;
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button class='btn btn-xs btn-success pull-right'>
                    <span class='glyphicon glyphicon-floppy-saved'></span> Salvar
                </button>
            </div>
        </div>        
    </form>
    <script type="text/javascript">
    /*Função que impede o envio do formulário pela tecla enter acidental*/        
        $(document).ready(function () {
           $('input').keypress(function (e) {
                var code = null;
                code = (e.keyCode ? e.keyCode : e.which);                
                return (code == 13) ? false : true;
           });
        });
    </script>
</div>
