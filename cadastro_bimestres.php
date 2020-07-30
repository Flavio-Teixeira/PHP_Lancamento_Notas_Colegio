<?php
/*
+------------------------------------------------+
| PROTEÇÃO AOS DIREITOS DE AUTOR E DO REGISTRO:  |
| Toda codificação deste Sistema está protegida  |
| pela Lei Nro.9609 onde se dispõe sobre a       |
| proteção da propriedade intelectual de         |
| programa de computador, sua comercialização    |
| no País, e dá outras providências.             |
| ATENÇÃO: Não é permitido efetuar alterações    |
| na codificação do sistema, efetuar instalações |
| em outros computadores, cópias e utilizá-lo    |
| como base no desenvolvimento de outro sistema  |
| semelhante ou de igual funcionamento.          |
+------------------------------------------------+
*/

    //******************************
    //*** INICIO - Valida Sessão ***
	//******************************
    //*** Valida a Sessão ***
    require_once("includes/valida_sessao_administracao.inc.php");   	
	
    //*** Ativa as Rotinas Gerais ***
    require_once('includes/rotinas_gerais.fnc.php');

    //*****************************
	//*** FINAL - Valida Sessão ***
	//*****************************

	//*** Efetua a Conexão com o Banco de Dados ***
	require_once("includes/conecta_banco.inc.php");

	//*** Configura a Acentuação do Banco de Dados ***
	mysql_query("SET NAMES 'utf8'");

	//*** Ativa as Configurações do Data Grid ***
	include("inc/jqgrid_dist.php");

	//*** Customização dos Campos do Data Grid ***
	//*** Sequencial ***
	$col = array();
	$col["title"] = "Sequencial"; //*** Título do Campo ***
	$col["name"] = "bimestre_sequencial"; //*** Nome do Campo na Tabela ***
	$col["width"] = "15";
	$col["editable"] = false;
	$col["align"] = "left";
	$col["search"] = true;
	$cols[] = $col;		

    //*** Descrição ***
	$col = array();
	$col["title"] = "Descrição";
	$col["name"] = "bimestre_descricao";
	$col["width"] = "65";
	$col["editable"] = true;
	$col["align"] = "left";
	$col["search"] = true;
	$cols[] = $col;

	//*** Instância a Classe do Grid ***
	$g = new jqgrid();

	// $grid["url"] = ""; //*** Sua parametrização de URL -- defaults to REQUEST_URI ***
	$grid["rowNum"] = 15; //*** Default 20 ***
	$grid["sortname"] = 'bimestre_descricao'; //*** Campo a ser Ordenado por Default no Grid ***
	$grid["sortorder"] = "asc"; //*** Forma da Ordenação ***
	$grid["caption"] = "Bimestres"; //*** Título do Grid ***
	$grid["autowidth"] = false; //*** Expandir o Grid para o Tamanho da Tela ***
	$grid["multiselect"] = true; //*** Ativa a Multiseleção ***
	$grid["width"] = 745;
	$grid["height"] = 350;

	//*** Seta as Opções do Grid ***
	$g->set_options($grid);

	//*** Configura as Opções de Manipulação de Registros ***
	$g->set_actions(array(	
						"add"=>true, //*** Ativa e Desativa a Inclusão de Novos de Registros ***
						"edit"=>true, //*** Ativa e Desativa a Alteração de Registros ***
						"delete"=>true, //*** Ativa e Desativa a Exclusão de Inserção de Registros ***
						"rowactions"=>true, //*** Ativa e Desativa as Opções de Linha edit/del/save ***
						"search" => "advance" //*** Tipo de Condição de Busca. Opções: simple or advance ***
					) 
	);

	//*** Nome da Tabela onde acontecerá a busca dos dados ***
	$g->table = "bimestres";

	//*** Joga as Colunas Manipuladas para o Grid ***
	$g->set_columns($cols);

	//*** Gera a Saída do Grid com o nome de 'list1' ***
	$out = $g->render("list1");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
		<meta charset="UTF-8" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<title>Colégio Clóvis Bevilacqua</title>  

		<link rel="stylesheet" type="text/css" href="css/estilos.css"/>

		<!-- ***************************** -->
		<!-- INÍCIO - Scripts do Data Grid -->
		<!-- ***************************** -->
		<link rel="stylesheet" type="text/css" media="screen" href="js/themes/ui-lightness/jquery-ui.custom.css"></link>	
		<link rel="stylesheet" type="text/css" media="screen" href="js/jqgrid/css/ui.jqgrid.css"></link>	
	
		<script src="js/jquery.min.js" type="text/javascript"></script>
		<script src="js/jqgrid/js/i18n/grid.locale-pt-br.js" type="text/javascript"></script>
		<script src="js/jqgrid/js/jquery.jqGrid.min.js" type="text/javascript"></script>	
		<script src="js/themes/jquery-ui.custom.min.js" type="text/javascript"></script>
		<!-- **************************** -->
		<!-- FINAL - Scripts do Data Grid -->
		<!-- ***************************** -->
</head>
<body>
    <div id="fundo">   

          <fieldset>
            <legend>Cadastro de Bimestres</legend>

			<?php
				//*** Exibe o Resultado do Data Grid *** 
				echo $out;

               //*** Fecha a Conexão com o Banco de Dados ***
	     	   mysql_close($nro_conexao);
			?>

		  </fieldset> 
    </div>
</body>
</html>