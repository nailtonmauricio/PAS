<?php

if (!isset($_SESSION['check'])) {
    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible'> "
            . "<button type='button' class='close' data-dismiss='alert'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Área restrita, faça login para acessar.</div>";
    header("Location: login.php");
}
?>
<div class="well conteudo">
    <div class="pull-right">
       <a href="<?php echo pg . '/register/reg_recados'; ?>"><button type="button" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-floppy-saved"></span> Cadastrar</button></a>
    </div>
    <div class="page-header">
        <?php
        if (isset($_SESSION['msg'])) {
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
        }
        /* Verificar botoes */
        $button_register = load('register/reg_recados', $conn);
        $button_edit = load('process/edit/proc_edit_recados', $conn);
        // Início da paginação, recebe o valor do número da página atual 
        $pg_rec = filter_input(INPUT_GET, 'pg', FILTER_SANITIZE_NUMBER_INT);
        $pg = (!empty($pg_rec)) ? $pg_rec : 1;
        $result_pg = 5;
        $ini_pag = ($result_pg * $pg) - $result_pg;

        $sql = "SELECT recados.id, recados.mensagem, recados.created, recados.modified, 
        recados.remetente_id,usuarios.nome AS remetente FROM recados
        JOIN usuarios ON usuarios.id = recados.remetente_id
        WHERE recados.destinatario_id = '".$_SESSION['id']."' AND recados.modified IS NULL ORDER BY recados.created DESC LIMIT $ini_pag, $result_pg";
        $result = mysqli_query($conn, $sql);
        // Fim da paginação.
        ?>
    </div>
    <div class="row">
        <div>
            <div class="col-sm-4 search-group">
                <form action="<?php echo pg . '/list/list_recados'; ?>" method="get">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="sr-only"><label for="buscar">Buscar registro</label></span>
                            <input type="search" class="form-control" name="q" placeholder="Buscar registro" id="buscar">
                            <span class="input-group-addon">
                                <button style="border:0;background:transparent;">
                                    <i class="glyphicon glyphicon-search"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </form>
            </div>
            <?php
                $busca = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_STRING);

                if(!empty($busca)){
                    
                    $sql= "SELECT recados.id, recados.mensagem, recados.created, recados.modified, 
                    recados.remetente_id, usuarios.nome AS remetente FROM recados
                    JOIN usuarios ON usuarios.id = recados.remetente_id
                    WHERE usuarios.nome LIKE '%$busca%' AND recados.destinatario_id = ".$_SESSION['id']."
                    ORDER BY created";
                    $result = mysqli_query($conn, $sql);
            ?>
                <div class="col-md-12">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th class="hidden-xs">Data</th>
                        <th>Remetente</th>
                        <th>Mensagem</th>
                        <th class="text-center">Ações</th>
                    </tr>                 
                </thead>
                <tbody>
                    <?php
                    if(mysqli_num_rows($result) == 0){
                        echo "<div class='alert alert-warning alert-dismissible text-center' role='alert'>
                              <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                              <span aria-hidden='true'>&times;</span>
                              </button>
                              <strong>Aviso!</strong> Nem um registro encontrado na base de dados para este remetente.
                              </div>";
                    }
                    while ($row = mysqli_fetch_array($result)) {
                        ?>
                        <tr>
                            <td class="hidden-xs"><?php echo date('d/m/Y', strtotime($row['created'])); ?></td>
                            <td><?php echo $row['remetente']; ?></td>
                            <td><?php echo $row['mensagem']; ?></td>
                            <td class="text-center">
                                <?php

                                if ($button_edit && is_null($row['modified'])) {
                                    echo "<a href= '" . pg . "/process/edit/proc_edit_recados?id=" . $row['id'] . "'><button type='button' class='btn btn-xs btn-danger' data-toggle='tooltip' data-placement='top' title='Confirmar leitura.'><span class='fas fa-check-square'></span></button></a> ";
                                } else {
                                    echo "<a href='#'><button type='button' class='btn btn-xs btn-info' data-toggle='tooltip' data-placement='top' title='Leitura confirmada.'><span class='fas fa-check-square'></span></button></a> ";
                                }
                                if ($button_register && is_null($row['modified'])) {
                                    echo "<a href='" . pg . "/register/reg_recados?idRecado=".$row['id']."&id=" . $row['remetente_id'] . "'><button type='button' class='btn btn-xs btn-warning' data-toggle='tooltip' data-placement='top' title='Responder mensagem.'><span class='fas fa-reply'></span></button></a> ";
                                }
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
                <?php
                } else {
                ?>
        <div class="col-md-12">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th class="hidden-xs">Data</th>
                        <th>Remetente</th>
                        <th>Mensagem</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if(mysqli_num_rows($result) == 0){
                        echo "<div class='alert alert-warning alert-dismissible text-center' role='alert'>
                              <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                              <span aria-hidden='true'>&times;</span>
                              </button>
                              <strong>Aviso!</strong> Você não possui novos recados.
                              </div>";
                    }
                    while ($row = mysqli_fetch_array($result)) {
                        ?>
                        <tr>
                            <td class="hidden-xs"><?php echo date('d/m/Y H:i:s', strtotime($row['created'])); ?></td>
                            <td><?php echo $row['remetente']; ?></td>
                            <td><?php echo $row['mensagem']; ?></td>
                            <td class="text-center">
                                <?php

                                if ($button_edit && is_null($row['modified'])) {
                                    echo "<a href='" . pg . "/process/edit/proc_edit_recados?id=" . $row['id'] . "'><button type='button' class='btn btn-xs btn-danger' data-toggle='tooltip' data-placement='top' title='Confirmar leitura.'><span class='fas fas fa-check-square'></span></button></a> ";
                                } else {
                                    echo "<a href='#'><button type='button' class='btn btn-xs btn-info' data-toggle='tooltip' data-placement='top' title='Leitura confirmada.'><span class='fas fa-check-square'></span></button></a> ";
                                }
                                if ($button_register) {
                                    echo "<a href='" . pg . "/register/reg_recados?idRecado=".$row['id']."&id=" . $row['remetente_id'] . "'><button type='button' class='btn btn-xs btn-warning' data-toggle='tooltip' data-placement='top' title='Responder mensagem.'><span class='fas fa-reply'></span></button></a> ";
                                }
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <!-- Início da paginação-->
            <?php
            $sql_pag = "SELECT COUNT(id) AS qnt_id FROM recados WHERE destinatario_id = '".$_SESSION['id']."'";
            $result_pag = mysqli_query($conn, $sql_pag);
            $row_pag = mysqli_fetch_assoc($result_pag);
            $qnt_pag = ceil($row_pag['qnt_id'] / $result_pg);

            $maxlink = 5;
            echo "<nav class='text-right'>";
            echo "<ul class='pagination'>";
            echo "<li><a href='" . pg . "/list/list_recados?pg=1 aria label='Previous'><span aria-hidden='true'>&laquo</span></a> ";
            for ($ipag = $pg - $maxlink; $ipag <= $pg - 1; $ipag++) {
                if ($ipag >= 1) {
                    echo "<li><a href='" . pg . "/list/list_recados?pg=$ipag'>$ipag </a></li>";
                }
            }

            echo "<li class='active'><a href='#'> $pg <span class='sr-only'></span></a></li>";

            for ($dpag = $pg + 1; $dpag <= $pg + $maxlink; $dpag++) {
                if ($dpag < $qnt_pag) {
                    echo "<li><a href='" . pg . "/list/list_recados?pg=$dpag'>$dpag </a></li>";
                }
            }
            echo "<li><a href='" . pg . "/list/list_recados?pg=" . $qnt_pag . "aria label='Previous'><span aria-hidden='true'>&raquo</span></a><li>";
            ?>
        </div>
                <?php
                }
            ?>
        </div>
    </div>
