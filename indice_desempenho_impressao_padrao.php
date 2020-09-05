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

//*** FPDF ***
require_once("FPDF/fpdf.php");

class PDF extends FPDF
{
   //*************************************
   //*** Seta as Definições Principais ***
   //*************************************

   var $nome_relatorio;
   var $pdf_ano;
   var $pdf_turma;
   var $pdf_coddisciplina;

   function PDF($or = 'P')
   {
      $this->FPDF($or);
   }

   function SetName($nomerel,$ano,$turma,$coddisciplina)
   {
      $this->nome_relatorio = $nomerel;
      $this->pdf_ano = $ano;
      $this->pdf_turma = $turma;
	  $this->pdf_coddisciplina = $coddisciplina;
   }

   //***************************************
   //*** INICIO - Cabeçalho do Relatório ***
   //***************************************

   function Header()
   {
      //*** Obtém o Nome da Disciplina ***
      $resultado_sql = mysql_query("SELECT * FROM planilhas WHERE planilha_coluna_e_turma = '" . trim($this->pdf_turma) . "' AND planilha_coluna_g_coddisciplina = '" . trim($this->pdf_coddisciplina) . "' ORDER BY planilha_coluna_f_disciplina ASC");
      $nome_disciplina = trim(mysql_result($resultado_sql,0,"planilha_coluna_f_disciplina"));

      //*** Prepara o Cabeçalho do Relatório ***
      $borda = 0;// 0-Célula Sem Borda | 1-Célula Com Borda
      $this->AliasNbPages();

      $this->SetFont('Arial', '', 6);

      if(file_exists('imagens/logo_clovis_pb.jpg'))
      {  
		 $this->Image('imagens/logo_clovis_pb.jpg', 10, 5, 40, 9, 'jpeg');
      }
      else
      {
         $this->Cell(25, 1, 'Colégio Clóvis Bevilacqua', $borda, 0);
      }

      $this->SetX(212);
      $this->Cell(25, 1, "Data: " . date("d/m/Y", time()), $borda, 0);
      $this->Cell(25, 1, "Hora: " . date("H:i:s", time()), $borda, 0);
      $this->Cell(25, 1, "Página: " . $this->PageNo() . "/{nb}", $borda, 1);

      //*** Título do Relatório ***
      $this->SetFont('Arial', 'B', 8);
      $this->Cell(287, 10, $this->nome_relatorio, $borda, 1, 'C');

      $this->SetFont('Arial', 'B', 8);
	  $this->Cell(8, 4, 'Ano: ', $borda, 0, 'L');
	  $this->SetFont('Arial', '', 8);
	  $this->Cell(192, 4, $this->pdf_ano, $borda, 1, 'L');

      $this->SetFont('Arial', 'B', 8);
	  $this->Cell(22, 4, 'Cód.Disciplina: ', $borda, 0, 'L');
	  $this->SetFont('Arial', '', 8);
	  $this->Cell(178, 4, $this->pdf_coddisciplina, $borda, 1, 'L');

      $this->SetFont('Arial', 'B', 8);
	  $this->Cell(25, 4, 'Turma/Disciplina: ', $borda, 0, 'L');
	  $this->SetFont('Arial', '', 8);
	  $this->Cell(175, 4, trim($this->pdf_turma) . ' - ' . trim($nome_disciplina), $borda, 1, 'L');

	  $this->Cell(175, 4, '', $borda, 1, 'L');
	  $this->Cell(200, 4, 'Observação: O Status "Reprovado" se dará caso o aluno não tenha atingido a média necessária ou se a porcentagem de faltas ultrapassar o limite de 25% das aulas dadas.', $borda, 1, 'L');
	  $this->Cell(287, 4, '', $borda, 1, 'L');

      //*** Título dos Campos do Relatório ***

	  //*** Títulos dos Bimestres ***
      $this->SetFont('Arial', 'BI', 8);
      $this->Cell(89, 5, '', $borda, 0, 'L');

      $borda = 1;

      //*** 1o. Bimestre ***
      $this->Cell(22, 5, '1o. Bim.', $borda, 0, 'C');

      //*** 2o. Bimestre ***
      $this->Cell(22, 5, '2o. Bim.', $borda, 0, 'C');

      //*** 3o. Bimestre ***
      $this->Cell(22, 5, '3o. Bim.', $borda, 0, 'C');

      //*** 4o. Bimestre ***
      $this->Cell(14, 5, '4o. Bim.', $borda, 0, 'C');

      //*** Avaliação Conceitual ***
      $this->Cell(14, 5, 'A.C.', $borda, 0, 'C');

      //*** Conselho Final ***
      $this->Cell(8, 5, '', 0, 0, 'C');

      //*** Total ***
      $this->Cell(14, 5, 'Total', $borda, 0, 'C');

      $borda = 0;

      //*** Peso Mínimo Aprovação ***
      $this->Cell(20, 5, 'Peso Mínimo', $borda, 0, 'R');
	  
	  //*** Diferença Faltante ***
      $this->Cell(15, 5, 'Diferença', $borda, 0, 'R');

	  //*** Diferença Faltante ***
      $this->Cell(20, 5, 'Porcentagem', $borda, 0, 'R');

      //*** Total ***
      $this->Cell(17, 5, 'Status da', $borda, 0, 'R');

      //*** Pula Linha ***
      $this->Cell(1, 5, '', $borda, 1, 'R');

      $borda = 0;

      //*** Detalhe dos Bimestres ***
      $this->SetFont('Arial', 'BI', 8);
      $this->Cell(15, 5, 'Matrícula', $borda, 0, 'L');
      $this->Cell(67, 5, 'Nome do Aluno', $borda, 0, 'L');
      $this->Cell(7, 5, 'Nro.', $borda, 0, 'R');

      $borda = 1;

      //*** 1o. Bimestre ***
      $this->Cell(8, 5, 'Nota', $borda, 0, 'C');
      $this->Cell(6, 5, 'F', $borda, 0, 'C');
	  $this->Cell(8, 5, 'R', $borda, 0, 'C');

      //*** 2o. Bimestre ***
      $this->Cell(8, 5, 'Nota', $borda, 0, 'C');
      $this->Cell(6, 5, 'F', $borda, 0, 'C');
	  $this->Cell(8, 5, 'R', $borda, 0, 'C');

      //*** 3o. Bimestre ***
      $this->Cell(8, 5, 'Nota', $borda, 0, 'C');
      $this->Cell(6, 5, 'F', $borda, 0, 'C');
	  $this->Cell(8, 5, 'R', $borda, 0, 'C');

      //*** 4o. Bimestre ***
      $this->Cell(8, 5, 'Nota', $borda, 0, 'C');
      $this->Cell(6, 5, 'F', $borda, 0, 'C');

      //*** Avaliação Conceitual ***
      $this->Cell(8, 5, 'Nota', $borda, 0, 'C');
      $this->Cell(6, 5, 'F', $borda, 0, 'C');

      //*** Conselho Final ***
      $this->Cell(8, 5, 'CF', $borda, 0, 'C');

      //*** Total ***
      $this->Cell(8, 5, 'Nota', $borda, 0, 'C');
      $this->Cell(6, 5, 'F', $borda, 0, 'C');

      $borda = 0;

      //*** Peso Mínimo Aprovação ***
      $this->Cell(20, 5, 'Aprovação', $borda, 0, 'R');
	  
	  //*** Diferença Faltante ***
      $this->Cell(15, 5, 'Faltante', $borda, 0, 'R');

	  //*** Diferença Faltante ***
      $this->Cell(20, 5, 'de Faltas', $borda, 0, 'R');

      //*** Total ***
      $this->Cell(17, 5, 'Previsão', $borda, 0, 'R');

      //*** Pula Linha ***
      $this->Cell(1, 5, '', $borda, 1, 'R');

      $this->line(10, $this->GetY(), 287, $this->GetY());// Desenha uma linha
   }

   //***************************************
   //*** FINAL - Cabeçalho do Relatório  ***
   //***************************************

   //************************************
   //*** INICIO - Rodapé do Relatório ***
   //************************************

   function Footer()
   {
      $this->SetXY( - 10, - 5);
      $this->line(10, $this->GetY() - 2, $this->GetX(), $this->GetY() - 2);
      $this->SetX(0);
      $this->SetFont('Arial', 'I', 6);
      $this->Cell(287, 1, "Sistema de Lançamento de Notas On-Line (V 1.00) - Colégio Clóvis Bevilacqua", $borda, 0, 'R');
   }

   //************************************
   //*** FINAL - Rodapé do Relatório  ***
   //************************************
}

function PDF_Detalhe()
{
   //*** Recupera os Valores para Efetuar a Geração do Relatório ***
   $planilha_usuario_loguin      = trim($_SESSION['identificacao']['usuario_loguin']);

   $planilha_ano                 = $_GET["planilha_ano"];		
   $planilha_turma				 = $_GET["planilha_turma"];
   $planilha_disciplina          = $_GET["planilha_disciplina"];

   if( strtoupper(substr($planilha_turma,1,1)) == 'P' )
   {
	   $peso_minimo_aprovacao = 14;
   }
   else
   {
	   $peso_minimo_aprovacao = 28;
   }

   //*** Detalhe dos Itens ***

   $total_alunos = 0;

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
   $comando_sql .= "planilha_coluna_e_turma = '" . trim($planilha_turma) . "' AND ";
   $comando_sql .= "planilha_coluna_g_coddisciplina = '" . trim($planilha_disciplina) . "' AND ";
   $comando_sql .= "(TRIM(planilha_coluna_h_matricula) <> '' AND planilha_coluna_h_matricula IS NOT NULL) AND ";
   $comando_sql .= "(TRIM(planilha_coluna_i_aluno) <> '' AND planilha_coluna_i_aluno IS NOT NULL) ";

   $comando_sql .= "ORDER BY "; 
   $comando_sql .= "planilha_ano DESC, ";
   $comando_sql .= "planilha_coluna_e_turma ASC, "; 
   $comando_sql .= "planilha_coluna_f_disciplina ASC, ";
   $comando_sql .= "planilha_coluna_h_matricula ASC, ";
   $comando_sql .= "planilha_bimestre_sequencial ASC ";   
	
   $resultado_sql = mysql_query($comando_sql);

   if(mysql_num_rows($resultado_sql) != 0)
   {
	 //*** Nome do PDF para Download ***
	 $nome_pdf = retira_acentos(trim(mysql_result($resultado_sql,0,"planilha_disciplina")),0) . '.pdf';

     //*** Geração do Relatório ***
     //$pdf = new PDF('P');// relatório em orientação "Paisagem"
	 $pdf = new PDF('L');// relatório em orientação "Landscape"

     $pdf->SetName("(ENSINO FUNDAMENTAL [28 PONTOS]) - ÍNDICE DE DESEMPENHO DO ALUNO",trim($planilha_ano),trim($planilha_turma),trim($planilha_disciplina));

     $pdf->Open();
     $pdf->AddPage();
     $pdf->SetFont('Arial', '', 8);

	 $cgm_anterior  = mysql_result($resultado_sql,0,"planilha_coluna_h_matricula");
	 $nome_impresso = false;
	 $total_alunos  = 0;

     //*** Inicializa o Array do Registro de Alunos ***
     for($i = 0; $i < 100; $i++)
     {
		 $registro_aluno = array($i => array('Matricula' => '', 'Nome' => '', 'Nro' => 0, 'Nota_1B' => 0, 'F_1B' => 0, 'R_1B' => 0, 'Nota_2B' => 0, 'F_2B' => 0, 'R_2B' => 0, 'Nota_3B' => 0, 'F_3B' => 0, 'R_3B' => 0, 'Nota_4B' => 0, 'F_4B' => 0, 'AC_4B' => 0, 'ACF_4B' => 0, 'CF' => 0, 'Nota_T' => 0, 'F_T' => 0, 'Faltante' => 0, 'Porcentagem' => 0, 'Previsao' => 0, 'Dada_1B' => 0, 'Dada_2B' => 0, 'Dada_3B' => 0, 'Dada_4B' => 0, 'Dada_AC' => 0));
		 $ordem_numero = array($i => 0);
	 }

	 //*** Carrega os Valores do Registro de Alunos ***
     for($i = 0; $i < mysql_num_rows($resultado_sql); $i++)
     {
		 if( mysql_result($resultado_sql,$i,"planilha_coluna_h_matricula") <> $cgm_anterior )
		 {
			 $registro_aluno[$total_alunos]['Nota_T'] = ($registro_aluno[$total_alunos]['Nota_1B'] + $registro_aluno[$total_alunos]['R_1B'] + $registro_aluno[$total_alunos]['Nota_2B'] + $registro_aluno[$total_alunos]['R_2B'] + $registro_aluno[$total_alunos]['Nota_3B'] + $registro_aluno[$total_alunos]['R_3B'] + $registro_aluno[$total_alunos]['Nota_4B'] + $registro_aluno[$total_alunos]['AC_4B'] + $registro_aluno[$total_alunos]['CF']);
             $registro_aluno[$total_alunos]['F_T'] = ($registro_aluno[$total_alunos]['F_1B'] + $registro_aluno[$total_alunos]['F_2B'] + $registro_aluno[$total_alunos]['F_3B'] + $registro_aluno[$total_alunos]['F_4B'] + $registro_aluno[$total_alunos]['ACF_4B']);

             if( ($peso_minimo_aprovacao - $registro_aluno[$total_alunos]['Nota_T']) > 0 )
			 {
				$registro_aluno[$total_alunos]['Faltante'] = ($peso_minimo_aprovacao - $registro_aluno[$total_alunos]['Nota_T']);
			 }
			 else
		 	 {
				$registro_aluno[$total_alunos]['Faltante'] = 0;
			 }
             
			 if( $registro_aluno[$total_alunos]['Nota_T'] < $peso_minimo_aprovacao )
			 {
				 $registro_aluno[$total_alunos]['Previsao'] = 'Reprovado';
			 }
			 else
			 {
				 $registro_aluno[$total_alunos]['Previsao'] = 'Aprovado';
			 }

			 if( ($registro_aluno[0]['Dada_1B'] + $registro_aluno[0]['Dada_2B'] + $registro_aluno[0]['Dada_3B'] + $registro_aluno[0]['Dada_4B'] + $registro_aluno[0]['Dada_AC']) <= 0 )
			 {
			   if( round((($registro_aluno[$total_alunos]['F_T'] * 100) / 1),0) > 25 )
			   {
				 $registro_aluno[$total_alunos]['Previsao'] = 'Reprovado';
			   }
			 }
			 else
			 {
 			   if( round((($registro_aluno[$total_alunos]['F_T'] * 100) / ($registro_aluno[0]['Dada_1B'] + $registro_aluno[0]['Dada_2B'] + $registro_aluno[0]['Dada_3B'] + $registro_aluno[0]['Dada_4B'] + $registro_aluno[0]['Dada_AC'])),0) > 25 )
			   {
				 $registro_aluno[$total_alunos]['Previsao'] = 'Reprovado';
			   }
			 }

   	         $cgm_anterior = mysql_result($resultado_sql,$i,"planilha_coluna_h_matricula");
			 $nome_impresso = false;

	         $total_alunos = $total_alunos + 1;
		 }

		 if( $nome_impresso == false )
		 {
			 $registro_aluno[$total_alunos]['Matricula'] = mysql_result($resultado_sql,$i,"planilha_coluna_h_matricula");
			 $registro_aluno[$total_alunos]['Nome'] = mysql_result($resultado_sql,$i,"planilha_coluna_i_aluno");
			 $registro_aluno[$total_alunos]['Nro'] = mysql_result($resultado_sql,$i,"planilha_coluna_j_numero");

			 $ordem_numero[$total_alunos] = mysql_result($resultado_sql,$i,"planilha_coluna_j_numero");

			 $nome_impresso = true;
		 }

		 if( mysql_result($resultado_sql,$i,"planilha_bimestre_sequencial") == 1 )
		 {
			 $registro_aluno[$total_alunos]['Nota_1B'] = mysql_result($resultado_sql,$i,"planilha_coluna_k_nota");
			 $registro_aluno[$total_alunos]['F_1B'] = mysql_result($resultado_sql,$i,"planilha_coluna_l_falta");
			 $registro_aluno[$total_alunos]['Dada_1B'] = mysql_result($resultado_sql,$i,"planilha_coluna_n_aulas_dadas");
		 }
		
		 if( mysql_result($resultado_sql,$i,"planilha_bimestre_sequencial") == 2 )
		 {
			 $registro_aluno[$total_alunos]['R_1B'] = mysql_result($resultado_sql,$i,"planilha_coluna_k_nota");
		 }

		 if( mysql_result($resultado_sql,$i,"planilha_bimestre_sequencial") == 3 )
		 {
			 $registro_aluno[$total_alunos]['Nota_2B'] = mysql_result($resultado_sql,$i,"planilha_coluna_k_nota");
			 $registro_aluno[$total_alunos]['F_2B'] = mysql_result($resultado_sql,$i,"planilha_coluna_l_falta");
			 $registro_aluno[$total_alunos]['Dada_2B'] = mysql_result($resultado_sql,$i,"planilha_coluna_n_aulas_dadas");
		 }

		 if( mysql_result($resultado_sql,$i,"planilha_bimestre_sequencial") == 4 )
		 {
			 $registro_aluno[$total_alunos]['R_2B'] = mysql_result($resultado_sql,$i,"planilha_coluna_k_nota");
		 }

		 if( mysql_result($resultado_sql,$i,"planilha_bimestre_sequencial") == 5 )
		 {
			 $registro_aluno[$total_alunos]['Nota_3B'] = mysql_result($resultado_sql,$i,"planilha_coluna_k_nota");
			 $registro_aluno[$total_alunos]['F_3B'] = mysql_result($resultado_sql,$i,"planilha_coluna_l_falta");
			 $registro_aluno[$total_alunos]['Dada_3B'] = mysql_result($resultado_sql,$i,"planilha_coluna_n_aulas_dadas");
		 }

		 if( mysql_result($resultado_sql,$i,"planilha_bimestre_sequencial") == 6 )
		 {
			 $registro_aluno[$total_alunos]['R_3B'] = mysql_result($resultado_sql,$i,"planilha_coluna_k_nota");
		 }

		 if( mysql_result($resultado_sql,$i,"planilha_bimestre_sequencial") == 7 )
		 {
			 $registro_aluno[$total_alunos]['Nota_4B'] = mysql_result($resultado_sql,$i,"planilha_coluna_k_nota");
			 $registro_aluno[$total_alunos]['F_4B'] = mysql_result($resultado_sql,$i,"planilha_coluna_l_falta");
			 $registro_aluno[$total_alunos]['Dada_4B'] = mysql_result($resultado_sql,$i,"planilha_coluna_n_aulas_dadas");
		 }

		 if( mysql_result($resultado_sql,$i,"planilha_bimestre_sequencial") == 8 )
		 {
			 $registro_aluno[$total_alunos]['AC_4B'] = mysql_result($resultado_sql,$i,"planilha_coluna_k_nota");
			 $registro_aluno[$total_alunos]['ACF_4B'] = mysql_result($resultado_sql,$i,"planilha_coluna_l_falta");
			 $registro_aluno[$total_alunos]['Dada_AC'] = mysql_result($resultado_sql,$i,"planilha_coluna_n_aulas_dadas");
		 }

		 if( mysql_result($resultado_sql,$i,"planilha_bimestre_sequencial") == 9 )
		 {
			 $registro_aluno[$total_alunos]['CF'] = mysql_result($resultado_sql,$i,"planilha_coluna_k_nota");		 
		 }
     } 
	 
	 $registro_aluno[$total_alunos]['Nota_T'] = ($registro_aluno[$total_alunos]['Nota_1B'] + $registro_aluno[$total_alunos]['R_1B'] + $registro_aluno[$total_alunos]['Nota_2B'] + $registro_aluno[$total_alunos]['R_2B'] + $registro_aluno[$total_alunos]['Nota_3B'] + $registro_aluno[$total_alunos]['R_3B'] + $registro_aluno[$total_alunos]['Nota_4B'] + $registro_aluno[$total_alunos]['AC_4B'] + $registro_aluno[$total_alunos]['CF']);
     $registro_aluno[$total_alunos]['F_T'] = ($registro_aluno[$total_alunos]['F_1B'] + $registro_aluno[$total_alunos]['F_2B'] + $registro_aluno[$total_alunos]['F_3B'] + $registro_aluno[$total_alunos]['F_4B'] + $registro_aluno[$total_alunos]['ACF_4B']);

     if( ($peso_minimo_aprovacao - $registro_aluno[$total_alunos]['Nota_T']) > 0 )
	 {
		$registro_aluno[$total_alunos]['Faltante'] = ($peso_minimo_aprovacao - $registro_aluno[$total_alunos]['Nota_T']);
	 }
	 else
	 {
		$registro_aluno[$total_alunos]['Faltante'] = 0;
	 }

	 if( $registro_aluno[$total_alunos]['Nota_T'] < $peso_minimo_aprovacao )
	 {
		 $registro_aluno[$total_alunos]['Previsao'] = 'Reprovado';
	 }
	 else
	 {
		 $registro_aluno[$total_alunos]['Previsao'] = 'Aprovado';
	 }

	 if( ($registro_aluno[0]['Dada_1B'] + $registro_aluno[0]['Dada_2B'] + $registro_aluno[0]['Dada_3B'] + $registro_aluno[0]['Dada_4B'] + $registro_aluno[0]['Dada_AC']) <= 0 )
	 {
       if( round((($registro_aluno[$total_alunos]['F_T'] * 100) / 1),0) > 25 )
	   {
		 $registro_aluno[$total_alunos]['Previsao'] = 'Reprovado';
	   }
	 }
	 else
	 {
       if( round((($registro_aluno[$total_alunos]['F_T'] * 100) / ($registro_aluno[0]['Dada_1B'] + $registro_aluno[0]['Dada_2B'] + $registro_aluno[0]['Dada_3B'] + $registro_aluno[0]['Dada_4B'] + $registro_aluno[0]['Dada_AC'])),0) > 25 )
	   {
		 $registro_aluno[$total_alunos]['Previsao'] = 'Reprovado';
	   }
	 }

     $total_alunos = $total_alunos + 1;

	 //*** Ordena o Vetor pelo Número de Chamada ***
	 sort($ordem_numero, SORT_NUMERIC);

	 //*** Gera os PDFs com os Valores Carregados ***
     for($ind = 0; $ind <= $total_alunos; $ind++)
     {
		 //*** Ordena os Alunos por Número no vetor de Exibição ***		 
		 $encontrou_indice = false;
		 $i = 0;

		 while($encontrou_indice == false)
		 {
			 if($ordem_numero[$ind] == $registro_aluno[$i]['Nro'])
			 {
				 $encontrou_indice = true;
			 }
			 else
			 {
				 $i = $i + 1;
			 }
		 } 

		 if( trim($registro_aluno[$i]['Nro']) <> '' )
		 {
			 //*** Exibe os Alunos Ordenados ***
			 $pdf->Cell(15, 5, $registro_aluno[$i]['Matricula'], $borda, 0, 'L');
			 $eixo_x = $pdf->GetX();
			 $eixo_y = $pdf->GetY();
			 $pdf->MultiCell(67, 5, trim($registro_aluno[$i]['Nome']), $borda, 'L');
			 $eixo_y_2 = $pdf->GetY();
			 $pdf->SetXY($eixo_x + 67, $eixo_y);

			 $pdf->Cell(7, 5, $registro_aluno[$i]['Nro'], $borda, 0, 'R');
			 $pdf->Cell(8, 5, $registro_aluno[$i]['Nota_1B'], $borda, 0, 'R');
			 $pdf->Cell(6, 5, $registro_aluno[$i]['F_1B'], $borda, 0, 'R');

			 $pdf->Cell(8, 5, $registro_aluno[$i]['R_1B'], $borda, 0, 'R');

			 $pdf->Cell(8, 5, $registro_aluno[$i]['Nota_2B'], $borda, 0, 'R');
			 $pdf->Cell(6, 5, $registro_aluno[$i]['F_2B'], $borda, 0, 'R');

			 $pdf->Cell(8, 5, $registro_aluno[$i]['R_2B'], $borda, 0, 'R');

			 $pdf->Cell(8, 5, $registro_aluno[$i]['Nota_3B'], $borda, 0, 'R');
			 $pdf->Cell(6, 5, $registro_aluno[$i]['F_3B'], $borda, 0, 'R');

			 $pdf->Cell(8, 5, $registro_aluno[$i]['R_3B'], $borda, 0, 'R');

			 $pdf->Cell(8, 5, $registro_aluno[$i]['Nota_4B'], $borda, 0, 'R');
			 $pdf->Cell(6, 5, $registro_aluno[$i]['F_4B'], $borda, 0, 'R');

			 $pdf->Cell(8, 5, $registro_aluno[$i]['AC_4B'], $borda, 0, 'R');
			 $pdf->Cell(6, 5, $registro_aluno[$i]['ACF_4B'], $borda, 0, 'R');

			 $pdf->Cell(8, 5, $registro_aluno[$i]['CF'], $borda, 0, 'R');

			 $pdf->Cell(8, 5, $registro_aluno[$i]['Nota_T'], $borda, 0, 'R');
			 $pdf->Cell(6, 5, $registro_aluno[$i]['F_T'], $borda, 0, 'R');

			 $pdf->Cell(20, 5, $peso_minimo_aprovacao, $borda, 0, 'C');
			 $pdf->Cell(15, 5, $registro_aluno[$i]['Faltante'], $borda, 0, 'R');

			 if( ($registro_aluno[0]['Dada_1B'] + $registro_aluno[0]['Dada_2B'] + $registro_aluno[0]['Dada_3B'] + $registro_aluno[0]['Dada_4B'] + $registro_aluno[0]['Dada_AC']) <= 0 )
			 {
			   $pdf->Cell(20, 5, round((($registro_aluno[$i]['F_T'] * 100) / 1),0) . '%', $borda, 0, 'R');
			 }
			 else
			 {
			   $pdf->Cell(20, 5, round((($registro_aluno[$i]['F_T'] * 100) / ($registro_aluno[0]['Dada_1B'] + $registro_aluno[0]['Dada_2B'] + $registro_aluno[0]['Dada_3B'] + $registro_aluno[0]['Dada_4B'] + $registro_aluno[0]['Dada_AC'])),0) . '%', $borda, 0, 'R');
			 }

			 $pdf->Cell(17, 5, $registro_aluno[$i]['Previsao'], $borda, 0, 'R');

			 //*** Pula Linha ***
			 $pdf->Cell(1, 5, '', $borda, 1, 'R');

			 $pdf->SetY($eixo_y_2);

			 $pdf->line(10, $pdf->GetY(), 287, $pdf->GetY());// Desenha uma linha
		 }
     }  

     //*** Total de Alunos e Aulas Dadas ***

     $total_aulas_dadas = ($registro_aluno[0]['Dada_1B'] + $registro_aluno[0]['Dada_2B'] + $registro_aluno[0]['Dada_3B'] + $registro_aluno[0]['Dada_4B'] + $registro_aluno[0]['Dada_AC']);

     $pdf->line(10, $pdf->GetY(), 287, $pdf->GetY());// Desenha uma linha

     $pdf->SetFont('Arial', 'B', 8);
     $pdf->Cell(39, 5, 'Total de Alunos:', $borda, 0, 'R');
     $pdf->Cell(8, 5, $total_alunos, $borda, 0, 'R');

     $pdf->Cell(204, 5, 'Total de Aulas Dadas:', $borda, 0, 'R');
     $pdf->Cell(8, 5, $total_aulas_dadas, $borda, 1, 'R');

     $pdf->line(10, $pdf->GetY(), 287, $pdf->GetY());// Desenha uma linha

     //*** Apaga o PDF existente ***
     if(file_exists('INDICE_' . trim($nome_pdf)))
     {
       unlink('INDICE_' . trim($nome_pdf));
     }

     $pdf->Output('INDICE_' . trim($nome_pdf),'I');
   }
   else
   {
		echo '<br><br><br><br><br><center><font color="#FF0000"><b>Ocorreu um erro de Processamento, por favor, comunique o Suporte do Sistema.</b></font></center>';
   }
}

PDF_Detalhe();

?>