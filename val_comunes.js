//<script>
//// validaciones comunes en la captura de datos
String.prototype.chunkString = function(len) {
    var _ret;
    if (this.length < 1) {
        return [];
    }
    if (typeof len === 'number' && len > 0) {
        var _size = Math.ceil(this.length / len), _offset = 0;
        _ret = new Array(_size);
        for (var _i = 0; _i < _size; _i++) {
            _ret[_i] = this.substring(_offset, _offset = _offset + len);
        }
    }
    else if (typeof len === 'object' && len.length) {
        var n = 0, l = this.length, chunk, that = this;
        _ret = [];
        do {
            len.forEach(function(o) {
                chunk = that.substring(n, n + o);
                if (chunk !== '') {
                    _ret.push(chunk);
                    n += chunk.length;
                }
            });
            if (n === 0) {
                return undefined; // prevent an endless loop when len = [0]
            }
        } while (n < l);
    }
    return _ret;
};

function SoloAlfanumerico(evt,busqueda)
//onKeyPress="return(SoloAlfanumerico(event))"
{

      evt = (evt) ? evt : window.event;
      var charCode = (evt.which) ? evt.which : evt.keyCode;
//	alert('entro en alfa'+charCode+' busqueda'+busqueda);      
	   if ((charCode > 47 && charCode < 58) || (charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || (charCode==38) || (charCode==37 && busqueda=='t') || (charCode==64) || (charCode==32) || (charCode==46))
      		return true;
      return false;
}

function SoloNumerico(evt)
//onKeyPress="return(SoloNumerico(event))"
{
      evt = (evt) ? evt : window.event;
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
      }
      return true;
}

function SoloMoneda(evt,valor)
//onKeyPress="return(SoloNumerico(event))"
{
	try
	{
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode > 31 && charCode!=46 && (charCode < 48 || charCode > 57)) { return false; }
	var str=valor.value
	var e=1
	for (var i = 0; i < str.length; i++)
	{ var ch = str.substring(i, i + 1); if(ch=='.') {e++} if (e>1 && charCode==46) {return false;} }
	var mySplitResult = str.split('.');
	if (mySplitResult[1] && mySplitResult[1].length>1){return false;}
	} catch (err) { alert('error SoloMoneda '+err.description); }
}

function valcomunes() 
{
  this.wlfecha = "";
  this.wlfechaf = ""; // fecha final de un rango de fechas
  this.wlnumero = "";
  this.wlpi = ""; // punto de recaudacion inicial
  this.wlpf = ""; // punto de recaudacion final
  this.wles = ""; // punto de recaudacion final
  this.ponfecha = function (wlfecha) { this.wlfecha=wlfecha; }
  this.ponfechaf = function (wlfechaf) { this.wlfechaf=wlfechaf; }
  this.ponnumero = function (wlnumero) { this.wlnumero=wlnumero; }
  this.ponpi = function (wlpi) { this.wlpi=wlpi; }
  this.ponpf = function (wlpf) { this.wlpf=wlpf; }
  this.pones = function (wles) { this.wles=wles; }

/*
 *  funcion que valida que el dato sea un email
 **/
   this.validarEmail = function ( email )
   {
    if (email.value!="")
    {
      expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      if ( !expr.test(email.value) )
      { alert("Error_v: La direccion del correo " + email.value + " es incorrecta."); email.focus(); return false; }
    }
      return true;
  }

  this.esnumerico = function ()
  {
    var errperiodo = false;
    var mes=0;
    var ano=0;
    var str=0;
    str = this.wlnumero.value.trim();
    //window.alert('Entro en numerico '+str);
//    hay que checar si hay ma de un punto en el campo
    for (var i = 0; i < str.length; i++)
//  20070313  se modifico para aceptar valores negatios
//  20070313            { var ch = str.substring(i, i + 1); if((ch < "0" || "9" < ch) && ch!="" && ch!="." ) errperiodo=true; }
            { var ch = str.substring(i, i + 1); if((ch < "0" || "9" < ch) && ch!="." && ch!="-" ) errperiodo=true; }
    if (errperiodo==true)  {
        window.alert('El dato debe ser numerico');
        this.wlnumero.focus();
        return false;
                         }
    return true;
  }

  this.esmoneda = function ()
  {
    var errperiodo = false;
    var mes=0;
    var ano=0;
    var str="";
    str = this.wlnumero.value;
    for (var i = 0; i < str.length; i++)
            { var ch = str.substring(i, i + 1); if((ch < "0" || "9" < ch) && ch!="" && ch!="." ) errperiodo=true; }
    if (errperiodo==true)  {
        window.alert('El Dato debe ser numerico');
        this.wlnumero.focus();
        return false;
                         }
    return true;
  }

  this.valsel = function (wlselect)
  {
      if(wlselect.selectedIndex==-1)
      {
           alert('No ha seleccionado un dato');
           wlselect.focus();
           return false;
      }
      return true;
  }

    this.valranpr = function ()
  {
	  // 2007-04-09 grecar - agregue estas dos variables para convertir el tipo de dato a numerico
	  var wlpf = parseFloat (this.wlpf.value);
	  var wlpi = parseFloat (this.wlpi.value);
	  //alert('entro en rangopr '+typeof(wlpi)+' '+typeof(wlpf));
      if(wlpf<wlpi)
      {
           alert('El punto de recaudacion final es menor que el inicial');
           this.wlpf.focus();
           return false;
      }
      return true;
  }

  this.valranfec = function ()
  {
//      alert('entro en rangofec'+this.wlfechaf.value);
      if(this.wlfechaf.value<this.wlfecha.value)
      {
           alert('La fecha final es menor que la fecha inicial');
           this.wlfechaf.focus();
           return false;
      }
      return true;
  }
  /*  convierte fecha de 2 digitos a AAAA-MM-DD 1 digito dia 2 digito mes
   *  3 digitos 1 digito el dia los siguientes dos digitos el mes
   *  4 digitos 2 digito el dia los siguientes dos digitos el mes
   */
  this.convfecha = function (xfecha)
  {
     var str = xfecha.value;
     hoy = new Date();
     if (str.length == 2)
     { str= hoy.getFullYear() + '-0' + str.substring(1,2) + '-0' + str.substring(0,1); }
     if (str.length == 3)
     { str= hoy.getFullYear() + '-' + str.substring(1,3) + '-0' + str.substring(0,1); }
     if (str.length == 4)
     { str= hoy.getFullYear() + '-' + str.substring(2,4) + '-' + str.substring(0,2); }
     if (str.length == 5)
     { str= '201' + str.substring(4,5) + '-' + str.substring(2,4) + '-' + str.substring(0,2); }
     if (str.length == 6)
     { str= '20' + str.substring(4,6) + '-' + str.substring(2,4) + '-' + str.substring(0,2); }

          return str;
  }


  this.valfecha = function (xfecha)
  {
	  try 
	  {
   xfecha.value=this.convfecha(xfecha);
   var str = xfecha.value;
   var month=0;
   var monthi=0;
   var day=0;
   var dayi=0;
   var year=0;
   var yeari=0;
    var errano = false;
    var errmes = false;
    var errdia = false;
    var errcaja = false;
    var errpartida = false;
    var limiteano=parseInt(1820);
    hoy = new Date();
    // 20080210 esta validacion la inclui a veces el usuario tecleaba mas datos
    if (str.length)
    {
    if (str.length != 10) {
        window.alert('La Longitud de la FECHA es incorrecto, el formato correcto es AAAA-MM-DD');
        xfecha.focus();
        return false;
                         }
    
    if (str.charAt(4) != "-" || str.charAt(7) != "-" ) {
        window.alert('El formato de la FECHA es incorrecto, el formato correcto es AAAA-MM-DD');
        xfecha.focus();
        return false;
                         }

       for (var i = 0; i < 4; i++)
            { var ch = str.substring(i, i + 1); if(ch < "0" || "9" < ch) errano=true; }
       for (var i = 5; i < 7; i++)
            { var ch = str.substring(i, i + 1); if(ch < "0" || "9" < ch) errmes=true; }
       for (var i = 8; i < 10; i++)
            { var ch = str.substring(i, i + 1); if(ch < "0" || "9" < ch) errdia=true; }

    if (errano==true) {
        window.alert('El año no es numerico');
        xfecha.focus();
        return false;
                      }

    if (errmes==true) {
        window.alert('El mes no es numerico');
        xfecha.focus();
        return false;
                      }
    if (errdia==true) {
        window.alert('El dia no es numerico');
        xfecha.focus();
        return false;
                      }
    month=eval(str.substring(5,7)); day=eval(str.substring(8,10)); year=eval(str.substring(0,4));
    monthi=eval(str.substring(5,7)); dayi=eval(str.substring(8,10));yeari=eval(str.substring(0,4));
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
        xfecha.focus();
        return false;
                      }
  // Check that month is between 1 &12.
   if(month<=0 || month>=13) {
        window.alert('El mes esta fuera de rango');
        xfecha.focus();
        return false;
                      }
   // Check that year is OK
//   alert('vaaño'+year+' limite'+limiteano);
   //|| year>hoy.getYear()
   if(year<limiteano )  {
//	   alert('entro');
        window.alert('El año esta fuera de rango '+year +' ');
        xfecha.focus();
        return false;
                      }
//                      alert('paso true');
   return true;
}
}
catch (err)
	{ alert('error en valfecha '+err.message); }
  }

  this.valida = function ()
  {

      var wlok=false; 
      if (this.wlfecha!="" && this.wlfechaf=="") 
      { 
            wlok=this.valfecha(this.wlfecha);
      }

      if (this.wlfechaf!="") 
      { 
         if (this.wlfecha=="")
            {  alert('errror la fecha inicial esta en cerros'); }
         else
            { 
	            //alert ('entro');
               wlok=this.valfecha(this.wlfecha);
               (wlok==true) ? wlok=this.valfecha(this.wlfechaf) : wlok=false ;
               (wlok==true) ? wlok=this.valranfec() : wlok=false ;
            }
      }

      if (this.wlpi!="" && wlok==true)
      {
          wlok=this.valsel(this.wlpi);
      }

      if (this.wlpf!="" && wlok==true)
      {
          wlok=this.valsel(this.wlpf);
          (wlok==true) ? wlok=this.valranpr() : wlok=false ;
      }

      if (this.wles!="" && wlok==true)
      {
          wlok=this.valsel(this.wles);
      }

      if (wlok==true)
      {   document.forms[0].submit(); }


  }
}

//</script>
