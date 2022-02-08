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

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
if (!empty($id)) {
    $sql = "SELECT usuarios.nome, usuarios.email, usuarios.celular, usuarios.usuario, usuarios.senha, usuarios.unidade_id,UPPER(nv_acessos.nome) AS nva, st_usuarios.nome AS status, usuarios.nva_id, UPPER(unidades.nome) AS unidade FROM usuarios JOIN nv_acessos ON usuarios.nva_id = nv_acessos.id JOIN st_usuarios ON usuarios.stu_id = st_usuarios.id JOIN unidades ON usuarios.unidade_id = unidades.id WHERE usuarios.id =:user_id";
    $res = $conn ->prepare($sql);
    $res ->bindParam(":user_id", $id, PDO::PARAM_INT);
    $res ->execute();
    $row = $res ->fetch(PDO::FETCH_ASSOC);
    /*
     * Variável global para edição
     */
    $_SESSION["user_edit"] = $row;

    if ($res ->rowCount()) {
        ?>
        <div class="well conteudo">
            <div class="pull-right">
                <a href="<?php echo pg . '/list/list_usuarios'; ?>"><button type="button" class="btn btn-xs btn-primary"><span class='glyphicon glyphicon-list'></span> Listar</button></a>
            </div>
            <div class="page-header">
                <?php
                    if (isset($_SESSION['msg'])) {
                        echo $_SESSION['msg'];
                        unset($_SESSION['msg']);
                    }
                ?>
            </div>
            <form name="editUsuarios" method="post" action="<?php echo pg; ?>/process/edit/proc_edit_usuario" class="form-horizontal">
                <input type="hidden" name="id" id="id" value="<?= isset($dados["id"])?$dados["id"]:$id ?>"/>
                <div class="form-group">
                    <label for="nome" class="col-sm-2 control-label">Nome</label>
                    <div class="col-sm-10">
                        <input type="text" name="nome" class="form-control text-uppercase" id="nome" placeholder="Nome Completo" value="<?= isset($_SESSION["dados"]["nome"])?$_SESSION["dados"]["nome"]:$row["nome"] ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" name="email" class="form-control" id="email" placeholder="E-mail" value="<?= isset($_SESSION["dados"]["email"])?$_SESSION["dados"]["email"]:$row["email"] ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="usuario" class="col-sm-2 control-label">Usuário</label>
                    <div class="col-sm-10">
                        <input type="text" name="usuario" class="form-control text-uppercase" id="usuario" placeholder="Nome de Usuário" value="<?= isset($_SESSION["dados"]["usuario"])?$_SESSION["dados"]["usuario"]:$row["usuario"] ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="senha" class="col-sm-2 control-label">Senha</label>
                    <div class="col-sm-10">
                        <input type="password" name="senha" class="form-control" id="senha" placeholder="Password" value="<?= isset($_SESSION["dados"]["senha"])?$_SESSION["dados"]["senha"]: null ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="nva_id" class="col-sm-2 control-label">Nível de Acesso</label>
                    <div class="col-sm-10">
                        <select name="nva_id" id="nva_id" class="form-control">
                            <?php
                                echo "<option value='" . $row['nva_id'] . "' selected>" . $row['nva'] . "</option>";

                                /*if($_SESSION['nva_user_id'] == 1 || $_SESSION['nva_user_id'] == 2){
                                    $sql = "SELECT id, nome FROM nv_acessos WHERE(id !=:sesseion_nva_user_id AND id !=:row_nva_id) ORDER BY nome";
                                    $res = $conn ->prepare($sql);
                                    $res ->bindParam(":session_nva_user_id", $_SESSION["nva_user_id"], PDO::PARAM_INT);
                                    $res ->bindParam("row_nva_id", $row["nva_id"], PDO::PARAM_INT);
                                    $res ->execute();
                                    $row = $res ->fetchAll(PDO::FETCH_ASSOC);

                                    foreach($row as $nv_nome){
                                        echo "<option value= " . $nv_nome ["id"] . ">" . $nv_nome ["nome"] . "</option>";
                                    }
                                } */
                            $sqlNva = "SELECT id, UPPER(nome) AS nome FROM nv_acessos WHERE id != :nva_id ORDER BY nome";
                            $resNva = $conn ->prepare($sqlNva);
                            $resNva ->bindValue(":nva_id", $row["nva_id"], PDO::PARAM_INT);
                            $resNva ->execute();
                            $rowNva = $resNva ->fetchAll(PDO::FETCH_ASSOC);
                            foreach($rowNva as $nva){
                                echo "<option value= " . $nva["id"] . ">" . $nva["nome"] . "</option>";
                            }

                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="unidade_id" class="col-sm-2 control-label">Unidade</label>
                    <div class="col-sm-10">
                        <select name="unidade_id" id="unidade_id" class="form-control">
                            <?php
                            echo "<option value='" . $row['unidade_id'] . "' selected>" . $row['unidade'] . "</option>";

                            $sqlUnidade = "SELECT id, UPPER(nome) AS nome FROM unidades WHERE id != :unidade_id ORDER BY nome";
                            $resUnidade = $conn ->prepare($sqlUnidade);
                            $resUnidade ->bindValue(":unidade_id", $row["unidade_id"], PDO::PARAM_INT);
                            $resUnidade ->execute();
                            $rowUnidade = $resUnidade ->fetchAll(PDO::FETCH_ASSOC);
                            foreach($rowUnidade as $un){
                                echo "<option value= " . $un["id"] . ">" . $un["nome"] . "</option>";
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
        <?php
        unset($_SESSION['dados']);
    } else {
        $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "Nem um usuário encontrado!</div>";
        $url_destino = pg . "/list/list_usuarios";
        header("Location: $url_destino");
    }
} else {
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Nem um usuário encontrado!</div>";
    $url_destino = pg . "/list/list_usuarios";
    header("Location: $url_destino");
}  


