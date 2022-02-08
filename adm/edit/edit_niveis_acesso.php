<?php
// Verifica se a sessção foi iniciada, caso não tenha sido a linha 15 redireciona para a página de login.
if (!isset($_SESSION['check'])) {
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible'> "
            . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Área restrita, faça login para acessar.</div>";
    header("Location: login.php");
}
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if (!empty($id)) {
    if ($_SESSION['nva_user_id'] == 1) {
        $sql_nv = "SELECT * FROM nv_acessos WHERE id= '$id'";
    } else {
        $sql_nv = "SELECT * FROM nv_acessos
                 WHERE ordem > '".$_SESSION['ordem']."' AND '".$_SESSION['nva_user_id']."' = '$id' LIMIT 1";
    }
    $result_nv = mysqli_query($conn, $sql_nv);
    if (mysqli_num_rows($result_nv) > 0) {
        $row_nv= mysqli_fetch_assoc($result_nv);
        ?>
        <div class="well conteudo">
            <div class="pull-right">
                <a href="<?php echo pg . '/list/list_niveis_acesso'; ?>">
                    <button type="button" class="btn btn-xs btn-primary">
                        <span class='glyphicon glyphicon-list'></span> Listar
                    </button>
                </a>
            </div>
            <div class="page-header">
                <h1>Editar Níveis de Acesso</h1>
            </div>
            <?php
            if (isset($_SESSION['msg'])) {
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            ?>
            <form name="editNvAcesso" method="post" action="<?php echo pg; ?>/process/edit/edit_niveis_acesso" class="form-horizontal" enctype="multipart/form-data">
                <input type="hidden" name="id" id="id" value="<?php echo $row_nv['id']; ?>"/>
                <div class="form-group">
                    <label for="nome" class="col-sm-2 control-label">Nome</label>
                    <div class="col-sm-10">
                        <input type="text" name="nome" class="form-control text-capitalize" id="nome" placeholder="Nome Completo" value="<?php
                        if (isset($_SESSION['dados']['nome'])) {
                            echo $_SESSION['dados']['nome'];
                        } elseif (isset($row_nv['nome'])) {
                            echo $row_nv['nome'];
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
        <?php
        unset($_SESSION['dados']);
    } else {
        $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "Nem um usuário encontrado(1)!</div>";
        $url_destino = pg . "/list/list_niveis_acesso";
        header("Location: $url_destino");
    }
} else {
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Nem um usuário encontrado(2)!</div>";
    $url_destino = pg . "/list/list_niveis_acesso";
    header("Location: $url_destino");
}  
                    

