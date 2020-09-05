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
   var $pdf_status;
   var $pdf_colegio;
   var $pdf_nome_bimestre;

   function PDF($or = 'P')
   {
      $this->FPDF($or);
   }

   function SetName($nomerel,$ano,$bimestre,$status,$colegio,$nome_bimestre)
   {
      $this->nome_relatorio = $nomerel;
      $this->pdf_ano = $ano;
      $this->pdf_bimestre = $bimestre;
      $this->pdf_status = $status;
      $this->pdf_colegio = $colegio;
	  $this->pdf_nome_bimestre = $nome_bimestre;
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

      $this->SetFont('Arial', 'B', 8);
	  $this->Cell(8, 4, 'Ano: ', $borda, 0, 'L');
	  $this->SetFont('Arial', '', 8);
	  $this->Cell(192, 4, $this->pdf_ano, $borda, 1, 'L');

      $this->SetFont('Arial', 'B', 8);
	  $this->Cell(15, 4, 'Bimestre: ', $borda, 0, 'L');
	  $this->SetFont('Arial', '', 8);
	  $this->Cell(185, 4, trim($this->pdf_nome_bimestre), $borda, 1, 'L');

      $this->SetFont('Arial', 'B', 8);
	  $this->Cell(28, 4, 'Status da Digitação: ', $borda, 0, 'L');
	  $this->SetFont('Arial', '', 8);
	  $this->Cell(178, 4, trim($this->pdf_status), $borda, 1, 'L');

      $this->SetFont('Arial', 'B', 8);
	  $this->Cell(47, 4, 'Colégio que o Professor Leciona: ', $borda, 0, 'L');
	  $this->SetFont('Arial', '', 8);
	  $this->Cell(175, 4, $this->pdf_colegio, $borda, 1, 'L');

	  $this->Cell(1, 4, '', $borda, 1, 'L');

	  $this->SetFont('Arial', '', 8);
	  $this->Cell(200, 4, 'O(s) professor(es) listado(s) abaixo possue(m) planilha(s) com o Status de ' . trim($this->pdf_status) . ' para o Bimestre de ' . trim($this->pdf_nome_bimestre), $borda, 1, 'C');

	  $this->Cell(200, 4, '', $borda, 1, 'L');

      //*** Título dos Campos do Relatório ***

      $this->SetFont('Arial', 'BI', 8);
      $this->Cell(135,5, 'Nome do Professor', $borda, 0, 'L');
      $this->Cell(55, 5, 'Status da Digitação', $borda, 1, 'R');

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
   $digitacao_ano	   = $_GET["digitacao_ano"];
   $digitacao_bimestre = $_GET["digitacao_bimestre"];
   $digitacao_status   = $_GET["digitacao_status"];
   $digitacao_colegio  = $_GET["digitacao_colegio"];

   //*** Detalhe dos Itens ***

   $total_professores = 0;

   //*** Obtem os Registros dos Bimestres ***
   $resultado_sql = mysql_query("SELECT * FROM bimestres WHERE bimestre_sequencial = '" . trim($digitacao_bimestre) . "' ORDER BY bimestre_sequencial ASC");
   $nome_bimestre = trim(mysql_result($resultado_sql,0,"bimestre_descricao"));

   //*** Lista os Registros Obtidos da Busca ***
   $comando_sql  = "SELECT planilha_ano, ";
   $comando_sql .= "planilha_nome_professor, ";
   $comando_sql .= "planilha_usuario_loguin, ";
   $comando_sql .= "planilha_bimestre_sequencial, ";
   $comando_sql .= "planilha_bimestre_descricao, ";
   $comando_sql .= "planilha_disciplina, ";
   $comando_sql .= "planilha_status, ";
   $comando_sql .= "DATE_FORMAT(planilha_data_alteracao,'%d/%m/%Y') AS planilha_data_alteracao, ";
   $comando_sql .= "planilha_hora_alteracao, ";
   $comando_sql .= "usuario_nome ";
   $comando_sql .= "FROM planilhas, usuarios ";
   $comando_sql .= "WHERE ";
   $comando_sql .= "planilha_coluna_h_matricula <> '' AND ";
   $comando_sql .= "planilha_ano = '" . trim($digitacao_ano) ."' AND ";
   $comando_sql .= "planilha_usuario_loguin = usuario_loguin AND ";
   $comando_sql .= "planilha_bimestre_sequencial = '" . trim($digitacao_bimestre) ."' AND ";
   $comando_sql .= "usuario_colegio = '" . trim($digitacao_colegio) ."' AND ";
   $comando_sql .= "planilha_status = '" . trim($digitacao_status) ."' ";
   $comando_sql .= "GROUP BY ";
   $comando_sql .= "planilha_usuario_loguin ";
   $comando_sql .= "ORDER BY "; 
   $comando_sql .= "planilha_nome_professor ASC "; 

   $resultado_sql = mysql_query($comando_sql);

   if(mysql_num_rows($resultado_sql) != 0)
   {
	 //*** Nome do PDF para Download ***
	 $nome_pdf = retira_acentos('DIGITACAO_'. trim($nome_bimestre),0) . '.pdf';

     //*** Geração do Relatório ***
     $pdf = new PDF('P');// relatório em orientação "paisagem"

     $pdf->SetName("RELATÓRIO DE DIGITAÇÃO",trim($digitacao_ano),trim($digitacao_bimestre),trim($digitacao_status),trim($digitacao_colegio),trim($nome_bimestre));

     $pdf->Open();
     $pdf->AddPage();
     $pdf->SetFont('Arial', '', 8);

     for($i = 0; $i < mysql_num_rows($resultado_sql); $i++)
     {
		$pdf->Cell(1, 5, '', $borda, 0, 'L');
        $eixo_x = $pdf->GetX();
        $eixo_y = $pdf->GetY();
        $pdf->MultiCell(135, 5, mysql_result($resultado_sql,$i,"usuario_nome"), $borda, 'L');
        $eixo_y_2 = $pdf->GetY();
        $pdf->SetXY($eixo_x + 135, $eixo_y);
        $pdf->Cell(55, 5, mysql_result($resultado_sql,$i,"planilha_status"), $borda, 1, 'R');
        $pdf->SetY($eixo_y_2);

        $total_professores = $total_professores + 1;
     }  

     //*** Total de Alunos ***

     $pdf->line(10, $pdf->GetY(), 200, $pdf->GetY());// Desenha uma linha

     $pdf->SetFont('Arial', 'B', 8);
     $pdf->Cell(162, 5, 'Total de Professores:', $borda, 0, 'R');
     $pdf->Cell(28, 5, $total_professores, $borda, 1, 'R');

     $pdf->line(10, $pdf->GetY(), 200, $pdf->GetY());// Desenha uma linha

     $pdf->Output($nome_pdf,'I');
   }
   else
   {
		echo '<br><br><br><br><br><center><font color="#FF0000"><b>Nenhum Registro foi Localizado para o Status Escolhido!</b></font></center>';
   }
}

PDF_Detalhe();

?>