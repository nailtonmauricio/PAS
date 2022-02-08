<?php
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

     /*if($id == 1 AND $_SESSION["id"] == 1){
             $sql = "SELECT usuarios.*, st_usuarios.nome AS situacao,nv_acessos.nome AS nv_nome FROM usuarios 
             JOIN nv_acessos ON usuarios.nva_id = nv_acessos.id
             JOIN st_usuarios ON usuarios.stu_id = st_usuarios.id
             WHERE usuarios.id = $id";
     } else {
        $sql = "SELECT usuarios.*, st_usuarios.nome AS situacao,nv_acessos.nome AS nv_nome FROM usuarios 
             JOIN nv_acessos ON usuarios.nva_id = nv_acessos.id
             JOIN st_usuarios ON usuarios.stu_id = st_usuarios.id
             WHERE usuarios.id = $id";
     }
    $result = mysqli_query($conn, $sql);*/
    $sql = "SELECT usuarios.*, st_usuarios.nome AS situacao,nv_acessos.nome AS nv_nome FROM usuarios 
             JOIN nv_acessos ON usuarios.nva_id = nv_acessos.id
             JOIN st_usuarios ON usuarios.stu_id = st_usuarios.id
             WHERE usuarios.id =:user_id";
    $res = $conn ->prepare($sql);
    $res ->bindParam(":user_id", $id, PDO::PARAM_INT);
    $res ->execute();

    if ($res ->rowCount()) {
        //$row = mysqli_fetch_assoc($result);
        $row = $res ->fetch(PDO::FETCH_ASSOC);
        ?>
        <div class="well conteudo">
            <div class="pull-right">
                <?php
                $button_edit = load('edit/edit_usuarios', $conn);
                $button_list = load('list/list_usuarios', $conn);
                $button_delete = load('process/del/del_usuario', $conn);
                if ($button_list) {
                    echo "<a href= '" . pg . "/list/list_usuarios'><button type='button' class='btn btn-xs btn-primary'><span class='glyphicon glyphicon-list'></span> Listar</button></a> ";
                }
                if ($button_edit) {
                    echo "<a href= '" . pg . "/edit/edit_usuarios?id=" . $row['id'] . "'><button type='button' class='btn btn-xs btn-warning'><span class='glyphicon glyphicon-edit'></span> Editar</button></a> ";
                }
                if ($button_delete) {
                    echo "<a href= '" . pg . "/process/del/del_usuario?id=" . $row['id'] . "'onclick=\"return confirm('Apagar usuário?');\"><button type='button' class='btn btn-xs btn-danger'><span class='glyphicon glyphicon-remove'></span> Remover</button></a> ";
                }
                ?>
            </div>
            <div class="page-header"></div>
            <div class="dl-horizontal">
                <dt>Id</dt>
                <dd><?php echo $row['id'];?></dd>
                <dt>Nome</dt>
                <dd class="text-uppercase"><?php echo $row['nome'];?></dd>
                <dt>E-Mail</dt>
                <dd><?php echo $row['email'];?></dd>
                <dt class="text-uppercase">Usuário</dt>
                <dd class="text-uppercase"><?php echo $row['usuario'];?></dd>
                <?php
                    if(!empty($row['recuperar_senha'])){
                        echo "<dt>Recuperar Senha</dt>";
                        echo "<dd>".$row['recuperar_senha']."</dd>";
                    }
                ?>
                <!--<dt>Unidade</dt>
                <dd class="text-uppercase"><?php //echo $row['unidade'];?></dd>-->
                <dt>Nível de Acesso</dt>
                <dd class="text-uppercase"><?php echo $row['nv_nome'];?></dd>
                <dt>Situação</dt>
                <dd class="text-uppercase"><?php echo $row['situacao'];?></dd>
                <dt>Data de Cadastro</dt>
                <dd><?php echo date ('d/m/Y H:i:s', strtotime ($row ["created"]));?></dd>
                <dt>Ultima Alteração</dt>
                <dd><?php 
                        if (!empty($row['modified'])){
                            echo date('d/m/Y H:i:s', strtotime($row['modified']));
                        } else {
                            echo $row['modified'];
                        }
                    ?>
                </dd>
            </div>
        </div>
        <?php
    } else {
        $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
                . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
                . "<span aria-hidden='true'>&times;</span>"
                . "</button><strong>Aviso!&nbsp;</stron>"
                . "Whoops! Registro de usuário não encontrado!</div>";
        $url_destino = pg . "/list/list_usuarios";
        header("Location: $url_destino");
    }
} else {
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Whoops! Registro de usuário não encontrado!</div>";
    $url_destino = pg . "/list/list_usuarios";
    header("Location: $url_destino");
}

