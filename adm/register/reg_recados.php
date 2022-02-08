<?php
// Verifica se a sessção foi iniciada, caso não tenha sido a linha 10 redireciona para a página de login.
if (!isset($_SESSION['check'])) {
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible'> "
            . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
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
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $idRecado = filter_input(INPUT_GET, 'idRecado', FILTER_SANITIZE_NUMBER_INT);

        if (isset($_SESSION['msg'])) {
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
        }
    ?>
    <form name="cadRecados" method="post" action="<?php echo pg; ?>/process/reg/proc_reg_recados" class="form-horizontal">
        <div class="form-group">
            <label for="destinatario_id" class="col-sm-2 control-label">Destinatário</label>
            <div class="col-sm-10">
                <?php
                    if(!empty($id)){
                        echo "<input name='destinatario_id' class='hidden' id='destinatario_id' value='$id'/>";
                        echo "<input name='idRecado' class='hidden' id='idRecado' value='$idRecado'/>";
                        $sqlDestinatario = "SELECT nome FROM usuarios WHERE id = '$id'";
                        $resDestinatario = mysqli_query($conn, $sqlDestinatario);
                        $rowDestinatario = mysqli_fetch_assoc($resDestinatario);
                        echo "<input name='destinatario' class='form-control' id='destinatario' value='".$rowDestinatario['nome']."' disabled/>";
                    } else {
                ?>
                <select name="destinatario_id" class="form-control">
                    <option value="*">[Todos]</option>
                    <?php
                        $sqlDestinatario = "SELECT id, nome FROM usuarios WHERE stu_id = 1 ORDER BY nome";
                        $resDestinatario = mysqli_query($conn, $sqlDestinatario);
                        while ($rowDestinatario = mysqli_fetch_assoc($resDestinatario)) {
                            echo "<option value= '" . $rowDestinatario ["id"] . "'>" . $rowDestinatario["nome"] ."</option>";
                        }
                    ?>
                </select>
                <?php
                    //Fechamento do select
                    }
                ?>
            </div>
        </div>
        <div class="form-group">
            <label for="setor_id" class="col-sm-2 control-label">Setor</label>
            <div class="col-sm-10">
                <select name="setor_id" class="form-control">
                    <option value="*">[Todos]</option>
     				<?php
     					$sqlSetor = "SELECT id, nome FROM nv_acessos ORDER BY nome";
                        $resSetor = mysqli_query($conn, $sqlSetor);
                        while ($rowSetor = mysqli_fetch_assoc($resSetor)) {
                            echo "<option value= '" . $rowSetor ["id"] . "'>" . $rowSetor["nome"] ."</option>";
                        }
     				?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="mensagem" class="col-sm-2 control-label">Recado</label>
            <div class="col-sm-10">
                <textarea class="form-control" id="mensagem" name="mensagem" rows="5" autofocus placeholder="Deixe um recado para seus colegas."></textarea>
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
