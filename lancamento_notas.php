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
            <legend>Lançamento de Notas</legend> 
            
            <div id="conteudo_exibicao">

			  <label id="fonte_fundo"><b>Ano:</b> <?php echo date("Y",time()); ?></label>
			  <label>Relação das Planilhas de Notas:</label>	   

			  <!-- Lista os Registros Obtidos da Busca -->

			  <?php
			   	//*** Efetua a Busca das Planilhas Importadas ***

				$comando_sql  = "SELECT planilha_ano, ";
				$comando_sql .= "planilha_nome_professor, ";
				$comando_sql .= "planilha_usuario_loguin, ";
				$comando_sql .= "planilha_bimestre_sequencial, ";
				$comando_sql .= "planilha_bimestre_descricao, ";
				$comando_sql .= "planilha_disciplina, ";
				$comando_sql .= "planilha_status, ";
				$comando_sql .= "DATE_FORMAT(planilha_data_alteracao,'%d/%m/%Y') AS planilha_data_alteracao, ";
				$comando_sql .= "planilha_hora_alteracao ";
				$comando_sql .= "FROM planilhas ";
				$comando_sql .= "WHERE ";
				$comando_sql .= "planilha_ano = '" . trim(date("Y",time())) ."' AND ";
				$comando_sql .= "planilha_usuario_loguin = '" . trim($_SESSION['identificacao']['usuario_loguin']) ."' ";
				$comando_sql .= "GROUP BY ";
				$comando_sql .= "planilha_ano, ";
				$comando_sql .= "planilha_nome_professor, ";
				$comando_sql .= "planilha_usuario_loguin, ";
				$comando_sql .= "planilha_bimestre_sequencial, ";
				$comando_sql .= "planilha_bimestre_descricao, ";
				$comando_sql .= "planilha_disciplina ";
				$comando_sql .= "ORDER BY "; 
				$comando_sql .= "planilha_ano DESC, "; 
				$comando_sql .= "planilha_bimestre_sequencial DESC, ";
				$comando_sql .= "planilha_nome_professor ASC, ";
				$comando_sql .= "planilha_disciplina ASC";				

			   	$resultado_sql = mysql_query($comando_sql);

		   		if(mysql_num_rows($resultado_sql) != 0)
		   		{
					$ultimo_bimestre = "999";

					echo '<center>';
					echo '<table width="740" border="1" cellspacing="0" cellpadding="2" bordercolor="#000000">';
					echo '<tr>';
					echo '<th scope="col" bgcolor="#F6B446"><font color="#FFFFFF">Opção</font></th>';
					echo '<th scope="col" bgcolor="#F6B446"><font color="#FFFFFF">Impressão</font></th>';
					echo '<th scope="col" bgcolor="#F6B446"><font color="#FFFFFF">Turma/Disciplina</font></th>';
					echo '<th scope="col" bgcolor="#F6B446"><font color="#FFFFFF">Status da Digitação</font></th>';
					echo '<th scope="col" bgcolor="#F6B446"><font color="#FFFFFF">Data</font></th>';
					echo '<th scope="col" bgcolor="#F6B446"><font color="#FFFFFF">Hora</font></th>';	
					echo '</tr>';
					
					for($i = 0; $i < mysql_num_rows($resultado_sql); $i++)
					{
						if( trim($ultimo_bimestre) <> trim(mysql_result($resultado_sql,$i,"planilha_bimestre_descricao")) )
						{
							$ultimo_bimestre = trim(mysql_result($resultado_sql,$i,"planilha_bimestre_descricao"));

							echo '<tr>';
							echo '<td bgcolor="#F6B446" width="740" colspan="6" align="left"><font color="#FFFFFF"><b>' . trim($ultimo_bimestre) . '</b></font></td>';
							echo '</tr>';
						}

						echo '<tr>';
						echo '<td bgcolor="#F8F8F8" width="100" align="center"><a href="lancamento_notas_edicao.php?planilha_ano=' . trim(mysql_result($resultado_sql,$i,"planilha_ano")) . '&planilha_usuario_loguin=' . trim(mysql_result($resultado_sql,$i,"planilha_usuario_loguin")) . '&planilha_bimestre_sequencial=' . trim(mysql_result($resultado_sql,$i,"planilha_bimestre_sequencial")) . '&planilha_disciplina=' . trim(mysql_result($resultado_sql,$i,"planilha_disciplina")) . '"><b>Lançar Notas</b></a></td>';
						echo '<td bgcolor="#F8F8F8" width="080" align="center"><a href="lancamento_notas_impressao.php?planilha_ano=' . trim(mysql_result($resultado_sql,$i,"planilha_ano")) . '&planilha_usuario_loguin=' . trim(mysql_result($resultado_sql,$i,"planilha_usuario_loguin")) . '&planilha_bimestre_sequencial=' . trim(mysql_result($resultado_sql,$i,"planilha_bimestre_sequencial")) . '&planilha_disciplina=' . trim(mysql_result($resultado_sql,$i,"planilha_disciplina")) . '"><b>Imprimir</b></a></td>';
						echo '<td bgcolor="#F8F8F8" width="290" align="left">' . mysql_result($resultado_sql,$i,"planilha_disciplina") . '</td>';
						echo '<td bgcolor="#F8F8F8" width="135"><center>';

						if( trim(mysql_result($resultado_sql,$i,"planilha_status")) == 'Aguardando' )
						{
							echo '<font color="#FF0000"><b>' . trim(mysql_result($resultado_sql,$i,"planilha_status")) . '</b></font>';
						}
						else if( trim(mysql_result($resultado_sql,$i,"planilha_status")) == 'Digitada' )
						{
							echo '<font color="#50B56F"><b>' . trim(mysql_result($resultado_sql,$i,"planilha_status")) . '</b></font>';
						}
						else if( trim(mysql_result($resultado_sql,$i,"planilha_status")) == 'Parcial' )
						{
							echo '<font color="#FCD578"><b>' . trim(mysql_result($resultado_sql,$i,"planilha_status")) . '</b></font>';
						}							
						
						echo '</center></td>';
						echo '<td bgcolor="#F8F8F8" width="75">' . mysql_result($resultado_sql,$i,"planilha_data_alteracao") . '</td>';
						echo '<td bgcolor="#F8F8F8" width="60">' . mysql_result($resultado_sql,$i,"planilha_hora_alteracao") . '</td>';						
  						echo '</tr>';
					}
					
					echo '</table>';
					echo '</center>';
				}
				else
				{
					echo '<BR><BR><BR><BR><center><font color="#FF0000"><b>Nenhuma Planilha de Notas foi Localizada!</b></font></center>';
				}
				
				//*** Fecha a Conexão com o Banco de Dados ***
     			mysql_close($nro_conexao);
			  ?>

			</div>

      </fieldset>

	</div>

</body>
</html>
