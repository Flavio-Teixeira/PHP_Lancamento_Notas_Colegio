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

    //*** Efetua a Conexão com o Banco de Dados ***
    require_once("includes/conecta_banco.inc.php");
	
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
	<div id="fundo">

      <fieldset style="width: 748px; height: 462px;">
            <legend>Índice de Desempenho do Aluno</legend> 
            
            <div id="conteudo_exibicao">

			  <label id="fonte_fundo"><b>Ano:</b> <?php echo date("Y",time()); ?></label>
			  <label>Escolha uma das opções:</label>	   
			  
         	  <table width="746" border="0">
  				<tr>
    				<td>&nbsp;</td>
  				</tr>
  				<tr>
    				<td><center><a href="indice_desempenho_clovis.php" title="ENSINO MÉDIO/TÉCNICO [24 PONTOS]">ENSINO MÉDIO/TÉCNICO [Média Baseada em 24 PONTOS]</a></center></td>
  				</tr>
  				<tr>
    				<td>&nbsp;</td>
  				</tr>
  				<tr>
    				<td><center><a href="indice_desempenho_padrao.php" title="ENSINO FUNDAMENTAL [28 PONTOS]">ENSINO FUNDAMENTAL [Média Baseada em 28 PONTOS]</a></center></td>
  				</tr>
			 </table>   

			</div>

      </fieldset>

	</div> 

</body>
</html>
