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

	//*** Verifica se o Botão de Entrar foi Clicado ***
	$btRegistrarNotas = $_POST['btRegistrarNotas'];
	
	if( !empty($btRegistrarNotas) )
	{
		//*** Recupera as Variáveis ***
		$planilha_usuario_loguin      = trim($_SESSION['identificacao']['usuario_loguin']);
		$planilha_lancamento          = trim($_SESSION['identificacao']['usuario_tipo_lancamento']);
		$planilha_ensino_pos           = trim($_SESSION['identificacao']['usuario_ensino_pos']);
		$planilha_ensino_medio_tecnico = trim($_SESSION['identificacao']['usuario_ensino_medio_tecnico']);
		$planilha_ensino_fundamental   = trim($_SESSION['identificacao']['usuario_ensino_fundamental']);

		$planilha_ano                 = $_POST["planilha_ano"];
		$planilha_bimestre_sequencial = $_POST["planilha_bimestre_sequencial"];
		$planilha_disciplina          = $_POST["planilha_disciplina"];

		$planilha_aulas_dadas = $_POST['planilha_aulas_dadas'];
		$planilha_nota		  = $_POST['planilha_nota'];
		$planilha_falta       = $_POST['planilha_falta'];
		$planilha_dispensa    = $_POST['planilha_dispensa'];

		//*** Validação dos Campos ***
		$msg_erro = '';

		//*** Verifica se as Aulas dadas foram informadas ***
		if( ($planilha_bimestre_sequencial == 1) or ($planilha_bimestre_sequencial == 3) or ($planilha_bimestre_sequencial == 5) or ($planilha_bimestre_sequencial == 7) or ($planilha_bimestre_sequencial == 8) )
		{
			if( $planilha_aulas_dadas <= 0 )
			{
				$msg_erro = "ATENÇÃO: A quantidade de aulas dadas não foi preenchida! Por favor, preencha.";
			}
		}

		//*** Verifica se as Notas Foram Lançadas ***
		if( ($planilha_bimestre_sequencial == 1) or ($planilha_bimestre_sequencial == 3) or ($planilha_bimestre_sequencial == 5) or ($planilha_bimestre_sequencial == 7) or ($planilha_bimestre_sequencial == 8) )
		{
			$nota_digitada = false;
			$nota_maior_que_dez = false;
			$nota_meio_ponto = false;

			//*** Obtem a Chave do Array ***
			$chave  =  @array_keys($planilha_nota);

			//*** Verifica as Notas ***
			for($ind=0; $ind < sizeof($chave); $ind++)
			{
				$indice = $chave[$ind];

				//*** Verifica se a Nota foi Digitada ***
				if( $planilha_nota[$indice] > 0 )
				{
					$nota_digitada = true;
				}

				//*** Verifica se a Nota é Maior que 10 ***
				if( $planilha_nota[$indice] > 10 )
				{
					$nota_maior_que_dez = true;
				}

				//*** Verifica se o Meio Ponto é diferente de 0,0 ou 0,5 ***
				if( (($planilha_nota[$indice] - intval($planilha_nota[$indice])) <> 0) and (($planilha_nota[$indice] - intval($planilha_nota[$indice])) <> 0.50) )
				{
					$nota_meio_ponto = true;
				}
			}

  		    if( trim($planilha_lancamento) == 'Nota' )
			{
				if( ($nota_digitada != true) and ($planilha_bimestre_sequencial <> 8) )
				{
					$msg_erro = "ATENÇÃO: Nenhuma nota foi digitada! Por favor, digite.";
				}

				if( $nota_maior_que_dez == true )
				{
					$msg_erro = "ATENÇÃO: Existem algumas Notas Superiores ao Valor de 10. Por favor, Corrija.";
				}

				if( $nota_meio_ponto == true )
				{
					$msg_erro = "ATENÇÃO: Existem algumas Notas com o intervalo decimal diferente de 0,50. Por favor, Corrija.";
				}
			}
		}
		else
		{
			$nota_maior_que_um_e_meio = false;

			//*** Obtem a Chave do Array ***
			$chave  =  @array_keys($planilha_nota);

			//*** Verifica as Notas ***
			for($ind=0; $ind < sizeof($chave); $ind++)
			{
				$indice = $chave[$ind];

				//*** Verifica se a Nota é Maior que 1,5 ***
				if( $planilha_nota[$indice] > 1.5 )
				{
					$nota_maior_que_um_e_meio = true;
				}

				//*** Verifica se o Meio Ponto é diferente de 0,0 ou 0,5 ***
				if( (($planilha_nota[$indice] - intval($planilha_nota[$indice])) <> 0) and (($planilha_nota[$indice] - intval($planilha_nota[$indice])) <> 0.50) )
				{
					$nota_meio_ponto = true;
				}
			}

			if( trim($planilha_lancamento) == 'Nota' )
			{
				if( $nota_maior_que_um_e_meio == true )
				{
					$msg_erro = "ATENÇÃO: Existem algumas Notas Superiores ao Valor de 1,5. Por favor, Corrija.";
				}

				if( $nota_meio_ponto == true )
				{
					$msg_erro = "ATENÇÃO: Existem algumas Notas com o intervalo decimal diferente de 0,50. Por favor, Corrija.";
				}
			}
		}

		//*** Registra os Valores na Tabela ***
		if( trim($msg_erro) == '' )
		{
		    //*** Obtem a Chave do Array ***
			if( trim($planilha_lancamento) == 'Nota' )
			{
				$chave  =  @array_keys($planilha_nota);
			}
			else
			{
				$chave  =  @array_keys($planilha_dispensa);
			}

			//*** Verifica as Notas ***
			for($ind=0; $ind < sizeof($chave); $ind++)
			{
				$indice = $chave[$ind];

				$comando_sql  = "UPDATE planilhas SET ";

				if( trim($planilha_lancamento) == 'Nota' )
				{
					$comando_sql .= "planilha_coluna_k_nota = '" . $planilha_nota[$indice] . "', ";
				}
				else 
				{
					$comando_sql .= "planilha_coluna_m_dispensa = '" . strtoupper(trim($planilha_dispensa[$indice])) . "', ";
				}

				$comando_sql .= "planilha_coluna_l_falta = '" . $planilha_falta[$indice] . "', ";
				$comando_sql .= "planilha_coluna_n_aulas_dadas = '" . $planilha_aulas_dadas . "', ";
				$comando_sql .= "planilha_status = 'Digitada', ";
				$comando_sql .= "planilha_data_alteracao = '" . date("Y-m-d",time()) . "', "; 
				$comando_sql .= "planilha_hora_alteracao = '" . date("H:i:s",time()) . "' "; 

				$comando_sql .= "WHERE ";
				$comando_sql .= "planilha_ano = '" . trim($planilha_ano) . "' AND ";
				$comando_sql .= "planilha_usuario_loguin = '" . trim($planilha_usuario_loguin) . "' AND ";
				$comando_sql .= "planilha_bimestre_sequencial = '" . trim($planilha_bimestre_sequencial) . "' AND ";
				$comando_sql .= "planilha_disciplina = '" . trim($planilha_disciplina) . "' AND ";
				$comando_sql .= "planilha_linha = '" . trim(($indice + 1)) . "' ";

                //*** Executa o Comando de Inserção ***
                $resultado = @mysql_query($comando_sql) or die(mysql_error());

			    if($resultado != true)
			    {
					echo $resultado;
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
			$comando_sql .= "planilha_disciplina = '" . trim($planilha_disciplina) . "' ";

            //*** Executa o Comando de Inserção ***
            $resultado = @mysql_query($comando_sql) or die(mysql_error());

  	        if($resultado != true)
			{
				echo $resultado;
				exit;
			}

            //*** Fecha a Conexão com o Banco de Dados ***
    	    //mysql_close($nro_conexao);

			//*** Exibe a Mensagem de Inclusão e Retorna a Tela Anterior ***
	        echo "<script language=\"JavaScript\">alert('Registro de Notas Efetuado!');window.location=\"lancamento_notas.php\";</script>";
		}
	}
	else
	{
		//*** Recupera os Valores para Efetuar a Digitação ***
		$planilha_usuario_loguin       = trim($_SESSION['identificacao']['usuario_loguin']);
		$planilha_lancamento           = trim($_SESSION['identificacao']['usuario_tipo_lancamento']);
		$planilha_ensino_pos           = trim($_SESSION['identificacao']['usuario_ensino_pos']);
		$planilha_ensino_medio_tecnico = trim($_SESSION['identificacao']['usuario_ensino_medio_tecnico']);
		$planilha_ensino_fundamental   = trim($_SESSION['identificacao']['usuario_ensino_fundamental']);

		$planilha_ano                 = $_GET["planilha_ano"];		
		$planilha_bimestre_sequencial = $_GET["planilha_bimestre_sequencial"];
		$planilha_disciplina          = $_GET["planilha_disciplina"];

		if( (trim($planilha_ano) == "") or (trim($planilha_usuario_loguin) == "") or (trim($planilha_bimestre_sequencial) == "") or (trim($planilha_disciplina) == "") )
		{
			//*** Redireciona para a Página de Lançamento de Notas ***
			header('Location: lancamento_notas.php');
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
		<script type="text/javascript" src="funcoes/so_valor.js"></script>
		<script type="text/javascript" src="funcoes/so_numero.js"></script>
		<script type="text/javascript" src="funcoes/so_dispensa.js"></script>
		<script type="text/javascript" src="funcoes/carregando.js"></script>
</head>
<body onload="carregado();document.lancamento_notas_edicao.planilha_aulas_dadas.focus();">

    <?php
		//*** Verifica qual Bimestre está Ativo ***

		if( trim($planilha_bimestre_sequencial) <> '' )
		{
			if( trim(substr($planilha_disciplina,1,1)) == 'P' )
			{
			  $resultado_ativo_sql = mysql_query("SELECT * FROM bimestre_ativo ORDER BY bimestre_ativo_sequencial_pos ASC");

			  if( $planilha_bimestre_sequencial < mysql_result($resultado_ativo_sql,0,"bimestre_ativo_sequencial_pos") )
			  {
			    echo "<script language=\"JavaScript\">alert('ATENÇÃO:\\n\\nCaro(a) Professor(a), o prazo de digitação de notas para este bimestre já foi encerrado.\\n\\nAs planilhas já foram importadas para o sistema local do Colégio Clóvis.\\n\\nVocê pode efetuar este lançamento de notas para um controle pessoal, mas esta digitação não será mais importada para o sistema local.\\n\\nCaso precise modificar alguma nota ou falta que interfira no processo acadêmico do aluno, por favor, utilize o sistema local do Colégio Clóvis.\\n\\nNo caso de dúvidas, procure a Coordenação!');</script>";
			  }
			}
			elseif( trim($planilha_ensino_medio_tecnico) == 'S' )
			{
			  $resultado_ativo_sql = mysql_query("SELECT * FROM bimestre_ativo ORDER BY bimestre_ativo_sequencial ASC");

			  if( $planilha_bimestre_sequencial < mysql_result($resultado_ativo_sql,0,"bimestre_ativo_sequencial") )
			  {
			    echo "<script language=\"JavaScript\">alert('ATENÇÃO:\\n\\nCaro(a) Professor(a), o prazo de digitação de notas para este bimestre já foi encerrado.\\n\\nAs planilhas já foram importadas para o sistema local do Colégio Clóvis.\\n\\nVocê pode efetuar este lançamento de notas para um controle pessoal, mas esta digitação não será mais importada para o sistema local.\\n\\nCaso precise modificar alguma nota ou falta que interfira no processo acadêmico do aluno, por favor, utilize o sistema local do Colégio Clóvis.\\n\\nNo caso de dúvidas, procure a Coordenação!');</script>";
			  }
			}
			else
			{
			  $resultado_ativo_sql = mysql_query("SELECT * FROM bimestre_ativo ORDER BY bimestre_ativo_sequencial_fundamental ASC");

			  if( $planilha_bimestre_sequencial < mysql_result($resultado_ativo_sql,0,"bimestre_ativo_sequencial_fundamental") )
			  {
			    echo "<script language=\"JavaScript\">alert('ATENÇÃO:\\n\\nCaro(a) Professor(a), o prazo de digitação de notas para este bimestre já foi encerrado.\\n\\nAs planilhas já foram importadas para o sistema local do Colégio Clóvis.\\n\\nVocê pode efetuar este lançamento de notas para um controle pessoal, mas esta digitação não será mais importada para o sistema local.\\n\\nCaso precise modificar alguma nota ou falta que interfira no processo acadêmico do aluno, por favor, utilize o sistema local do Colégio Clóvis.\\n\\nNo caso de dúvidas, procure a Coordenação!');</script>";
			  }
			}
		}
	?>

	<div id="fundo">

       <fieldset style="width: 748px; height: 462px;">
             <legend>Lançamento de Notas - Edição</legend>

			 <div id="exibe_carregando"><img src="imagens/carregando.gif" width="105" height="16" border="0" alt=""></div>
            
             <div id="conteudo_exibicao" style="display: none;">

			 <!-- Formulário de Lançamento de Notas -->

			 <form id="lancamento_notas_edicao" name="lancamento_notas_edicao" action="lancamento_notas_edicao.php" method="post">

			  <?php
			    //*** Mensagem de Erro ***
				if( trim($msg_erro) <> '' )
				{
					echo '<p><center><font color="#FF0000"><b>'. $msg_erro . '</b></font></center></p>';
				}

				//*** Cria os Inputs do Tipo Hidden contendo os valores que vieram da Tela Anterior ***
				echo '<input type="hidden" id="planilha_ano" name="planilha_ano" value="' . $planilha_ano . '">';
				echo '<input type="hidden" id="planilha_bimestre_sequencial" name="planilha_bimestre_sequencial" value="' . $planilha_bimestre_sequencial . '">';
				echo '<input type="hidden" id="planilha_disciplina" name="planilha_disciplina" value="' . $planilha_disciplina . '">';

				//*** Obtem os Registros dos Bimestres ***
				$resultado_sql = mysql_query("SELECT * FROM bimestres WHERE bimestre_sequencial = '" . trim($planilha_bimestre_sequencial) . "' ORDER BY bimestre_sequencial ASC");
				$nome_bimestre = trim(mysql_result($resultado_sql,0,"bimestre_descricao"));

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
				$comando_sql .= "planilha_coluna_m_dispensa, ";
				$comando_sql .= "planilha_coluna_n_aulas_dadas, ";

				$comando_sql .= "planilha_status, ";
				$comando_sql .= "DATE_FORMAT(planilha_data_alteracao,'%d/%m/%Y') AS planilha_data_alteracao, ";
				$comando_sql .= "planilha_hora_alteracao ";

				$comando_sql .= "FROM planilhas ";
				$comando_sql .= "WHERE ";
				$comando_sql .= "planilha_ano = '" . trim($planilha_ano) . "' AND ";
				$comando_sql .= "planilha_usuario_loguin = '" . trim($planilha_usuario_loguin) . "' AND ";
				$comando_sql .= "planilha_bimestre_sequencial = '" . trim($planilha_bimestre_sequencial) . "' AND ";
				$comando_sql .= "planilha_disciplina = '" . trim($planilha_disciplina) . "' AND ";
				$comando_sql .= "planilha_coluna_h_matricula <> '' ";

				$comando_sql .= "ORDER BY "; 
				$comando_sql .= "planilha_ano DESC, "; 
				$comando_sql .= "planilha_bimestre_sequencial DESC, ";
				$comando_sql .= "planilha_linha ASC";
				
			   	$resultado_sql = mysql_query($comando_sql);

		   		if(mysql_num_rows($resultado_sql) != 0)
		   		{
					echo '<label><b>Ano:&nbsp;</b>' . trim($planilha_ano) . '</label><BR>';
					echo '<label><b>Bimestre:&nbsp;</b>' . trim($nome_bimestre) . '</label><BR>';
					echo '<label><b>Cód.Disciplina:&nbsp;</b>' . trim(mysql_result($resultado_sql,0,"planilha_coluna_g_coddisciplina")) . '</label><BR>';
					echo '<label><b>Turma/Disciplina:&nbsp;</b>' . trim($planilha_disciplina) . '</label><BR>';
					echo '<BR>';
					echo '<label for="planilha_aulas_dadas"><b>Quantidade de Aulas Dadas:&nbsp;</b></label>';

					if( empty($btRegistrarNotas) )
					{
						if( ($planilha_bimestre_sequencial == 2) or ($planilha_bimestre_sequencial == 4) or ($planilha_bimestre_sequencial == 6) or ($planilha_bimestre_sequencial == 9) )
						{
							echo '<input id="planilha_aulas_dadas" name="planilha_aulas_dadas" type="text" size="1" value="' . mysql_result($resultado_sql,0,"planilha_coluna_n_aulas_dadas") . '" readonly onKeyPress="return EnterToTab(this,event);" onKeyUp="return so_numero(this);" onmouseup="return false" onFocus="this.select()" onClick="this.select()" style="background-color: #EBE9ED;" maxlength="3"/><BR>';
						}
						else
						{
							echo '<input id="planilha_aulas_dadas" name="planilha_aulas_dadas" type="text" size="1" value="' . mysql_result($resultado_sql,0,"planilha_coluna_n_aulas_dadas") . '" onKeyPress="return EnterToTab(this,event);" onKeyUp="return so_numero(this);" onmouseup="return false" onFocus="this.select()" onClick="this.select()" style="background-color: #FFFCAB;" maxlength="3"/><BR>';
						}
					}
					else
					{
						if( ($planilha_bimestre_sequencial == 2) or ($planilha_bimestre_sequencial == 4) or ($planilha_bimestre_sequencial == 6) or ($planilha_bimestre_sequencial == 9) )
						{
							echo '<input id="planilha_aulas_dadas" name="planilha_aulas_dadas" type="text" size="1" value="' . $planilha_aulas_dadas . '" readonly onKeyPress="return EnterToTab(this,event);" onKeyUp="return so_numero(this);" onmouseup="return false" onFocus="this.select()" onClick="this.select()" style="background-color: #EBE9ED;" maxlength="3"/><BR>';
						}
						else
						{
							echo '<input id="planilha_aulas_dadas" name="planilha_aulas_dadas" type="text" size="1" value="' . $planilha_aulas_dadas . '" onKeyPress="return EnterToTab(this,event);" onKeyUp="return so_numero(this);" onmouseup="return false" onFocus="this.select()" onClick="this.select()" style="background-color: #FFFCAB;" maxlength="3"/><BR>';
						}
					}

					echo '&nbsp;';
					echo '<center>';

					if( trim($planilha_lancamento) <> 'Nota' )
					{
						echo '<b>Siglas:</b> <b>D</b>-Diversos | <b>E</b>-Disciplina | <b>F</b>-Frequente | <b>M</b>-Médico | <b>T</b>-Trabalho<br>';
					}

					echo '<table width="740" border="1" cellspacing="0" cellpadding="2" bordercolor="#000000">';
					echo '<tr>';
					echo '<th scope="col" bgcolor="#F6B446"><font color="#FFFFFF">Matrícula</font></th>';
					echo '<th scope="col" bgcolor="#F6B446"><font color="#FFFFFF">Nome do Aluno</font></th>';
					echo '<th scope="col" bgcolor="#F6B446"><font color="#FFFFFF">Nro.</font></th>';

					if( trim($planilha_lancamento) == 'Nota' )
					{
					  echo '<th scope="col" bgcolor="#F6B446"><font color="#FFFFFF">Nota</font></th>';
					}
					else
					{
					  echo '<th scope="col" bgcolor="#F6B446"><font color="#FFFFFF">Dispensa</font></th>';
					}

					echo '<th scope="col" bgcolor="#F6B446"><font color="#FFFFFF">Faltas</font></th>';
					echo '</tr>';
					
					for($i = 0; $i < mysql_num_rows($resultado_sql); $i++)
					{
						echo '<tr>';
						echo '<td bgcolor="#F8F8F8" width="65">' . mysql_result($resultado_sql,$i,"planilha_coluna_h_matricula") . '</td>';
						echo '<td bgcolor="#F8F8F8" width="560" align="left">' . mysql_result($resultado_sql,$i,"planilha_coluna_i_aluno") . '</td>';
						echo '<td bgcolor="#F8F8F8" width="30">' . mysql_result($resultado_sql,$i,"planilha_coluna_j_numero") . '</td>';						

   					    if( trim($planilha_lancamento) == 'Nota' )
					    {
							if( empty($btRegistrarNotas) )
							{
								echo '<td bgcolor="#F8F8F8" width="40"><input id="planilha_nota[' . $i . ']" name="planilha_nota[' . $i . ']" type="text" size="2" value="' . mysql_result($resultado_sql,$i,"planilha_coluna_k_nota") . '" onKeyPress="return EnterToTab(this,event);" onKeyUp="return so_valor(this);" onmouseup="return false" onFocus="this.select()" onClick="this.select()" style="background-color: #FFFCAB;" maxlength="5"/></td>';						
							}
							else
							{
								echo '<td bgcolor="#F8F8F8" width="40"><input id="planilha_nota[' . $i . ']" name="planilha_nota[' . $i . ']" type="text" size="2" value="' . $planilha_nota[$i] . '" onKeyPress="return EnterToTab(this,event);" onKeyUp="return so_valor(this);" onmouseup="return false" onFocus="this.select()" onClick="this.select()" style="background-color: #FFFCAB;" maxlength="5"/></td>';						
							}
						}
						else
						{
							if( empty($btRegistrarNotas) )
							{
								echo '<td bgcolor="#F8F8F8" width="40"><input id="planilha_dispensa[' . $i . ']" name="planilha_dispensa[' . $i . ']" type="text" size="2" value="' . mysql_result($resultado_sql,$i,"planilha_coluna_m_dispensa") . '" onKeyPress="return EnterToTab(this,event);" onKeyUp="return so_dispensa(this);" onmouseup="return false" onFocus="this.select()" onClick="this.select()" style="background-color: #FFFCAB; text-transform:uppercase;" maxlength="1"/></td>';
							}
							else
							{
								echo '<td bgcolor="#F8F8F8" width="40"><input id="planilha_dispensa[' . $i . ']" name="planilha_dispensa[' . $i . ']" type="text" size="2" value="' . $planilha_nota[$i] . '" onKeyPress="return EnterToTab(this,event);" onKeyUp="return so_dispensa(this);" onmouseup="return false" onFocus="this.select()" onClick="this.select()" style="background-color: #FFFCAB; text-transform:uppercase;" maxlength="1"/></td>';						
							}
						}

						if( empty($btRegistrarNotas) )
						{
							if( ($planilha_bimestre_sequencial == 2) or ($planilha_bimestre_sequencial == 4) or ($planilha_bimestre_sequencial == 6) or ($planilha_bimestre_sequencial == 9) )
							{
								echo '<td bgcolor="#F8F8F8" width="45"><input id="planilha_falta[' . $i . ']" name="planilha_falta[' . $i . ']" type="text" size="1" value="' . mysql_result($resultado_sql,$i,"planilha_coluna_l_falta") . '" readonly onKeyPress="return EnterToTab(this,event);" onKeyUp="return so_numero(this);" onmouseup="return false" onFocus="this.select()" onClick="this.select()" style="background-color: #EBE9ED;" maxlength="3"/></td>';						
							}
							else
							{
								echo '<td bgcolor="#F8F8F8" width="45"><input id="planilha_falta[' . $i . ']" name="planilha_falta[' . $i . ']" type="text" size="1" value="' . mysql_result($resultado_sql,$i,"planilha_coluna_l_falta") . '" onKeyPress="return EnterToTab(this,event);" onKeyUp="return so_numero(this);" onmouseup="return false" onFocus="this.select()" onClick="this.select()" style="background-color: #FFFCAB;" maxlength="3"/></td>';						
							}
						}
						else
						{
							if( ($planilha_bimestre_sequencial == 2) or ($planilha_bimestre_sequencial == 4) or ($planilha_bimestre_sequencial == 6) or ($planilha_bimestre_sequencial == 9) )
							{
								echo '<td bgcolor="#F8F8F8" width="45"><input id="planilha_falta[' . $i . ']" name="planilha_falta[' . $i . ']" type="text" size="1" value="' . $planilha_falta[$i] . '" readonly onKeyPress="return EnterToTab(this,event);" onKeyUp="return so_numero(this);" onmouseup="return false" onFocus="this.select()" onClick="this.select()" style="background-color: #EBE9ED;" maxlength="3"/></td>';			
							}
							else
							{
								echo '<td bgcolor="#F8F8F8" width="45"><input id="planilha_falta[' . $i . ']" name="planilha_falta[' . $i . ']" type="text" size="1" value="' . $planilha_falta[$i] . '" onKeyPress="return EnterToTab(this,event);" onKeyUp="return so_numero(this);" onmouseup="return false" onFocus="this.select()" onClick="this.select()" style="background-color: #FFFCAB;" maxlength="3"/></td>';			
							}
						}

  						echo '</tr>';
					}
					
					echo '</table>';
					echo '</center>';

					echo '<BR><BR><center><input id="btRegistrarNotas" name="btRegistrarNotas" type="submit" onclick="carregando()" value="Registrar as Notas" /></center><BR><BR>&nbsp;';
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
