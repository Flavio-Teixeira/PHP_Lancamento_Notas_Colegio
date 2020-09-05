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

   /*
   *******************************************************************
   *** Utilização de Sessão com o Register_Globals = Off           ***
   *** Esta preparação também funciona com o Register_Globals = On ***
   *******************************************************************
   */
   if(!ini_get('register_globals')) {
      $superglobals = array($_SERVER, $_ENV, $_FILES, $_COOKIE, $_POST, $_GET);

      if(isset($_SESSION))
	  {
         array_unshift($superglobals, $_SESSION);
      }
      foreach ($superglobals as $superglobal)
	  {
         extract($superglobal, EXTR_SKIP);
      }
   }
?>