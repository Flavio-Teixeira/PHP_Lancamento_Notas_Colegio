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
    $btLembrar = $_POST['btLembrar'];
	
	if( !empty($btLembrar) )
	{
		//*** Recupera as Variáveis ***
		$indentificacao_loguin = troca_aspas_simples($_POST['indentificacao_loguin']);
		
		//*** Efetua a Conexão com o Banco de Dados ***
        require_once("includes/conecta_banco.inc.php");

        //*** Monta o Comando SQL para a Busca do Login ***
		$Comando_SQL = "SELECT * FROM usuarios ";
		$Comando_SQL .= "WHERE usuario_loguin = '" . trim($indentificacao_loguin) . "' ";

        //*** Executa o Comando de Seleção ***
        $resultado = mysql_query($Comando_SQL) or die(mysql_error());
		
		if( mysql_num_rows($resultado) <= 0 )
		{
  		   $MSG_Erro = "ATENÇÃO: Login de Usuário não está Correto! Informe o Dado Novamente!";
		}
		else
		{
			//*** Envia o E-Mail para o Usuário ***

			$para      = trim($indentificacao_loguin);

			$assunto   = "Colégio Clóvis Bevilacqua - Lembrança de Senha";

			$conteudo  = "Colégio Clóvis Bevilacqua<br>\n";
			$conteudo .= "Sistema de Lançamento de Notas On-Line<br><br>\n\n";

			$conteudo .= "<b>Ref.: Lembrança de Senha</b><br><br>\n\n";
			$conteudo .= "Sua senha cadastrada em nosso sistema é: ";
			$conteudo .= mysql_result($resultado,0,'usuario_senha');
			$conteudo .= "<br><br>\n\n";
			$conteudo .= "Atenciosamente,<br><br>\n\n";
			$conteudo .= "Coordenação<br>\n";
			$conteudo .= "Colégio Clóvis Bevilacqua<br>\n";
			$conteudo .= "coordenacao@clovisbevilaqua.com.br";

			/* Medida preventiva para evitar que outros domínios sejam remetente da sua mensagem. */
			if (eregi('tempsite.ws$|locaweb.com.br$|hospedagemdesites.ws$|websiteseguro.com$', $_SERVER[HTTP_HOST])) {
				$emailsender='datatex@datatex.com.br'; // Substitua essa linha pelo seu e-mail@seudominio
			} else {
				$emailsender = "datatex@datatex.com.br"; //. $_SERVER[HTTP_HOST];
				// Na linha acima estamos forçando que o remetente seja 'webmaster@seudominio',
				// Você pode alterar para que o remetente seja, por exemplo, 'contato@seudominio'.
			}
 
			/* Verifica qual éo sistema operacional do servidor para ajustar o cabeçalho de forma correta.  */
			if(PATH_SEPARATOR == ";")
			{
				$quebra_linha = "\r\n"; //Se for Windows
			}
			else
			{
				$quebra_linha = "\n"; //Se "Não for Windows"
			}
 
			// Passando os dados obtidos pelo formulário para as variáveis abaixo
			$nomeremetente     = "Coordenação (Colégio Clóvis Bevilacqua)";
			$emailremetente    = 'coordenacao@clovisbevilaqua.com.br';
			$emaildestinatario = $para;
 
			$conteudo_email  = $conteudo;
 
			/* Montando o cabeÃ§alho da mensagem */
			$headers = "MIME-Version: 1.1" .$quebra_linha;
			$headers .= "Content-type: text/html; charset=UTF-8" .$quebra_linha;
			// Perceba que a linha acima contém "text/html", sem essa linha, a mensagem não chegará formatada.
			$headers .= "From: " . $emailsender.$quebra_linha;
			//$headers .= "Cc: " . $comcopia . $quebra_linha;
			//$headers .= "Bcc: " . $comcopiaoculta . $quebra_linha;
			$headers .= "Reply-To: " . $emailremetente . $quebra_linha;
			// Note que o e-mail do remetente será usado no campo Reply-To (Responder Para)
 
			/* Enviando a mensagem */

			//É obrigatório o uso do parâmetro -r (concatenação do "From na linha de envio"), aqui na Locaweb:

			if(!mail($emaildestinatario, $assunto, $conteudo_email, $headers ,"-r".$emailsender)){ // Se for Postfix
				$headers .= "Return-Path: " . $emailsender . $quebra_linha; // Se "não for Postfix"
				mail($emaildestinatario, $assunto, $conteudo_email, $headers );
			}

			//*** Mensagem de Envio da Senha ***

			$MSG_Erro = "ATENÇÃO: Sua senha foi enviada para o E-Mail: " . trim($indentificacao_loguin) . "  Aguarde alguns para receber o E-Mail e tente acessar o sistema novamente.";
		}
		
        //*** Fecha a Conexão com o Banco de Dados ***
   	    mysql_close($nro_conexao);
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
<body onload="document.lembrar.indentificacao_loguin.focus();">
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
            <legend>Lembrar Senha</legend>
            
            <form id="lembrar" name="lembrar" method="post" action="lembrar_senha.php">
            
            <p> <span id="fonte"><b>Informe seu Login de Usuário:</b></span>
              <label for="indentificacao_loguin" id="label">Usuário</label>
              <input name="indentificacao_loguin" type="text" size="30" onkeypress="return EnterToTab(this,event);" />
            </p>
            <center>
              <input name="btLembrar" type="submit" value="Lembrar" />
            </center>

            </form>
          </fieldset>
        </div>
	</div>
	<div id="rodape">&nbsp;© Copyright 2013 - Colégio Clóvis Bevilacqua</div>	
 	<div id="desenvolvedor">Tenha uma vida mais saudável:&nbsp;<a href="http://www.vidaperfeita.com.br">Vida Perfeita</a>&nbsp;</div>
</body>
</html>
