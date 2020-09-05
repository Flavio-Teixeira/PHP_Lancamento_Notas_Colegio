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
    //*** Valida a Sessão ***
    require_once("includes/valida_sessao_administracao.inc.php");   	
	
    //*** Ativa as Rotinas Gerais ***
    require_once('includes/rotinas_gerais.fnc.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
        <!-- <meta charset="ISO-8859-1" /> -->
        <meta charset="UTF-8" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<title>Colégio Clóvis Bevilacqua</title>
        <link rel="stylesheet" type="text/css" href="css/estilos.css"/>
</head>
<body>
	<div id="cabecalho"></div>
    
<div id="cabecalho_titulo">      
	  <p>
		 <b>Sistema de Lançamento de Notas On-Line</b><br />
         <span id="fonte_vermelha">Área da Administração</span>
         <span id="fonte"><b>Usuário:&nbsp;</b><?php echo $_SESSION['identificacao']['usuario_nome']; ?></span>       
  	  </p>
</div>	
    
	<div id="corpo">
		<div id="menu">
        	<table width="183" border="1">
		  		<tr>
    				<th>Opções</th>
  				</tr>
  				<tr>
    				<td><a href="cadastro_usuarios.php" title="Cadastro de Usuários" target="frame_exibicao">Cadastro de Usuários</a></td>
  				</tr>
  				<tr>
    				<td><a href="cadastro_bimestres.php" title="Cadastro de Bimestres" target="frame_exibicao">Cadastro de Bimestres</a></td>
  				</tr>
  				<tr>
    				<td><a href="bimestre_ativo.php" title="Bimestre Ativo" target="frame_exibicao">Bimestre Ativo</a></td>
  				</tr>
  				<tr>
    				<td><a href="planilhas_notas.php" title="Planilhas de Notas" target="frame_exibicao">Planilhas de Notas</a></td>
  				</tr>
  				<tr>
    				<td><a href="relatorio_digitacao.php" title="Relatório de Digitação" target="frame_exibicao">Relatório de Digitação</a></td>
  				</tr>
  				<!-- <tr>
    				<td><a href="gerar_planilhas_bimestrais.php" title="Gerar Planilhas Bimestrais" target="frame_exibicao">Gerar Planilhas Bimestrais</a></td>
  				</tr> -->
  				<tr>
    				<td><a href="importar_planilhas_notas.php" title="Importar as Planilha de Notas" target="frame_exibicao">Importar Planilha de Notas</a></td>
  				</tr>
  				<tr>
    				<td><a href="exportar_planilhas_notas.php" title="Exportar as Planilha de Notas" target="frame_exibicao">Exportar Planilha de Notas</a></td>
  				</tr>                
  				<tr>
    				<td>&nbsp;</td>
  				</tr>                                
  				<tr>
    				<td><a href="sair_sistema.php" title="Sair do Sistema" target="_top"><b>Sair do Sistema</b></a></td>
  				</tr>                
			</table>   
		</div>
		<div id="area_exibicao">
		   <iframe id="frame_exibicao" name="frame_exibicao" width="794" height="484" src="fundo.php" frameborder="0"></iframe>		
		</div>        
</div>
    
	<div id="rodape">&nbsp;© Copyright 2013 - Colégio Clóvis Bevilacqua</div>	
 	<div id="desenvolvedor">Tenha uma vida mais saudável:&nbsp;<a href="http://www.vidaperfeita.com.br">Vida Perfeita</a>&nbsp;</div>
</body>
</html>
