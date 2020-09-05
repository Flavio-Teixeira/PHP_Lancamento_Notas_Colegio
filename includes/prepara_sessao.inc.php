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

   /*
   *******************************************************************
   *** Utiliza��o de Sess�o com o Register_Globals = Off           ***
   *** Esta prepara��o tamb�m funciona com o Register_Globals = On ***
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