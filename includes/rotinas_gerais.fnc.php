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

	function inverte_data_dma_to_amd($data_exibicao){
	   //
	   //Ex.: 01/10/2008 -> 2008/10/01
	   //

	   $data_exibicao = substr($data_exibicao,6,4)."-".substr($data_exibicao,3,2)."-".substr($data_exibicao,0,2);
	   $data_exibicao = trim($data_exibicao);

       if(trim($data_exibicao) == '--')
       {
         $data_exibicao = '0000-00-00';
       }

	   return $data_exibicao;
	}

	function inverte_data_amd_to_dma($data_exibicao){
	   //
	   //Ex.: 2008/10/01 -> 01/10/2008
	   //

	   $data_exibicao = substr($data_exibicao,8,2)."/".substr($data_exibicao,5,2)."/".substr($data_exibicao,0,4);
	   $data_exibicao = trim($data_exibicao);

   	   return $data_exibicao;    
	}

    function retira_caracter($texto, $caracter)
    {
       for($ind = 0; $ind < strlen($texto); $ind++)
       {
          if(substr($texto, $ind, 1) <> $caracter)
          {
            $resultado = $resultado . substr($texto, $ind, 1);
          }
       }

       return $resultado;
    }
	
    function troca_aspas_simples($texto)
    {
       str_replace("'","�",$texto);
	    
       return $texto;
    }	

	function retira_acentos($texto, $tamanho)
	{
	   //*** Caracteres Especiais ***

	   $texto = str_replace(" ", '_', $texto);
	   $texto = str_replace(".", '', $texto);

	   $texto = str_replace("'", '', $texto);
	   $texto = str_replace("&", '', $texto);
	   $texto = str_replace("�", '', $texto);
	   $texto = str_replace("�", '', $texto);
	   $texto = str_replace("�", '', $texto);
	   $texto = str_replace("�", '', $texto);

	   //*** Letras Mai�sculas ***

	   $texto = str_replace('�', 'C', $texto);

	   $texto = str_replace('�', 'A', $texto);
	   $texto = str_replace('�', 'A', $texto);
	   $texto = str_replace('�', 'A', $texto);
	   $texto = str_replace('�', 'A', $texto);
	   $texto = str_replace('�', 'A', $texto);

	   $texto = str_replace('�', 'E', $texto);
	   $texto = str_replace('�', 'E', $texto);
	   $texto = str_replace('�', 'E', $texto);
	   $texto = str_replace('�', 'E', $texto);

	   $texto = str_replace('�', 'I', $texto);
	   $texto = str_replace('�', 'I', $texto);
	   $texto = str_replace('�', 'I', $texto);
	   $texto = str_replace('�', 'I', $texto);

	   $texto = str_replace('�', 'O', $texto);
	   $texto = str_replace('�', 'O', $texto);
	   $texto = str_replace('�', 'O', $texto);
	   $texto = str_replace('�', 'O', $texto);
	   $texto = str_replace('�', 'O', $texto);

	   $texto = str_replace('�', 'U', $texto);
	   $texto = str_replace('�', 'U', $texto);
	   $texto = str_replace('�', 'U', $texto);
	   $texto = str_replace('�', 'U', $texto);

	   //*** Letras Min�sculas ***

	   $texto = str_replace('�', 'c', $texto);

	   $texto = str_replace('�', 'a', $texto);
	   $texto = str_replace('�', 'a', $texto);
	   $texto = str_replace('�', 'a', $texto);
	   $texto = str_replace('�', 'a', $texto);
	   $texto = str_replace('�', 'a', $texto);

	   $texto = str_replace('�', 'e', $texto);
	   $texto = str_replace('�', 'e', $texto);
	   $texto = str_replace('�', 'e', $texto);
	   $texto = str_replace('�', 'e', $texto);

	   $texto = str_replace('�', 'i', $texto);
	   $texto = str_replace('�', 'i', $texto);
	   $texto = str_replace('�', 'i', $texto);
	   $texto = str_replace('�', 'i', $texto);

	   $texto = str_replace('�', 'o', $texto);
	   $texto = str_replace('�', 'o', $texto);
	   $texto = str_replace('�', 'o', $texto);
	   $texto = str_replace('�', 'o', $texto);
	   $texto = str_replace('�', 'o', $texto);

	   $texto = str_replace('�', 'u', $texto);
	   $texto = str_replace('�', 'u', $texto);
	   $texto = str_replace('�', 'u', $texto);
	   $texto = str_replace('�', 'u', $texto);

	   //*** Resultado ***

	   $resulta_codigo = trim($texto);

	   if($tamanho > 0)
	   {
	      $resulta_codigo = substr($resulta_codigo, 0, $tamanho);
	   }

	   return $resulta_codigo;
	}


?>