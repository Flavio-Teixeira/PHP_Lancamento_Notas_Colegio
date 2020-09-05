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
	$msg_erro             = "";      
    $btExportar           = $_POST["btExportar"];
	$exportacao_ano	      = $_POST["exportacao_ano"];
	$exportacao_bimestre  = $_POST["exportacao_bimestre"];
	$exportacao_professor = $_POST["exportacao_professor"];
	$exportacao_ensino    = $_POST["exportacao_ensino"];

	if( trim($btExportar) == "Exportar as Planilhas do Professor" )
	{
		//*** Valida os Valores Informados ***
		if( (trim($exportacao_bimestre) == '') or (trim($exportacao_bimestre) == '0') )
		{
			$msg_erro = "Por favor, Informe o Bimestre Desejado !!!";         
		}
		elseif( (trim($exportacao_professor) == '') or (trim($exportacao_professor) == '0') )
		{
            $msg_erro = "Por favor, Informe o Professor Desejado !!!"; 
		}
		elseif( (trim($exportacao_ensino) == '') or (trim($exportacao_ensino) == '0') )
		{
            $msg_erro = "Por favor, Informe o Tipo de Ensino Desejado !!!"; 
		}

        //*** Exporta as Planilhas do Professor ***
		if( trim($msg_erro) == "" )
		{
			if( trim($exportacao_professor) <> 'todos' )
			{
			  //*** Gera as Planilhas do Professor ***
			  $comando_sql  = "SELECT planilha_ano, ";
			  $comando_sql .= "planilha_bimestre_sequencial, ";
			  $comando_sql .= "planilha_usuario_loguin, ";
			  $comando_sql .= "planilha_disciplina, ";
			  $comando_sql .= "planilha_linha, ";
			  $comando_sql .= "planilha_bimestre_descricao, ";
			  $comando_sql .= "planilha_nome_professor, ";
			  $comando_sql .= "planilha_coluna_a_escola, ";
			  $comando_sql .= "planilha_coluna_b_ano, ";
			  $comando_sql .= "planilha_coluna_c_mes, ";
			  $comando_sql .= "planilha_coluna_d_epoca, ";
			  $comando_sql .= "planilha_coluna_e_turma, ";
			  $comando_sql .= "planilha_coluna_f_disciplina, ";
			  $comando_sql .= "planilha_coluna_g_coddisciplina, ";
			  $comando_sql .= "planilha_coluna_h_matricula, ";
			  $comando_sql .= "planilha_coluna_i_aluno, ";
			  $comando_sql .= "planilha_coluna_j_numero, ";
			  $comando_sql .= "planilha_coluna_k_nota, ";
			  $comando_sql .= "planilha_coluna_l_falta, ";
			  $comando_sql .= "planilha_coluna_m_dispensa, ";
			  $comando_sql .= "planilha_coluna_n_aulas_dadas, ";
			  $comando_sql .= "planilha_coluna_o_vazia, ";
			  $comando_sql .= "planilha_coluna_p_dispensa_codigo, ";
			  $comando_sql .= "planilha_coluna_q_dispensa_descricao, ";
			  $comando_sql .= "planilha_status, ";
			  $comando_sql .= "DATE_FORMAT(planilha_data_alteracao,'%d/%m/%Y') AS planilha_data_alteracao, ";
			  $comando_sql .= "planilha_hora_alteracao ";
			  $comando_sql .= "FROM ";
			  $comando_sql .= "planilhas ";
			  $comando_sql .= "WHERE ";
			  $comando_sql .= "planilha_ano = '" . trim($exportacao_ano) ."' AND ";
			  $comando_sql .= "planilha_usuario_loguin = '" . trim($exportacao_professor) ."' AND ";
			  $comando_sql .= "planilha_bimestre_sequencial = '" . trim($exportacao_bimestre) ."' ";

			  if(trim($exportacao_ensino) == 'P')
			  {
				  $comando_sql .= "AND SUBSTRING(planilha_coluna_e_turma,2,1) = 'P' ";
			  }

			  $comando_sql .= "ORDER BY "; 
			  $comando_sql .= "planilha_ano DESC, ";
			  $comando_sql .= "planilha_bimestre_sequencial ASC, ";
			  $comando_sql .= "planilha_usuario_loguin ASC, ";
			  $comando_sql .= "planilha_disciplina ASC, ";
			  $comando_sql .= "planilha_linha ASC ";

			  $resultado_sql = mysql_query($comando_sql);

			  if(mysql_num_rows($resultado_sql) != 0)
			  {
				$nome_planilha_anterior = '';
				$primeira_planilha = true;
				$posicao_linha = 1;

				//*** Obtem os Registros dos Professores ***
				$resultado_usuario = mysql_query("SELECT * FROM usuarios WHERE usuario_tipo = 'Professor' AND usuario_loguin = '" . trim($exportacao_professor) . "' ORDER BY usuario_nome ASC");

				//*** Cria a Pasta para Salvar as Planilhas ***							
				$pasta_planilhas = $_SERVER["SCRIPT_FILENAME"];
				$pasta_planilhas = str_replace('exportar_planilhas_notas.php', '', $pasta_planilhas);
				$pasta_planilhas = trim($pasta_planilhas) . "exportacao/" . date("Y",time()) . "/" . trim($exportacao_bimestre) . "/" . trim(mysql_result($resultado_usuario,0,"usuario_pasta")) . "/";
				$pasta_planilhas = trim($pasta_planilhas);

				$pasta_planilhas_link = "exportacao/" . date("Y",time()) . "/" . trim($exportacao_bimestre) . "/" . trim(mysql_result($resultado_usuario,0,"usuario_pasta")) . "/";
				$pasta_planilhas_link = trim($pasta_planilhas_link);

				$pasta_zipada = "exportacao/" . date("Y",time()) . "/" . trim($exportacao_bimestre) . "/" . trim(mysql_result($resultado_usuario,0,"usuario_pasta")) . "/" . trim(mysql_result($resultado_usuario,0,"usuario_pasta")) . ".zip";
				$pasta_zipada = trim($pasta_zipada);

				$pasta_zipada_link = trim(mysql_result($resultado_usuario,0,"usuario_pasta")) . ".zip";
				$pasta_zipada_link = trim($pasta_zipada_link);

				//*** Verifica se a Pasta Existe ***
				//*** Se não existir cria ***
				if( !is_dir( $pasta_planilhas ) )
				{
					mkdir($pasta_planilhas, 0777, true);
				}

				//*** Cria o Arquivo ZIP ***
				$zip = new ZipArchive();

				if ($zip->open($pasta_zipada, ZIPARCHIVE::CREATE)!==TRUE) {
					exit("cannot open <$pasta_zipada>\n");
				}

				//*** Inicia o processo para Exportação das Planilhas ***

				for($i = 0; $i < mysql_num_rows($resultado_sql); $i++)
				{
					//if( retira_acentos(trim(mysql_result($resultado_sql,$i,"planilha_disciplina")),0) <> trim($nome_planilha_anterior) )
					if( trim(mysql_result($resultado_sql,$i,"planilha_disciplina")) <> trim($nome_planilha_anterior) )
					{
						if( $primeira_planilha != true )
						{
							//*** Nomeia o Nome da Aba da Planilha ***
							$objPHPExcel->getActiveSheet()->setTitle('Nota');

							//*** Seta a Planilha que será salva ***
							$objPHPExcel->setActiveSheetIndex(0);

							//*** Salva a Planilha na Pasta do Professor ***
							$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
							$objWriter->save($pasta_planilhas . trim($nome_planilha_anterior) . '.xls');

							//*** Adiciona o Arquivo ZIP ***
							$zip->addFile($pasta_planilhas . trim($nome_planilha_anterior) . '.xls');
						}

						//*** Guarda o Nome da Planilha Anterior e Seta como Segunda Planilha ***
						$nome_planilha_anterior = trim(mysql_result($resultado_sql,$i,"planilha_disciplina"));

						$primeira_planilha = false;
						$posicao_linha = 1;

						//*** Cria um Novo Objeto PHPExcel ***
						$objPHPExcel = new PHPExcel();

						//*** Seta as Propriedades do Documento ***
						$objPHPExcel->getProperties()->setCreator("Colegio Clovis Bevilacqua")
													 ->setLastModifiedBy("Datatex")
													 ->setTitle("Planilha de Notas")
													 ->setSubject("Notas Bimestrais")
													 ->setDescription("Notas Bimestrais dos Professores.")
													 ->setKeywords("notas")
													 ->setCategory("Notas");

						//*** Adiciona uma Nova Linha ***
						$objPHPExcel->setActiveSheetIndex(0)
						            ->setCellValue('A1', 'Escola')
						            ->setCellValue('B1', 'Ano')
						            ->setCellValue('C1', 'Mes')
						            ->setCellValue('D1', 'Epoca')
									->setCellValue('E1', 'Turma')
									->setCellValue('F1', 'Disciplina')
									->setCellValue('G1', 'CodDisciplina')
									->setCellValue('H1', 'Matricula')
									->setCellValue('I1', 'Aluno')
									->setCellValue('J1', 'Numero')
									->setCellValue('K1', 'Nota')
									->setCellValue('L1', 'Falta')
									->setCellValue('M1', 'Dispensa')
									->setCellValue('N1', 'Aulas Dadas')
									->setCellValue('O1', '')
									->setCellValue('P1', 'Tabela de Dispensa')
									->setCellValue('Q1', '');
					}

					$posicao_linha = $posicao_linha + 1;

					//*** Gera as Demais Linha da Planilha ***

					if( $posicao_linha == 2 )
					{ 
						$objPHPExcel->setActiveSheetIndex(0)
									->setCellValue('A' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_a_escola"))))
									->setCellValue('B' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_b_ano"))))
									->setCellValue('C' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_c_mes"))))
									->setCellValue('D' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_d_epoca"))))
									->setCellValue('E' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_e_turma"))))
									->setCellValue('F' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_f_disciplina"))))
									->setCellValue('G' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_g_coddisciplina"))))
									->setCellValue('H' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_h_matricula"))))
									->setCellValue('I' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_i_aluno"))))
									->setCellValue('J' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_j_numero"))))
									->setCellValue('K' . trim($posicao_linha), trim(mysql_result($resultado_sql,$i,"planilha_coluna_k_nota")) )
									->setCellValue('L' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_l_falta"))))
									->setCellValue('M' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_m_dispensa"))))
									->setCellValue('N' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_n_aulas_dadas"))))
									->setCellValue('O' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_o_vazia"))))
									->setCellValue('P' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_p_dispensa_codigo"))))
									->setCellValue('Q' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_q_dispensa_descricao"))));

						//*** Formata a Célula como Numérica ***
						if( trim(mysql_result($resultado_sql,$i,"planilha_coluna_k_nota")) <> '' )
						{
							$objPHPExcel->setActiveSheetIndex(0)->getCell('K' . trim($posicao_linha))->setValueExplicit( trim(mysql_result($resultado_sql,$i,"planilha_coluna_k_nota")) , PHPExcel_Cell_DataType::TYPE_NUMERIC);
						    $objPHPExcel->setActiveSheetIndex(0)->getStyle('K' . trim($posicao_linha))->getNumberFormat()->setFormatCode("0.00");
						}
					}
					else
					{
						if( trim(mysql_result($resultado_sql,$i,"planilha_coluna_h_matricula")) <> '' )
						{
							$objPHPExcel->setActiveSheetIndex(0)
										->setCellValue('A' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_a_escola"))))
										->setCellValue('B' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_b_ano"))))
										->setCellValue('C' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_c_mes"))))
										->setCellValue('D' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_d_epoca"))))
										->setCellValue('E' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_e_turma"))))
										->setCellValue('F' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_f_disciplina"))))
										->setCellValue('G' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_g_coddisciplina"))))
										->setCellValue('H' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_h_matricula"))))
										->setCellValue('I' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_i_aluno"))))
										->setCellValue('J' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_j_numero"))))
										->setCellValue('K' . trim($posicao_linha), trim(mysql_result($resultado_sql,$i,"planilha_coluna_k_nota")) )
										->setCellValue('L' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_l_falta"))))
										->setCellValue('M' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_m_dispensa"))))
										->setCellValue('N' . trim($posicao_linha), '')
										->setCellValue('O' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_o_vazia"))))
										->setCellValue('P' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_p_dispensa_codigo"))))
										->setCellValue('Q' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_q_dispensa_descricao"))));

							//*** Formata a Célula como Numérica ***
						    if( trim(mysql_result($resultado_sql,$i,"planilha_coluna_k_nota")) <> '' )
						    {
								$objPHPExcel->setActiveSheetIndex(0)->getCell('K' . trim($posicao_linha))->setValueExplicit( trim(mysql_result($resultado_sql,$i,"planilha_coluna_k_nota")) , PHPExcel_Cell_DataType::TYPE_NUMERIC);
							    $objPHPExcel->setActiveSheetIndex(0)->getStyle('K' . trim($posicao_linha))->getNumberFormat()->setFormatCode("0.00");
							}
						}
						else
						{
							$objPHPExcel->setActiveSheetIndex(0)
										->setCellValue('A' . trim($posicao_linha), '')
										->setCellValue('B' . trim($posicao_linha), '')
										->setCellValue('C' . trim($posicao_linha), '')
										->setCellValue('D' . trim($posicao_linha), '')
										->setCellValue('E' . trim($posicao_linha), '')
										->setCellValue('F' . trim($posicao_linha), '')
										->setCellValue('G' . trim($posicao_linha), '')
										->setCellValue('H' . trim($posicao_linha), '')
										->setCellValue('I' . trim($posicao_linha), '')
										->setCellValue('J' . trim($posicao_linha), '')
										->setCellValue('K' . trim($posicao_linha), '')
										->setCellValue('L' . trim($posicao_linha), '')
										->setCellValue('M' . trim($posicao_linha), '')
										->setCellValue('N' . trim($posicao_linha), '')
										->setCellValue('O' . trim($posicao_linha), '')
										->setCellValue('P' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_p_dispensa_codigo"))))
										->setCellValue('Q' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_q_dispensa_descricao"))));
						}
					}
				}
			  }

			  //*** Nomeia o Nome da Aba da Planilha ***
			  $objPHPExcel->getActiveSheet()->setTitle('Nota');

			  //*** Seta a Planilha que será salva ***
			  $objPHPExcel->setActiveSheetIndex(0);

			  //*** Salva a Planilha na Pasta do Professor ***
			  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			  $objWriter->save($pasta_planilhas . trim($nome_planilha_anterior) . '.xls');

			  //*** Adiciona o Arquivo ZIP ***
			  $zip->addFile($pasta_planilhas . trim($nome_planilha_anterior) . '.xls');
			  $zip->close();
			}
			else
			{
				//*** Total Planilhas Geradas ***
				$total_planilhas_geradas = 0;

				//*** Seleciona o Nome do Bimestre ***
				$resultado_sql = mysql_query("SELECT * FROM bimestres WHERE bimestre_sequencial = " . trim($exportacao_bimestre) . " ORDER BY bimestre_sequencial ASC");
				$nome_bimestre = mysql_result($resultado_sql,0,"bimestre_descricao");
				$nome_bimestre = retira_acentos(trim($nome_bimestre),0);

				//*** Seleciona Todos os Professores do Colégio Escolhido ***
				$comando_sql  = "SELECT ";
				$comando_sql .= "* ";
				$comando_sql .= "FROM "; 
				$comando_sql .= "usuarios ";
				$comando_sql .= "WHERE ";
				$comando_sql .= "usuario_tipo = 'Professor' ";

				if( $exportacao_ensino <> "O" )
				{
				   if( $exportacao_ensino == "F" )
				   {
						$comando_sql .= "AND usuario_ensino_fundamental = 'S' ";
						$exportacao_colegio = "Fundamental";
				   }

				   if( $exportacao_ensino == "M" )
				   {
						$comando_sql .= "AND usuario_ensino_medio_tecnico = 'S' ";
						$exportacao_colegio = "Medio_Tecnico";
				   }

				   if( $exportacao_ensino == "P" )
				   {
						$comando_sql .= "AND usuario_ensino_pos = 'S' ";
						$exportacao_colegio = "Pos";
				   }
				}
				else
				{
					$exportacao_colegio = "Todos";
				}

				$comando_sql .= "ORDER BY usuario_nome ASC";

			    $resultado_professor = mysql_query($comando_sql);

			    if(mysql_num_rows($resultado_professor) != 0)
			    {
					//*** Cria a Pasta para Salvar as Planilhas ***							
				    $pasta_planilhas = $_SERVER["SCRIPT_FILENAME"];
				    $pasta_planilhas = str_replace('exportar_planilhas_notas.php', '', $pasta_planilhas);
				    $pasta_planilhas = trim($pasta_planilhas) . "exportacao/" . date("Y",time()) . "/" . trim($exportacao_bimestre) . "/todos/" . trim($exportacao_colegio) . "_" . trim($nome_bimestre) . "/";
				    $pasta_planilhas = trim($pasta_planilhas);

				    $pasta_planilhas_link = "exportacao/" . date("Y",time()) . "/" . trim($exportacao_bimestre) . "/todos/" . trim($exportacao_colegio) . "_" . trim($nome_bimestre) . "/";
				    $pasta_planilhas_link = trim($pasta_planilhas_link);

				    $pasta_zipada = "exportacao/" . date("Y",time()) . "/" . trim($exportacao_bimestre) . "/todos/" . trim($exportacao_colegio) . "_" . trim($nome_bimestre) . "/" . trim($exportacao_colegio) . "_" . trim($nome_bimestre) . ".zip";
				    $pasta_zipada = trim($pasta_zipada);

				    $pasta_zipada_link = trim($exportacao_colegio) . "_" . trim($nome_bimestre) . ".zip";
				    $pasta_zipada_link = trim($pasta_zipada_link);

					//*** Remove a Pasta de Todos os Professores se existir ***
				    if( is_dir( $pasta_planilhas ) )
				    {
					  rmdir($pasta_planilhas); 
					}

				    //*** Verifica se a Pasta Existe ***
				    //*** Se não existir cria ***
				    if( !is_dir( $pasta_planilhas ) )
				    {
					  mkdir($pasta_planilhas, 0777, true);
				    }

  				    //*** Cria o Arquivo ZIP ***
					$zip = new ZipArchive();

					if ($zip->open($pasta_zipada, ZIPARCHIVE::CREATE)!==TRUE) {
						exit("cannot open <$pasta_zipada>\n");
					}

					//*** Gera as Planilhas do Professor ***
					for($u = 0; $u < mysql_num_rows($resultado_professor); $u++)
				    {
					  $loguin_professor = trim(mysql_result($resultado_professor,$u,"usuario_loguin"));

					  //*** Gera as Planilhas do Professor ***
					  $comando_sql  = "SELECT planilha_ano, ";
					  $comando_sql .= "planilha_bimestre_sequencial, ";
					  $comando_sql .= "planilha_usuario_loguin, ";
					  $comando_sql .= "planilha_disciplina, ";
					  $comando_sql .= "planilha_linha, ";
					  $comando_sql .= "planilha_bimestre_descricao, ";
					  $comando_sql .= "planilha_nome_professor, ";
					  $comando_sql .= "planilha_coluna_a_escola, ";
					  $comando_sql .= "planilha_coluna_b_ano, ";
					  $comando_sql .= "planilha_coluna_c_mes, ";
					  $comando_sql .= "planilha_coluna_d_epoca, ";
					  $comando_sql .= "planilha_coluna_e_turma, ";
					  $comando_sql .= "planilha_coluna_f_disciplina, ";
					  $comando_sql .= "planilha_coluna_g_coddisciplina, ";
					  $comando_sql .= "planilha_coluna_h_matricula, ";
					  $comando_sql .= "planilha_coluna_i_aluno, ";
					  $comando_sql .= "planilha_coluna_j_numero, ";
					  $comando_sql .= "planilha_coluna_k_nota, ";
					  $comando_sql .= "planilha_coluna_l_falta, ";
					  $comando_sql .= "planilha_coluna_m_dispensa, ";
					  $comando_sql .= "planilha_coluna_n_aulas_dadas, ";
					  $comando_sql .= "planilha_coluna_o_vazia, ";
					  $comando_sql .= "planilha_coluna_p_dispensa_codigo, ";
					  $comando_sql .= "planilha_coluna_q_dispensa_descricao, ";
					  $comando_sql .= "planilha_status, ";
					  $comando_sql .= "DATE_FORMAT(planilha_data_alteracao,'%d/%m/%Y') AS planilha_data_alteracao, ";
					  $comando_sql .= "planilha_hora_alteracao ";
					  $comando_sql .= "FROM ";
					  $comando_sql .= "planilhas ";
					  $comando_sql .= "WHERE ";
					  $comando_sql .= "planilha_ano = '" . trim($exportacao_ano) ."' AND ";
					  $comando_sql .= "planilha_usuario_loguin = '" . trim($loguin_professor) ."' AND ";
					  $comando_sql .= "planilha_bimestre_sequencial = '" . trim($exportacao_bimestre) ."' ";

					  if(trim($exportacao_ensino) == 'P')
					  {
						  $comando_sql .= "AND SUBSTRING(planilha_coluna_e_turma,2,1) = 'P' ";
					  }

					  $comando_sql .= "ORDER BY "; 
					  $comando_sql .= "planilha_ano DESC, ";
					  $comando_sql .= "planilha_bimestre_sequencial ASC, ";
					  $comando_sql .= "planilha_usuario_loguin ASC, ";
					  $comando_sql .= "planilha_disciplina ASC, ";
					  $comando_sql .= "planilha_linha ASC ";

					  $resultado_sql = mysql_query($comando_sql);

					  if(mysql_num_rows($resultado_sql) != 0)
					  {
						$nome_planilha_anterior = '';
						$primeira_planilha = true;
						$posicao_linha = 1;

						//*** Obtem os Registros dos Professores ***
						$resultado_usuario = mysql_query("SELECT * FROM usuarios WHERE usuario_tipo = 'Professor' AND usuario_loguin = '" . trim($loguin_professor) . "' ORDER BY usuario_nome ASC");

						//*** Inicia o processo para Exportação das Planilhas ***

						for($i = 0; $i < mysql_num_rows($resultado_sql); $i++)
						{
							//if( retira_acentos(trim(mysql_result($resultado_sql,$i,"planilha_disciplina")),0) <> trim($nome_planilha_anterior) )
							if( trim(mysql_result($resultado_sql,$i,"planilha_disciplina")) <> trim($nome_planilha_anterior) )
							{
								if( $primeira_planilha != true )
								{
									//*** Nomeia o Nome da Aba da Planilha ***
									$objPHPExcel->getActiveSheet()->setTitle('Nota');

									//*** Seta a Planilha que será salva ***
									$objPHPExcel->setActiveSheetIndex(0);

									//*** Salva a Planilha na Pasta do Professor ***
							        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
									$objWriter->save($pasta_planilhas . trim($nome_planilha_anterior) . '.xls');

									//*** Adiciona o Arquivo ZIP ***
									$zip->addFile($pasta_planilhas . trim($nome_planilha_anterior) . '.xls');

									$total_planilhas_geradas = $total_planilhas_geradas + 1;
								}

								//*** Guarda o Nome da Planilha Anterior e Seta como Segunda Planilha ***
								$nome_planilha_anterior = trim(mysql_result($resultado_sql,$i,"planilha_disciplina"));

								$primeira_planilha = false;
								$posicao_linha = 1;

								//*** Cria um Novo Objeto PHPExcel ***
								$objPHPExcel = new PHPExcel();

								//*** Seta as Propriedades do Documento ***
								$objPHPExcel->getProperties()->setCreator("Colegio Clovis Bevilacqua")
															 ->setLastModifiedBy("Datatex")
															 ->setTitle("Planilha de Notas")
															 ->setSubject("Notas Bimestrais")
															 ->setDescription("Notas Bimestrais dos Professores.")
															 ->setKeywords("notas")
															 ->setCategory("Notas");

								//*** Adiciona uma Nova Linha ***
								$objPHPExcel->setActiveSheetIndex(0)
											->setCellValue('A1', 'Escola')
											->setCellValue('B1', 'Ano')
											->setCellValue('C1', 'Mes')
											->setCellValue('D1', 'Epoca')
											->setCellValue('E1', 'Turma')
											->setCellValue('F1', 'Disciplina')
											->setCellValue('G1', 'CodDisciplina')
											->setCellValue('H1', 'Matricula')
											->setCellValue('I1', 'Aluno')
											->setCellValue('J1', 'Numero')
											->setCellValue('K1', 'Nota')
											->setCellValue('L1', 'Falta')
											->setCellValue('M1', 'Dispensa')
											->setCellValue('N1', 'Aulas Dadas')
											->setCellValue('O1', '')
											->setCellValue('P1', 'Tabela de Dispensa')
											->setCellValue('Q1', '');
							}

							$posicao_linha = $posicao_linha + 1;

							//*** Gera as Demais Linha da Planilha ***

							if( $posicao_linha == 2 )
							{ 
								$objPHPExcel->setActiveSheetIndex(0)
											->setCellValue('A' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_a_escola"))))
											->setCellValue('B' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_b_ano"))))
											->setCellValue('C' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_c_mes"))))
											->setCellValue('D' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_d_epoca"))))
											->setCellValue('E' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_e_turma"))))
											->setCellValue('F' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_f_disciplina"))))
											->setCellValue('G' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_g_coddisciplina"))))
											->setCellValue('H' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_h_matricula"))))
											->setCellValue('I' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_i_aluno"))))
											->setCellValue('J' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_j_numero"))))
											->setCellValue('K' . trim($posicao_linha), trim(mysql_result($resultado_sql,$i,"planilha_coluna_k_nota")) )
											->setCellValue('L' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_l_falta"))))
											->setCellValue('M' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_m_dispensa"))))
											->setCellValue('N' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_n_aulas_dadas"))))
											->setCellValue('O' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_o_vazia"))))
											->setCellValue('P' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_p_dispensa_codigo"))))
											->setCellValue('Q' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_q_dispensa_descricao"))));

								//*** Formata a Célula como Numérica ***
   						        if( trim(mysql_result($resultado_sql,$i,"planilha_coluna_k_nota")) <> '' )
						        {
									$objPHPExcel->setActiveSheetIndex(0)->getCell('K' . trim($posicao_linha))->setValueExplicit( trim(mysql_result($resultado_sql,$i,"planilha_coluna_k_nota")) , PHPExcel_Cell_DataType::TYPE_NUMERIC);
								    $objPHPExcel->setActiveSheetIndex(0)->getStyle('K' . trim($posicao_linha))->getNumberFormat()->setFormatCode("0.00");
								}
							}
							else
							{
								if( trim(mysql_result($resultado_sql,$i,"planilha_coluna_h_matricula")) <> '' )
								{
									$objPHPExcel->setActiveSheetIndex(0)
												->setCellValue('A' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_a_escola"))))
												->setCellValue('B' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_b_ano"))))
												->setCellValue('C' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_c_mes"))))
												->setCellValue('D' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_d_epoca"))))
												->setCellValue('E' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_e_turma"))))
												->setCellValue('F' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_f_disciplina"))))
												->setCellValue('G' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_g_coddisciplina"))))
												->setCellValue('H' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_h_matricula"))))
												->setCellValue('I' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_i_aluno"))))
												->setCellValue('J' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_j_numero"))))
												->setCellValue('K' . trim($posicao_linha), trim(mysql_result($resultado_sql,$i,"planilha_coluna_k_nota")) )
												->setCellValue('L' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_l_falta"))))
												->setCellValue('M' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_m_dispensa"))))
												->setCellValue('N' . trim($posicao_linha), '')
												->setCellValue('O' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_o_vazia"))))
												->setCellValue('P' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_p_dispensa_codigo"))))
												->setCellValue('Q' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_q_dispensa_descricao"))));

									//*** Formata a Célula como Numérica ***
  								    if( trim(mysql_result($resultado_sql,$i,"planilha_coluna_k_nota")) <> '' )
						            {
										$objPHPExcel->setActiveSheetIndex(0)->getCell('K' . trim($posicao_linha))->setValueExplicit( trim(mysql_result($resultado_sql,$i,"planilha_coluna_k_nota")) , PHPExcel_Cell_DataType::TYPE_NUMERIC);
									    $objPHPExcel->setActiveSheetIndex(0)->getStyle('K' . trim($posicao_linha))->getNumberFormat()->setFormatCode("0.00");
									}
								}
								else
								{
									$objPHPExcel->setActiveSheetIndex(0)
												->setCellValue('A' . trim($posicao_linha), '')
												->setCellValue('B' . trim($posicao_linha), '')
												->setCellValue('C' . trim($posicao_linha), '')
												->setCellValue('D' . trim($posicao_linha), '')
												->setCellValue('E' . trim($posicao_linha), '')
												->setCellValue('F' . trim($posicao_linha), '')
												->setCellValue('G' . trim($posicao_linha), '')
												->setCellValue('H' . trim($posicao_linha), '')
												->setCellValue('I' . trim($posicao_linha), '')
												->setCellValue('J' . trim($posicao_linha), '')
												->setCellValue('K' . trim($posicao_linha), '')
												->setCellValue('L' . trim($posicao_linha), '')
												->setCellValue('M' . trim($posicao_linha), '')
												->setCellValue('N' . trim($posicao_linha), '')
												->setCellValue('O' . trim($posicao_linha), '')
												->setCellValue('P' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_p_dispensa_codigo"))))
												->setCellValue('Q' . trim($posicao_linha), utf8_encode(trim(mysql_result($resultado_sql,$i,"planilha_coluna_q_dispensa_descricao"))));
								}
							}
						}

					    //*** Nomeia o Nome da Aba da Planilha ***
					    $objPHPExcel->getActiveSheet()->setTitle('Nota');

					    //*** Seta a Planilha que será salva ***
					    $objPHPExcel->setActiveSheetIndex(0);

					    //*** Salva a Planilha na Pasta do Professor ***
						$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
					    $objWriter->save($pasta_planilhas . trim($nome_planilha_anterior) . '.xls');

					    //*** Adiciona o Arquivo ZIP ***
					    $zip->addFile($pasta_planilhas . trim($nome_planilha_anterior) . '.xls');

						$total_planilhas_geradas = $total_planilhas_geradas + 1;
					  }
					}

					$zip->close();
				}
				else
				{
					$msg_erro = "Nenhum Professor foi Localizado.";
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

		<script type="text/javascript" src="funcoes/carregando.js"></script>
</head>

<body onload="carregado()">

   <div id="fundo">

       <fieldset style="width: 748px; height: 462px;">
           <legend>Exportar as Planilhas de Notas</legend>

		   <div id="exibe_carregando"><img src="imagens/carregando.gif" width="105" height="16" border="0" alt=""></div>
            
           <div id="conteudo_exibicao" style="display: none;">

		   <!-- Formulário para a Exportação de Notas -->

            <form id="exportacao" name="exportacao" enctype="multipart/form-data" action="exportar_planilhas_notas.php" method="post" accept-charset="utf-8">

			  <p><?php echo '<center><font color="#FF0000"><b>' . $msg_erro . '</b></font></center>'; ?></p>

			  <p>
              <label for="exportacao_ano" id="fonte_fundo">Ano</label>
              <input name="exportacao_ano" type="text" size="30" readonly value="<?php echo date("Y",time()); ?>" style="background-color: #EBE9ED; width: 35px;" onkeypress="return EnterToTab(this,event);"/>
			  </p>

			  <p>
              <label for="exportacao_bimestre" id="fonte_fundo">Bimestre</label>
			  <select name="exportacao_bimestre" size="1" style="width: 350px;" onkeypress="return EnterToTab(this,event);">
			  <option value="0">--- Selecione um Bimestre ---</option>

			  <?php
					//*** Obtem os Registros dos Bimestres ***
					$resultado_sql = mysql_query("SELECT * FROM bimestres ORDER BY bimestre_sequencial ASC");

					for($indice = 0; $indice < mysql_num_rows($resultado_sql); $indice++)
					{
						if( $exportacao_bimestre == mysql_result($resultado_sql,$indice,"bimestre_sequencial") )
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
              <label for="exportacao_ensino" id="fonte_fundo">Tipo de Ensino</label>
			  <select name="exportacao_ensino" size="1" style="width: 250px;" onkeypress="return EnterToTab(this,event);" onchange="this.form.submit();">
			  <option value="0">--- Selecione um Tipo de Ensino ---</option>
			  <?php
					if( $exportacao_ensino == "F" )
					{
					    echo '<option value="F" selected>Fundamental</option>';
					}
					else
					{
						echo '<option value="F">Fundamental</option>';
					}

					if( $exportacao_ensino == "M" )
					{
					    echo '<option value="M" selected>Médio/Técnico</option>';
					}
					else
					{
						echo '<option value="M">Médio/Técnico</option>';
					}

					if( $exportacao_ensino == "P" )
					{
					    echo '<option value="P" selected>Pós-Médio</option>';
					}
					else
					{
						echo '<option value="P">Pós-Médio</option>';
					}

					if( $exportacao_ensino == "O" )
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
              <label for="exportacao_professor" id="fonte_fundo">Professor</label>
			  <select name="exportacao_professor" size="1" style="width: 550px;" onkeypress="return EnterToTab(this,event);">
				  <option value="0">--- Selecione um Professor ---</option>

			  <?php
			       if( (trim($exportacao_ensino) <> '') and (trim($exportacao_ensino) <> '0') )
				   {
					    //*** Obtem os Registros dos Professores ***
					    $comando_sql = "SELECT * FROM usuarios WHERE usuario_tipo = 'Professor' ";

					    if( $exportacao_ensino <> "O" )
					    {
						   if( $exportacao_ensino == "F" )
					       {
							    $comando_sql .= "AND usuario_ensino_fundamental = 'S' ";
						   }

						   if( $exportacao_ensino == "M" )
						   {
							    $comando_sql .= "AND usuario_ensino_medio_tecnico = 'S' ";
						   }

						   if( $exportacao_ensino == "P" )
						   {
							    $comando_sql .= "AND usuario_ensino_pos = 'S' ";
						   }
					    }

					    $comando_sql .= "ORDER BY usuario_nome ASC";

					    $resultado_sql = mysql_query($comando_sql);

						if( $exportacao_professor == 'todos' )
						{
						  echo '<option value="todos" selected>- Todos os Professores -</option>';
						}
						else
						{
						  echo '<option value="todos">- Todos os Professores -</option>';
						}				  

						for($indice = 0; $indice < mysql_num_rows($resultado_sql); $indice++)
						{
							if( $exportacao_professor == mysql_result($resultado_sql,$indice,"usuario_loguin") )
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
              <center>
              <input name="btExportar" type="submit" onclick="carregando()" value="Exportar as Planilhas do Professor" />
              </center>
			  </p>

			  <?php			    
			   	//*** Efetua a Busca das Planilhas Exportadas ***

				if( trim($btExportar) == "Exportar as Planilhas do Professor" )
				{
					if( trim($exportacao_professor) <> 'todos')
					{
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
						$comando_sql .= "planilha_ano = '" . trim($exportacao_ano) ."' AND ";
						$comando_sql .= "planilha_usuario_loguin = '" . trim($exportacao_professor) ."' AND ";
						$comando_sql .= "planilha_bimestre_sequencial = '" . trim($exportacao_bimestre) ."' ";
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
							echo '<b>Aquivo Zipado das Planilhas: </b><a href="' . trim($pasta_zipada) . '" target="_blank">' . trim($pasta_zipada_link) . '</a><BR><BR>';

							echo '<b>Planilhas Individuais:</b>';
							echo '<center>';
							echo '<table width="740" border="1" cellspacing="0" cellpadding="2" bordercolor="#000000">';
							echo '<tr>';
							echo '<th scope="col" bgcolor="#F6B446"><font color="#FFFFFF">Professor</font></th>';
							echo '<th scope="col" bgcolor="#F6B446"><font color="#FFFFFF">Bimestre</font></th>';
							echo '<th scope="col" bgcolor="#F6B446"><font color="#FFFFFF">Turma/Disciplina</font></th>';
							echo '<th scope="col" bgcolor="#F6B446"><font color="#FFFFFF">Status</font></th>';
							echo '<th scope="col" bgcolor="#F6B446"><font color="#FFFFFF">Data</font></th>';
							echo '<th scope="col" bgcolor="#F6B446"><font color="#FFFFFF">Hora</font></th>';					
							echo '</tr>';
					
							for($i = 0; $i < mysql_num_rows($resultado_sql); $i++)
							{
								echo '<tr>';
								echo '<td bgcolor="#F8F8F8" width="215">' . mysql_result($resultado_sql,$i,"planilha_nome_professor") . '</td>';
								echo '<td bgcolor="#F8F8F8" width="60">' . mysql_result($resultado_sql,$i,"planilha_bimestre_descricao") . '</td>';
								echo '<td bgcolor="#F8F8F8" width="250">';
								//echo '<a href="' . trim($pasta_planilhas_link) . retira_acentos(trim(mysql_result($resultado_sql,$i,"planilha_disciplina")),0) . '.xls" target="_blank">';
								echo '<a href="' . trim($pasta_planilhas_link) . trim(mysql_result($resultado_sql,$i,"planilha_disciplina")) . '.xls" target="_blank">';
								echo mysql_result($resultado_sql,$i,"planilha_disciplina");
								echo '</a>';
								echo '</td>';

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
							echo '<BR><BR>&nbsp;';
						}
						else
						{
							echo '<BR><BR><BR><BR><center><font color="#FF0000"><b>Nenhuma Planilha de Notas foi Localizada!</b></font></center>';
						}
					}
					else
					{
						if( trim($pasta_zipada) <> "" )
						{
						  echo '<b>Total de Planilhas Geradas: </b>' . trim($total_planilhas_geradas) . '<br>';
						  echo '<b>Aquivo Zipado de Todas as Planilhas: </b><a href="' . trim($pasta_zipada) . '" target="_blank">' . trim($pasta_zipada_link) . '</a><BR><BR>';
						}
					}
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
