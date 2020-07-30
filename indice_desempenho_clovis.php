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
            <legend>(ENSINO MÉDIO/TÉCNICO [24 PONTOS]) - Índice de Desempenho do Aluno</legend> 
            
            <div id="conteudo_exibicao">

			  <label id="fonte_fundo"><b>Ano:</b> <?php echo date("Y",time()); ?></label>
			  <label>Relação das Disciplinas:</label>	   

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
				$comando_sql .= "planilha_hora_alteracao, ";
				$comando_sql .= "planilha_coluna_e_turma, ";
				$comando_sql .= "planilha_coluna_g_coddisciplina, ";
				$comando_sql .= "planilha_coluna_f_disciplina ";				
				$comando_sql .= "FROM planilhas ";
				$comando_sql .= "WHERE ";
				$comando_sql .= "planilha_ano = '" . trim(date("Y",time())) ."' AND ";
				$comando_sql .= "planilha_usuario_loguin = '" . trim($_SESSION['identificacao']['usuario_loguin']) ."' AND ";
				$comando_sql .= "planilha_coluna_g_coddisciplina <> '' ";
				$comando_sql .= "GROUP BY ";
				$comando_sql .= "planilha_coluna_e_turma, ";
				$comando_sql .= "planilha_coluna_g_coddisciplina ";
				$comando_sql .= "ORDER BY "; 
				$comando_sql .= "planilha_ano DESC, "; 
				$comando_sql .= "planilha_nome_professor ASC, ";
				$comando_sql .= "planilha_disciplina ASC";				

			   	$resultado_sql = mysql_query($comando_sql);

		   		if(mysql_num_rows($resultado_sql) != 0)
		   		{
					echo '<center>';
					echo '<table width="740" border="1" cellspacing="0" cellpadding="2" bordercolor="#000000">';
					echo '<tr>';
					echo '<th scope="col" bgcolor="#F6B446"><font color="#FFFFFF">Opção</font></th>';
					echo '<th scope="col" bgcolor="#F6B446"><font color="#FFFFFF">Turma/Disciplina</font></th>';
					echo '</tr>';
					
					for($i = 0; $i < mysql_num_rows($resultado_sql); $i++)
					{
						echo '<tr>';
						echo '<td bgcolor="#F8F8F8" width="100" align="center"><a href="indice_desempenho_impressao_clovis.php?planilha_ano=' . trim(mysql_result($resultado_sql,$i,"planilha_ano")) . '&planilha_usuario_loguin=' . trim(mysql_result($resultado_sql,$i,"planilha_usuario_loguin")) . '&planilha_turma=' . trim(mysql_result($resultado_sql,$i,"planilha_coluna_e_turma")) . '&planilha_disciplina=' . trim(mysql_result($resultado_sql,$i,"planilha_coluna_g_coddisciplina")) . '"><b>Gerar Índice</b></a></td>';
						echo '<td bgcolor="#F8F8F8" width="640" align="left">' . trim(mysql_result($resultado_sql,$i,"planilha_coluna_e_turma")) . '-' . trim(mysql_result($resultado_sql,$i,"planilha_coluna_f_disciplina")) . '</td>';
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
