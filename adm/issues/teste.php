<?php

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
	<!-- Refresh na página para atualizar as coletas recentes-->
	<script language="Javascript">
    window.onload = function () {
        //Tempo em milisegundos 1 min = 60000 ms
        setTimeout('location.reload();', 60000);
      }

      function escolherFormulario() {
       if (confirm("Cadastrar grupo para coleta?")) {
        location.href="<?php echo pg; ?>/register/reg_drive_thru";
      } else {
        location.href="<?php echo pg; ?>/register/reg_coletas";
      }
    }
  </script>

  <div class="pull-right">
   <?php
   $button_cad = load('register/reg_coletas', $conn);
   $button_agenda = load('list/list_coletas', $conn);
   if ($button_agenda) {
    echo "<a href= '#'><button type='button' class='btn btn-xs btn-info' data-toggle='modal' data-target='#horarioModal' data-whatever='EdtName'><span class='glyphicon glyphicon-search'></span> Pesquisar</button></a> ";
  }
  if ($button_cad) {
    echo "<a href= '" . pg . "/register/reg_coletas'><button type='button' class='btn btn-xs btn-success'><span class='glyphicon glyphicon-floppy-saved'></span> Cadastrar</button></a> ";
  }

	//Limpar sessões de outras páginas
  if(isset($_SESSION['dados'])){
   unset($_SESSION['dados']);
 }

 ?>
</div>
<div class="page-header">
  <?php
  if (isset($_SESSION['msg'])) {
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
  }

  $button_edit = load('edit/edit_coletas', $conn);
  $button_edit_sem_rota = load('edit/edit_coleta_sem_rota', $conn);
  $button_view = load('viewer/view_coletas', $conn);
  $button_delete = laod('register/reg_excluir_coleta', $conn);
  $button_rota = laod('process/search/search_rotas', $conn);
  $button_confirm = laod('process/edit/proc_edit_confirmar_coletas', $conn);
  $button_definir = laod('edit/edit_confirmar_rotas', $conn);
  $button_remove = laod('process/edit/proc_remove_rota', $conn);

  $hoje = date('d/m/Y H:i:s');

        // Início da paginação 
  $pg_rec = filter_input(INPUT_GET, 'pg', FILTER_SANITIZE_NUMBER_INT);
  $pg = (!empty($pg_rec)) ? $pg_rec : 1;
  $result_pg = 5;
  $ini_pag = ($result_pg * $pg) - $result_pg;
        // Fim da paginação.
  ?>
</div>

<div class="row">
  <div class="col-sm-4">
    <?php
        //Busca por coletas da ROTA_01
    $sqlCarro1 = "SELECT coletas.id, coletas.agendamento, bairros.nome AS bairro, clientes.nome AS cliente FROM coletas
    JOIN clientes ON coletas.cliente_id = clientes.id
    JOIN bairros ON bairros.id = clientes.bairro_id
    WHERE coletas.verificacao = '0' AND ucm_id = 1 AND drive_thru = 0 ORDER BY agendamento ASC";
    $resultCarro1 = mysqli_query($conn, $sqlCarro1); 
        ?>
        <table class="table table-hover table-striped">
          <thead>
            <h4 class="text-center"><strong>ROTA 1</strong></h4>
            <tr>
              <th>AGENDAMENTO</th>
              <th class="hidden-xs">BAIRRO</th>
              <th class="text-center">AÇÕES</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if(mysqli_num_rows($resultCarro1) == 0){
              echo "<div class='alert alert-warning alert-dismissible text-center' role='alert'>
              <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
              <span aria-hidden='true'>&times;</span>
              </button>
              <strong>Aviso!</strong> Nenhum registro encontrado na base de dados.
              </div>";
            }
            while ($rowCarro1 = mysqli_fetch_assoc($resultCarro1)) {
              ?>
              <tr>
                <td>
                  <?php 
                  echo date('d/m/Y H:i', strtotime($rowCarro1['agendamento'])); 
                  ?>
                </td>
                <td class="hidden-xs text-uppercase"><?php echo $rowCarro1['bairro']; ?></td>
                <td class="text-center">
                  <?php
                  if ($button_view) {
                    echo "<a href= '" . pg . "/viewer/view_coletas?id=" . $rowCarro1['id'] . "'><button type='button' class='btn btn-xs btn-info' data-toggle='tooltip' data-placement='top' title='Visualizar Coleta'><span class='glyphicon glyphicon-folder-open'></span></button></a> ";
                  }
                  if ($button_edit) {
                    echo "<a href= '" . pg . "/edit/edit_coletas?id=" . $rowCarro1['id'] . "' onclick=\"return confirm('Deseja editar as informações de coleta?');\"><button type='button' class='btn btn-xs btn-warning' data-toggle='tooltip' data-placement='top' title='Editar Coleta'><span class='glyphicon glyphicon-edit'></span></button></a> ";
                  }

                  if ($button_confirm) {
                   echo "<a href= '" . pg . "/process/edit/proc_edit_confirmar_coletas?id=" . $rowCarro1['id'] . "' onclick=\"return confirm('Coleta realizada?');\"><button type='button' class='btn btn-xs btn-primary' data-toggle='tooltip' data-placement='top' title='Confirmar Coleta'><span class='glyphicon glyphicon-check'></span></button></a> ";
                 }
                 if($button_rota){
                   echo "<a href= '" . pg . "/process/search/search_rotas?id=" . $rowCarro1['id'] . "' onclick=\"return confirm('Buscar rota?');\" target='_BLANCK'><button type='button' class='btn btn-xs btn-success' data-toggle='tooltip' data-placement='top' title='Visualizar Rota Para Coleta'><span class='fas fa-map-marked-alt'></span></button></a> ";
                 }
                 if($button_remove){
                   echo "<a href= '" . pg . "/process/edit/proc_remove_rota?id=" . $rowCarro1['id'] . "' onclick=\"return confirm('Remover da rota?');\"><button type='button' class='btn btn-xs btn-danger' data-toggle='tooltip' data-placement='top' title='Remover Coleta Desta Rota'><span class='fas fa-map'></span></button></a> ";
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
     <div class="col-sm-4">
      <?php
      $sqlCarro2 = "SELECT coletas.id, coletas.agendamento, bairros.nome AS bairro, clientes.nome AS cliente FROM coletas
      JOIN clientes ON coletas.cliente_id = clientes.id
      JOIN bairros ON bairros.id = clientes.bairro_id
      WHERE coletas.verificacao = '0' AND ucm_id = 2 ORDER BY agendamento ASC";
      $resultCarro2 = mysqli_query($conn, $sqlCarro2);
      ?>
      <table class="table table-hover table-striped">
        <thead>
          <h4 class="text-center"><strong>ROTA 2</strong></h4>
          <tr>
            <th>AGENDAMENTO</th>
            <th class="hidden-xs">BAIRRO</th>
            <th class="text-center">AÇÕES</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if(mysqli_num_rows($resultCarro2) == 0){
            echo "<div class='alert alert-warning alert-dismissible text-center' role='alert'>
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>&times;</span>
            </button>
            <strong>Aviso!</strong> Nenhum registro encontrado na base de dados.
            </div>";
          }
          while ($rowCarro2 = mysqli_fetch_assoc($resultCarro2)) {
            ?>
            <tr>
              <td>
                <?php 
                echo date('d/m/Y H:i', strtotime($rowCarro2['agendamento'])); 
                ?>
              </td>
              <td class="hidden-xs text-uppercase"><?php echo $rowCarro2['bairro']; ?></td>
              <td class="text-center">
                <?php
                if ($button_view) {
                  echo "<a href= '" . pg . "/viewer/view_coletas?id=" . $rowCarro2['id'] . "'><button type='button' class='btn btn-xs btn-info' data-toggle='tooltip' data-placement='top' title='Visualizar Coleta'><span class='glyphicon glyphicon-folder-open'></span></button></a> ";
                }
                if ($button_edit) {
                  echo "<a href= '" . pg . "/edit/edit_coletas?id=" . $rowCarro2['id'] . "' onclick=\"return confirm('Deseja editar as informações de coleta?');\"><button type='button' class='btn btn-xs btn-warning' data-toggle='tooltip' data-placement='top' title='Editar Coleta'><span class='glyphicon glyphicon-edit'></span></button></a> ";
                }
                if ($button_confirm) {
                 echo "<a href= '" . pg . "/process/edit/proc_edit_confirmar_coletas?id=" . $rowCarro2['id'] . "' onclick=\"return confirm('Coleta realizada?');\"><button type='button' class='btn btn-xs btn-primary' data-toggle='tooltip' data-placement='top' title='Confirmar Coleta'><span class='glyphicon glyphicon-check'></span></button></a> ";
               }
               if($button_rota){
                 echo "<a href= '" . pg . "/process/search/search_rotas?id=" . $rowCarro2['id'] . "' onclick=\"return confirm('Buscar rota?');\" target='_BLANCK'><button type='button' class='btn btn-xs btn-success' data-toggle='tooltip' data-placement='top' title='Visualizar Rota Para Coleta'><span class='fas fa-map-marked-alt'></span></button></a> ";
               }
               if($button_remove){
                 echo "<a href= '" . pg . "/process/edit/proc_remove_rota?id=" . $rowCarro2['id'] . "' onclick=\"return confirm('Remover da rota?');\"><button type='button' class='btn btn-xs btn-danger' data-toggle='tooltip' data-placement='top' title='Remover Coleta Desta Rota'><span class='fas fa-map'></span></button></a> ";
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
   <div class="col-sm-4">
    <?php
    $sqlCarro3 = "SELECT coletas.id, coletas.agendamento, bairros.nome AS bairro, clientes.nome AS cliente FROM coletas
    JOIN clientes ON coletas.cliente_id = clientes.id
    JOIN bairros ON bairros.id = clientes.bairro_id
    WHERE coletas.verificacao = '0' AND ucm_id = 3 ORDER BY agendamento ASC";
    $resultCarro3 = mysqli_query($conn, $sqlCarro3);
    ?>
    <table class="table table-hover table-striped">
      <thead>
        <h4 class="text-center"><strong>ROTA 3</strong></h4>
        <tr>
          <th>AGENDAMENTO</th>
          <th class="hidden-xs">BAIRRO</th>
          <th class="text-center">AÇÕES</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if(mysqli_num_rows($resultCarro3) ==0){
          echo "<div class='alert alert-warning alert-dismissible text-center' role='alert'>
          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
          <span aria-hidden='true'>&times;</span>
          </button>
          <strong>Aviso!</strong> Nenhum registro encontrado na base de dados.
          </div>";
        }
        while ($rowCarro3 = mysqli_fetch_assoc($resultCarro3)) {
          ?>
          <tr>
            <td>
              <?php 
              echo date('d/m/Y H:i', strtotime($rowCarro3['agendamento'])); 
              ?>
            </td>
            <td class="hidden-xs text-uppercase"><?php echo $rowCarro3['bairro']; ?></td>
            <td class="text-center">
              <?php
              if ($button_view) {
                echo "<a href= '" . pg . "/viewer/view_coletas?id=" . $rowCarro3['id'] . "'><button type='button' class='btn btn-xs btn-info' data-toggle='tooltip' data-placement='top' title='Visualizar Coleta'><span class='glyphicon glyphicon-folder-open'></span></button></a> ";
              }
              if ($button_edit) {
                echo "<a href= '" . pg . "/edit/edit_coletas?id=" . $rowCarro3['id'] . "' onclick=\"return confirm('Deseja editar as informações de coleta?');\"><button type='button' class='btn btn-xs btn-warning' data-toggle='tooltip' data-placement='top' title='Editar Coleta'><span class='glyphicon glyphicon-edit'></span></button></a> ";
              }
              if ($button_confirm) {
               echo "<a href= '" . pg . "/process/edit/proc_edit_confirmar_coletas?id=" . $rowCarro3['id'] . "' onclick=\"return confirm('Coleta realizada?');\"><button type='button' class='btn btn-xs btn-primary' data-toggle='tooltip' data-placement='top' title='Confirmar Coleta'><span class='glyphicon glyphicon-check'></span></button></a> ";
             }
             if($button_rota){
               echo "<a href= '" . pg . "/process/search/search_rotas?id=" . $rowCarro3['id'] . "' onclick=\"return confirm('Buscar rota?');\" target='_BLANCK'><button type='button' class='btn btn-xs btn-success' data-toggle='tooltip' data-placement='top' title='Visualizar Rota Para Coleta'><span class='fas fa-map-marked-alt'></span></button></a> ";
             }
             if($button_remove){
               echo "<a href= '" . pg . "/process/edit/proc_remove_rota?id=" . $rowCarro3['id'] . "' onclick=\"return confirm('Remover da rota?');\"><button type='button' class='btn btn-xs btn-danger' data-toggle='tooltip' data-placement='top' title='Remover Coleta Desta Rota'><span class='fas fa-map'></span></button></a> ";
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
</div>

<div class="row">
  <div class="col-sm-4">
    <?php
    //Busca por coletas da ROTA_02
    $sqlCarro4 = "SELECT coletas.id, coletas.agendamento, bairros.nome AS bairro, clientes.nome AS cliente FROM coletas
    JOIN clientes ON coletas.cliente_id = clientes.id
    JOIN bairros ON bairros.id = clientes.bairro_id
    WHERE coletas.verificacao = '0' AND ucm_id = 4 ORDER BY agendamento ASC";
    $resultCarro4 = mysqli_query($conn, $sqlCarro4);

    ?>
    <table class="table table-hover table-striped">
      <thead>
        <h4 class="text-center"><strong>ROTA 4</strong></h4>
        <tr>
          <th>AGENDAMENTO</th>
          <th class="hidden-xs">BAIRRO</th>
          <th class="text-center">AÇÕES</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if(mysqli_num_rows($resultCarro4) == 0){
          echo "<div class='alert alert-warning alert-dismissible text-center' role='alert'>
          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
          <span aria-hidden='true'>&times;</span>
          </button>
          <strong>Aviso!</strong> Nenhum registro encontrado na base de dados.
          </div>";
        }
        while ($rowCarro4 = mysqli_fetch_assoc($resultCarro4)) {
          ?>
          <tr>
            <td>
              <?php 
              echo date('d/m/Y H:i', strtotime($rowCarro4['agendamento'])); 
              ?>
            </td>
            <td class="hidden-xs text-uppercase"><?php echo $rowCarro4['bairro']; ?></td>
            <td class="text-center">
              <?php
              if ($button_view) {
                echo "<a href= '" . pg . "/viewer/view_coletas?id=" . $rowCarro4['id'] . "'><button type='button' class='btn btn-xs btn-info' data-toggle='tooltip' data-placement='top' title='Visualizar Coleta'><span class='glyphicon glyphicon-folder-open'></span></button></a> ";
              }
              if ($button_edit) {
                echo "<a href= '" . pg . "/edit/edit_coletas?id=" . $rowCarro4['id'] . "' onclick=\"return confirm('Deseja editar as informações de coleta?');\"><button type='button' class='btn btn-xs btn-warning' data-toggle='tooltip' data-placement='top' title='Editar Coleta'><span class='glyphicon glyphicon-edit'></span></button></a> ";
              }

              if ($button_confirm) {
                echo "<a href= '" . pg . "/process/edit/proc_edit_confirmar_coletas?id=" . $rowCarro4['id'] . "' onclick=\"return confirm('Coleta realizada?');\"><button type='button' class='btn btn-xs btn-primary' data-toggle='tooltip' data-placement='top' title='Confirmar Coleta'><span class='glyphicon glyphicon-check'></span></button></a> ";
              }
              if($button_rota){
                echo "<a href= '" . pg . "/process/search/search_rotas?id=" . $rowCarro4['id'] . "' onclick=\"return confirm('Buscar rota?');\" target='_BLANCK'><button type='button' class='btn btn-xs btn-success' data-toggle='tooltip' data-placement='top' title='Visualizar Rota Para Coleta'><span class='fas fa-map-marked-alt'></span></button></a> ";
              }
              if($button_remove){
                echo "<a href= '" . pg . "/process/edit/proc_remove_rota?id=" . $rowCarro4['id'] . "' onclick=\"return confirm('Remover da rota?');\"><button type='button' class='btn btn-xs btn-danger' data-toggle='tooltip' data-placement='top' title='Remover Coleta Desta Rota'><span class='fas fa-map'></span></button></a> ";
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
  <div class="col-sm-4">
    <?php
    $sqlCarro5 = "SELECT coletas.id, coletas.agendamento, bairros.nome AS bairro, clientes.nome AS cliente FROM coletas
    JOIN clientes ON coletas.cliente_id = clientes.id
    JOIN bairros ON bairros.id = clientes.bairro_id
    WHERE coletas.verificacao = '0' AND ucm_id = 5 ORDER BY agendamento ASC";
    $resultCarro5 = mysqli_query($conn, $sqlCarro5);
    ?>
    <table class="table table-hover table-striped">
      <thead>
        <h4 class="text-center"><strong>ROTA 5</strong></h4>
        <tr>
          <th>AGENDAMENTO</th>
          <th class="hidden-xs">BAIRRO</th>
          <th class="text-center">AÇÕES</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if(mysqli_num_rows($resultCarro5) ==0){
          echo "<div class='alert alert-warning alert-dismissible text-center' role='alert'>
          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
          <span aria-hidden='true'>&times;</span>
          </button>
          <strong>Aviso!</strong> Nenhum registro encontrado na base de dados.
          </div>";
        }
        while ($rowCarro5 = mysqli_fetch_assoc($resultCarro5)) {
          ?>
          <tr>
            <td>
              <?php 
              echo date('d/m/Y H:i', strtotime($rowCarro5['agendamento'])); 
              ?>
            </td>
            <td class="hidden-xs text-uppercase"><?php echo $rowCarro5['bairro']; ?></td>
            <td class="text-center">
              <?php
              if ($button_view) {
                echo "<a href= '" . pg . "/viewer/view_coletas?id=" . $rowCarro5['id'] . "'><button type='button' class='btn btn-xs btn-info' data-toggle='tooltip' data-placement='top' title='Visualizar Coleta'><span class='glyphicon glyphicon-folder-open'></span></button></a> ";
              }
              if ($button_edit) {
                echo "<a href= '" . pg . "/edit/edit_coletas?id=" . $rowCarro5['id'] . "' onclick=\"return confirm('Deseja editar as informações de coleta?');\"><button type='button' class='btn btn-xs btn-warning' data-toggle='tooltip' data-placement='top' title='Editar Coleta'><span class='glyphicon glyphicon-edit'></span></button></a> ";
              }
              if ($button_confirm) {
                echo "<a href= '" . pg . "/process/edit/proc_edit_confirmar_coletas?id=" . $rowCarro5['id'] . "' onclick=\"return confirm('Coleta realizada?');\"><button type='button' class='btn btn-xs btn-primary' data-toggle='tooltip' data-placement='top' title='Confirmar Coleta'><span class='glyphicon glyphicon-check'></span></button></a> ";
              }
              if($button_rota){
                echo "<a href= '" . pg . "/process/search/search_rotas?id=" . $rowCarro5['id'] . "' onclick=\"return confirm('Buscar rota?');\" target='_BLANCK'><button type='button' class='btn btn-xs btn-success' data-toggle='tooltip' data-placement='top' title='Visualizar Rota Para Coleta'><span class='fas fa-map-marked-alt'></span></button></a> ";
              }
              if($button_remove){
                echo "<a href= '" . pg . "/process/edit/proc_remove_rota?id=" . $rowCarro5['id'] . "' onclick=\"return confirm('Remover da rota?');\"><button type='button' class='btn btn-xs btn-danger' data-toggle='tooltip' data-placement='top' title='Remover Coleta Desta Rota'><span class='fas fa-map'></span></button></a> ";
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
  <div class="col-sm-4">
    <?php
    $sqlCarro6= "SELECT coletas.id, coletas.agendamento, bairros.nome AS bairro, clientes.nome AS cliente FROM coletas
    JOIN clientes ON coletas.cliente_id = clientes.id
    JOIN bairros ON bairros.id = clientes.bairro_id
    WHERE coletas.verificacao = '0' AND ucm_id = 7 ORDER BY agendamento ASC";
    var_dump($sqlCarro6);
    $resultCarro6 = mysqli_query($conn, $sqlCarro6);
    ?>
    <table class="table table-hover table-striped">
      <thead>
        <h4 class="text-center"><strong>ROTA 6</strong></h4>
        <tr>
          <th>AGENDAMENTO</th>
          <th class="hidden-xs">BAIRRO</th>
          <th class="text-center">AÇÕES</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if(mysqli_num_rows($resultCarro6) == 0){
          echo "<div class='alert alert-warning alert-dismissible text-center' role='alert'>
          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
          <span aria-hidden='true'>&times;</span>
          </button>
          <strong>Aviso!</strong> Nenhum registro encontrado na base de dados.
          </div>";
        }
        while ($rowCarro6 = mysqli_fetch_assoc($resultCarro6)) {
          ?>
          <tr>
            <td>
              <?php 
              echo date('d/m/Y H:i', strtotime($rowCarro6['agendamento'])); 
              ?>
            </td>
            <td class="hidden-xs text-uppercase"><?php echo $rowCarro6['bairro']; ?></td>
            <td class="text-center">
              <?php
              if ($button_view) {
                echo "<a href= '" . pg . "/viewer/view_coletas?id=" . $rowCarro6['id'] . "'><button type='button' class='btn btn-xs btn-info' data-toggle='tooltip' data-placement='top' title='Visualizar Coleta'><span class='glyphicon glyphicon-folder-open'></span></button></a> ";
              }
              if ($button_edit) {
                echo "<a href= '" . pg . "/edit/edit_coletas?id=" . $rowCarro6['id'] . "' onclick=\"return confirm('Deseja editar as informações de coleta?');\"><button type='button' class='btn btn-xs btn-warning' data-toggle='tooltip' data-placement='top' title='Editar Coleta'><span class='glyphicon glyphicon-edit'></span></button></a> ";
              }
              if ($button_confirm) {
                echo "<a href= '" . pg . "/process/edit/proc_edit_confirmar_coletas?id=" . $rowCarro6['id'] . "' onclick=\"return confirm('Coleta realizada?');\"><button type='button' class='btn btn-xs btn-primary' data-toggle='tooltip' data-placement='top' title='Confirmar Coleta'><span class='glyphicon glyphicon-check'></span></button></a> ";
              }
              if($button_rota){
                echo "<a href= '" . pg . "/process/search/search_rotas?id=" . $rowCarro6['id'] . "' onclick=\"return confirm('Buscar rota?');\" target='_BLANCK'><button type='button' class='btn btn-xs btn-success' data-toggle='tooltip' data-placement='top' title='Visualizar Rota Para Coleta'><span class='fas fa-map-marked-alt'></span></button></a> ";
              }
              if($button_remove){
                echo "<a href= '" . pg . "/process/edit/proc_remove_rota?id=" . $rowCarro6['id'] . "' onclick=\"return confirm('Remover da rota?');\"><button type='button' class='btn btn-xs btn-danger' data-toggle='tooltip' data-placement='top' title='Remover Coleta Desta Rota'><span class='fas fa-map'></span></button></a> ";
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
</div>

<div class="row">
  <!--<div class="col-sm-4">
    <?php
    //Busca por coletas da ROTA_02
    $sqlCarro4 = "SELECT coletas.id, coletas.agendamento, bairros.nome AS bairro, clientes.nome AS cliente FROM coletas
    JOIN clientes ON coletas.cliente_id = clientes.id
    JOIN bairros ON bairros.id = clientes.bairro_id
    WHERE coletas.verificacao = '0' AND ucm_id = 4 ORDER BY agendamento ASC";
    $resultCarro4 = mysqli_query($conn, $sqlCarro4);

    ?>
    <table class="table table-hover table-striped">
      <thead>
        <h4 class="text-center"><strong>ROTA 4</strong></h4>
        <tr>
          <th>AGENDAMENTO</th>
          <th class="hidden-xs">BAIRRO</th>
          <th class="text-center">AÇÕES</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if(mysqli_num_rows($resultCarro4) == 0){
          echo "<div class='alert alert-warning alert-dismissible text-center' role='alert'>
          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
          <span aria-hidden='true'>&times;</span>
          </button>
          <strong>Aviso!</strong> Nenhum registro encontrado na base de dados.
          </div>";
        }
        while ($rowCarro4 = mysqli_fetch_assoc($resultCarro4)) {
          ?>
          <tr>
            <td>
              <?php 
              echo date('d/m/Y H:i', strtotime($rowCarro4['agendamento'])); 
              ?>
            </td>
            <td class="hidden-xs text-uppercase"><?php echo $rowCarro4['bairro']; ?></td>
            <td class="text-center">
              <?php
              if ($button_view) {
                echo "<a href= '" . pg . "/viewer/view_coletas?id=" . $rowCarro4['id'] . "'><button type='button' class='btn btn-xs btn-info' data-toggle='tooltip' data-placement='top' title='Visualizar Coleta'><span class='glyphicon glyphicon-folder-open'></span></button></a> ";
              }
              if ($button_edit) {
                echo "<a href= '" . pg . "/edit/edit_coletas?id=" . $rowCarro4['id'] . "' onclick=\"return confirm('Deseja editar as informações de coleta?');\"><button type='button' class='btn btn-xs btn-warning' data-toggle='tooltip' data-placement='top' title='Editar Coleta'><span class='glyphicon glyphicon-edit'></span></button></a> ";
              }

              if ($button_confirm) {
                echo "<a href= '" . pg . "/process/edit/proc_edit_confirmar_coletas?id=" . $rowCarro4['id'] . "' onclick=\"return confirm('Coleta realizada?');\"><button type='button' class='btn btn-xs btn-primary' data-toggle='tooltip' data-placement='top' title='Confirmar Coleta'><span class='glyphicon glyphicon-check'></span></button></a> ";
              }
              if($button_rota){
                echo "<a href= '" . pg . "/process/search/search_rotas?id=" . $rowCarro4['id'] . "' onclick=\"return confirm('Buscar rota?');\" target='_BLANCK'><button type='button' class='btn btn-xs btn-success' data-toggle='tooltip' data-placement='top' title='Visualizar Rota Para Coleta'><span class='fas fa-map-marked-alt'></span></button></a> ";
              }
              if($button_remove){
                echo "<a href= '" . pg . "/process/edit/proc_remove_rota?id=" . $rowCarro4['id'] . "' onclick=\"return confirm('Remover da rota?');\"><button type='button' class='btn btn-xs btn-danger' data-toggle='tooltip' data-placement='top' title='Remover Coleta Desta Rota'><span class='fas fa-map'></span></button></a> ";
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
  <div class="col-sm-4">
    <?php
    $sqlCarro5 = "SELECT coletas.id, coletas.agendamento, bairros.nome AS bairro, clientes.nome AS cliente FROM coletas
    JOIN clientes ON coletas.cliente_id = clientes.id
    JOIN bairros ON bairros.id = clientes.bairro_id
    WHERE coletas.verificacao = '0' AND ucm_id = 5 ORDER BY agendamento ASC";
    $resultCarro5 = mysqli_query($conn, $sqlCarro5);
    ?>
    <table class="table table-hover table-striped">
      <thead>
        <h4 class="text-center"><strong>ROTA 5</strong></h4>
        <tr>
          <th>AGENDAMENTO</th>
          <th class="hidden-xs">BAIRRO</th>
          <th class="text-center">AÇÕES</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if(mysqli_num_rows($resultCarro5) ==0){
          echo "<div class='alert alert-warning alert-dismissible text-center' role='alert'>
          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
          <span aria-hidden='true'>&times;</span>
          </button>
          <strong>Aviso!</strong> Nenhum registro encontrado na base de dados.
          </div>";
        }
        while ($rowCarro5 = mysqli_fetch_assoc($resultCarro5)) {
          ?>
          <tr>
            <td>
              <?php 
              echo date('d/m/Y H:i', strtotime($rowCarro5['agendamento'])); 
              ?>
            </td>
            <td class="hidden-xs text-uppercase"><?php echo $rowCarro5['bairro']; ?></td>
            <td class="text-center">
              <?php
              if ($button_view) {
                echo "<a href= '" . pg . "/viewer/view_coletas?id=" . $rowCarro5['id'] . "'><button type='button' class='btn btn-xs btn-info' data-toggle='tooltip' data-placement='top' title='Visualizar Coleta'><span class='glyphicon glyphicon-folder-open'></span></button></a> ";
              }
              if ($button_edit) {
                echo "<a href= '" . pg . "/edit/edit_coletas?id=" . $rowCarro5['id'] . "' onclick=\"return confirm('Deseja editar as informações de coleta?');\"><button type='button' class='btn btn-xs btn-warning' data-toggle='tooltip' data-placement='top' title='Editar Coleta'><span class='glyphicon glyphicon-edit'></span></button></a> ";
              }
              if ($button_confirm) {
                echo "<a href= '" . pg . "/process/edit/proc_edit_confirmar_coletas?id=" . $rowCarro5['id'] . "' onclick=\"return confirm('Coleta realizada?');\"><button type='button' class='btn btn-xs btn-primary' data-toggle='tooltip' data-placement='top' title='Confirmar Coleta'><span class='glyphicon glyphicon-check'></span></button></a> ";
              }
              if($button_rota){
                echo "<a href= '" . pg . "/process/search/search_rotas?id=" . $rowCarro5['id'] . "' onclick=\"return confirm('Buscar rota?');\" target='_BLANCK'><button type='button' class='btn btn-xs btn-success' data-toggle='tooltip' data-placement='top' title='Visualizar Rota Para Coleta'><span class='fas fa-map-marked-alt'></span></button></a> ";
              }
              if($button_remove){
                echo "<a href= '" . pg . "/process/edit/proc_remove_rota?id=" . $rowCarro5['id'] . "' onclick=\"return confirm('Remover da rota?');\"><button type='button' class='btn btn-xs btn-danger' data-toggle='tooltip' data-placement='top' title='Remover Coleta Desta Rota'><span class='fas fa-map'></span></button></a> ";
              }
              ?>
            </td>
          </tr>
          <?php
        }
        ?>
      </tbody>
    </table>
  </div>-->
  <div class="col-sm-12">
    <?php
    $sqlDriveThru = "SELECT coletas.id, coletas.agendamento, clientes.nome AS cliente FROM coletas
    JOIN clientes ON coletas.cliente_id = clientes.id
    WHERE coletas.verificacao = '0' AND ucm_id = 6 ORDER BY agendamento ASC";
    $resultDriveThru = mysqli_query($conn, $sqlDriveThru);
    ?>
    <table class="table table-hover table-striped">
      <thead>
        <h4 class="text-center"><strong>DRIVE THRU</strong></h4>
        <tr>
          <th>AGENDAMENTO</th>
          <th class="hidden-xs">CLIENTE</th>
          <th class="text-center">AÇÕES</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if(mysqli_num_rows($resultDriveThru) == 0){
          echo "<div class='alert alert-warning alert-dismissible text-center' role='alert'>
          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
          <span aria-hidden='true'>&times;</span>
          </button>
          <strong>Aviso!</strong> Nenhum registro encontrado na base de dados.
          </div>";
        }
        while ($rowDriveThru = mysqli_fetch_assoc($resultDriveThru)) {
          ?>
          <tr>
            <td>
              <?php 
              echo date('d/m/Y H:i', strtotime($rowDriveThru['agendamento'])); 
              ?>
            </td>
            <td class="hidden-xs text-uppercase"><?php echo $rowDriveThru['cliente']; ?></td>
            <td class="text-center">
              <?php
              if ($button_view) {
                echo "<a href= '" . pg . "/viewer/view_coletas?id=" . $rowDriveThru['id'] . "'><button type='button' class='btn btn-xs btn-info' data-toggle='tooltip' data-placement='top' title='Visualizar Coleta'><span class='glyphicon glyphicon-folder-open'></span></button></a> ";
              }
              if ($button_edit) {
                echo "<a href= '" . pg . "/edit/edit_coletas?id=" . $rowDriveThru['id'] . "' onclick=\"return confirm('Deseja editar as informações de coleta?');\"><button type='button' class='btn btn-xs btn-warning' data-toggle='tooltip' data-placement='top' title='Editar Coleta'><span class='glyphicon glyphicon-edit'></span></button></a> ";
              }
              if ($button_confirm) {
                echo "<a href= '" . pg . "/process/edit/proc_edit_confirmar_coletas?id=" . $rowDriveThru['id'] . "' onclick=\"return confirm('Coleta realizada?');\"><button type='button' class='btn btn-xs btn-primary' data-toggle='tooltip' data-placement='top' title='Confirmar Coleta'><span class='glyphicon glyphicon-check'></span></button></a> ";
              }
              if($button_rota){
                echo "<a href= '" . pg . "/process/search/search_rotas?id=" . $rowDriveThru['id'] . "' onclick=\"return confirm('Buscar rota?');\" target='_BLANCK'><button type='button' class='btn btn-xs btn-success' data-toggle='tooltip' data-placement='top' title='Visualizar Rota Para Coleta'><span class='fas fa-map-marked-alt'></span></button></a> ";
              }
              if($button_remove){
                echo "<a href= '" . pg . "/process/edit/proc_remove_rota?id=" . $rowDriveThru['id'] . "' onclick=\"return confirm('Remover da rota?');\"><button type='button' class='btn btn-xs btn-danger' data-toggle='tooltip' data-placement='top' title='Remover Coleta Desta Rota'><span class='fas fa-map'></span></button></a> ";
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
</div>

</div>
<div class="well conteudo">
  <div class="row">
   <!--Essa seção será apresentada para quem realiza o cadastro porem não finaliza a rota -->
   <div class="col-sm-6">
    <?php
    $sqlConfirmar = "SELECT coletas.id, coletas.agendamento, bairros.nome AS bairro, bairros.zona AS zona FROM coletas 
    JOIN clientes ON coletas.cliente_id = clientes.id 
    JOIN bairros ON bairros.id = clientes.bairro_id 
    JOIN usuarios ON usuarios.id = coletas.user_id WHERE coletas.verificacao = '0' AND coletas.ucm_id IS NULL AND coletas.drive_thru = 0 ORDER BY coletas.agendamento ASC";
    $resultConfirmar = mysqli_query($conn, $sqlConfirmar);
    /*
    var_dump($sqlConfirmar);
    var_dump($resultConfirmar);
    */
    ?>
    <table class="table table-hover table-striped">
      <thead>
        <h4 class="text-center"><strong>COLETAS PARA CONFIRMAÇÃO DE ROTA</strong></h4>
        <tr>
          <th>AGENDAMENTO</th>
          <th>BAIRRO</th>
          <th class="hidden-xs">ZONA</th>
          <th class="text-center">AÇÕES</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if(mysqli_num_rows($resultConfirmar) == 0){
          echo "<div class='alert alert-warning alert-dismissible text-center' role='alert'>
          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
          <span aria-hidden='true'>&times;</span>
          </button>
          <strong>Aviso!</strong> Nenhum registro encontrado na base de dados.
          </div>";
        }
        while ($rowConfirmar = mysqli_fetch_assoc($resultConfirmar)) {
          ?>
          <tr>
            <td>
              <?php 
              echo date('d/m/Y H:i', strtotime($rowConfirmar['agendamento'])); 
              ?>
            </td>
            <td class="text-uppercase"><?php echo $rowConfirmar['bairro']; ?></td>
            <td class="hidden-xs text-uppercase"><?php echo $rowConfirmar['zona']; ?></td>
            <td class="text-center">
              <?php
              if ($button_view) {
                echo "<a href= '" . pg . "/viewer/view_coletas_sem_rota?id=" . $rowConfirmar['id'] . "'><button type='button' class='btn btn-xs btn-info' data-toggle='tooltip' data-placement='top' title='Visualizar Coleta'><span class='glyphicon glyphicon-folder-open'></span></button></a> ";
              }
              if ($button_edit_sem_rota) {
                echo "<a href= '" . pg . "/edit/edit_coleta_sem_rota?id=" . $rowConfirmar['id'] . "' onclick=\"return confirm('Deseja editar as informações de coleta?');\"><button type='button' class='btn btn-xs btn-warning' data-toggle='tooltip' data-placement='top' title='Editar Coleta Sem Rota'><span class='glyphicon glyphicon-edit'></span></button></a> ";
              }
              if ($button_definir) {
                echo "<a href= '" . pg . "/edit/edit_confirmar_rotas?id=" . $rowConfirmar['id'] . "' onclick=\"return confirm('Definir rota para esta coleta?');\"><button type='button' class='btn btn-xs btn-primary' data-toggle='tooltip' data-placement='top' title='Definir Rota'><span class='fas fa-car'></span></button></a> ";
              }
              if($button_rota){
                echo "<a href= '" . pg . "/process/search/search_rotas?id=" . $rowConfirmar['id'] . "' onclick=\"return confirm('Buscar rota?');\"><button type='button' class='btn btn-xs btn-success' data-toggle='tooltip' data-placement='top' title='Visualizar Rota Para Coleta'><span class='fas fa-map-marked-alt'></span></button></a> ";
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

  <div class="col-sm-6">
   <?php
   $sqlDriveThru = "SELECT coletas.id,coletas.agendamento, bairros.nome AS bairro, bairros.zona AS zona FROM coletas
   JOIN clientes ON coletas.cliente_id = clientes.id
   JOIN bairros ON bairros.id = clientes.bairro_id
   JOIN usuarios ON usuarios.id = coletas.user_id
   WHERE coletas.verificacao = '0' AND coletas.ucm_id IS NULL AND coletas.drive_thru = 1  ORDER BY agendamento, bairro ASC";
   #$sqlDriveThru = "SELECT * FROM coletas2";
   $resDriveThru = mysqli_query($conn, $sqlDriveThru);
     /*
     var_dump($sqlDriveThru);
     var_dump($resDriveThru);
     */
     ?>
     <table class="table table-hover table-striped">
      <thead>
        <h4 class="text-center"><strong>CONFIRMAR COLETAS PARA DRIVE THRU</strong></h4>
        <tr>
          <th>AGENDAMENTO</th>
          <th>BAIRRO</th>
          <th class="hidden-xs">ZONA</th>
          <th class="text-center">AÇÕES</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if(mysqli_num_rows($resDriveThru) == 0){
          echo "<div class='alert alert-warning alert-dismissible text-center' role='alert'>
          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
          <span aria-hidden='true'>&times;</span>
          </button>
          <strong>Aviso!</strong> Nenhum registro encontrado na base de dados.
          </div>";
        }
        while ($rowDriveThru = mysqli_fetch_assoc($resDriveThru)) {
          ?>
          <tr>
            <td>
              <?php 
              echo date('d/m/Y H:i', strtotime($rowDriveThru['agendamento'])); 
              ?>
            </td>
            <td class="text-uppercase"><?php echo $rowDriveThru['bairro']; ?></td>
            <td class="hidden-xs text-uppercase"><?php echo $rowDriveThru['zona']; ?></td>
            <td class="text-center">
              <?php
              if ($button_view) {
                echo "<a href= '" . pg . "/viewer/view_coletas_sem_rota?id=" . $rowDriveThru['id'] . "'><button type='button' class='btn btn-xs btn-info' data-toggle='tooltip' data-placement='top' title='Visualizar Coleta'><span class='glyphicon glyphicon-folder-open'></span></button></a> ";
              }
              if ($button_edit_sem_rota) {
                echo "<a href= '" . pg . "/edit/edit_coleta_sem_rota?id=" . $rowDriveThru['id'] . "' onclick=\"return confirm('Deseja editar as informações de coleta?');\"><button type='button' class='btn btn-xs btn-warning' data-toggle='tooltip' data-placement='top' title='Editar Coleta Sem Rota'><span class='glyphicon glyphicon-edit'></span></button></a> ";
              }
              if ($button_definir) {
                echo "<a href= '" . pg . "/edit/edit_confirmar_rotas?id=" . $rowDriveThru['id'] . "' onclick=\"return confirm('Definir rota para esta coleta?');\"><button type='button' class='btn btn-xs btn-primary' data-toggle='tooltip' data-placement='top' title='Definir Rota'><span class='fas fa-car'></span></button></a> ";
              }
              if($button_rota){
                echo "<a href= '" . pg . "/process/search/search_rotas?id=" . $rowDriveThru['id'] . "' onclick=\"return confirm('Buscar rota?');\"><button type='button' class='btn btn-xs btn-success' data-toggle='tooltip' data-placement='top' title='Visualizar Rota Para Coleta'><span class='fas fa-map-marked-alt'></span></button></a> ";
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

  <!-- Início do modal buscar data e horário disponível -->
  <div class="modal fade" id="horarioModal" tabindex="-1" role="dialog" aria-labelledby="horarioModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <form name="buscarRelatorio" method="get" action="<?php echo pg; ?>/list/list_coletas" class="form-horizontal" enctype="multipart/form-data" autocomplete="off">
            <div class="form-group">
              <label for="cidade" class="col-sm-2 control-label">Cidade</label>
              <div class="col-sm-10">
                <select name="cidade" class="form-control" id="cidade_id" required>
                  <?php
                  echo "<option value=''>[SELECIONE]</option>";
                  $sql_cidade = "SELECT id, UPPER(nome) AS nome, UPPER(uf) AS uf FROM cidades WHERE uf ='PB' AND situacao = 1 ORDER By id";
                  $result_cidade = mysqli_query($conn, $sql_cidade);
                  while ($row_cidade = mysqli_fetch_assoc($result_cidade)) {
                    echo "<option value= '" . $row_cidade ["id"] . "'>" . $row_cidade ["nome"] . " - " . $row_cidade ["uf"] . "</option>";
                  }
                  ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="bairro" class="col-sm-2 control-label">Bairro</label>
              <div class="col-sm-10">
                <select name="bairro" class="form-control" id="bairro_id" required>
                  <option>[SELECIONE]</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="start" class="col-sm-2 control-label">Data</label>
              <div class="col-sm-10">
                <div class="input-group date data_format" data-date-format="dd/mm/yyyy">
                  <input type="text" name="start" id="start" class="form-control" placeholder="dd/mm/aaaa" value="<?php
                  if (isset($_SESSION['dados']['start'])) {
                    echo $_SESSION['dados']['start'];
                  }
                  ?>"/>
                  <div class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                <button class="btn btn-xs btn-success pull-right">
                  <span class="glyphicon glyphicon-search"></span> 
                  Buscar
                </button>
              </div>
            </div>
          </form>
          <script type="text/javascript">
            $('.data_format').datepicker({
              format: 'dd/mm/yyyy',
              weekStart: 1,
              todayBtn: 1,
              autoclose: 1,
              todayHighlight: 1,
              language: 'pt-BR'
            });
            /*Mascara para os inputs do form*/
            jQuery(function ($) {
              $("#start").mask("99/99/9999"); 
            });
          </script>
        </div>
      </div>
    </div>
  </div>
  <!-- Fim do modal  buscar data e horário disponível -->
  <!-- Início no modal de apresentação dos horários disponíveis -->
  <?php
  $bairroModal = filter_input(INPUT_GET, 'bairro', FILTER_SANITIZE_NUMBER_INT);
  $dataModal = $_GET["start"];

  if(isset($bairroModal) && (isset($dataModal))){
    ?>
    <script>
      $(document).ready(function(){
        $("#myModal").modal();
      });
    </script>
    <?php
    $start_data =str_replace("/", "-", $dataModal);
    $start = date('Y-m-d', strtotime($start_data));
    if(!empty($bairroModal)){
      $sql = "SELECT coletas.id, coletas.agendamento AS agendamento, coletas.ra AS ra, clientes.nome AS cliente, 
      bairros.nome AS bairro  FROM coletas 
      JOIN clientes ON clientes.id = coletas.cliente_id 
      JOIN bairros ON bairros.id = clientes.bairro_id 
      WHERE (agendamento BETWEEN '".$start." 05:00:00' AND '".$start." 18:00:00') AND coletas.bairro_id = '$bairroModal' ORDER BY agendamento";
    } else {
      $sql = "SELECT coletas.id, coletas.agendamento AS agendamento, coletas.ra AS ra, clientes.nome AS cliente, 
      bairros.nome AS bairro  FROM coletas 
      JOIN clientes ON clientes.id = coletas.cliente_id 
      JOIN bairros ON bairros.id = clientes.bairro_id 
      WHERE agendamento BETWEEN '".$start." 05:00:00' AND '".$start." 18:00:00' ORDER BY agendamento,
      agendamento";
    }
  }
  ?>
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="horarioModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="redirecione()">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="well conteudo">
          <div class="modal-body">
            <table class="table table-hover table-striped">
              <thead>
                <tr>
                  <th>RA</th>
                  <th class="hidden-xs">Nome</th>
                  <th>Bairro</th>
                  <th>Agendamento</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $result = mysqli_query ($conn, $sql);
                if(mysqli_num_rows($result) == 0){
                  echo "<div class='alert alert-warning alert-dismissible text-center' role='alert'>
                  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                  <span aria-hidden='true'>&times;</span>
                  </button>
                  <strong>Aviso!</strong> Nenhum registro encontrado na base de dados.
                  </div>";
                }
                while ($row = mysqli_fetch_assoc($result)) {
                  ?>
                  <tr>
                    <td><?php echo $row['ra']; ?></td>
                    <td class="text-uppercase hidden-xs"><?php echo $row['cliente']; ?></td>
                    <td class="text-uppercase"><?php echo $row['bairro'];?></td>
                    <td><?php echo date('d/m/Y H:i:s', strtotime($row['agendamento'])); ?></td>
                  </tr>
                  <?php
                }
                ?>  
              </tbody>
            </table>
            <script type="text/javascript">
              function redirecione() {
                // Faz um redirecionamento mantendo a página original no histórico de navegaçãodo browser.
                window.location.href = "https://scd.nmatec.com.br/list/list_coletas";
              }
            </script>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

