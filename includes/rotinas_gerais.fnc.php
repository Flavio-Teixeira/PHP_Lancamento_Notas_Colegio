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
       str_replace("'","´",$texto);
	    
       return $texto;
    }	

	function retira_acentos($texto, $tamanho)
	{
	   //*** Caracteres Especiais ***

	   $texto = str_replace(" ", '_', $texto);
	   $texto = str_replace(".", '', $texto);

	   $texto = str_replace("'", '', $texto);
	   $texto = str_replace("&", '', $texto);
	   $texto = str_replace("°", '', $texto);
	   $texto = str_replace("º", '', $texto);
	   $texto = str_replace("ª", '', $texto);
	   $texto = str_replace("§", '', $texto);

	   //*** Letras Maiúsculas ***

	   $texto = str_replace('Ç', 'C', $texto);

	   $texto = str_replace('Á', 'A', $texto);
	   $texto = str_replace('À', 'A', $texto);
	   $texto = str_replace('Ã', 'A', $texto);
	   $texto = str_replace('Â', 'A', $texto);
	   $texto = str_replace('Ä', 'A', $texto);

	   $texto = str_replace('É', 'E', $texto);
	   $texto = str_replace('È', 'E', $texto);
	   $texto = str_replace('Ê', 'E', $texto);
	   $texto = str_replace('Ë', 'E', $texto);

	   $texto = str_replace('Í', 'I', $texto);
	   $texto = str_replace('Ì', 'I', $texto);
	   $texto = str_replace('Î', 'I', $texto);
	   $texto = str_replace('Ï', 'I', $texto);

	   $texto = str_replace('Ó', 'O', $texto);
	   $texto = str_replace('Ò', 'O', $texto);
	   $texto = str_replace('Ô', 'O', $texto);
	   $texto = str_replace('Ö', 'O', $texto);
	   $texto = str_replace('Õ', 'O', $texto);

	   $texto = str_replace('Ú', 'U', $texto);
	   $texto = str_replace('Ù', 'U', $texto);
	   $texto = str_replace('Û', 'U', $texto);
	   $texto = str_replace('Ü', 'U', $texto);

	   //*** Letras Minúsculas ***

	   $texto = str_replace('ç', 'c', $texto);

	   $texto = str_replace('á', 'a', $texto);
	   $texto = str_replace('à', 'a', $texto);
	   $texto = str_replace('ã', 'a', $texto);
	   $texto = str_replace('â', 'a', $texto);
	   $texto = str_replace('ä', 'a', $texto);

	   $texto = str_replace('é', 'e', $texto);
	   $texto = str_replace('è', 'e', $texto);
	   $texto = str_replace('ê', 'e', $texto);
	   $texto = str_replace('ë', 'e', $texto);

	   $texto = str_replace('í', 'i', $texto);
	   $texto = str_replace('ì', 'i', $texto);
	   $texto = str_replace('î', 'i', $texto);
	   $texto = str_replace('ï', 'i', $texto);

	   $texto = str_replace('ó', 'o', $texto);
	   $texto = str_replace('ò', 'o', $texto);
	   $texto = str_replace('ô', 'o', $texto);
	   $texto = str_replace('ö', 'o', $texto);
	   $texto = str_replace('õ', 'o', $texto);

	   $texto = str_replace('ú', 'u', $texto);
	   $texto = str_replace('ù', 'u', $texto);
	   $texto = str_replace('û', 'u', $texto);
	   $texto = str_replace('ü', 'u', $texto);

	   //*** Resultado ***

	   $resulta_codigo = trim($texto);

	   if($tamanho > 0)
	   {
	      $resulta_codigo = substr($resulta_codigo, 0, $tamanho);
	   }

	   return $resulta_codigo;
	}


?>