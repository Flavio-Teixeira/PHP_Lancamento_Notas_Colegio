<?php
/*
+------------------------------------------------+
| PROTE��O AOS DIREITOS DE AUTOR E DO REGISTRO:  |
| Toda codifica��o deste Sistema est� protegida  |
| pela Lei Nro.9609 onde se disp�e sobre a       |
| prote��o da propriedade intelectual de         |
| programa de computador, sua comercializa��o    |
| no Pa�s, e d� outras provid�ncias.             |
| ATEN��O: N�o � permitido efetuar altera��es    |
| na codifica��o do sistema, efetuar instala��es |
| em outros computadores, c�pias e utiliz�-lo    |
| como base no desenvolvimento de outro sistema  |
| semelhante ou de igual funcionamento.          |
+------------------------------------------------+
*/

/*   
******************************************************************************
* Esta include conecta um banco de dados MySQL conforme par�metros enviados. *
* Banco de Dados: $dbname                                                    *
* Porta.........: $porta                                                     * 
* Usu�rio.......: $usuario                                                   *
* Senha.........: $senha                                                     *  
******************************************************************************
*/

//*** Par�metros de Conex�o ***

//*** Local ***

$dbname         = "colegio_clovis";
$porta          = "localhost";
$usuario        = "root";
$senha          = "";

//*** Remoto ***
/*
$dbname         = "colegio_clovis";
$porta          = "localhost";
$usuario        = "root";
$senha          = "";
*/

//*** Conecta ao servidor MySQL ***
$nro_conexao = @mysql_connect($porta,$usuario,$senha);
$res_conexao = @mysql_select_db($dbname,$nro_conexao);

if( $res_conexao != 1 ){
  echo "<p align=center><b>N�o foi poss�vel estabelecer uma conex�o com o Banco de Dados. Erro: " . mysql_error() . "</b></p>";
  exit;
}
//*** Remoto ***
else
{
	mysql_query("SET NAMES 'utf8'");
	mysql_query('SET character_set_connection=utf8');
	mysql_query('SET character_set_client=utf8');
	mysql_query('SET character_set_results=utf8');
	mysql_query("SET GLOBAL sql_mode = ''");
	mysql_query("SET SESSION sql_mode = ''");
}
?>