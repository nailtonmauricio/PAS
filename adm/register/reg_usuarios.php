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
        <a href="<?php echo pg . '/list/list_usuarios'; ?>"><button type="button" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-list"></span> Listar</button></a>
    </div>
    <div class="page-header">
        <!--<h1>Cadastrar Usuários</h1>-->
    </div>
    <?php
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    ?>
    <form name="cadUsuarios" method="post" action="<?php echo pg; ?>/process/reg/reg_usuarios" class="form-horizontal" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nome" class="col-sm-2 control-label">Nome</label>
            <div class="col-sm-10">
                <input type="text" name="nome" class="form-control text-uppercase" id="nome" placeholder="Nome Completo" value="<?php
                if (isset($_SESSION['dados']['nome'])) {
                    echo $_SESSION['dados']['nome'];
                }
                ?>" autofocus/>
            </div>
        </div>
        <div class="form-group">
            <label for="email" class="col-sm-2 control-label">Email</label>
            <div class="col-sm-10">
                <input type="email" name="email" class="form-control text-lowercase" id="email" placeholder="E-mail" value="<?php
                if (isset($_SESSION['dados']['email'])) {
                    echo $_SESSION['dados']['email'];
                }
                ?>"/>
            </div>
        </div>
        <div class="form-group">
            <label for="cel" class="control-label col-sm-2">Celular</label>
            <div class="col-sm-10">
                <input type="text" name="cel" id="cel" class="form-control celular" placeholder="(99) 99999-9999" value="<?php if(isset($_SESSION['dados']['cel'])){echo $_SESSION['dados']['cel'];} elseif(!empty($rowCliente['celular'])){ echo $rowCliente['celular']; } ?>" <?php if(($busca||$cliente) && $found){ echo "disabled";} ?>/>
            </div>
        </div>
        <div class="form-group">
            <label for="usuario" class="col-sm-2 control-label">Usuário</label>
            <div class="col-sm-10">
                <input type="text" name="usuario" class="form-control text-uppercase" id="usuario" placeholder="Nome de Usuário" value="<?php
                if (isset($_SESSION['dados']['usuario'])) {
                    echo $_SESSION['dados']['usuario'];
                }
                ?>"/>
            </div>
        </div>
        <div class="form-group">
            <label for="senha" class="col-sm-2 control-label">Senha</label>
            <div class="col-sm-10">
                <input type="password" name="senha" class="form-control" id="senha" placeholder="Password"/>
            </div>
        </div>
        <div class="row form-group">
            <label for="nivelAcesso" class="col-sm-2 control-label">Nível de Acesso</label>
            <div class="col-sm-10">
                <div class="input-group">
                    <select class="form-control" name="nva_id">
                        <option value="">[Selecione]</option>
                        <?php
                        $sql_nva = "SELECT id, UPPER(nome) AS nome FROM nv_acessos WHERE ordem >= (SELECT ordem FROM nv_acessos WHERE id ='" . $_SESSION['nva_user_id'] . "') AND situacao = 1 ORDER BY id";
                        $result_nva = mysqli_query($conn, $sql_nva);
                        while ($row_nva = mysqli_fetch_array($result_nva)) {
                            if (isset($_SESSION['dados']['nva_id']) AND ( $_SESSION['dados']['nva_id']) == $row_nva ['id']) {
                                echo "<option value= '" . $row_nva ['id'] . "' selected>" . $row_nva ['nome'] . "</option>";
                            } else {
                                echo "<option value= '" . $row_nva ['id'] . "'>" . $row_nva ['nome'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                    <span class="input-group-btn">
                        <button class="btn btn-danger" type="button">
                            <a href="<?php echo pg; ?>/register/reg_niveis_acesso" style="color: #ffffff;">
                                <span class="glyphicon glyphicon-option-horizontal"></span>
                            </a>
                        </button>
                    </span>
                </div>

            </div>
        </div>
        <div class="row form-group">
            <label for="unidade" class="col-sm-2 control-label">Unidade</label>
            <div class="col-sm-10">
                <div class="input-group">
                    <select class="form-control" name="unidade_id">
                        <option value="">[Selecione]</option>
                        <?php
                        $sql_unidade = "SELECT id, UPPER(nome) AS nome FROM unidades WHERE situacao = '1' ORDER BY nome";
                        $result_unidade = mysqli_query($conn, $sql_unidade);
                        while ($row_unidade = mysqli_fetch_assoc($result_unidade)) {
                            if (isset($_SESSION['dados']['unidade_id']) AND ( $_SESSION['dados']['unidade_id']) == $row_unidade ['id']) {
                                echo "<option value= '" . $row_unidade ['id'] . "' selected>" . $row_unidade ['nome'] . "</option>";
                            } else {
                                echo "<option value= '" . $row_unidade ['id'] . "'>" . $row_unidade ['nome'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                    <span class="input-group-btn">
                        <button class="btn btn-danger" type="button">
                            <a href="<?php echo pg; ?>/register/reg_niveis_acesso" style="color: #ffffff;">
                                <span class="glyphicon glyphicon-option-horizontal"></span>
                            </a>
                        </button>
                    </span>
                </div>

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
