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

    //*** Efetua a Conexão com o Banco de Dados ***
    require_once("includes/conecta_banco.inc.php");
	
    //*** Ativa as Rotinas Gerais ***
    require_once('includes/rotinas_gerais.fnc.php');

	//*** Recupera os Valores Informados ***
	$btBuscar   = $_POST["btBuscar"];    

	if( trim($btBuscar) == "Buscar" )
	{
		$importacao_ano       = $_POST["importacao_ano"];
		$importacao_bimestre  = $_POST["importacao_bimestre"];
		$importacao_professor = $_POST["importacao_professor"];
		$importacao_ensino    = $_POST["importacao_ensino"];
	}
	else
	{
		$btExcluir  = $_GET["btExcluir"];

        $importacao_ano       = $_POST["importacao_ano"];
		$importacao_bimestre  = $_POST["importacao_bimestre"];
		$importacao_professor = $_POST["importacao_professor"];
		$importacao_ensino    = $_POST["importacao_ensino"];

		if( trim($btExcluir) == "Excluir" )
		{
			$selecionado                  = $_POST["selecionado"];
			$planilha_ano                 = $_POST["planilha_ano"];
			$planilha_bimestre_sequencial = $_POST["planilha_bimestre_sequencial"];
			$planilha_usuario_loguin      = $_POST["planilha_usuario_loguin"];
			$planilha_disciplina          = $_POST["planilha_disciplina"];

    		$quantidade_selecao = count($selecionado);

			if($quantidade_selecao > 0)
			{
			  foreach(array_keys($selecionado) as $chave)
			  {
				if( trim($selecionado[$chave]) <> "" )
				{
					$comando_sql  = "DELETE FROM planilhas ";
					$comando_sql .= "WHERE ";
					$comando_sql .= "planilha_ano = '" . trim($planilha_ano[$chave]) . "' AND ";
					$comando_sql .= "planilha_usuario_loguin = '" . trim($planilha_usuario_loguin[$chave]) . "' AND ";
					$comando_sql .= "planilha_bimestre_sequencial = '" . trim($planilha_bimestre_sequencial[$chave]) . "' AND ";
					$comando_sql .= "planilha_disciplina = '" . trim($planilha_disciplina[$chave]) . "'";

					//*** Executa o Comando de Inserção ***
					$resultado = mysql_query($comando_sql) or die(mysql_error()); 
				}
			  }
			}
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

		<script type="text/javascript" src="funcoes/pula_campo.js"></script>
        <script type="text/javascript" src="funcoes/carregando.js"></script>
</head>
<body onload="carregado()">
	<div id="fundo">

      <fieldset style="width: 748px; height: 462px;">
            <legend>Planilhas de Notas</legend> 			

			<div id="exibe_carregando"><img src="imagens/carregando.gif" width="105" height="16" border="0" alt=""></div>

            <div id="conteudo_exibicao" style="display: none;">		

			 <!-- Formulário de Planilhas -->

			 <form id="planilhas_notas" name="planilhas_notas" action="planilhas_notas.php?btExcluir=Excluir" method="post">

				<fieldset style="width: 740px;">
                   <legend>Dados para Busca</legend>
				   
				   <table>
						<tr>
							<td>				  
								<label for="importacao_ano" id="fonte_fundo">Ano</label>
								<input name="importacao_ano" type="text" size="30" readonly value="<?php echo date("Y",time()); ?>" style="background-color: #EBE9ED; width: 35px;" onkeypress="return EnterToTab(this,event);" />
							</td>
							<td>
								<label for="importacao_bimestre" id="fonte_fundo">Bimestre</label>
								<select name="importacao_bimestre" size="1" style="width: 200px;" onkeypress="return EnterToTab(this,event);">
								<option value="0">--- Selecione um Bimestre ---</option>

								<?php
									//*** Obtem os Registros dos Bimestres ***
									$resultado_sql = mysql_query("SELECT * FROM bimestres ORDER BY bimestre_sequencial ASC");

									for($indice = 0; $indice < mysql_num_rows($resultado_sql); $indice++)
									{
										if( $importacao_bimestre == mysql_result($resultado_sql,$indice,"bimestre_sequencial") )
										{
											echo '<option value="' . mysql_result($resultado_sql,$indice,"bimestre_sequencial") . '" selected>' .  mysql_result($resultado_sql,$indice,"bimestre_descricao") . '</option>';
										}
										else
										{
											echo '<option value="' . mysql_result($resultado_sql,$indice,"bimestre_sequencial") . '">' .  mysql_result($resultado_sql,$indice,"bimestre_descricao") . '</option>';
										}
  									}
								?>								
								</select>
							</td>

							<td>&nbsp;</td>

							<td>				  
							  <label for="importacao_ensino" id="fonte_fundo">Tipo de Ensino</label>
							  <select name="importacao_ensino" size="1" style="width: 250px;" onkeypress="return EnterToTab(this,event);" onchange="this.form.submit();">
							  <option value="0">--- Selecione um Tipo de Ensino ---</option>
							  <?php
									if( $importacao_ensino == "F" )
									{
										echo '<option value="F" selected>Fundamental</option>';
									}
									else
									{
										echo '<option value="F">Fundamental</option>';
									}

									if( $importacao_ensino == "M" )
									{
										echo '<option value="M" selected>Médio/Técnico</option>';
									}
									else
									{
										echo '<option value="M">Médio/Técnico</option>';
									}

									if( $importacao_ensino == "P" )
									{
										echo '<option value="P" selected>Pós-Médio</option>';
									}
									else
									{
										echo '<option value="P">Pós-Médio</option>';
									}

									if( $importacao_ensino == "O" )
									{
										echo '<option value="O" selected>Todos</option>';
									}
									else
									{
										echo '<option value="O">Todos</option>';
									}
							  ?>
							  </select> 
							</td>
						</tr>
				   </table>

				   <table>
						<tr>
							<td>
								<label for="importacao_professor" id="fonte_fundo">Professor</label>
								<select name="importacao_professor" size="1" style="width: 535px;" onkeypress="return EnterToTab(this,event);">
								<option value="0">--- Selecione um Professor ---</option>

								<?php
								   if( trim($importacao_ensino) <> "" )
								   {
									    //*** Obtem os Registros dos Professores ***
									    $comando_sql = "SELECT * FROM usuarios WHERE usuario_tipo = 'Professor' ";

									    if( $importacao_ensino <> "O" )
									    {
										   if( $importacao_ensino == "F" )
										   {
												$comando_sql .= "AND usuario_ensino_fundamental = 'S' ";
										   }

										   if( $importacao_ensino == "M" )
										   {
												$comando_sql .= "AND usuario_ensino_medio_tecnico = 'S' ";
										   }

										   if( $importacao_ensino == "P" )
										   {
												$comando_sql .= "AND usuario_ensino_pos = 'S' ";
										   }
									    }

									    $comando_sql .= "ORDER BY usuario_nome ASC";

									    $resultado_sql = mysql_query($comando_sql);

										for($indice = 0; $indice < mysql_num_rows($resultado_sql); $indice++)
										{
											if( $importacao_professor == mysql_result($resultado_sql,$indice,"usuario_loguin") )
											{
												echo '<option value="' . mysql_result($resultado_sql,$indice,"usuario_loguin") . '" selected>' .  mysql_result($resultado_sql,$indice,"usuario_nome") . '</option>';
											}
											else
											{
												echo '<option value="' . mysql_result($resultado_sql,$indice,"usuario_loguin") . '">' .  mysql_result($resultado_sql,$indice,"usuario_nome") . '</option>';
											}
										}
								   }
								?>

								</select>
							</td>
							<td width="200">
								<center><input type="submit" onclick="carregando()" name="btBuscar" value="Buscar"></center>
							</td>
						</tr>
					</table>
			    </fieldset>

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
				$comando_sql .= "planilha_ano = '" . trim($importacao_ano) ."' AND ";
				$comando_sql .= "planilha_usuario_loguin = '" . trim($importacao_professor) ."' AND ";
				$comando_sql .= "planilha_bimestre_sequencial = '" . trim($importacao_bimestre) ."' ";
				$comando_sql .= "GROUP BY ";
				$comando_sql .= "planilha_ano, ";
				$comando_sql .= "planilha_nome_professor, ";
				$comando_sql .= "planilha_usuario_loguin, ";
				$comando_sql .= "planilha_bimestre_sequencial, ";
				$comando_sql .= "planilha_bimestre_descricao, ";
				$comando_sql .= "planilha_disciplina ";
				$comando_sql .= "ORDER BY "; 
				$comando_sql .= "planilha_ano DESC, "; 
				$comando_sql .= "planilha_nome_professor ASC, "; 
				$comando_sql .= "planilha_bimestre_sequencial ASC, ";
				$comando_sql .= "planilha_disciplina ASC";				

			   	$resultado_sql = mysql_query($comando_sql);

		   		if(mysql_num_rows($resultado_sql) != 0)
		   		{
					echo '<center>';
					echo '<table width="740" border="1" cellspacing="0" cellpadding="2" bordercolor="#000000">';
					echo '<tr>';
					echo '<th scope="col" bgcolor="#F6B446"><font color="#FFFFFF">[]</font></th>';
					echo '<th scope="col" bgcolor="#F6B446"><font color="#FFFFFF">Opção</font></th>';
					echo '<th scope="col" bgcolor="#F6B446"><font color="#FFFFFF">Professor</font></th>';
					echo '<th scope="col" bgcolor="#F6B446"><font color="#FFFFFF">Bimestre</font></th>';
					echo '<th scope="col" bgcolor="#F6B446"><font color="#FFFFFF">Disciplina</font></th>';
					echo '<th scope="col" bgcolor="#F6B446"><font color="#FFFFFF">Status</font></th>';
					echo '<th scope="col" bgcolor="#F6B446"><font color="#FFFFFF">Data</font></th>';
					echo '<th scope="col" bgcolor="#F6B446"><font color="#FFFFFF">Hora</font></th>';					
					echo '</tr>';
					
					for($i = 0; $i < mysql_num_rows($resultado_sql); $i++)
					{
						echo '<input type="hidden" name="planilha_ano[' . trim($i) . ']" value="' . mysql_result($resultado_sql,$i,"planilha_ano") . '">';
						echo '<input type="hidden" name="planilha_bimestre_sequencial[' . trim($i) . ']" value="' . mysql_result($resultado_sql,$i,"planilha_bimestre_sequencial") . '">';
 						echo '<input type="hidden" name="planilha_usuario_loguin[' . trim($i) . ']" value="' . mysql_result($resultado_sql,$i,"planilha_usuario_loguin") . '">';
  						echo '<input type="hidden" name="planilha_disciplina[' . trim($i) . ']" value="' . mysql_result($resultado_sql,$i,"planilha_disciplina") . '">';

  						echo '<tr>';
						echo '<td bgcolor="#F8F8F8" width="15"><input type="checkbox" name="selecionado[' . trim($i) . ']" value="' . trim($i) . '"></td>';
						echo '<td bgcolor="#F8F8F8" width="65"><center><input type="button" name="btExcluir" value="Excluir" onClick="if(confirm(\'Confirma a Exclusão da(s) Planilha(s) Selecionada(s)?\')){ carregando();document.forms[\'planilhas_notas\'].submit(); } else { return 0; } "></center></td>';

						echo '<td bgcolor="#F8F8F8" width="135">' . mysql_result($resultado_sql,$i,"planilha_nome_professor") . '</td>';
						echo '<td bgcolor="#F8F8F8" width="60">' . mysql_result($resultado_sql,$i,"planilha_bimestre_descricao") . '</td>';
						echo '<td bgcolor="#F8F8F8" width="250">' . mysql_result($resultado_sql,$i,"planilha_disciplina") . '</td>';
						echo '<td bgcolor="#F8F8F8" width="80"><center>';

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

			</form>
			
			</div>

      </fieldset>

	</div>

</body>
</html>
