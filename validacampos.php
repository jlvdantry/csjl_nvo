function validacampos()
{
    var str = document.forms[0].wlfecha.value;
   var month=0;
   var day=0;
   var year=0;
    var errano = false;
    var errmes = false;
    var errdia = false;
    var errcaja = false;
    var errpartida = false;
    hoy = new Date();
    if (document.forms[0].wlfecha.value == "" ) {
        window.alert('Primero debe de teclear la fecha'); 
        document.forms[0].wlfecha.focus();
        return;
                         }
    if (str.charAt(4) != "-" || str.charAt(7) != "-" ) {
        window.alert('Esta mal el formato de la fecha AAAA-MM-DD');
        document.forms[0].wlfecha.focus();
        return;
                         }

       for (var i = 0; i < 4; i++) 
            { var ch = str.substring(i, i + 1); if(ch < "0" || "9" < ch) errano=true; }
         // Check that day is a number.
       for (var i = 5; i < 7; i++) 
            { var ch = str.substring(i, i + 1); if(ch < "0" || "9" < ch) errmes=true; }
         // Check that year is a number.
       for (var i = 8; i < 10; i++) 
            { var ch = str.substring(i, i + 1); if(ch < "0" || "9" < ch) errdia=true; }

    if (errano==true) { 
        window.alert('El año no es numerico');
        document.forms[0].wlfecha.focus();
        return;
                      }

    if (errmes==true) { 
        window.alert('El mes no es numerico');
        document.forms[0].wlfecha.focus();
        return;
                      }

    if (errdia==true) { 
        window.alert('El dia no es numerico');
        document.forms[0].wlfecha.focus();
        return;
                      }

    month=eval(str.substring(5,7)); day=eval(str.substring(8,10)); year=eval(str.substring(0,4)); 
//    window.alert('el mes es ' +  month);
//    window.alert('el dia es ' +  day);
//    window.alert('el ano es ' +  year);
// Check that day is right depending on month.
   if( month==2 && ((year/4)==parseInt(year/4)) )
      { if(day<=0 || day>29) errdia=true; }
   if( month==2 && ((year/4)!=parseInt(year/4)) )
      { if(day<=0 || day>28) errdia=true; }
   if( month==4 || month==6 || month==9 || month==11 )
      { if(day<=0 || day>30) errdia=true; }
   if( month==1 || month==3 || month==5 || month==7 || month==8 || month==10 || month==12 )
      { if(day<=0 || day>31) errdia=true; }

    if (errdia==true) { 
        window.alert('El dia esta fuera de rango' );
        document.forms[0].wlfecha.focus();
        return;
                      }

  // Check that month is between 1 &12.
   if(month<=0 || month>=13) {
        window.alert('El mes esta fuera de rango');
        document.forms[0].wlfecha.focus();
        return;
                      }



   // Check that year is OK
   if(year<1990 || year>hoy.getYear())  {
        window.alert('El año esta fuera de rango');
        document.forms[0].wlfecha.focus();
        return;
                      }

    if (document.forms[0].wlcaja.value == "" ) {
        window.alert('Primero debe de teclear la caja'); 
        document.forms[0].wlcaja.focus();
        return;
                         }
    var str = document.forms[0].wlcaja.value;
     for (var i = 0; i < 8; i++)
            { var ch = str.substring(i, i + 1); if((ch < "0" || "9" < ch) && ch!="") errcaja=true; }
    if (errcaja==true)  {
        window.alert('El valor de caja debe ser numerico'); 
        document.forms[0].wlcaja.focus();
        return;
                         }
    if (document.forms[0].wlpartida.value == "" ) {
        window.alert('Primero debe de teclear la partida'); 
        document.forms[0].wlpartida.focus();
        return;
                         }
    var str = document.forms[0].wlpartida.value;
     for (var i = 0; i < 8; i++)
            { var ch = str.substring(i, i + 1); if((ch < "0" || "9" < ch) && ch!="") errpartida=true; }

    if (errpartida==true)  {
        window.alert('El valor de partida debe ser numerico'); 
        document.forms[0].wlpartida.focus();
        return;
                         }
    if (document.forms[0].wlhracob.value != "" ) {
        str = document.forms[0].wlhracob.value;
        if (str.charAt(2) != ":" || str.charAt(5) != ":" ) {
           window.alert('Esta mal el formato de la Hora hh:mm:ss'+str.charAt(2));
           document.forms[0].wlhracob.focus();
           return;
                                                            }
                                                  }

    document.forms[0].submit();
}
