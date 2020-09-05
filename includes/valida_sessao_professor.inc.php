<?php 
/*
+------------------------------------------------+
| Desenvolvido Por:                              |
| DATATEX INFORMATICA E SERVICOS LTDA            |
| System of the New Generation                   |
|                                                |
| http://www.datatex.com.br                      |
| sistemas@datatex.com.br                        |
| Fone: 55 11 2629-4605                          |
|                                                |
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

   session_start();

   //*** Prepara a Sess�o para Recuperar Valores ***
   require_once("includes/prepara_sessao.inc.php");   

   if( isset($_SESSION['identificacao']) )
   {
	   if( $_SESSION['identificacao']['identificado'] != true )
	   {
		   //*** Destroi a Sess�o ***
		   session_destroy();
		   
           //*** Redireciona para a P�gina de Identifica��o ***
           header('Location: index.php');
	   }
	   else
	   {
		   if( $_SESSION['identificacao']['tipo'] != 'Professor' )
		   {		   
			 //*** Destroi a Sess�o ***
		     session_destroy();
		   
             //*** Redireciona para a P�gina de Identifica��o ***
             header('Location: index.php');
		   }
	   }		   
   }
   else
   {
	  //*** Destroi a Sess�o ***
	  session_destroy();
	   
      //*** Redireciona para a P�gina de Identifica��o ***
      header('Location: index.php');
   }
?>