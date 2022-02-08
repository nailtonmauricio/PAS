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
        <a href="<?php echo pg . '/list/list_niveis_acesso'; ?>"><button type="button" class="btn btn-xs btn-primary"><span class='glyphicon glyphicon-list'></span> Listar</button></a>
    </div>
    <div class="page-header"></div>
    <?php
        if (isset($_SESSION['msg'])){
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
        }
    ?>
    <form name="cadNvAcessos" method="post" action="<?php echo pg; ?>/process/reg/reg_niveis_acesso" class="form-horizontal" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nome" class="col-sm-2 control-label">Nome</label>
            <div class="col-sm-10">
                <input type="text" name="nome" class="form-control text-uppercase" id="nome" placeholder="Nome do Nível de Acesso" value="<?php if(isset($_SESSION['dados']['nome'])){ echo $_SESSION['dados']['nome'];}?>" autofocus/>
            </div>
        </div>
        <div class="form-group">
            <label for="perfil" class="col-sm-2 control-label">Perfil</label>
            <div class="col-sm-10">
                <select name="perfil" id="perfil" class="form-control">
                    <option>[SELECIONE]</option>
                    <?php
                        $sqlPerfil = "SELECT id, UPPER(nome) AS nome, ordem FROM nv_acessos WHERE id !=1 AND situacao =1";
                        $resPerfil = mysqli_query($conn,$sqlPerfil);
                        while ($rowPerfil = mysqli_fetch_assoc($resPerfil)) {
                            echo "<option value='".$rowPerfil['id']."'>".$rowPerfil['nome']."</option>";
                        }
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
