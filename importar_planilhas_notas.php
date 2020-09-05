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

    //*** Importa a Include de Planilha Excell ***
    require_once('includes/excel_reader2.php');

	//*** Obtem as Variáveis Digitadas ***
	$msg_erro             = "";      
    $btImportar           = $_POST["btImportar"];
	$importacao_bimestre  = $_POST["importacao_bimestre"];
	$importacao_professor = $_POST["importacao_professor"];
	$importacao_ensino    = $_POST["importacao_ensino"];

	$exibe_alerta = false;

	if( trim($btImportar) == "Importar as Planilhas do Professor" )
	{
		//*** Valida os Valores Informados ***
		if( (trim($importacao_bimestre) == '') or (trim($importacao_bimestre) == '0') )
		{
			$msg_erro = "Por favor, Informe o Bimestre Desejado !!!";         
		}
		elseif( (trim($importacao_professor) == '') or (trim($importacao_professor) == '0') )
		{
            $msg_erro = "Por favor, Informe o Professor Desejado !!!"; 
		}
		elseif( empty($_FILES["importacao_planilhas"]["name"][0]) )
		{
			$msg_erro = "Por favor, Informe as Planilhas do Professor !!!"; 
		}

        //*** Importa as Planilhas do Professor ***
		if( trim($msg_erro) == "" )
		{
			//*** Obtem os Registros dos Bimestres ***
			$resultado_sql = mysql_query("SELECT * FROM bimestres WHERE bimestre_sequencial = '" . trim($importacao_bimestre) . "' ORDER BY bimestre_sequencial ASC");
			$nome_bimestre = trim(mysql_result($resultado_sql,$indice,"bimestre_descricao"));

			//*** Obtem os Registros dos Professores ***
			$resultado_sql = mysql_query("SELECT * FROM usuarios WHERE usuario_tipo = 'Professor' AND usuario_loguin = '" . trim($importacao_professor) . "' ORDER BY usuario_nome ASC");
			$nome_professor  = trim(mysql_result($resultado_sql,0,"usuario_nome"));

			$pasta_planilhas = $_SERVER["SCRIPT_FILENAME"];
			$pasta_planilhas = str_replace('importar_planilhas_notas.php', '', $pasta_planilhas);
			$pasta_planilhas = trim($pasta_planilhas) . "planilhas/" . date("Y",time()) . "/" . trim($importacao_bimestre) . "/" . trim(mysql_result($resultado_sql,0,"usuario_pasta")) . "/";

			//*** Verifica se a Pasta Existe ***
			//*** Se não existir cria ***
			if( !is_dir( $pasta_planilhas ) )
			{
				mkdir($pasta_planilhas, 0777, true);
			}

			//*** Efetua o Upload das Planilhas ***
			//*** Prepara o Arquivo ***
            $i = 0;
            $importacao_planilhas = array( array( ) );
            foreach(  $_FILES as $key=>$info ) {
                   foreach( $info as $key=>$dados ) {
                          for( $i = 0; $i < sizeof( $dados ); $i++ ) {
                             $importacao_planilhas[$i][$key] = $info[$key][$i];
                          }
                   }
            }
            $i = 1;

			//*** Efetua o Upload ***

			$nome_planilha = array( );

            foreach( $importacao_planilhas as $file ) {

                   //*** Verificar se o campo do arquivo foi preenchido ***
                   if( $file['name'] != '' ) 
				   {
                     $arquivoTmp = $file['tmp_name'];
                     $arquivo = $pasta_planilhas . $file['name'];

					 $nome_planilha[$i] = trim($file['name']);

                     if( !move_uploaded_file( $arquivoTmp, $arquivo ) ) {
                       $msg_erro = 'Erro no upload da Planilha!'.$i;
                     }
                   } 
				   else 
				   {
                     $msg_erro = sprintf('A Planilha não foi informada! ',$i);
                   }
                   $i++;
            }

			$total_planilhas = $i;

			//*** Verifica se as Planilhas não Foram Importadas ***

			for($posicao_planilha = 1; $posicao_planilha < $total_planilhas; $posicao_planilha++)
			{
				$comando_sql = "SELECT * FROM planilhas ";
				$comando_sql .= "WHERE ";
				$comando_sql .= "planilha_ano = '" . trim(date("Y",time())) . "' AND ";
				$comando_sql .= "planilha_bimestre_sequencial = '" . trim($importacao_bimestre) . "' AND ";
				$comando_sql .= "planilha_usuario_loguin = '" . trim($importacao_professor) . "' AND ";
				$comando_sql .= "planilha_disciplina = '" . trim(str_replace('.xls', '', $nome_planilha[$posicao_planilha])) . "'";

				$resultado_sql = mysql_query($comando_sql);

				if(mysql_num_rows($resultado_sql) != 0)
				{
	              $msg_erro = 'Duplicidade, Esta(s) Planilha(s) já fo(i)(ram) importada(s) para o(a) Professor(a): ' . trim($nome_professor) . ', no Bimestre: ' . trim($nome_bimestre) . '.<br><br>Por favor, verifique!';
				}
			}

			//*** Efetua a Leitura das Planilhas ***
			//*** Abre a Planilha para Leitura ***

            if( trim($msg_erro) == '' )
			{
				for($posicao_planilha = 1; $posicao_planilha < $total_planilhas; $posicao_planilha++)
				{
					$data = new Spreadsheet_Excel_Reader($pasta_planilhas . $nome_planilha[$posicao_planilha]);

					for( $i=0; $i <= $data->rowcount($sheet_index=0); $i++ ){
						if( ($i <> 0) and ($i <> 1) )
						{
							if( (trim($data->val($i, 1)) <> '') or (trim($data->val($i, 16)) <> '') )
							{
								$comando_sql  = "INSERT INTO planilhas(";
								$comando_sql .= "planilha_ano,";
								$comando_sql .= "planilha_bimestre_sequencial,";
								$comando_sql .= "planilha_usuario_loguin,";
								$comando_sql .= "planilha_disciplina,";
								$comando_sql .= "planilha_linha,";
								$comando_sql .= "planilha_bimestre_descricao, ";
								$comando_sql .= "planilha_nome_professor, ";
								$comando_sql .= "planilha_coluna_a_escola,";
								$comando_sql .= "planilha_coluna_b_ano,";
								$comando_sql .= "planilha_coluna_c_mes,";
								$comando_sql .= "planilha_coluna_d_epoca,";
								$comando_sql .= "planilha_coluna_e_turma,";
								$comando_sql .= "planilha_coluna_f_disciplina,";
								$comando_sql .= "planilha_coluna_g_coddisciplina,";
								$comando_sql .= "planilha_coluna_h_matricula,";
								$comando_sql .= "planilha_coluna_i_aluno,";
								$comando_sql .= "planilha_coluna_j_numero,";
								$comando_sql .= "planilha_coluna_k_nota,";
								$comando_sql .= "planilha_coluna_l_falta,";
								$comando_sql .= "planilha_coluna_m_dispensa,";
								$comando_sql .= "planilha_coluna_n_aulas_dadas,";
								$comando_sql .= "planilha_coluna_o_vazia,";
								$comando_sql .= "planilha_coluna_p_dispensa_codigo,";
								$comando_sql .= "planilha_coluna_q_dispensa_descricao,";
								$comando_sql .= "planilha_status,";
								$comando_sql .= "planilha_data_alteracao,";
								$comando_sql .= "planilha_hora_alteracao) ";

								$comando_sql .= "VALUES(";
								$comando_sql .= "'" . trim(date("Y",time())) . "', ";
								$comando_sql .= "'" . trim($importacao_bimestre) . "',";
								$comando_sql .= "'" . trim($importacao_professor) . "',";
								$comando_sql .= "'" . trim(str_replace('.xls', '', $nome_planilha[$posicao_planilha])) . "',";
								$comando_sql .= "'" . ($i - 1) . "',";
								$comando_sql .= "'" . trim($nome_bimestre) . "',";
								$comando_sql .= "'" . trim($nome_professor) . "',";
								$comando_sql .= "'" . trim($data->val($i, 1)) . "',";
								$comando_sql .= "'" . trim($data->val($i, 2)) . "',";
								$comando_sql .= "'" . trim($data->val($i, 3)) . "',";
								$comando_sql .= "'" . trim($data->val($i, 4)) . "',";
								$comando_sql .= "'" . trim($data->val($i, 5)) . "',";
								$comando_sql .= "'" . trim($data->val($i, 6)) . "',";
								$comando_sql .= "'" . trim($data->val($i, 7)) . "',";
								$comando_sql .= "'" . trim($data->val($i, 8)) . "',";
								$comando_sql .= "'" . trim(str_replace("'","´",$data->val($i, 9))) . "',";
								$comando_sql .= "'" . trim($data->val($i, 10)) . "',";
								$comando_sql .= "'" . trim($data->val($i, 11)) . "',";
								$comando_sql .= "'" . trim($data->val($i, 12)) . "',";
								$comando_sql .= "'" . trim($data->val($i, 13)) . "',";
								$comando_sql .= "'" . trim($data->val($i, 14)) . "',";
								$comando_sql .= "'" . trim($data->val($i, 15)) . "',";
								$comando_sql .= "'" . trim($data->val($i, 16)) . "',";
								$comando_sql .= "'" . trim($data->val($i, 17)) . "',";
								$comando_sql .= "'Aguardando',";
								$comando_sql .= "'" . trim(date("Y-m-d",time())) . "',";
								$comando_sql .= "'" . trim(date("H:i:s",time())) . "') ";

		                        //*** Executa o Comando de Inserção ***
		                        $resultado = mysql_query($comando_sql) or die(mysql_error());
							}
						}
				    }
				}
			}

			//*** Exibe a Mensagem de Upload ***
            if( trim($msg_erro) == '' )
			{
              $msg_erro = 'Fo(i)(ram) Importadas com Sucesso: ' . trim(($total_planilhas - 1)) .  ' Planilha(s) do(a) Professor(a): ' . trim($nome_professor) . ', do Bimestre: ' . trim($nome_bimestre) . '.<br><br>Por favor, Selecione o Próximo Professor.';
			  $exibe_alerta = true;

			  //*** Envia o E-Mail Avisando o Professor sobre a Planilha que foi Importada ***
			  //**************
			  //*** Inicio ***
			  //**************

			  $para      = trim($importacao_professor);

			  $assunto   = "Colégio Clóvis Bevilacqua - PLANILHA(S) DO(A) " . trim($nome_bimestre) . " JÁ ESTA(ÃO) DISPONÍVEL(IS) PARA DIGITAÇÃO";

			  $conteudo  = "Colégio Clóvis Bevilacqua<br>\n";
			  $conteudo .= "Sistema de Lançamento de Notas On-Line<br><br>\n\n";

			  $conteudo .= "<b>Ref.: PLANILHA(S) DO(A) " . trim($nome_bimestre) . " JÁ ESTA(ÃO) DISPONÍVEL(IS) PARA DIGITAÇÃO</b><br><br>\n\n";
			  $conteudo .= "Sua(s) planilha(s) cadastrada(s) em nosso sistema de notas on-line referente a(o) " . trim($nome_bimestre) . " já esta(ão) disponível(is) para digitação.<br>\n";
			  $conteudo .= "Verifique se suas Planilhas estão corretas, do contrário, avise a Coordenação do Colégio.<br>\n";
              $conteudo .= "Fique atento ao prazo máximo de digitação das notas. Tendo dúvidas, referente aos prazos, consulte os comunicados que são fornecidos bimestralmente.<br>\n";
			  $conteudo .= "<br>\n";
			  $conteudo .= "<b>ATENÇÃO:</b> Enquanto a digitação do sistema de notas on-line estiver ativa, não utilize o sistema de notas local (SAE), pois ao ocorrer a importação das notas on-line para o SAE, suas notas digitadas localmente (SAE) serão apagadas, prevalecendo as notas do sistema on-line.<br>\n";
			  $conteudo .= "Só utilize o sistema local de notas (SAE) após ocorrer a importação das notas on-line.<br>\n";
			  $conteudo .= "<br>\n";
              $conteudo .= "Para acessar o sistema de notas on-line, digite a URL a seguir em seu navegador: http://www.clovisnotas.com.br<br>\n";
              $conteudo .= "Ecolha o 'Tipo de Usuário: Professor', informe seu E-Mail e sua senha de acesso para o sistema. A senha padrão é 'clovisnotas'.<br>\n";
			  $conteudo .= "Para aumentar sua segurança troque a senha padrão por outra de sua escolha.<br>\n";
			  $conteudo .= "<br>\n";
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

			  //*************
			  //*** Final ***
			  //*************
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
            <legend>Importar as Planilhas de Notas</legend>

			<div id="exibe_carregando"><img src="imagens/carregando.gif" width="105" height="16" border="0" alt=""></div>

            <div id="conteudo_exibicao" style="display: none;">		

            <form id="importacao" name="importacao" enctype="multipart/form-data" action="importar_planilhas_notas.php" method="post">

			  <p>
			  <?php 
				echo '<center><font color="#FF0000"><b>' . $msg_erro . '</b></font></center>'; 

				if( $exibe_alerta == true )
				{
					//*** Exibe a Mensagem de Inclusão e Retorna a Tela Anterior ***
					echo "<script language=\"JavaScript\">alert('Importação Efetuada!');</script>";
				}
			  ?>
			  </p>

			  <p>
              <label for="importacao_ano" id="fonte_fundo">Ano</label>
              <input name="importacao_ano" type="text" size="30" readonly value="<?php echo date("Y",time()); ?>" style="background-color: #EBE9ED; width: 35px;" onkeypress="return EnterToTab(this,event);"/>
			  </p>

			  <p>
              <label for="importacao_bimestre" id="fonte_fundo">Bimestre</label>
			  <select name="importacao_bimestre" size="1" style="width: 350px;" onkeypress="return EnterToTab(this,event);">
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
			  </p>

			  <p>
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
			  </p>

			  <p>
              <label for="importacao_professor" id="fonte_fundo">Professor</label>
			  <select name="importacao_professor" size="1" style="width: 550px;" onkeypress="return EnterToTab(this,event);">
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
			  </p>

              <p>
              <label for="importacao_planilhas[]" id="fonte_fundo">Planilhas de Notas</label>
              <input name="importacao_planilhas[]" type="file" multiple size="30" onkeypress="return EnterToTab(this,event);" />
			  </p>

			  <p>	
              <center><BR><BR>
              <input name="btImportar" type="submit" onclick="carregando()" value="Importar as Planilhas do Professor" />
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
