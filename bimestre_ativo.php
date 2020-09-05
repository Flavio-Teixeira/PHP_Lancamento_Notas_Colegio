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
	$btAtivar   = $_POST["btAtivar"];    

	if( trim($btAtivar) == "Ativar" )
	{
		//*** Obtem o Bimestre Selecionado para Ativação *** 
        $ativa_ano                  = $_POST["ativa_ano"];
		$ativa_bimestre             = $_POST["ativa_bimestre"];
		$ativa_bimestre_pos         = $_POST["ativa_bimestre_pos"];
		$ativa_bimestre_fundamental = $_POST["ativa_bimestre_fundamental"];

		//*** Médio/Técnico ***
		//*** Seleciona a Descrição do Bimestre Selecionado para Ativação ***        
		$resultado_sql = mysql_query("SELECT * FROM bimestres WHERE bimestre_sequencial = " . trim($ativa_bimestre) . " ORDER BY bimestre_sequencial ASC");

		//*** Registra o Bimestre Escolhido para Ativação na Tabela ***
		if( trim($ativa_bimestre) > 0 )
		{
		  $comando_sql  = "UPDATE bimestre_ativo SET ";
		  $comando_sql .= "bimestre_ativo_sequencial = " . trim($ativa_bimestre) . ", ";
		  $comando_sql .= "bimestre_ativo_descricao = '" . trim(mysql_result($resultado_sql,0,"bimestre_descricao")) . "'";
		}
		else
		{
		  $comando_sql  = "UPDATE bimestre_ativo SET ";
		  $comando_sql .= "bimestre_ativo_sequencial = 0, ";
		  $comando_sql .= "bimestre_ativo_descricao = 'Todos os Bimestres estão Ativos'";
		}

        //*** Executa o Comando de Inserção ***
        $resultado = @mysql_query($comando_sql) or die(mysql_error());

  	    if($resultado != true)
		{
			echo $resultado;
			exit;
		}

		//*** Pós-Médio ***
		//*** Seleciona a Descrição do Bimestre Selecionado para Ativação ***        
		$resultado_sql = mysql_query("SELECT * FROM bimestres WHERE bimestre_sequencial = " . trim($ativa_bimestre_pos) . " ORDER BY bimestre_sequencial ASC");

		//*** Registra o Bimestre Escolhido para Ativação na Tabela ***
		if( trim($ativa_bimestre_pos) > 0 )
		{
		  $comando_sql  = "UPDATE bimestre_ativo SET ";
		  $comando_sql .= "bimestre_ativo_sequencial_pos = " . trim($ativa_bimestre_pos) . ", ";
		  $comando_sql .= "bimestre_ativo_descricao_pos = '" . trim(mysql_result($resultado_sql,0,"bimestre_descricao")) . "'";
		}
		else
		{
		  $comando_sql  = "UPDATE bimestre_ativo SET ";
		  $comando_sql .= "bimestre_ativo_sequencial_pos = 0, ";
		  $comando_sql .= "bimestre_ativo_descricao_pos = 'Todos os Bimestres estão Ativos'";
		}

        //*** Executa o Comando de Inserção ***
        $resultado = @mysql_query($comando_sql) or die(mysql_error());

  	    if($resultado != true)
		{
			echo $resultado;
			exit;
		}

		//*** Fundamental ***
		//*** Seleciona a Descrição do Bimestre Selecionado para Ativação ***        
		$resultado_sql = mysql_query("SELECT * FROM bimestres WHERE bimestre_sequencial = " . trim($ativa_bimestre_fundamental) . " ORDER BY bimestre_sequencial ASC");

		//*** Registra o Bimestre Escolhido para Ativação na Tabela ***
		if( trim($ativa_bimestre_fundamental) > 0 )
		{
		  $comando_sql  = "UPDATE bimestre_ativo SET ";
		  $comando_sql .= "bimestre_ativo_sequencial_fundamental = " . trim($ativa_bimestre_fundamental) . ", ";
		  $comando_sql .= "bimestre_ativo_descricao_fundamental = '" . trim(mysql_result($resultado_sql,0,"bimestre_descricao")) . "'";
		}
		else
		{
		  $comando_sql  = "UPDATE bimestre_ativo SET ";
		  $comando_sql .= "bimestre_ativo_sequencial_fundamental = 0, ";
		  $comando_sql .= "bimestre_ativo_descricao_fundamental = 'Todos os Bimestres estão Ativos'";
		}

        //*** Executa o Comando de Inserção ***
        $resultado = @mysql_query($comando_sql) or die(mysql_error());

  	    if($resultado != true)
		{
			echo $resultado;
			exit;
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
            <legend>Bimestre Ativo</legend>			

			<div id="exibe_carregando"><img src="imagens/carregando.gif" width="105" height="16" border="0" alt=""></div>

            <div id="conteudo_exibicao" style="display: none;">

			 <!-- Formulário de Planilhas -->

			 <form id="bimestre_ativo" name="bimestre_ativo" action="bimestre_ativo.php" method="post">

				<fieldset style="width: 740px;">
                   <legend>Informe qual o Bimestre será ativado para a digitação de notas</legend>

					<?php
						//*** Obtem os Registros dos Bimestres ***
						$resultado_ativo_sql = mysql_query("SELECT * FROM bimestre_ativo ORDER BY bimestre_ativo_sequencial ASC");

						$resultado_sql = mysql_query("SELECT * FROM bimestres ORDER BY bimestre_sequencial ASC");

						$ativa_bimestre             = mysql_result($resultado_ativo_sql,0,"bimestre_ativo_sequencial");
						$ativa_bimestre_pos         = mysql_result($resultado_ativo_sql,0,"bimestre_ativo_sequencial_pos");
						$ativa_bimestre_fundamental = mysql_result($resultado_ativo_sql,0,"bimestre_ativo_sequencial_fundamental");
					?>

					<label for="ativa_ano" id="fonte_fundo">Ano</label>
					<input name="ativa_ano" type="text" size="30" readonly value="<?php echo date("Y",time()); ?>" style="background-color: #EBE9ED; width: 35px;" onkeypress="return EnterToTab(this,event);" />

					<!-- Médio/Técnico -->

					<fieldset>
						<legend>Médio/Técnico</legend>

						<p>
							<?php
								echo '<b>Bimestre Ativo no Momento: <font color="#FF0000">' . mysql_result($resultado_ativo_sql,0,"bimestre_ativo_descricao") . '</font></b><BR>';
							?>

							<label for="ativa_bimestre" id="fonte_fundo">Bimestre a Ficar Ativo:</label>
							<select name="ativa_bimestre" size="1" style="width: 350px;" onkeypress="return EnterToTab(this,event);">
							<option value="0">--- Selecione um Bimestre ---</option>

							<?php
								//*** Obtem os Registros dos Bimestres ***
								for($indice = 0; $indice < mysql_num_rows($resultado_sql); $indice++)
								{
									if( $ativa_bimestre == mysql_result($resultado_sql,$indice,"bimestre_sequencial") )
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
						</p>
					</fieldset>

					<!-- Pós-Médio -->

					<fieldset>
						<legend>Pós-Médio</legend>

						<p>
							<?php
								echo '<b>Bimestre Ativo no Momento: <font color="#FF0000">' . mysql_result($resultado_ativo_sql,0,"bimestre_ativo_descricao_pos") . '</font></b><BR>';
							?>

							<label for="ativa_bimestre_pos" id="fonte_fundo">Bimestre a Ficar Ativo:</label>
							<select name="ativa_bimestre_pos" size="1" style="width: 350px;" onkeypress="return EnterToTab(this,event);">
							<option value="0">--- Selecione um Bimestre ---</option>

							<?php
								//*** Obtem os Registros dos Bimestres ***
								for($indice = 0; $indice < mysql_num_rows($resultado_sql); $indice++)
								{
									if( $ativa_bimestre_pos == mysql_result($resultado_sql,$indice,"bimestre_sequencial") )
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
						</p>
					</fieldset>

					<!-- Fundamental -->

					<fieldset>
						<legend>Fundamental</legend>

						<p>
							<?php
								echo '<b>Bimestre Ativo no Momento: <font color="#FF0000">' . mysql_result($resultado_ativo_sql,0,"bimestre_ativo_descricao_fundamental") . '</font></b><BR>';
							?>

							<label for="ativa_bimestre_fundamental" id="fonte_fundo">Bimestre a Ficar Ativo:</label>
							<select name="ativa_bimestre_fundamental" size="1" style="width: 350px;" onkeypress="return EnterToTab(this,event);">
							<option value="0">--- Selecione um Bimestre ---</option>

							<?php
								//*** Obtem os Registros dos Bimestres ***
								for($indice = 0; $indice < mysql_num_rows($resultado_sql); $indice++)
								{
									if( $ativa_bimestre_fundamental == mysql_result($resultado_sql,$indice,"bimestre_sequencial") )
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
						</p>
					</fieldset>

					<BR>

					<center><input type="submit" onclick="carregando()" name="btAtivar" value="Ativar"></center>				
							
			    </fieldset>

			  <?php	
				//*** Fecha a Conexão com o Banco de Dados ***
     			mysql_close($nro_conexao);
			  ?>

			</form>
			
			</div>

      </fieldset>

	</div>

</body>
</html>
