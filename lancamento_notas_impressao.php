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
   var $pdf_bimestre;
   var $pdf_coddisciplina;
   var $pdf_turma;
   var $pdf_aulasdadas;
   var $pdf_data;
   var $pdf_hora;

   function PDF($or = 'P')
   {
      $this->FPDF($or);
   }

   function SetName($nomerel,$ano,$bimestre,$coddisciplina,$turma,$aulasdadas,$data,$hora)
   {
      $this->nome_relatorio = $nomerel;
      $this->pdf_ano = $ano;
      $this->pdf_bimestre = $bimestre;
      $this->pdf_coddisciplina = $coddisciplina;
      $this->pdf_turma = $turma;
      $this->pdf_aulasdadas = $aulasdadas;
	  $this->pdf_data = $data;
	  $this->pdf_hora = $hora;
   }

   //***************************************
   //*** INICIO - Cabeçalho do Relatório ***
   //***************************************

   function Header()
   {

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

      $this->SetX(135);
      $this->Cell(25, 1, "Data: " . date("d/m/Y", time()), $borda, 0);
      $this->Cell(25, 1, "Hora: " . date("H:i:s", time()), $borda, 0);
      $this->Cell(25, 1, "Página: " . $this->PageNo() . "/{nb}", $borda, 1);

      //*** Título do Relatório ***
      $this->SetFont('Arial', 'B', 8);
      $this->Cell(200, 10, $this->nome_relatorio, $borda, 1, 'C');

	  $this->SetFont('Arial', '', 8);
	  $this->Cell(8, 4, 'Último Registro de Notas Efetuado em: ' . trim($this->pdf_data) . ' às ' . trim($this->pdf_hora), $borda, 1, 'L');
	  $this->Cell(200, 4, '', $borda, 1, 'L');

      $this->SetFont('Arial', 'B', 8);
	  $this->Cell(8, 4, 'Ano: ', $borda, 0, 'L');
	  $this->SetFont('Arial', '', 8);
	  $this->Cell(192, 4, $this->pdf_ano, $borda, 1, 'L');

      $this->SetFont('Arial', 'B', 8);
	  $this->Cell(15, 4, 'Bimestre: ', $borda, 0, 'L');
	  $this->SetFont('Arial', '', 8);
	  $this->Cell(185, 4, $this->pdf_bimestre, $borda, 1, 'L');

      $this->SetFont('Arial', 'B', 8);
	  $this->Cell(22, 4, 'Cód.Disciplina: ', $borda, 0, 'L');
	  $this->SetFont('Arial', '', 8);
	  $this->Cell(178, 4, $this->pdf_coddisciplina, $borda, 1, 'L');

      $this->SetFont('Arial', 'B', 8);
	  $this->Cell(25, 4, 'Turma/Disciplina: ', $borda, 0, 'L');
	  $this->SetFont('Arial', '', 8);
	  $this->Cell(175, 4, $this->pdf_turma, $borda, 1, 'L');

      $this->SetFont('Arial', 'B', 8);
	  $this->Cell(40, 4, 'Quantidade de Aulas Dadas: ', $borda, 0, 'L');
	  $this->SetFont('Arial', '', 8);
	  $this->Cell(160, 4, $this->pdf_aulasdadas, $borda, 1, 'L');

	  $this->Cell(200, 4, '', $borda, 1, 'L');

      $this->SetFont('Arial', 'B', 8);
	  $this->Cell(10, 4, 'Siglas: ', $borda, 0, 'L');
	  $this->SetFont('Arial', '', 8);
	  $this->Cell(190, 4, 'D-Diversos | E-Disciplina | F-Frequente | M-Médico | T-Trabalho', $borda, 1, 'L');

	  $this->Cell(200, 4, '', $borda, 1, 'L');

      //*** Título dos Campos do Relatório ***

      $this->SetFont('Arial', 'BI', 8);
      $this->Cell(27, 5, 'Matrícula', $borda, 0, 'L');
      $this->Cell(108,5, 'Nome do Aluno', $borda, 0, 'L');
      $this->Cell(07, 5, 'Nro.', $borda, 0, 'R');
      $this->Cell(20, 5, 'Nota', $borda, 0, 'R');
	  $this->Cell(16, 5, 'Dispensa', $borda, 0, 'R');
      $this->Cell(12, 5, 'Faltas', $borda, 1, 'R');

      $this->line(10, $this->GetY(), 200, $this->GetY());// Desenha uma linha
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
      $this->Cell(200, 1, "Sistema de Lançamento de Notas On-Line (V 1.00) - Colégio Clóvis Bevilacqua", $borda, 0, 'R');
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
   $planilha_bimestre_sequencial = $_GET["planilha_bimestre_sequencial"];
   $planilha_disciplina          = $_GET["planilha_disciplina"];

   //*** Detalhe dos Itens ***

   $Total_Alunos = 0;

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
   $comando_sql .= "planilha_coluna_m_dispensa, ";
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
   $comando_sql .= "planilha_disciplina = '" . trim($planilha_disciplina) . "' AND ";
   $comando_sql .= "planilha_coluna_h_matricula <> '' ";

   $comando_sql .= "ORDER BY "; 
   $comando_sql .= "planilha_ano DESC, "; 
   $comando_sql .= "planilha_bimestre_sequencial DESC, ";
   $comando_sql .= "planilha_linha ASC";
	
   $resultado_sql = mysql_query($comando_sql);

   if(mysql_num_rows($resultado_sql) != 0)
   {
	 //*** Nome do PDF para Download ***
	 $nome_pdf = retira_acentos(trim(mysql_result($resultado_sql,0,"planilha_disciplina")),0) . '.pdf';

     //*** Geração do Relatório ***
     $pdf = new PDF('P');// relatório em orientação "paisagem"

     $pdf->SetName("LANÇAMENTO DE NOTAS",trim($planilha_ano),trim($nome_bimestre),trim(mysql_result($resultado_sql,0,"planilha_coluna_g_coddisciplina")),trim($planilha_disciplina),mysql_result($resultado_sql,0,"planilha_coluna_n_aulas_dadas"),trim(mysql_result($resultado_sql,0,"planilha_data_alteracao")),trim(mysql_result($resultado_sql,0,"planilha_hora_alteracao")));

     $pdf->Open();
     $pdf->AddPage();
     $pdf->SetFont('Arial', '', 8);

     for($i = 0; $i < mysql_num_rows($resultado_sql); $i++)
     {
        $pdf->Cell(27, 5, mysql_result($resultado_sql,$i,"planilha_coluna_h_matricula"), $borda, 0, 'L');
        $eixo_x = $pdf->GetX();
        $eixo_y = $pdf->GetY();
        $pdf->MultiCell(108, 5, mysql_result($resultado_sql,$i,"planilha_coluna_i_aluno"), $borda, 'L');
        $eixo_y_2 = $pdf->GetY();
        $pdf->SetXY($eixo_x + 108, $eixo_y);
        $pdf->Cell(7, 5, mysql_result($resultado_sql,$i,"planilha_coluna_j_numero"), $borda, 0, 'R');
        $pdf->Cell(20, 5, mysql_result($resultado_sql,$i,"planilha_coluna_k_nota"), $borda, 0, 'R');
		$pdf->Cell(16, 5, mysql_result($resultado_sql,$i,"planilha_coluna_m_dispensa"), $borda, 0, 'C');
        $pdf->Cell(12, 5, mysql_result($resultado_sql,$i,"planilha_coluna_l_falta"), $borda, 1, 'R');
        $pdf->SetY($eixo_y_2);

        $Total_Alunos = $Total_Alunos + 1;
     }  

     //*** Total de Alunos ***

     $pdf->line(10, $pdf->GetY(), 200, $pdf->GetY());// Desenha uma linha

     $pdf->SetFont('Arial', 'B', 8);
     $pdf->Cell(162, 5, 'Total de Alunos:', $borda, 0, 'R');
     $pdf->Cell(28, 5, $Total_Alunos, $borda, 1, 'R');

     $pdf->line(10, $pdf->GetY(), 200, $pdf->GetY());// Desenha uma linha

     $pdf->Output($nome_pdf,'I');
   }
   else
   {
		echo '<br><br><br><br><br><center><font color="#FF0000"><b>Ocorreu um erro de Processamento, por favor, comunique o Suporte do Sistema.</b></font></center>';
   }
}

PDF_Detalhe();

?>