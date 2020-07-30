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
	//*** Login ***
	$col = array();
	$col["title"] = "Login"; //*** Título do Campo ***
	$col["name"] = "usuario_loguin"; //*** Nome do Campo na Tabela ***
	$col["width"] = "10";
	$col["editable"] = true;
	$col["align"] = "left";
	$col["search"] = true;
	$cols[] = $col;		

    //*** Senha ***
	$col = array();
	$col["title"] = "Senha";
	$col["name"] = "usuario_senha";
	$col["width"] = "10";
	$col["editable"] = true;
	$col["align"] = "left";
	$col["search"] = false;
	//$col["edittype"] = "password";
	//$col["formatter"] = "password";
	$cols[] = $col;		

    //*** Nome ***
	$col = array();
	$col["title"] = "Nome";
	$col["name"] = "usuario_nome";
	$col["width"] = "30";
	$col["editable"] = true;
	$col["align"] = "left";
	$col["search"] = true;
	$cols[] = $col;

	//*** Pasta ***
	$col = array();
	$col["title"] = "Pasta";
	$col["name"] = "usuario_pasta";
	$col["width"] = "15";
	$col["editable"] = true;
	$col["align"] = "left";
	$col["search"] = true;
	$cols[] = $col;

    //*** Tipo ***
	$col = array();
	$col["title"] = "Tipo";
	$col["name"] = "usuario_tipo";
	$col["width"] = "20";
	$col["editable"] = true;
	$col["align"] = "left";
	$col["search"] = true;
	$col["edittype"] = "select";
	$col["editoptions"] = array("value"=>'Professor:$Professor;Administrador:$Administrador'); //*** Conteúdo do Select com o Padrão: "key:value;key:value;key:value"
	$cols[] = $col;

    //*** Lançamento ***
	$col = array();
	$col["title"] = "Lançamento";
	$col["name"] = "usuario_tipo_lancamento";
	$col["width"] = "18";
	$col["editable"] = true;
	$col["align"] = "left";
	$col["search"] = true;
	$col["edittype"] = "select";
	$col["editoptions"] = array("value"=>'Nota:$Nota;Dispensa:$Dispensa'); //*** Conteúdo do Select com o Padrão: "key:value;key:value;key:value"
	$cols[] = $col;

    //*** Colégio ***
	$col = array();
	$col["title"] = "Colégio";
	$col["name"] = "usuario_colegio";
	$col["width"] = "11";
	$col["editable"] = true;
	$col["align"] = "left";
	$col["search"] = true;
	$col["edittype"] = "select";
	$col["editoptions"] = array("value"=>'Clovis:$Clovis;Padrao:$Padrao;Ambos:$Ambos'); //*** Conteúdo do Select com o Padrão: "key:value;key:value;key:value"
	$cols[] = $col;

    //*** Ensino Pós ***
	$col = array();
	$col["title"] = "Pós-Médio";
	$col["name"] = "usuario_ensino_pos";
	$col["width"] = "11";
	$col["editable"] = true;
	$col["align"] = "left";
	$col["search"] = false;
	$col["edittype"] = "checkbox";
	$col["editoptions"] = array("value"=>'S:N');
	$cols[] = $col;

    //*** Ensino Médio-Técnico ***
	$col = array();
	$col["title"] = "Médio/Técnico";
	$col["name"] = "usuario_ensino_medio_tecnico";
	$col["width"] = "11";
	$col["editable"] = true;
	$col["align"] = "left";
	$col["search"] = false;
	$col["edittype"] = "checkbox";
	$col["editoptions"] = array("value"=>'S:N');
	$cols[] = $col;

    //*** Ensino Fundamental ***
	$col = array();
	$col["title"] = "Fundamental";
	$col["name"] = "usuario_ensino_fundamental";
	$col["width"] = "11";
	$col["editable"] = true;
	$col["align"] = "left";
	$col["search"] = false;
	$col["edittype"] = "checkbox";
	$col["editoptions"] = array("value"=>'S:N');
	$cols[] = $col;

	//*** Instância a Classe do Grid ***
	$g = new jqgrid();

	// $grid["url"] = ""; //*** Sua parametrização de URL -- defaults to REQUEST_URI ***
	$grid["rowNum"] = 15; //*** Default 20 ***
	$grid["sortname"] = 'usuario_nome'; //*** Campo a ser Ordenado por Default no Grid ***
	$grid["sortorder"] = "asc"; //*** Forma da Ordenação ***
	$grid["caption"] = "Usuários"; //*** Título do Grid ***
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
	$g->table = "usuarios";

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
            <legend>Cadastro de Usuários</legend>

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