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

   session_start();

   //*** Prepara a Sessão para Recuperar Valores ***
   require_once("includes/prepara_sessao.inc.php");   

   if( isset($_SESSION['identificacao']) )
   {
	   if( $_SESSION['identificacao']['identificado'] != true )
	   {
		   //*** Destroi a Sessão ***
		   session_destroy();
		   
           //*** Redireciona para a Página de Identificação ***
           header('Location: index.php');
	   }
	   else
	   {
		   if( $_SESSION['identificacao']['tipo'] != 'Professor' )
		   {		   
			 //*** Destroi a Sessão ***
		     session_destroy();
		   
             //*** Redireciona para a Página de Identificação ***
             header('Location: index.php');
		   }
	   }		   
   }
   else
   {
	  //*** Destroi a Sessão ***
	  session_destroy();
	   
      //*** Redireciona para a Página de Identificação ***
      header('Location: index.php');
   }
?>