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
    require_once("includes/valida_sessao_professor.inc.php");   	
	
    //*** Ativa as Rotinas Gerais ***
    require_once('includes/rotinas_gerais.fnc.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
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
         <span id="fonte_vermelha">Área do Professor</span>
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
    				<td><a href="alterar_senha.php" title="Alterar Senha" target="frame_exibicao">Alterar Senha</a></td>
  				</tr>
  				<tr>
    				<td><a href="lancamento_notas.php" title="Lançamento de Notas" target="frame_exibicao">Lançamento de Notas</a></td>
  				</tr>
  				<tr>
    				<td><a href="duplicar_notas.php" title="Duplicação de Notas" target="frame_exibicao">Duplicação de Notas</a></td>
  				</tr>
  				<tr>
    				<td><a href="indice_desempenho.php" title="Índice de Desempenho" target="frame_exibicao">Índice de Desempenho</a></td>
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
		   <iframe id="frame_exibicao" name="frame_exibicao" width="794" height="484" src="fundo_professor.php" frameborder="0"></iframe>		
		</div>        
</div>
    
	<div id="rodape">&nbsp;© Copyright 2013 - Colégio Clóvis Bevilacqua</div>	
 	<div id="desenvolvedor">Tenha uma vida mais saudável:&nbsp;<a href="http://www.vidaperfeita.com.br">Vida Perfeita</a>&nbsp;</div>
</body>
</html>
