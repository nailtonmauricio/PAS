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
        <a href="<?php echo pg . '/list/list_recados'; ?>"><button type="button" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-list"></span> Listar</button></a>
    </div>
    <div class="page-header"></div>
    <?php
        $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
        $idRecado = filter_input(INPUT_GET, "idRecado", FILTER_SANITIZE_NUMBER_INT);

        if (isset($_SESSION["msg"])) {
            echo $_SESSION["msg"];
            unset($_SESSION["msg"]);
        }
    ?>
    <form name="cadRecados" method="post" action="<?php echo pg; ?>/process/reg/proc_reg_recados" class="form-horizontal">
        <div class="form-group">
            <label for="destinatario_id" class="col-sm-2 control-label">Destinatário</label>
            <div class="col-sm-10">
                <select name="destinatario_id" class="form-control">
                    <option value="*">[Todos]</option>
                    <?php
                        $sql = "SELECT id, name FROM users WHERE id !=:id";
                        $res = $conn ->prepare($sql);
                        $res ->bindValue(":id", $_SESSION["credentials"]["id"], PDO::PARAM_INT);
                        $res ->execute();
                        $row = $res ->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($row as $user):
                    ?>
                    <option value="<?=$user["id"]?>"><?=$user["name"]?></option>
                    <?php
                        endforeach;
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="mensagem" class="col-sm-2 control-label">Recado</label>
            <div class="col-sm-10">
                <textarea class="form-control" id="mensagem" name="mensagem" rows="5" autofocus placeholder="Deixe um recado para seus colegas."style="resize: none"></textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button class="btn btn-xs btn-success pull-right">
                    <span class="glyphicon glyphicon-floppy-saved"></span> 
                    Cadastrar
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
