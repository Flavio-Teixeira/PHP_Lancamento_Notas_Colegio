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

    //*** Ativa as Rotina para Exportação das Planilhas ***
    require_once('classes/PHPExcel.php');

	//*** Obtem as Variáveis Digitadas ***
	$msg_erro           = "";      
    $btGerar            = $_POST["btGerar"];
	$digitacao_ano	    = $_POST["digitacao_ano"];
	$digitacao_bimestre = $_POST["digitacao_bimestre"];
	$digitacao_status   = $_POST["digitacao_status"];
	$digitacao_colegio  = $_POST["digitacao_colegio"];

	if( trim($btGerar) == "Gerar Relatório" )
	{
		//*** Valida os Valores Informados ***
		if( (trim($digitacao_bimestre) == '') or (trim($digitacao_bimestre) == '0') )
		{
			$msg_erro = "Por favor, Informe o Bimestre Desejado !!!";         
		}
		elseif( (trim($digitacao_status) == '') or (trim($digitacao_status) == '0') )
		{
            $msg_erro = "Por favor, Informe o Status Desejado !!!"; 
		}
		elseif( (trim($digitacao_colegio) == '') or (trim($digitacao_colegio) == '0') )
		{
            $msg_erro = "Por favor, Informe o Colégio Desejado !!!"; 
		}

        //*** Direciona para a Página de Relatório ***
		if( trim($msg_erro) == "" )
		{
			//*** Fecha a Conexão com o Banco de Dados ***
	     	mysql_close($nro_conexao);

			echo "<script language=\"JavaScript\">window.location=\"relatorio_digitacao_impressao.php?digitacao_ano=" . $digitacao_ano . "&digitacao_bimestre=" . $digitacao_bimestre . "&digitacao_status=" . $digitacao_status . "&digitacao_colegio=" . $digitacao_colegio . "\";</script>";
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
</head>

<body>

   <div id="fundo"> 

       <fieldset style="width: 748px; height: 462px;">
           <legend>Relatório de Digitação</legend>
            
           <div id="conteudo_exibicao"> 

		   <!-- Formulário para a Exportação de Notas -->

            <form id="digitacao" name="digitacao" enctype="multipart/form-data" action="relatorio_digitacao.php" method="post">

			  <p><?php echo '<center><font color="#FF0000"><b>' . $msg_erro . '</b></font></center>'; ?></p>

			  <p>
              <label for="digitacao_ano" id="fonte_fundo">Ano</label>
              <input name="digitacao_ano" type="text" size="30" readonly value="<?php echo date("Y",time()); ?>" style="background-color: #EBE9ED; width: 35px;" onkeypress="return EnterToTab(this,event);"/>
			  </p>

			  <p>
              <label for="digitacao_bimestre" id="fonte_fundo">Bimestre</label>
			  <select name="digitacao_bimestre" size="1" style="width: 350px;" onkeypress="return EnterToTab(this,event);">
			  <option value="0">--- Selecione um Bimestre ---</option>

			  <?php
					//*** Obtem os Registros dos Bimestres ***
					$resultado_sql = mysql_query("SELECT * FROM bimestres ORDER BY bimestre_sequencial ASC");

					for($indice = 0; $indice < mysql_num_rows($resultado_sql); $indice++)
					{
						if( $digitacao_bimestre == mysql_result($resultado_sql,$indice,"bimestre_sequencial") )
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

			  <p>
              <label for="digitacao_status" id="fonte_fundo">Status da Digitação</label>
			  <select name="digitacao_status" size="1" style="width: 200px;" onkeypress="return EnterToTab(this,event);">
			  <option value="0">--- Selecione um Status ---</option>
			  <?php
					if( $digitacao_status == "Aguardando" )
					{
						echo '<option value="Aguardando" selected>Aguardando</option>';
					}
					else
					{
						echo '<option value="Aguardando">Aguardando</option>';
					}

					if( $digitacao_status == "Digitada" )
					{
					    echo '<option value="Digitada" selected>Digitada</option>';
					}
					else
					{
						echo '<option value="Digitada">Digitada</option>';
					}
			  ?>
			  </select> 
			  </p>
			  
			  <p>
              <label for="digitacao_colegio" id="fonte_fundo">Colégio</label>
			  <select name="digitacao_colegio" size="1" style="width: 200px;" onkeypress="return EnterToTab(this,event);">
			  <option value="0">--- Selecione um Colégio ---</option>
			  <?php
					if( $digitacao_colegio == "Clovis" )
					{
						echo '<option value="Clovis" selected>Clóvis</option>';
					}
					else
					{
						echo '<option value="Clovis">Clóvis</option>';
					}

					if( $digitacao_colegio == "Padrao" )
					{
					    echo '<option value="Padrao" selected>Padrão</option>';
					}
					else
					{
						echo '<option value="Padrao">Padrão</option>';
					}

					if( $digitacao_colegio == "Ambos" )
					{
					    echo '<option value="Ambos" selected>Ambos</option>';
					}
					else
					{
						echo '<option value="Ambos">Ambos</option>';
					}
			  ?>
			  </select> 
			  </p>

			  <p>	
              <center>
              <input name="btGerar" type="submit" value="Gerar Relatório" />
              </center>
			  </p>

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
