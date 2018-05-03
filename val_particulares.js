//<script>

function val_particulares() 
{

  this.wlrfc = ""; // rfc
  
  this.esimss = function (wlimss)
  {
    try {
      str=wlimss.value;
      if (str.length != 11 )
      {
	  window.alert('El numero de imss debe ser de 11 posiciones ');
          return false;
      }
      return true;
      } catch (err) { alert('error esimss'+err.description); }
  }
    
  this.iniciooperaciones = function (iop)
  {
    try {
	    alert('entro en inicio operaciones');
		var myDate=new Date()
		myDate.setDate(myDate.getDate()+30) 
	    
      	str=iop.value;
      if (iop < myDate )
      {
	      window.alert('La fecha de inicio de operaciones no puede ser menor a la de hace un mes ');
          return false;
      }
      return true;
      } catch (err) { alert('error iniciooperaciones'+err.description); }
  }
  
   this.esperiodo = function (valor)
  {
    try
    {
      str=valor.value;
      wltc=document.getElementById('wl_idtiposcobros');
	  if (wltc.value==3 || wltc.value==1 || wltc.value==8)  // 3=predial 1=isai 8=isr
		{
			if (str.length != 6 )
			{
				window.alert('El periodo del impuesto predial es de 6 posiciones AAAABB');
				return false;
			}
			wlbim=str.substring(4,6);
//			alert('bim'+wlbim);
			if (wlbim<=0 || wlbim>=7)
			{ window.alert('El bimestre esta erroneo');		return false; }
		}
		
	  if (wltc.value==4 || wltc.value==6)  // Nomina  hospedaje 
		{
			if (str.length != 6 )
			{
				window.alert('El periodo del Nomina es de 6 posiciones AAAAMM');
				return false;
			}
		}		

      return true;
    }
    catch (err) { alert('error esperiodo'+err.description); }
  }
  
  this.esinfonavit = function (wlinfonavit)
  {
    try {
      str=wlinfonavit.value;
      if (str.length != 10 )
      {
	  window.alert('El numero de infonavit debe ser de 10 posiciones ');
          return false;
      }
      return true;
      } catch (err) { alert('error esinfonavit'+err.description); }
  }

  this.edadok = function (wledad)
  {
    try {
      str=wledad.value;
      if (Number(str) >= "41")
      {
	  window.alert('La edad debe ser menor o igual 40');
          return false;
      }
      if (Number(str) == 0)
      {
	  		window.alert('La edad debe ser mayor a 0');
          	return false;
      }      
      return true;
      } catch (err) { alert('error edadok'+err.description); }
  }

  this.habitacionok = function (wledad)
  {
    try {
      str=wledad.value;
      if (Number(str) == 0)
      {
	  window.alert('La habitacion debe ser mayor a cero');
          return false;
      }
      return true;
      } catch (err) { alert('error edadok'+err.description); }
  }  
  
  this.nivelesok = function (wledad)
  {
    try {
      str=wledad.value;
      if (Number(str) == 0)
      {
	  window.alert('Los niveles deben ser mayor a cero');
          return false;
      }
      return true;
      } catch (err) { alert('error edadok'+err.description); }
  }  
    
  this.anook = function (wlano)
  {
    try {
	    var d = new Date();
	    var wlanolimite1=1990;
	    var wlanolimite2=d.getFullYear()+1;
		str=wlano.value;
		if (str.length!=4) {alert('La longitud del año es de 4 digitos'); wlano.focus(); return false; }
		if (str < wlanolimite1 || str > wlanolimite2 ) { alert('El año esta fuera del rango '+wlanolimite1+'-'+wlanolimite2); wlano.focus(); return false; }
      } catch (err) { alert('error anook '+err.description); }
  }
  
  this.mesok = function (wlmes)
  {
    try {
      str=wlmes.value;
      if (str < "01" || str > "12") { alert('El mes esta fuera de rango'); wlmes.focus(); return false; }
      } catch (err) { alert('error anook '+err.description); }
  }  
    
  
  this.cuentaok = function (wlcuenta)
  {
    try
    {
      str=wlcuenta.value;
      wltc=document.getElementById('wl_idtiposcobros');
	  if (wltc.value==3)  // 3=predial 1=isai 8=isr
		{
			var valretorno = this.espredial(wlcuenta);
			return valretorno;
		}
		
	  if (wltc.value==4 || wltc.value==6)  // Nomina  hospedaje 
		{
			var valretorno = this.esrfc(wlcuenta);
			return valretorno;
		}
		
		if (wltc.value==9)
		{
			tipo=this.tipoplaca (wlcuenta.value);
			if (tipo==0) { alert('El formato de la placa es incorrecto'); wlcuenta.focus(); 	return false;}
		}
		

      return true;
    }
    catch (err) { alert('error cuentaok'+err.description); }
    	  
  }

  this.cpok = function (wlcp)
  {
//	  alert('entro en cp ok');
	  try
	  {
    	str=wlcp.value;	  
		for (var i = 0; i < 5; i++)
		{
			var ch=	str.substring(i, i + 1) 
			if	((ch < "0" || "9" < ch) && ch!="")
				{ 
					window.alert('El Codigo Postal no puede contener letras');
					return false;
				}
		}
	}
	catch(err)
	{  alert('error cpok'+err.description); }				  
  }
  
  this.espredial = function (wlpredial)
  {
	  var dv=0;
	  var dvok=0;
	  var dvok2=0;
	  var dvok3=0;
	  var dmul=2;
	  var lar=0;
	  var dbg='';
	try 
	{
    str=wlpredial.value;
    if (str.length!=12 && str!="") {
       window.alert('La longitud de la cuenta predial debe ser de 12 digitos');
       return false; }
	
	for (var i = 0; i < 11; i++)
	{
		var ch=	str.substring(i, i + 1) 
		if	((ch < "0" || "9" < ch) && ch!="")
			{ 
				window.alert('La cuenta predial no puede contener letras');return false;
			}
			else
			{
				dv=( ch * dmul );
				if (dv>9)
				{
					var n1=dv.toString().substring(0,1);
					var n2=dv.toString().substring(1,2);
					dv=(parseFloat(n1)+parseFloat(n2));
				}
				dvok=( dvok	 + dv );
//				dbg+='dmul'+dmul+'suma'+dvok+'\n'
				if (dmul==2){dmul=1;}else{dmul=2;}				
			}
	}	
//	alert('dbg'+dbg);
//	dvok2=dvok.toString();
//	lar=dvok.toString().length;
//	dvok3=dvok2.substring(lar,1);
	if (dvok.toString().length==2)
	{ 	diven=dvok.toString().substring(2,1); diven=10-diven; }
	else { diven=10-dvok }
	if (diven == 10) { diven=0 } 
	var wldv_cta = parseFloat(str.substring(11,12));
	if (wldv_cta!=diven) {window.alert('El digito verificador es incorrecto');return false;}
	}
	catch(err)
	{
		alert('error espredial '+err.description);
	}

  }

  this.surfcok = function (wlrfc)
  {  
//	alert('entro en surfcok'+wlrfc.name+' value'+wlrfc.value);
    try
    {
      wlrfc=document.getElementById('wl_cuenta');	  
//	  alert('encontro cuenta'+wlrfc.value);      
//      if (wlpersona.value!='') - grecar 080103
	  if (wlrfc.value!='')
      {
	      //var regresa=this.esrfc(wlpersona);  - grecar 080103
		  var regresa=this.esrfc(wlrfc);
	      return regresa;
      }
    }
    catch(err) { 
//	    alert('error surfcok'+err.description);
	}
  }
  
	this.esrfc12 = function (wlrfc)
	{
		var str=wlrfc.value;
		var errperiodo = false;
		var errctarfc = false;
		var errdia=false;
		var mes=0;
		var dia=0;
		var ano=0;
		var patron;
		
		var letrasrfc=str.substr(0,3);
		var fecharfc=str.substr(3,6);
		var homorfc=str.substr(9,3);
		
		//alert (letrasrfc+'-'+fecharfc+'-'+homorfc);
           /*for (var i = 0; i < 3; i++)
               { var ch = str.substring(i, i + 1);if((ch < "A" || "Z" < ch) && ch!="" && ch!="&" && ch!="Ñ") errctarfc=true; }
           if (errctarfc==true) { window.alert('Los tres primeros digitos del rfc deben ser letras y mayusculas');   return false; }
           for (var i = 3; i < 9; i++)
               { var ch = str.substring(i, i + 1); if((ch < "0" || "9" < ch) && ch!="")   errctarfc=true; } 
           if (errctarfc==true) { window.alert('La fecha del rfc no es numerico');   return false;  }*/

		patron=/([\000-\045]|[\047-\100]|[\133-\177])/;
		if (patron.test(letrasrfc)) { alert ('Los tres primeros digitos del rfc solo pueden ser letras o el signo "&"'); return false; }
		patron=/([\000-\057]|[\072-\177])/;
		if (patron.test(fecharfc)) { alert ('La fecha del rfc solo puede un dato numerico'); return false; }

           dia=eval(str.substring(7,9));mes=eval(str.substring(5,7)); ano=eval(str.substring(3,5));
           if(mes<=0 || mes>=13) { window.alert('El mes del rfc esta fuera de rango');   return false; }
           
//           if(dia<=0 || dia>=32) { window.alert('El dia del rfc esta fuera de rango');   return false; }	  
		   if( mes==2 && ((ano/4)==parseInt(ano/4)) )
		      	{ if(dia<=0 || dia>29) errdia=true; }
		   if( mes==2 && ((ano/4)!=parseInt(ano/4)) )
      			{ if(dia<=0 || dia>28) errdia=true; }
		   if( mes==4 || mes==6 || mes==9 || mes==11 )
      			{ if(dia<=0 || dia>30) errdia=true; }
		   if( mes==1 || mes==3 || mes==5 || mes==7 || mes==8 || mes==10 || mes==12 )
      			{ if(dia<=0 || dia>31) errdia=true; }           
           if(errdia==true) { window.alert('El dia del rfc esta fuera de rango');   return false; }	        			

           /*var ch = str.substring(9, 10);
           if(ch=="0" || ch=="O" || ch=="\!" || ch=="\"" || ch=="\#" ||ch=="\$" || ch=="\%" || ch=="\&" || ch=="\/" || ch=="\(" || ch=="\)" || ch=="\=" || ch=="\?" || ch=="\¡")  //agregue ana 070108
           {  window.alert('El primer digito de la homonimia no puede ser 0, O, !, ", #, $, %, &, /, (, ), =, ?, ¡, ');  return false;  }
           var ch = str.substring(10, 11);
           if(ch=="0" || ch=="O" || ch=="\!" || ch=="\"" || ch=="\#" ||ch=="\$" || ch=="\%" || ch=="\&" || ch=="\/" || ch=="\(" || ch=="\)" || ch=="\=" || ch=="\?" || ch=="\¡")
           {  window.alert('El segundo digito de la homonimia no puede ser 0, O, !, ", #, $, %, &, /, (, ), =, ?, ¡, ');  return false;  }
           var ch = str.substring(11, 12);
           if(ch!="A" && (ch < "0" || "9" < ch))
           { window.alert('El tercer caracter de la homonimia debe ser una A o el digito 0 al 9');   return false;  }*/

		patron=/([\000-\060]|[\072-\100]|[\117]|[\133-\177])/;
		if (patron.test(homorfc.substr(0,2))) { alert ('Los primeros dos digitos de la homoclave solo pueden ser letras o numeros, con excepcion de la letra "O" y el numero "0"'); return false; }
		patron=/([\000-\057]|[\072-\100]|[\102-\177])/;
		if (patron.test(homorfc.substr(2,1))) { alert ('El tercer digito de la homoclave solo puede ser la letra A o un digito del 0 al 9'); return false; }
  } 
  this.esrfc13 = function (wlrfc)
  {
		var errperiodo = false;
		var errctarfc = false;
		var mes=0;
		var dia=0;
		var ano=0;
		var str=wlrfc.value;
		var errdia=false;
		var patron;

		var letrasrfc=str.substr(0,4);
		var fecharfc=str.substr(4,6);
		var homorfc=str.substr(10,3);

		//alert (letrasrfc+'-'+fecharfc+'-'+homorfc+' '+letrasrfc.substr(1,1));
		patron=/^[\000-\100]|[\133-\177]{1}/;
		if (patron.test(letrasrfc)) { alert ('El primer digito del rfc solo puede ser letra'); return false; }
		//patron=/A|E|I|O|U|X|Ñ|&/; se quito la Ñ y el & por correcciones en la definicion
		patron=/A|E|I|O|U|X/;
		if (!patron.test(letrasrfc.substr(1,1))) { alert ('El segundo digito del rfc solo puede ser vocal ó la letra X'); return false; }	
		patron=/([\000-\100]|[\133-\177])/;
		if (patron.test(letrasrfc.substr(2,2))) { alert ('El tercer y cuarto digito del rfc solo pueden ser letras'); return false; }

      /*for (var i = 0; i < 1; i++)
               { var ch = str.substring(i, i + 1);if((ch < "A" || "Z" < ch) && ch!="" && ch!="&" && ch!="Ñ") errctarfc=true; }
      if (errctarfc==true) { window.alert('El primer digito del rfc debe ser letras o mayusculas'); return false; }
      for (var i = 1; i < 2; i++)
               { var ch = str.substring(i, i + 1);if((ch != "A" && ch != "E" && ch != "I" && ch != "O" && ch != "U" ) )  { errctarfc=true; }}
      if (errctarfc==true) { window.alert('El segundo digito del rfc debe ser vocal'); return false; }   //agrege ana
      for (var i = 2; i < 4; i++)
               { var ch = str.substring(i, i + 1);if((ch < "A" || "Z" < ch) && ch!="" && ch!="&" && ch!="Ñ") errctarfc=true; }
      if (errctarfc==true) { window.alert('El tercer y cuato digitos del rfc deben ser letras y mayusculas'); return false; }   //agrege ana*/

           for (var i = 4; i < 10; i++)
               { var ch = str.substring(i, i + 1); if((ch < "0" || "9" < ch) && ch!="") errctarfc=true; } 
           if (errctarfc==true) {  window.alert('La fecha del rfc es no numerico');  return false;     }
           dia=eval(str.substring(8,10));mes=eval(str.substring(6,8)); ano=eval(str.substring(4,6));
           if(mes<=0 || mes>=13) { window.alert('El mes del rfc esta fuera de rango'); return false; }

		   if( mes==2 && ((ano/4)==parseInt(ano/4)) )
		      	{ if(dia<=0 || dia>29) errdia=true; }
		   if( mes==2 && ((ano/4)!=parseInt(ano/4)) )
      			{ if(dia<=0 || dia>28) errdia=true; }
		   if( mes==4 || mes==6 || mes==9 || mes==11 )
      			{ if(dia<=0 || dia>30) errdia=true; }
		   if( mes==1 || mes==3 || mes==5 || mes==7 || mes==8 || mes==10 || mes==12 )
      			{ if(dia<=0 || dia>31) errdia=true; }           
           if(errdia==true) { window.alert('El dia del rfc esta fuera de rango');   return false; }	        			
           
/*           var ch = str.substring(10, 11);
           if(ch=="0" || ch=="O" || ch=="\!" || ch=="\"" || ch=="\#" ||ch=="\$" || ch=="\%" || ch=="\&" || ch=="\/" || ch=="\(" || ch=="\)" || ch=="\=" || ch=="\?" || ch=="\¡")  //agregue ana 070108
           {  window.alert('El primer digito de la homonimia no puede ser 0, O, !, ", #, $, %, &, /, (, ), =, ?, ¡, ');  return false;  }                        
           var ch = str.substring(11, 12);
           if(ch=="0" || ch=="O" || ch=="\!" || ch=="\"" || ch=="\#" ||ch=="\$" || ch=="\%" || ch=="\&" || ch=="\/" || ch=="\(" || ch=="\)" || ch=="\=" || ch=="\?" || ch=="\¡")  //agregue ana 070108
           {  window.alert('El segundo digito de la homonimia no puede ser 0, O, !, ", #, $, %, &, /, (, ), =, ?, ¡, ');  return false;  }
           var ch = str.substring(12, 13);
           if(ch!="A" && (ch < "0" || "9" < ch))
           { window.alert('El tercer caracter de la homonimia debe ser una A o el digito 0 al 9'); return false;  }*/
           
		patron=/([\000-\060]|[\072-\100]|[\117]|[\133-\177])/;
		if (patron.test(homorfc.substr(0,2))) { alert ('Los primeros dos digitos de la homoclave solo pueden ser letras o numeros, con excepcion de la letra "O" y el numero "0"'); return false; }
		patron=/([\000-\057]|[\072-\100]|[\102-\177])/;
		if (patron.test(homorfc.substr(2,1))) { alert ('El tercer digito de la homoclave solo puede ser la letra A o un digito del 0 al 9'); return false; }
  }
  this.esrfc10 = function (wlrfc)
  {
		var errperiodo = false;
		var errctarfc = false;
		var mes=0;
		var dia=0;
		var ano=0;
		var str=wlrfc.value;
		var errdia=false;
		var patron;

		var letrasrfc=str.substr(0,4);
		var fecharfc=str.substr(4,6);
		//var homorfc=str.substr(10,3);

		//alert (letrasrfc+'-'+fecharfc+'-'+homorfc+' '+letrasrfc.substr(1,1));
		patron=/^[\000-\100]|[\133-\177]{1}/;
		if (patron.test(letrasrfc)) { alert ('El primer digito del rfc solo puede ser letra'); return false; }
		patron=/A|E|I|O|U|X|Ñ|&/;
		if (!patron.test(letrasrfc.substr(1,1))) { alert ('El segundo digito del rfc solo puede ser vocal, "Ñ", "X" o el signo "&"'); return false; }	
		patron=/([\000-\100]|[\133-\177])/;
		if (patron.test(letrasrfc.substr(2,2))) { alert ('El tercer y cuarto digito del rfc solo pueden ser letras'); return false; }

      /*for (var i = 0; i < 1; i++)
               { var ch = str.substring(i, i + 1);if((ch < "A" || "Z" < ch) && ch!="" && ch!="&" && ch!="Ñ") errctarfc=true; }
      if (errctarfc==true) { window.alert('El primer digito del rfc debe ser letras o mayusculas'); return false; }
      for (var i = 1; i < 2; i++)
               { var ch = str.substring(i, i + 1);if((ch != "A" && ch != "E" && ch != "I" && ch != "O" && ch != "U" ) )  { errctarfc=true; }}
      if (errctarfc==true) { window.alert('El segundo digito del rfc debe ser vocal'); return false; }   //agrege ana
      for (var i = 2; i < 4; i++)
               { var ch = str.substring(i, i + 1);if((ch < "A" || "Z" < ch) && ch!="" && ch!="&" && ch!="Ñ") errctarfc=true; }
      if (errctarfc==true) { window.alert('El tercer y cuato digitos del rfc deben ser letras y mayusculas'); return false; }   //agrege ana*/

           for (var i = 4; i < 10; i++)
               { var ch = str.substring(i, i + 1); if((ch < "0" || "9" < ch) && ch!="") errctarfc=true; } 
           if (errctarfc==true) {  window.alert('La fecha del rfc es no numerico');  return false;     }
           dia=eval(str.substring(8,10));mes=eval(str.substring(6,8)); ano=eval(str.substring(4,6));
           if(mes<=0 || mes>=13) { window.alert('El mes del rfc esta fuera de rango'); return false; }

		   if( mes==2 && ((ano/4)==parseInt(ano/4)) )
		      	{ if(dia<=0 || dia>29) errdia=true; }
		   if( mes==2 && ((ano/4)!=parseInt(ano/4)) )
      			{ if(dia<=0 || dia>28) errdia=true; }
		   if( mes==4 || mes==6 || mes==9 || mes==11 )
      			{ if(dia<=0 || dia>30) errdia=true; }
		   if( mes==1 || mes==3 || mes==5 || mes==7 || mes==8 || mes==10 || mes==12 )
      			{ if(dia<=0 || dia>31) errdia=true; }           
           if(errdia==true) { window.alert('El dia del rfc esta fuera de rango');   return false; }	        			
           
/*           var ch = str.substring(10, 11);
           if(ch=="0" || ch=="O" || ch=="\!" || ch=="\"" || ch=="\#" ||ch=="\$" || ch=="\%" || ch=="\&" || ch=="\/" || ch=="\(" || ch=="\)" || ch=="\=" || ch=="\?" || ch=="\¡")  //agregue ana 070108
           {  window.alert('El primer digito de la homonimia no puede ser 0, O, !, ", #, $, %, &, /, (, ), =, ?, ¡, ');  return false;  }                        
           var ch = str.substring(11, 12);
           if(ch=="0" || ch=="O" || ch=="\!" || ch=="\"" || ch=="\#" ||ch=="\$" || ch=="\%" || ch=="\&" || ch=="\/" || ch=="\(" || ch=="\)" || ch=="\=" || ch=="\?" || ch=="\¡")  //agregue ana 070108
           {  window.alert('El segundo digito de la homonimia no puede ser 0, O, !, ", #, $, %, &, /, (, ), =, ?, ¡, ');  return false;  }
           var ch = str.substring(12, 13);
           if(ch!="A" && (ch < "0" || "9" < ch))
           { window.alert('El tercer caracter de la homonimia debe ser una A o el digito 0 al 9'); return false;  }*/
           
/*		patron=/([\000-\060]|[\072-\100]|[\117]|[\133-\177])/;
		if (patron.test(homorfc.substr(0,2))) { alert ('Los primeros dos digitos de la homoclave solo pueden ser letras o numeros, con excepcion de la letra "O" y el numero "0"'); return false; }
		patron=/([\000-\057]|[\072-\100]|[\102-\177])/;
		if (patron.test(homorfc.substr(2,1))) { alert ('El tercer digito de la homoclave solo puede ser la letra A o un digito del 0 al 9'); return false; }*/
  }
  // valida el rfc de acuerdo a si es una persona fisica o moral
  this.esrfc = function (wlrfc)
  {
       var errperiodo = false;  var errctarfc = false;  var mes=0;   var dia=0;
       var ano=0;       var str="";    str=wlrfc.value;
    	try
    	{
	  		// este codigo esta especificamente pensado para el padron de hospedaje  
      		wlpersona=document.getElementById('wl_caracter3_0');
      		if (wlpersona.value=='1' && str.length!=13)
      			{ window.alert('La longitud del rfc no corresponde a una persona fisica'); return false;  }
      		if (wlpersona.value=='2' && str.length!=12)
      			{ window.alert('La longitud del rfc no corresponde a una persona moral'); return false;   }      
  		}        
  		catch (err) { 	  	}
  		var wlreturn = this.valrfc(wlrfc);
    	return wlreturn;
	}
  // valida el rfc de acuerdo a si es una persona fisica o moral
  
  this.escurp = function (wlrfc)
  {
var errperiodo = false;  var errctarfc = false;  var mes=0;   var dia=0;
       var ano=0;       var str="";    str=wlrfc.value;;
       
        	if (str.length!=18 )
    	     { window.alert('La longitud del curp esta erronea');      return false; }
            
            
            for (var i = 0; i < 4; i++)
             { var ch = str.substring(i, i + 1);if((ch < "A" || "Z" < ch) && ch!="" && ch!="&" && ch!="Ñ") errctarfc=true; }
            if (errctarfc==true) { window.alert('Los cuatros primeros digitos del curp deben ser letras y mayusculas'); return false; }
            
            for (var i = 1; i < 2; i++)
             { var ch = str.substring(i, i + 1);if((ch != "A" && ch != "E" && ch != "I" && ch != "O" && ch != "U" ) )  { errctarfc=true; }}
            if (errctarfc==true) { window.alert('El segundo digito del curp debe ser vocal'); return false; }
                                   
            
            for (var i = 4; i < 10; i++)
             { var ch = str.substring(i, i + 1); if((ch < "0" || "9" < ch) && ch!="") errctarfc=true; } 
            if (errctarfc==true) {  window.alert('La fecha del curp no es correcta');  return false;     }
             dia=eval(str.substring(8,10));mes=eval(str.substring(6,8)); ano=eval(str.substring(4,6));
            if(mes<=0 || mes>=13) { window.alert('El mes del curp esta fuera de rango'); return false; }
            if(dia<=0 || dia>=32) { window.alert('El dia del curp esta fuera de rango'); return false; }
            var ch = str.substring(10, 11);
//           if(ch=="M" || ch=="H" )
//           { }
//          	else
//           {  window.alert('El sexo esta mal');  return false;  }                   // esto es lo mismo que la instruccion de abajo       
            if(ch!="M" && ch!="H" )           
             {  window.alert('El sexo del curp no es correcto debe ser "M" mujer "H" hombre que cooresponde a la posicion 11');  return false;  }                                     
            var ch = str.substring(11, 12);
            if(ch < "A" || "Z" < ch)
             {  window.alert('El lugar de nacimiento deben ser letras y mayusculas que corresponden a la posicion 12 y 13 ');  return false; }
            var ch = str.substring(12, 13);
            if(ch < "A" || "Z" < ch)
             {  window.alert('El lugar de nacimiento deben ser letras y mayusculas que corresponden a la posicion 12 y 13 '); return false;  }
            var ch = str.substring(13,14);
            if(ch < "A" || "Z" < ch)
             {  window.alert(' debe ser letras y mayusculas la posicion 14 ');  return false; }
            var ch = str.substring(14, 15);
            if(ch < "A" || "Z" < ch)
             { window.alert(' debe ser letras y mayusculas la posicion 15 '); return false;  }
            var ch = str.substring(15, 16);
            if(ch < "A" || "Z" < ch)
             {  window.alert('debe ser letras y mayusculas la posicion 16 ');  return false; }
			
             /*
            var ch = str.substring(16, 17);
            if(ch < "0" || "9" < ch)
             { window.alert('El primer caracter de la homonimia debe ser numerico'); return false;  }      
            var ch = str.substring(17, 18);
            if(ch < "0" || "9" < ch)
             { window.alert('El segundo caracter de la homonimia debe ser numerico'); return false;  }         
             */
             
             
             
  }	

	// valida que el dato que se reciba tenga el formato de un rfc sin importar si es fisica o moral
	this.valrfc = function (wlrfc)
	{
       var errperiodo = false;  var errctarfc = false;  var mes=0;   var dia=0;
       var ano=0;       var str="";    str=wlrfc.value;
    	if (str.length!=12 && str.length!=13 && str!="" && str.length!=10) 
    	{ window.alert('La longitud del rfc esta erronea');      return false; }
  		var wlreturn;
  		try
  		{
	    	if (str!="")
    		{
        		if (str.length==12) 
	        	{ wlreturn=this.esrfc12(wlrfc);   	}
    	
    	    	if (str.length==13)
        		{ wlreturn=this.esrfc13(wlrfc) ;  	}
        		
    	    	if (str.length==10)
        		{ wlreturn=this.esrfc10(wlrfc) ;  	}

			}
  		}        
  		catch (err) { 
		  	alert('error valrfc'+err.description); 
		}	
    	return wlreturn;
	}
	/**
	* funcion para validar patron para el formato de una placa
	*/
	this.tipoplaca	= function (placa)
	{
	try
	{
	var patron;
	var tipo=0;
		// patron del tipo de placa A - ok!
		patron=/^[1-9][0-9]{2}[A-Z]{3}$/
		if (patron.test(placa) && placa.length==6 && parseFloat(placa.substr(0,3))>99) { tipo='A';}
		// patron del tipo de placa B - ok!
		patron=/^[1-9][0-9]{3}[A-W][A-Z]$/
		if (patron.test(placa) && placa.length==6) { tipo='B';}
		// patron del tipo de placa C - ok!
		patron=/^[1-9][A-Z]{3}$/
		if (patron.test(placa) && placa.length==4) { tipo='C';}
		// patron del tipo de placa D - ok!
		patron=/^[A-Z][0-9]{4}$/
		if (patron.test(placa) && placa.length==5 && parseFloat(placa.substr(1,4))>0 ) { tipo='D';}
		patron=/^[0-9]\d+$/
		if (patron.test(placa) && placa.length==6 && (parseFloat(placa)>=400001 && parseFloat(placa)<=500000)) { tipo='D';}
		// patron del tipo de placa E - AUTO DE ALQUILER - ok!
		patron=/^[0-9]\d+$/
		if (patron.test(placa) && placa.length==7 && (parseFloat(placa)>=10001 && parseFloat(placa)<=9999999)) { tipo='E';}
		// patron del tipo de placa E - COLECTIVO (METROPOLITANO) - ok!
		patron=/^[0-9]{3}[N][A][0-9]{3}[C|M]$/
		if (patron.test(placa) && placa.length==9 && parseFloat(placa.substr(0,3))>0 && parseFloat(placa.substr(5,3))>0 ) { tipo='E';}
		patron=/^[0-9]{3}[N][A][0-9]{3}$/
		if (patron.test(placa) && placa.length==8 && parseFloat(placa.substr(0,3))>0 && parseFloat(placa.substr(5,3))>0 ) { tipo='E';}		
		patron=/^[0-9]{3}[T][L][0-9]{3}[C|M]$/
		if (patron.test(placa) && placa.length==9 && parseFloat(placa.substr(0,3))>0 && parseFloat(placa.substr(5,3))>0 ) { tipo='E';}
		patron=/^[0-9]{3}[T][L][0-9]{3}$/
		if (patron.test(placa) && placa.length==8 && parseFloat(placa.substr(0,3))>0 && parseFloat(placa.substr(5,3))>0 ) { tipo='E';}
		patron=/^[0-9]{3}[N][Z][0-9]{3}[C|M]$/
		if (patron.test(placa) && placa.length==9 && parseFloat(placa.substr(0,3))>0 && parseFloat(placa.substr(5,3))>0 ) { tipo='E';}
		patron=/^[0-9]{3}[N][Z][0-9]{3}$/
		if (patron.test(placa) && placa.length==8 && parseFloat(placa.substr(0,3))>0 && parseFloat(placa.substr(5,3))>0 ) { tipo='E';}
		patron=/^[0-9]{3}[E][C][0-9]{3}[C|M]$/
		if (patron.test(placa) && placa.length==9 && parseFloat(placa.substr(0,3))>0 && parseFloat(placa.substr(5,3))>0 ) { tipo='E';}
		patron=/^[0-9]{3}[E][C][0-9]{3}$/
		if (patron.test(placa) && placa.length==8 && parseFloat(placa.substr(0,3))>0 && parseFloat(placa.substr(5,3))>0 ) { tipo='E';}
		patron=/^[0-9]{3}[C][H][0-9]{3}[C|M]$/
		if (patron.test(placa) && placa.length==9 && parseFloat(placa.substr(0,3))>0 && parseFloat(placa.substr(5,3))>0 ) { tipo='E';}
		patron=/^[0-9]{3}[C][H][0-9]{3}$/
		if (patron.test(placa) && placa.length==8 && parseFloat(placa.substr(0,3))>0 && parseFloat(placa.substr(5,3))>0 ) { tipo='E';}
		patron=/^[0-9]{3}[T][X][0-9]{3}[C|M]$/
		if (patron.test(placa) && placa.length==9 && parseFloat(placa.substr(0,3))>0 && parseFloat(placa.substr(5,3))>0 ) { tipo='E';}
		patron=/^[0-9]{3}[T][X][0-9]{3}$/
		if (patron.test(placa) && placa.length==8 && parseFloat(placa.substr(0,3))>0 && parseFloat(placa.substr(5,3))>0 ) { tipo='E';}
		// patron del tipo de placa E - TAXI SIN ITINERARIO - ok!
		patron=/^[L][0-9]{5}$/
		if (patron.test(placa) && placa.length==6 && parseFloat(placa.substr(1,5))>0 ) { tipo='E';}
		patron=/^[A][0-9]{5}$/
		if (patron.test(placa) && placa.length==6 && parseFloat(placa.substr(1,5))>0 ) { tipo='E';}
		// patron del tipo de placa E - TAXI DE SITIO - ok!
		patron=/^[S][0-9]{5}$/
		if (patron.test(placa) && placa.length==6 && parseFloat(placa.substr(1,5))>0 ) { tipo='E';}
		patron=/^[B][0-9]{5}$/
		if (patron.test(placa) && placa.length==6 && parseFloat(placa.substr(1,5))>0 ) { tipo='E';}
		// patron del tipo de placa F - CARGA GENERAL - ok!
		patron=/^[0-9]\d+$/
		if (patron.test(placa) && placa.length==6 && (parseFloat(placa)>=200001 && parseFloat(placa)<=300000)) { tipo='F';}
		// patron del tipo de placa H - OMNIBUS URBANO DE PASAJEROS
		patron=/^[0-9]\d+$/
		if (patron.test(placa) && placa.length==6 && (parseFloat(placa)>=300001 && parseFloat(placa)<=400000)) { tipo='H';}
		// patron del tipo de placa I - DIPLOMATICOS - ok!
		patron=/^[A-Z]{3}[0-9]{3}$/
		if (patron.test(placa) && placa.length==6 && parseFloat(placa.substr(3,3))>0 ) { tipo='I';}
		patron=/^[0-9]{2}[A-Z]{2}$/
		if (patron.test(placa) && placa.length==4 && parseFloat(placa.substr(0,2))>0) { tipo='I';}
		// patron del tipo de placa J - DEMOSTRADORAS - ok!
		patron=/^[Y-Z][K-Z][0-9]{2}$/
		if (patron.test(placa) && placa.length==4 && parseFloat(placa.substr(2,2))>0) { tipo='J';}
		patron=/^[1-9][Y-Z][K-Z][0-9]{2}$/
		if (patron.test(placa) && placa.length==5 && parseFloat(placa.substr(3,2))>0) { tipo='J';}
		// patron del tipo de placa K - AUTO ANTIGUO - ok!
		patron=/^[C][A-Z][0-9]{3}$/
		if (patron.test(placa) && placa.length==5 && parseFloat(placa.substr(2,3))>0) { tipo='K';}
		// patron del tipo de placa L - S.P.F. CARGA - ok!
		//patron=/^[0-9]{2}[1-9][A-E][A-L][1-9]$/ Jose Luis cardenas idico el rengo correcto 
		patron=/^[0-9]{3}[A][A-Z][1-9]$/
		if (patron.test(placa) && placa.length==6 && parseFloat(placa.substr(0,3))>0) { tipo='L';}
		patron=/^[0-9]{3}[B][A-Z][1-9]$/
		if (patron.test(placa) && placa.length==6 && parseFloat(placa.substr(0,3))>0) { tipo='L';}
		patron=/^[0-9]{3}[C][A-Z][1-9]$/
		if (patron.test(placa) && placa.length==6 && parseFloat(placa.substr(0,3))>0) { tipo='L';}
		patron=/^[0-9]{3}[D][A-Z][1-9]$/
		if (patron.test(placa) && placa.length==6 && parseFloat(placa.substr(0,3))>0) { tipo='L';}
		patron=/^[0-9]{3}[E][A-L][1-9]$/
		if (patron.test(placa) && placa.length==6 && parseFloat(placa.substr(0,3))>0) { tipo='L';}
		// patron del tipo de placa M - SERV. PUB. FED. PASAJE - ok!
		patron=/^[0-9]{3}[H-L][A-Z][1-9]$/
		if (patron.test(placa) && placa.length==6 && parseFloat(placa.substr(0,3))>0) { tipo='M';}
		// patron del tipo de placa N - SERV. PUB. FED. TURISMO - ok!
		patron=/^[0-9]{3}[R][A-Z][1-9]$/
		if (patron.test(placa) && placa.length==6 && parseFloat(placa.substr(0,3))>0) { tipo='N';}
		// patron del tipo de placa P - S.P.F. ARREND. PARTICULAR - ok!
		patron=/^[0-9]{3}[F-G][A-Z][1-9]$/
		if (patron.test(placa) && placa.length==6 && parseFloat(placa.substr(0,3))>0) { tipo='P';}
		// patron del tipo de placa Q - S.P.F. ARREND. PASAJE - ok!
		patron=/^[0-9]{3}[M][A-Z][1-9]$/
		if (patron.test(placa) && placa.length==6 && parseFloat(placa.substr(0,3))>0) { tipo='Q';}
		// patron del tipo de placa R - S.P.F. ARREND. TURISMO - ok!
		patron=/^[0-9]{3}[S-T][W][1-9]$/
		if (patron.test(placa) && placa.length==6 && parseFloat(placa.substr(0,3))>0) { tipo='R';}
		// patron del tipo de placa S - S.P.F REMOLQUES - ok!
		patron=/^[0-9]{3}[T][X-Z][1-9]$/
		if (patron.test(placa) && placa.length==6 && parseFloat(placa.substr(0,3))>0) { tipo='S';}
		patron=/^[0-9]{3}[V][A-Z][1-9]$/
		if (patron.test(placa) && placa.length==6 && parseFloat(placa.substr(0,3))>0) { tipo='S';}
		// patron del tipo de placa T - S.P.F. ARREND. REMOLQUES - ok!
		patron=/^[0-9]{3}[Y-Z][A-Z][1-9]$/
		if (patron.test(placa) && placa.length==6 && parseFloat(placa.substr(0,3))>0) { tipo='T';}
		// patron del tipo de placa Y - MOTOCICLETAS - ok!
		patron=/^[0-9]\d+$/
		if (patron.test(placa) && placa.length==5 && (parseFloat(placa)>=00001 && parseFloat(placa)<=99999)) { tipo='Y';}
		patron=/^[0-9]{4}[A-W]$/
		if (patron.test(placa) && placa.length==5 && parseFloat(placa.substr(0,4))>0) { tipo='Y';}
		// patron del tipo de placa Z - PER. C/DISCAPACIDAD - ok!
		patron=/^[0-9]{3}[S][W-Z]$/
		if (patron.test(placa) && placa.length==5 && parseFloat(placa.substr(0,3))>0) { tipo='Z';}
		// patron del tipo de placa W - SUSTITUCION DE UNIDA - ok!
		patron=/^[S][U][S][0-9]{6}$/
		if (patron.test(placa) && placa.length==9 && parseFloat(placa.substr(3,6))>0) { tipo='W';}
		return tipo;
	} catch (err) { alert ('error tipoplaca '+err.description); }
	}
	
}

//</script>
