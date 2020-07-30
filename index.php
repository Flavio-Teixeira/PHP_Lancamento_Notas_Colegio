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

    //*** Ativa as Rotinas Gerais ***
    require_once('includes/rotinas_gerais.fnc.php');
	
	//*** Verifica se o Botão de Entrar foi Clicado ***
	$opcao = $_GET['opcao'];

	if( !empty($opcao) )
	{
		header('Location: lembrar_senha.php');
	}
	else
	{
	    $btEntrar = $_POST['btEntrar'];
	
		if( !empty($btEntrar) )
		{
			//*** Recupera as Variáveis ***
			$indentificacao_loguin = troca_aspas_simples($_POST['indentificacao_loguin']);
			$indentificacao_senha  = troca_aspas_simples($_POST['indentificacao_senha']);
			$identificaco_tipo     = troca_aspas_simples($_POST['identificaco_tipo']);

			//*** Remove as Aspas Simples para evitar o SQL Injection *** 
			$indentificacao_loguin = str_replace("'","",$indentificacao_loguin);
			$indentificacao_senha  = str_replace("'","",$indentificacao_senha);
			$identificaco_tipo     = str_replace("'","",$identificaco_tipo);
		
			$indentificacao_loguin = str_replace("´","",$indentificacao_loguin);
			$indentificacao_senha  = str_replace("´","",$indentificacao_senha);
			$identificaco_tipo     = str_replace("´","",$identificaco_tipo);
			
			//*** Efetua a Conexão com o Banco de Dados ***
	        require_once("includes/conecta_banco.inc.php");

	        //*** Monta o Comando SQL para a Busca do Login ***
			$Comando_SQL = "SELECT * FROM usuarios ";
			$Comando_SQL .= "WHERE usuario_loguin = '" . trim($indentificacao_loguin) . "' AND ";
			$Comando_SQL .= "usuario_senha = '" . trim($indentificacao_senha) . "' AND ";	
			$Comando_SQL .= "usuario_tipo = '" . trim($identificaco_tipo) . "'";	

	        //*** Executa o Comando de Seleção ***
	        $resultado = mysql_query($Comando_SQL) or die(mysql_error());
		
			if( mysql_num_rows($resultado) <= 0 )
			{
	  		   $MSG_Erro = "ATENÇÃO: Usuário ou Senha não estão Corretos! Informe os Dados Novamente!";
			}
			else
			{
	           //*** Ativa a Sessão para o Usuário e Redireciona para a Página de Pagamento ***

			   //*** Inicializa a Sessão ***
			   session_start();

			   //*** Prepara a Sessão para Recuperar Valores ***
			   require_once("includes/prepara_sessao.inc.php");   

			   //*** Cria a Sessão para este Usuário ***
		       $_SESSION['identificacao']['identificado']   = true;
			   $_SESSION['identificacao']['tipo']           = trim($identificaco_tipo);
		       $_SESSION['identificacao']['usuario_loguin'] = trim($indentificacao_loguin);
		       $_SESSION['identificacao']['usuario_nome']   = mysql_result($resultado,0,'usuario_nome');
			   $_SESSION['identificacao']['usuario_pasta']  = mysql_result($resultado,0,'usuario_pasta');
			   $_SESSION['identificacao']['usuario_tipo_lancamento'] = mysql_result($resultado,0,'usuario_tipo_lancamento');
			   $_SESSION['identificacao']['usuario_colegio'] = mysql_result($resultado,0,'usuario_colegio');
			   $_SESSION['identificacao']['usuario_ensino_pos'] = mysql_result($resultado,0,'usuario_ensino_pos');
			   $_SESSION['identificacao']['usuario_ensino_medio_tecnico'] = mysql_result($resultado,0,'usuario_ensino_medio_tecnico');
			   $_SESSION['identificacao']['usuario_ensino_fundamental'] = mysql_result($resultado,0,'usuario_ensino_fundamental');

	           //*** Redireciona para a Página de Utilização ***
			   if( trim($identificaco_tipo) == 'Professor' )
			   {
				   header('Location: professor.php');
			   }
			   else
			   {
				   header('Location: administracao.php');
			   }
			}
		
	        //*** Fecha a Conexão com o Banco de Dados ***
	   	    mysql_close($nro_conexao);
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
</head>
<body onload="document.identificacao.indentificacao_loguin.focus();">
	<div id="cabecalho"></div>
    
<div id="cabecalho_titulo">      
	  <p><b>Sistema de Lançamento de Notas On-Line</b></p>
	</div>	
    
	<div id="corpo">

		<?php
	   		if( !empty($MSG_Erro) )
	   		{
		 		echo '<center><p><span id="fonte_vermelha"><b>'. $MSG_Erro .'</b></span></p></center>';
	   		}	  
   		?> 

        <div id="loguin">
          <fieldset>
            <legend>Identificação</legend>
            
            <form id="identificacao" name="identificacao" method="post" action="index.php">
            
            <p> <span id="fonte"><b>Informe seus dados de acesso:</b></span>
              <label for="indentificacao_loguin" id="label">Usuário</label>
              <input name="indentificacao_loguin" type="text" size="30" onkeypress="return EnterToTab(this,event);" />
            </p>
            <p>
              <label for="indentificacao_senha" id="label">Senha</label>
              <input name="indentificacao_senha" type="password" size="30" onkeypress="return EnterToTab(this,event);" />
            </p>
            <p> <span id="fonte"><b>Tipo de Usuário:</b></span>
              <label for="identificaco_tipo" id="label">
                <input name="identificaco_tipo" type="radio" id="identificaco_tipo_0" value="Professor" checked="checked" onkeypress="return EnterToTab(this,event);" />
                Professor</label>
              <label for="identificaco_tipo" id="label">
                <input type="radio" name="identificaco_tipo" value="Administrador" id="identificaco_tipo_1" onkeypress="return EnterToTab(this,event);" />
                Administrador</label>
            </p>
			<p><center><input name="btEntrar" type="submit" value="Entrar" /></center></p>

			<p><font color="#FA930D"><b>ATENÇÃO:</b> Para melhor funcionamento do Sistema utilize o Navegador: <a href="https://www.mozilla.org/pt-BR/firefox/fx/" target="_blank"><b>Firefox</b></a>. Os demais Navegadores não possibilitarão a utilização de todos os recursos, você pode utilizá-los, mas alguns recursos serão limitados.&nbsp;&nbsp;&nbsp;<a href="index.php?opcao=lembrarsenha"><b>[Lembrar Senha]</b></a></font></p>

            </form>
          </fieldset>
        </div>
	</div>
	<div id="rodape">&nbsp;© Copyright 2013 - Colégio Clóvis Bevilacqua</div>	
 	<div id="desenvolvedor">Tenha uma vida mais saudável:&nbsp;<a href="http://www.vidaperfeita.com.br">Vida Perfeita</a>&nbsp;</div>
</body>
</html>
