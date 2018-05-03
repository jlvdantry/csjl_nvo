function validacampos_pr()
{
	var str = document.forms[0].wlpr.value;
    var errpr = false;
    var errnombre = false;

    if (document.forms[0].wlpr.value == "" ) {
        window.alert('Primero debe de teclear el pr'); 
        document.forms[0].wlpr.focus();
        return;
                         }
    var str = document.forms[0].wlpr.value;
     for (var i = 0; i < 3; i++)
            { var ch = str.substring(i, i + 1); if((ch < "0" || "9" < ch) && ch!="") errcaja=true; }
    if (errpr==true)  {
        window.alert('El valor del pr debe ser numerico'); 
        document.forms[0].wlpr.focus();
        return;
                         }
    if (document.forms[0].wlnombre.value == "" ) {
        window.alert('Primero debe de teclear el nombre del pr'); 
        document.forms[0].wlnombre.focus();
        return;
                         }
    var str = document.forms[0].wlnombre.value;
     for (var i = 0; i < 50; i++)
            { var ch = str.substring(i, i + 1); if((ch < "a" || "z" < ch) && ch!="") errpartida=true; }

    if (errnombre==true)  {
        window.alert('El valor de nombre debe ser alfabetico'); 
        document.forms[0].wlnombre.focus();
        return;
                         }

    document.forms[0].submit();
}
