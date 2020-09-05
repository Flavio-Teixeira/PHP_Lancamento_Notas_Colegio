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

	//*** Verifica se o Botão de Duplicar foi Clicado ***
	$btDuplicarNotas = $_POST['btDuplicarNotas'];
	
	if( !empty($btDuplicarNotas) )
	{
		//*** Recupera as Variáveis ***
		$planilha_usuario_loguin      = trim($_SESSION['identificacao']['usuario_loguin']);
		$planilha_ano                 = $_POST["planilha_ano"];
		$planilha_bimestre_sequencial = $_POST["planilha_bimestre_sequencial"];

		$planilha_origem  = $_POST['planilha_origem'];
		$planilha_destino = $_POST['planilha_destino'];

		//*** Validação dos Campos ***
		$msg_erro = '';

		//*** Verifica se a Planilha de Origem foi Selecionada ***
        if( trim($planilha_origem) == '' )
		{
			$msg_erro = "ATENÇÃO: Por favor, selecione a planilha de Origem.";
        }

		//*** Verifica se a Planilha de Destino foi Selecionada ***
        if( trim($planilha_destino) == '' )
		{
			$msg_erro = "ATENÇÃO: Por favor, selecione a planilha de Destino.";
        }

		//*** Efetua a Duplicação de Notas da Planilha ***
		if( trim($msg_erro) == '' )
		{
			//*** Lista os Registros Obtidos da Busca ***
			$comando_sql  = "SELECT planilha_ano, ";
			$comando_sql .= "planilha_nome_professor, ";
			$comando_sql .= "planilha_usuario_loguin, ";
			$comando_sql .= "planilha_bimestre_sequencial, ";
			$comando_sql .= "planilha_bimestre_descricao, ";
			$comando_sql .= "planilha_disciplina, ";

			$comando_sql .= "planilha_linha, ";
			$comando_sql .= "planilha_coluna_g_coddisciplina, ";
			$comando_sql .= "planilha_coluna_h_matricula, ";
			$comando_sql .= "planilha_coluna_i_aluno, ";
			$comando_sql .= "planilha_coluna_j_numero, ";
			$comando_sql .= "planilha_coluna_k_nota, ";
			$comando_sql .= "planilha_coluna_l_falta, ";
			$comando_sql .= "planilha_coluna_n_aulas_dadas, ";

			$comando_sql .= "planilha_status, ";
			$comando_sql .= "DATE_FORMAT(planilha_data_alteracao,'%d/%m/%Y') AS planilha_data_alteracao, ";
			$comando_sql .= "planilha_hora_alteracao ";

			$comando_sql .= "FROM planilhas ";
			$comando_sql .= "WHERE ";
			$comando_sql .= "planilha_ano = '" . trim($planilha_ano) . "' AND ";
			$comando_sql .= "planilha_usuario_loguin = '" . trim($planilha_usuario_loguin) . "' AND ";
			$comando_sql .= "planilha_bimestre_sequencial = '" . trim($planilha_bimestre_sequencial) . "' AND ";
			$comando_sql .= "planilha_disciplina = '" . trim($planilha_origem) . "' ";

			$comando_sql .= "ORDER BY "; 
			$comando_sql .= "planilha_ano DESC, "; 
			$comando_sql .= "planilha_bimestre_sequencial DESC, ";
			$comando_sql .= "planilha_linha ASC";

			$resultado_origem = mysql_query($comando_sql);

	   		if(mysql_num_rows($resultado_origem) != 0)
	   		{
				for($i = 0; $i < mysql_num_rows($resultado_origem); $i++)
				{
					$comando_sql  = "UPDATE planilhas SET ";
					$comando_sql .= "planilha_coluna_k_nota = '" . trim(mysql_result($resultado_origem,$i,"planilha_coluna_k_nota")) . "', ";
					$comando_sql .= "planilha_coluna_l_falta = '" . trim(mysql_result($resultado_origem,$i,"planilha_coluna_l_falta")) . "', ";
					$comando_sql .= "planilha_coluna_n_aulas_dadas = '" . trim(mysql_result($resultado_origem,$i,"planilha_coluna_n_aulas_dadas")) . "', ";
					$comando_sql .= "planilha_status = 'Digitada', ";
					$comando_sql .= "planilha_data_alteracao = '" . date("Y-m-d",time()) . "', "; 
					$comando_sql .= "planilha_hora_alteracao = '" . date("H:i:s",time()) . "' "; 

					$comando_sql .= "WHERE ";
					$comando_sql .= "planilha_ano = '" . trim($planilha_ano) . "' AND ";
					$comando_sql .= "planilha_usuario_loguin = '" . trim($planilha_usuario_loguin) . "' AND ";
					$comando_sql .= "planilha_bimestre_sequencial = '" . trim($planilha_bimestre_sequencial) . "' AND ";
					$comando_sql .= "planilha_disciplina = '" . trim($planilha_destino) . "' AND ";
					$comando_sql .= "planilha_coluna_h_matricula = '" . trim(mysql_result($resultado_origem,$i,"planilha_coluna_h_matricula")) . "' ";

					//*** Executa o Comando de Inserção ***
					$resultado_destino = @mysql_query($comando_sql) or die(mysql_error());

					if($resultado_destino != true)
					{
						echo $resultado_destino;
						exit;
					}
				}

				//*** Altera o Status da Planilha para Digitada ***
				$comando_sql  = "UPDATE planilhas SET ";
				$comando_sql .= "planilha_status = 'Digitada', ";
				$comando_sql .= "planilha_data_alteracao = '" . date("Y-m-d",time()) . "', "; 
				$comando_sql .= "planilha_hora_alteracao = '" . date("H:i:s",time()) . "' ";
				$comando_sql .= "WHERE ";
				$comando_sql .= "planilha_ano = '" . trim($planilha_ano) . "' AND ";
				$comando_sql .= "planilha_usuario_loguin = '" . trim($planilha_usuario_loguin) . "' AND ";
				$comando_sql .= "planilha_bimestre_sequencial = '" . trim($planilha_bimestre_sequencial) . "' AND ";
				$comando_sql .= "planilha_disciplina = '" . trim($planilha_destino) . "' ";

				//*** Executa o Comando de Inserção ***
				$resultado = @mysql_query($comando_sql) or die(mysql_error());

  				if($resultado != true)
				{
					echo $resultado;
					exit;
				}

				$msg_erro = 'Duplicação da Disciplina: ' . trim($planilha_origem) . ' para a Disciplina: ' . trim($planilha_destino) . ', foi Finalizada.';
			}
			else
			{
				$msg_erro = 'Valores para duplicação não foram encontrados.';
			}
		}
	}
	else
	{
		//*** Recupera os Valores para Efetuar a Duplicação ***
		$planilha_usuario_loguin      = trim($_SESSION['identificacao']['usuario_loguin']);

		$planilha_ano                 = $_GET["planilha_ano"];		
		$planilha_bimestre_sequencial = $_GET["planilha_bimestre_sequencial"];

		if( (trim($planilha_ano) == "") or (trim($planilha_usuario_loguin) == "") or (trim($planilha_bimestre_sequencial) == "") )
		{
			//*** Redireciona para a Página de Duplicação de Notas ***
			header('Location: duplicar_notas.php');
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
		<meta charset="UTF-8" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<title>Colégio Clóvis Bevilacqua</title>  

		<link rel="stylesheet" type="text/css" href="css/estilos.css"/>

		<script type="text/javascript" src="funcoes/carregando.js"></script>
</head>
<body onload="carregado()">
	<div id="fundo">

      <fieldset style="width: 748px; height: 462px;">
            <legend>Duplicação de Notas - Edição</legend> 

			<div id="exibe_carregando"><img src="imagens/carregando.gif" width="105" height="16" border="0" alt=""></div>
            
            <div id="conteudo_exibicao" style="display: none;">

			 <!-- Formulário de Duplicação de Notas -->

			 <form id="duplicar_notas_edicao" name="duplicar_notas_edicao" action="duplicar_notas_edicao.php" method="post">

			  <!-- Lista os Registros Obtidos da Busca -->

			  <?php
			    //*** Mensagem de Erro ***
				if( trim($msg_erro) <> '' )
				{
					echo '<p><center><font color="#FF0000"><b>'. $msg_erro . '</b></font></center></p>';
				}

				//*** Cria os Inputs do Tipo Hidden contendo os valores que vieram da Tela Anterior ***
				echo '<input type="hidden" name="planilha_ano" value="' . $planilha_ano . '">';
				echo '<input type="hidden" name="planilha_bimestre_sequencial" value="' . $planilha_bimestre_sequencial . '">';

				//*** Obtem os Registros dos Bimestres ***
				$resultado_sql = mysql_query("SELECT * FROM bimestres WHERE bimestre_sequencial = '" . trim($planilha_bimestre_sequencial) . "' ORDER BY bimestre_sequencial ASC");
				$nome_bimestre = trim(mysql_result($resultado_sql,0,"bimestre_descricao"));

				//*** Exibe os Dados do Bimestre ***
   	  		    echo '<label><b>Ano:&nbsp;</b>' . trim($planilha_ano) . '</label><BR>';
				echo '<label><b>Bimestre:&nbsp;</b>' . trim($nome_bimestre) . '</label><BR>';
				echo '<label>Selecione a Planilha de Origem e a Planilha de Destino das Notas:</label><BR>';

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
				$comando_sql .= "planilha_ano = '" . trim($planilha_ano) ."' AND ";
				$comando_sql .= "planilha_usuario_loguin = '" . trim($_SESSION['identificacao']['usuario_loguin']) ."' AND ";
				$comando_sql .= "planilha_bimestre_sequencial = '" . trim($planilha_bimestre_sequencial) ."' ";
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
					echo '<center>';
					echo '<table width="740" border="1" cellspacing="0" cellpadding="2" bordercolor="#000000">';
					echo '<tr>';
					echo '<th scope="col" bgcolor="#F6B446"><font color="#FFFFFF">Opção</font></th>';
					echo '<th scope="col" bgcolor="#F6B446"><font color="#FFFFFF">Turma/Disciplina</font></th>';
					echo '<th scope="col" bgcolor="#F6B446"><font color="#FFFFFF">Status da Digitação</font></th>';
					echo '<th scope="col" bgcolor="#F6B446"><font color="#FFFFFF">Data</font></th>';
					echo '<th scope="col" bgcolor="#F6B446"><font color="#FFFFFF">Hora</font></th>';	
					echo '</tr>';

					//*** Lista as Planilhas de Origens das Notas ***

					echo '<tr>';
					echo '<td bgcolor="#F6B446" width="740" colspan="5"><font color="#FFFFFF"><b>Planilha de Origem das Notas</b></font></td>';
					echo '</tr>';					
					
					for($i = 0; $i < mysql_num_rows($resultado_sql); $i++)
					{
						echo '<tr>';
						echo '<td bgcolor="#F8F8F8" width="50">';
						
						if( trim(mysql_result($resultado_sql,$i,"planilha_status")) == 'Digitada' )
						{
							echo '<center><input type="radio" name="planilha_origem" value="' . trim(mysql_result($resultado_sql,$i,"planilha_disciplina")) . '"></center>';
						}
						else
						{
							echo "<center>-</center>";
						}
						
						echo '</td>';
						echo '<td bgcolor="#F8F8F8" width="420" align="left">' . mysql_result($resultado_sql,$i,"planilha_disciplina") . '</td>';
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

					//*** Lista as Planilhas de Destino das Notas ***

					echo '<tr>';
					echo '<td bgcolor="#F6B446" width="740" colspan="5"><font color="#FFFFFF"><b>Planilha de Destino das Notas</b></font></td>';
					echo '</tr>';
					
					for($i = 0; $i < mysql_num_rows($resultado_sql); $i++)
					{
						echo '<tr>';
						echo '<td bgcolor="#F8F8F8" width="50">';
						
						if( trim(mysql_result($resultado_sql,$i,"planilha_status")) == 'Aguardando' )
						{
							echo '<center><input type="radio" name="planilha_destino" value="' . trim(mysql_result($resultado_sql,$i,"planilha_disciplina")) . '"></center>';
						}
						else
						{
							echo "<center>-</center>";
						}
						
						echo '</td>';
						echo '<td bgcolor="#F8F8F8" width="420" align="left">' . mysql_result($resultado_sql,$i,"planilha_disciplina") . '</td>';
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

					echo '<BR><BR><center><input name="btDuplicarNotas" type="submit" onclick="carregando()" value="Duplicar as Notas" /></center><BR><BR>&nbsp;';
				}
				else
				{
					echo '<BR><BR><BR><BR><center><font color="#FF0000"><b>Nenhuma Planilha de Notas foi Localizada!</b></font></center>';
				}
				
				//*** Fecha a Conexão com o Banco de Dados ***
     			mysql_close($nro_conexao);
			  ?>

			</form>
			
			</div>

      </fieldset>

	</div>

</body>
</html>
