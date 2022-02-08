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
        <a href="<?php echo pg . '/list/list_paginas'; ?>"><button type="button" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-list"></span> Listar</button></a>
    </div>
    <div class="page-header">
        <!--<h1>Cadastrar Página</h1>-->
    </div>
    <?php
    if (isset($_SESSION["msg"])) {
        echo $_SESSION["msg"];
        unset($_SESSION["msg"]);
    }
    if(isset($_SESSION["data"])){
        var_dump(
                $_SESSION["data"]
        );
    }
    ?>
    <form name="cadPagina" method="post" action="<?php echo pg; ?>/process/reg/reg_paginas" class="form-horizontal" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nome" class="col-sm-2 control-label">Nome</label>
            <div class="col-sm-10">
                <input type="text" name="nome" class="form-control" id="nome" placeholder="Nome que será apresentado no menú." value="<?php
                if (isset($_SESSION['dados']['nome'])) {
                    echo $_SESSION['dados']['nome'];
                }
                ?>" autofocus/>
            </div>
        </div>
        <div class="form-group">
            <label for="endereco" class="col-sm-2 control-label">Endereço</label>
            <div class="col-sm-10">
                <input type="endereco" name="endereco" class="form-control" id="email" placeholder="diretorio/nome_pagina" value="<?php
                if (isset($_SESSION['dados']['endereco'])) {
                    echo $_SESSION['dados']['endereco'];
                }
                ?>"/>
            </div>
        </div>
        <div class="form-group">
            <label for="descricao" class="col-sm-2 control-label">Descrição</label>
            <div class="col-sm-10">
                <input type="text" name="descricao" class="form-control" id="descricao" placeholder="Descição do conteúdo da página cadastrada." value="<?php
                if (isset($_SESSION['dados']['descricao'])) {
                    echo $_SESSION['dados']['descricao'];
                }
                ?>"/>
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
