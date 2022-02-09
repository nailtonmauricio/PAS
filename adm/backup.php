<?php

	if (!isset($_SESSION['check'])) {
	    $_SESSION ['msg'] = "<div class='alert alert-danger alert-dismissible'> "
	            . "<button type='button' class='close' data-dismiss='alert'>"
	            . "<span aria-hidden='true'>&times;</span>"
	            . "</button><strong>Aviso!&nbsp;</stron>"
	            . "Área restrita, faça login para acessar.</div>";
	    header("Location: login.php");
	}


/*$result_tabela = "SHOW TABLES";
$resultado_tabela = mysqli_query($conn, $result_tabela);
while($row_tabela = mysqli_fetch_row($resultado_tabela)){
	$tabelas[] = $row_tabela[0];
}*/

$sql_table = "SHOW TABLES";
$res_table = $conn ->prepare($sql_table);
$res_table ->execute();

while($row_table = $res_table ->fetchAll(PDO::FETCH_ASSOC)){
    $tables[] = $row_table;
}

var_dump(
    $tables
);
//var_dump($tabelas);

$result = "";
foreach($tabelas as $tabela){
	//Pesquisar o nome das colunas
	$result_colunas = "SELECT * FROM " . $tabela;
	$resultado_colunas = mysqli_query($conn, $result_colunas);
	$num_colunas = mysqli_num_fields($resultado_colunas);
	echo "Quantidade de colunas na tabela: ". $tabela. " - " . $num_colunas . "<br>";
	
	//Criar a intrução para apagar a tabela caso a mesma exista no BD
	$result .= 'DROP TABLE IF EXISTS '.$tabela.';';
	
	//Pesquisar como a coluna é criada
	$result_cr_col = "SHOW CREATE TABLE " . $tabela;
	$resultado_cr_col = mysqli_query($conn, $result_cr_col);
	$row_cr_col = mysqli_fetch_row($resultado_cr_col);
	//var_dump($row_cr_col);
	$result .= "\n\n" . $row_cr_col[1] . ";\n\n";
	//echo $result;
	
	//Percorrer o array de colunas
	for($i = 0; $i < $num_colunas; $i++){
		//Ler o valor de cada coluna no bando de dados
		while($row_tp_col = mysqli_fetch_row($resultado_colunas)){
			var_dump($row_tp_col);
			//Criar a intrução da Query para inserir os dados
			$result .= 'INSERT INTO ' . $tabela . ' VALUES(';
			
			//Ler os dados da tabela
			for($j = 0; $j < $num_colunas; $j++){
				//addslashes — Adiciona barras invertidas a uma string
				$row_tp_col[$j] = addslashes($row_tp_col[$j]);
				//str_replace — Substitui todas as ocorrências da string \n pela \\n
				$row_tp_col[$j] = str_replace("\n", "\\n", $row_tp_col[$j]);
				
				if(isset($row_tp_col[$j])){
					if(!empty($row_tp_col[$j])){
						$result .= '"' . $row_tp_col[$j].'"';
					}else{
						$result .= 'NULL';
					}
				}else{
					$result .= 'NULL';
				}
				
				if($j < ($num_colunas - 1)){
					$result .=',';
				}				
			}
			$result .= ");\n";
		}
	}
	$result .= "\n\n";
	//echo $result;
}

//Criar o diretório de backup
$diretorio = 'backups/';
if(!is_dir($diretorio)){
	mkdir($diretorio, 0777, true);
	chmod($diretorio, 0777);
}

//Nome do arquivo de backup
$data = date('Y-m-d_H-i-s');
$nome_arquivo = $diretorio . "db_backup_".$data;
//echo $nome_arquivo;

$handle = fopen($nome_arquivo . '.sql', 'w+');
fwrite($handle, $result);
fclose($handle);

//Montagem do link do arquivo
$download = $nome_arquivo . ".sql";


	//echo "Realziar a rotina de backup";
	 if($handle){
    	$_SESSION ['msg_bkp'] = "<div class='alert alert-success alert-dismissible text-center'> "
            . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
            . "<span aria-hidden='true'>&times;</span>"
            . "</button><strong>Aviso!&nbsp;</stron>"
            . "Backup  e logout realizados com sucesso.</div>";
    	$url_destino = pg . "/sair.php";
    	#header("Location: $url_destino");
    }else {
		$_SESSION ['msg_bkp'] = "<div class='alert alert-danger alert-dismissible text-center'> "
	            . "<button type='button' class='close' data-dismiss='alert' area-label='Close'>"
	            . "<span aria-hidden='true'>&times;</span>"
	            . "</button><strong>Aviso!&nbsp;</stron>"
	            . "Erro ao realizar o backup.</div>";
	    $url_destino = pg . "/sair.php";
	    #header("Location: $url_destino");
	}