// JavaScript Document

//*** Função que Só aceita Valores com Decimal ***
function so_numero(valor) {
	var campo = valor;
    var digits="0123456789";
    var campo_temp;

    for (var i=0;i<campo.value.length;i++){
        campo_temp=campo.value.substring(i,i+1);
        if (digits.indexOf(campo_temp)==-1){
           campo.value = campo.value.substring(0,i);
           break;
        }
    }
    campo.value = campo.value.replace(',','.');

	return false;
}