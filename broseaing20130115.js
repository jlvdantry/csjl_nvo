//<script>
// variables globales
var isIE = false;
var req;
var wlurl;
var glr=0 ;  // variable donde se gurdar el renglon a actualizar
var passData ; // variable donde se guardan todos los datos de la format
var __eventocontinua = false;   // resultado o respuesta de haber llamado un objeto en el servidor
var Cambiosize = 0;
var autocomplete = "";

/*
   funcion para quitar el enter en los campos texto y de un click en el primer boton
  */
function quitaenter(e) {
	if (e.keyCode==13) {e.keyCode=9; return e.keyCode }
    return e.keyCode;

}

function armaImgPdf (archivo)
{
	//alert (archivo);	
	archivoarr=archivo.split('.');
	//alert  (archivoarr[1]);
	if (archivoarr[1]=='jpg')
	{
		eventos_servidor("",0,"armaImgPdf","","",document.body.clientWidth,document.body.clientHeight);
	}	else	{
		open ('upload_ficheros/'+archivo,'x');
	}
}

function formReset(wlforma,limpiaralta)
{
	if (limpiaralta=='t')
  		document.getElementById(wlforma).reset();
  		//alert ('entro0');
  theForm = document.getElementById(wlforma);
  var qs = '';
  for (e=0;e<theForm.elements.length;e++) {
    if (theForm.elements[e].name!='' && theForm.elements[e].name.indexOf('nc_')>=0) {
	    //alert ('entro1');
	   var str=theForm.elements[e].name;
//	   var nc=str.replace(/nc_/,"nc_");
       var wl=str.replace(/nc_/,"wl_");		  		
	   try { objwl=document.getElementById(wl); objnc=document.getElementById(str); 
	          objnc.readOnly=false; objwl.readOnly=false; objwl.disabled=false; objwl.className=''; 
	          	//alert('cambio'+str+' nc'+nc);
	       } catch (err) { };

    }
  }
  return qs

}

function siguienteregistro()
{
	var nr=parseInt(glr)+1;
   	var wlid = 	'cam'+nr;
   	try { document.getElementById(wlid).click() } catch (err) { };   		
}
function anteriorregistro()
{
	var nr=parseInt(glr)-1;
   	var wlid = 	'cam'+nr;
   	try { document.getElementById(wlid).click() } catch (err) { };   		
}
function registroinicial()
{

   	var wlid = 	'cam0';
   	try { document.getElementById(wlid).click() } catch (err) { };   		
}
function registrofinal()
{
//	alert('rows'+document.getElementById('tabdinamica').rows.length);
	var nr=document.getElementById('tabdinamica').rows.length-2;
   	var wlid = 	'cam'+nr;
   	try { document.getElementById(wlid).click() } catch (err) { };   		
}

	/*	restaura del campos donde los valores del autocomplete
	*/
  function restaura_autocomplete(objeto)
  {
      var nombre=objeto.name.replace(/wl_/,"au_");						  
	  document.getElementById(nombre).value="";
  }
	/*	LLena un campo select con los datos tecleados
	*/  
  function autollenado(objeto,e,sql,filtro)
  {
		if(e.keyCode==13 || e.keyCode==9 || e.keyCode==16 || e.keyCode==48 || e.keyCode==36 || e.keyCode==40 || e.keyCode==38 || e.keyCode==91 || e.keyCode==18)return;	  
//		alert('keycode'+e.keyCode);
		try 
		{ 
			var nombre=objeto.name.replace(/wl_/,"au_");					
			if(e.keyCode==8 || e.keyCode==46 || e.keyCode==37)
			{	
				var len = document.getElementById(nombre).value.length;
				if (len>=1)
				{
					document.getElementById(nombre).value=document.getElementById(nombre).value.substring(0,len-1); 
				}
				if (len==1) return;
			}
			else
			{	document.getElementById(nombre).value+=String.fromCharCode(e.keyCode) ; }
		    pon_Select(sql,'',objeto.name.replace(/wl_/,""),filtro+' like \''+nombre+'%\'',0,1);	  		
		}
		catch (err) { alert('no existe el campo de autollenado'+err.description+'objeto.name'+objeto.name ); }
  }
  function toggleDiv(divid,objeto){
	try {
//		alert('objeto'+objeto.innerHTML);
    	if(document.getElementById(divid).style.display == 'none'){
      		document.getElementById(divid).style.display = 'block';
      		objeto.innerHTML=objeto.innerHTML.replace(/Mostrar/,"Ocultar");      	      		
    	}else{
      		document.getElementById(divid).style.display = 'none';
      		objeto.innerHTML=objeto.innerHTML.replace(/Ocultar/,"Mostrar");
    	}
	}
	catch(err) 
	{ alert('error en toggleDiv '+err.description); };    
  }

/*  20070524
	Funcion que solamente indica que la pantalla sufrio un cambio de pantalla,
	que al cerrarse la ventana manda a actualizar a el servidor el size de la pantalla
	Parametro recibido numero de menu
*/
function Cambiasize(idmenu)
{	stickhead(); // esta line va ligado con sortable_otro.js 20070524
    Cambiosize=idmenu; 
//		alert('size'+window.outerwidth+' si'+window.outerheight);    
//		alert('size'+document.body.clientWidth+' si'+document.body.clientHeight);    
//    alert('idemenu'+idmenu);
    }
/*    20071029
 *    se cambio para que en vez de utilizar el onchange se utilizar el keyup
 *    para cambiar de mayusculas a minusculas y viceversa ya que el onchange no funciona
 *    cuando se cambia varias veces en la misma session de mayusculas a minusculas
 */  
function mayusculas(objeto,evento)    
{
	//alert(evento.keyCode);
	if (evento.keyCode=='37' || evento.keyCode=='36' || evento.keyCode=='8' || evento.keyCode=='46' || evento.keyCode=='39')
	{ }
	else
	{   objeto.value=objeto.value.toUpperCase();  return true; }
}

function minusculas(objeto,evento)    
{
//	alert(evento.keyCode);
	if (evento.keyCode=='37' || evento.keyCode=='36' || evento.keyCode=='8' || evento.keyCode=='46' || evento.keyCode=='39')
	{ }
	else
	{   objeto.value=objeto.value.toLowerCase();  return true; }
}

/*  20070702
    Da un click al boto de seleccionar para que muestre el renglon en los campos de captura
    recibe el nombre del campo
*/        
function daunClick(wlcampo)
{
	try
	{	document.getElementById(wlcampo).click(); }
	catch (err) { return false; };
}

/*  20070702
    Da un click al boto de seleccionar para que muestre el renglon en los campos de captura
    recibe el nombre del campo
*/        
function desplega(si)
{
	si.focus();
//	alert('si');
}

function sumatotales(theFormName) {
	try
	{
	    var tfoot = '';
//  alert('rows'+document.getElementById('tabdinamica').rows.length);
//	var all = document.getElementsByTagName("th");
		var tabla=document.getElementById('tabdinamica')
		if (tabla.rows.length>1)
		{
			var renglon=tabla.insertRow();
			header=document.getElementById('tabdinamica').rows[0];
//		alert('cells'+document.getElementById('tabdinamica').rows[0].cells.length);
			for( var i=0; i<header.cells.length ; ++i ) {
//			alert('celda'+document.getElementById('tabdinamica').rows[0].cells[i].name);
				if (header.cells[i].name=='totales')
				{
//				tfoot=tfoot+'<th>'+dametotalcolumna(i)+'</th';
					var x=renglon.insertCell(i);
					x.innerHTML=dametotalcolumna(i);
					x.style.fontWeight="bold";
					x.style.borderTop="thin solid #0000FF";
				}
				else
				{
					var x=renglon.insertCell(i);
					header.cells[i].name=='noimprime' ? x.name='noimprime' : x.name="";
//				tfoot=tfoot+'<th></th>';				
				}
			}
		}  
	}
	catch(err) { alert('error en sumatotales'); }
}

function dametotalcolumna(colu)
{
	try
	{
		var vart = 0;
		for( var i=1; i<document.getElementById('tabdinamica').rows.length ; ++i ) {
//			alert('elemento'+colu+' i='+i+" inner="+document.getElementById('tabdinamica').rows[i].cells[colu].innerHTML);
			if (isNaN(document.getElementById('tabdinamica').rows[i].cells[colu].innerHTML)==false)
			{
				vart = vart + Number(document.getElementById('tabdinamica').rows[i].cells[colu].innerHTML);
			}
		}
		return vart;
	}
	catch (err) { alert('error dametotalcolumna'); return 0; }
}

function quecamponoimprime(theFormName,desplega) {

 theForm = document.getElementById(theFormName);
  var qs = '';
//  alert('entro a validadar fechas');
  for (e=0;e<theForm.elements.length;e++) {
//  	alert('name '+theForm.elements[e].name+" type"+theForm.elements[e].type);
    if (theForm.elements[e].name!='' && (theForm.elements[e].name.indexOf('_np_')>=0 || theForm.elements[e].name=='noimprime') ){
	   var str=theForm.elements[e].name;
	   var str1=str.replace(/_np_/,"wl_");
//	   alert('str1'+str1);
	   x=document.getElementById(str1);	    
    	x.style.display=desplega;		
	   str1=str.replace(/_np_/,"wlt_");
	   x=document.getElementById(str1);	    		
		x.style.display=desplega;			   
    }
  }
  
	var all = document.getElementsByTagName("TABLE");
	for( var i=0; i<all.length; ++i ) {
		if( all[i].name=="noimprime" || all[i].name=="tabbotones") {
			all[i].style.display=desplega
		}
	}  
	var all = document.getElementsByTagName("a");
	for( var i=0; i<all.length; ++i ) {
			all[i].style.display=desplega
	}  	
	var all = document.getElementsByTagName("center");
	for( var i=0; i<all.length; ++i ) {
			all[i].style.display=desplega
	}  	
	var all = document.getElementsByTagName("input");
	for( var i=0; i<all.length; ++i ) {
		if( all[i].type=="button" || all[i].name=="botcam" ) {
			all[i].style.display=desplega
		}
	}  			
	
	var all = document.getElementsByTagName("th");
	for( var i=0; i<all.length; ++i ) {
		if( all[i].name=="noimprime") {
			all[i].style.display=desplega
		}
	}
		
	var all = document.getElementsByTagName("td");
	for( var i=0; i<all.length; ++i ) {
		if( all[i].name=="noimprime") {
			all[i].style.display=desplega
		}
				
	}  			
//	try { document.getElementsByName('botcam').style.display='none' } catch (err) { };
  return true	
	
}

/*  20070707
    Funcion que la impresi�n se ve en patalla
*/        
function imprime()
{
try
{
	if (top.frames.length==1)
		{ imprime_sinframes(); }
	else { imprime_conframes(); }
}
	catch(e){alert("Fallo la impresion! " + e.message); 		top.document.getElementById('fs').cols=varcols;	}
}

function imprime_sinframes()
{
var OLECMDID = 7; 
try
{
	var PROMPT = 1; // 1 PROMPT & 2 DONT PROMPT USER 
 	var oWebBrowser = document.getElementById("WebBrowser1");
		alert('entro'); 	
	if(oWebBrowser == null)
	{
		quecamponoimprime('formpr','none');
		var sWebBrowser = '<OBJECT ID="WebBrowser1" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></OBJECT>'; 
//		alert('antes de inserta');
//		top.frames(0).document.body.insertAdjacentHTML('beforeEnd', sWebBrowser); 
		document.body.insertAdjacentHTML('beforeEnd', sWebBrowser); 		
//		alert('despues de inserta='+document.URL);		
//		top.frames(0).WebBrowser1.ExecWB(OLECMDID,PROMPT);
		oWebBrowser = document.getElementById("WebBrowser1");
//		alert('despues del get1 '+oWebBrowser.outerHTML);		
		oWebBrowser.ExecWB(7,-1);		
//		oWebBrowser.ExecWB(1,PROMPT);				
//		alert('despues de exec');				
		quecamponoimprime('formpr','');
		oWebBrowser.outerHTML='';
//		top.frames(0).WebBrowser1.outerHTML="";	
	}
}
	catch(e){ alert("Fallo la impresion sinframes! " + e.description );			quecamponoimprime('formpr',''); oWebBrowser.outerHTML='';}	
}

function imprime_conframes()
{
var OLECMDID = 7; 
/* OLECMDID values: 
* 6 - print 
* 7 - print preview 
* 8 - page setup (for printing) 
* 1 - open window 
* 4 - Save As 
* 10 - properties 
*/
try
{
	varcols=top.document.getElementById('fs').cols;
	varrows=top.document.getElementById('fs').rows;			
	var PROMPT = 1; // 1 PROMPT & 2 DONT PROMPT USER 
 	var oWebBrowser = document.getElementById("WebBrowser1");
	if(oWebBrowser == null)
	{
		quecamponoimprime('formpr','none');
		var sWebBrowser = '<OBJECT ID="WebBrowser1" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></OBJECT>'; 
		if (top.document.getElementById('fs').cols=='25%,*')
		{ top.document.getElementById('fs').cols='0%,100%'; }
		else
		{ top.document.getElementById('fs').rows='0%,100%'; top.document.getElementById('fs').cols=''; 
		}		
		top.frames('derecho').document.body.insertAdjacentHTML('beforeEnd', sWebBrowser); 
		top.frames('derecho').WebBrowser1.ExecWB(OLECMDID,PROMPT);
		quecamponoimprime('formpr','');
		top.frames('derecho').WebBrowser1.outerHTML="";	
		top.document.getElementById('fs').cols=varcols;		
		top.document.getElementById('fs').rows=varrows;				
	}
}
	catch(e){alert("Fallo la impresion conframes! " + e.message); 		top.document.getElementById('fs').cols=varcols;	}	
}
        
/*  20070524
	Funcion que se ejecuta al cerrar la ventana y lo unico que hace es actualizar el size de la misma
	si esta fue cambiada
*/    
function Cierraforma()
{
	// cambiosize contiene el numero de menu
	if (Cambiosize!=0)
	{
//		alert('size'+document.body.clientWidth+' si'+document.body.clientHeight);
		eventos_servidor("cambio de size",0,"cambiotamano","",Cambiosize,document.body.clientWidth,document.body.clientHeight)		;
	}
}    

//  quita los espacios del lado izquierdo y derecho de un string
String.prototype.trim = function()
{
   return this.replace(/(^\s*)|(\s*$)/g, "");
}


//  20070703 se habilito el popmenu y cambio de color del renglon seleccionado
function muestra_renglon(wlrenglon)
{

   	var wlid = 	'cam'+wlrenglon.id.substring(2);
/*   	
   	muestra_renglon.poneclase(wlrenglon,'todofoco');
  	var wltabladinamica = document.getElementById('tabdinamica');
   	var wlTRs = wltabladinamica.getElementsByTagName('TR');  	
  	for (e=0;e<wlTRs.length;e++) {		
		if (wlTRs[e].className=='todofoco')
		{
				muestra_renglon.poneclase(wlTRs[e],'');
				wlTRs[e].className='';   			
		}
  	}  	
  	wlrenglon.className='todofoco';   			
  	*/
   	try { document.getElementById(wlid).click() } catch (err) { };   	
}

/* funcion que cambia el color del renglon seleccionado
*/
function color_renglon(wlrenglon)
{
   	color_renglon.poneclase(wlrenglon,'todofoco');
  	var wltabladinamica = document.getElementById('tabdinamica');
   	var wlTRs = wltabladinamica.getElementsByTagName('TR');  	
  	for (e=0;e<wlTRs.length;e++) {		
		if (wlTRs[e].className=='todofoco' && wlTRs[e].id!=wlrenglon.id)
		{
				color_renglon.poneclase(wlTRs[e],'');
				wlTRs[e].className='';
				break;   			
		}
  	}  	
  	wlrenglon.className='todofoco';   				
}


color_renglon.poneclase= function(wlrenglon,wlclase)
{
//	alert('entro en muestra_renglon pone clase='+wlclase);	
   	var siTD = wlrenglon.getElementsByTagName('TD');
   	for (ex=0;ex<siTD.length;ex++) {		
//		alert('esta cambiando='+siTD[ex].className);				   	
		if (siTD[ex].className!='botones')
		{
			siTD[ex].className=wlclase;
		}
  	}
}

/*  20070710 se inclulyo el popup menu a nivel de la tabla de captura */
function contextForTABLE(objtabla)
{
	var wlTABLE=document.getElementById("tabbotones");

//	alert('tabbotones='+wlTABLE.innerHTML);

	var siSelect = wlTABLE.getElementsByTagName('TD');
	var wlstr='';
//	alert('longitud'+siSelect.length);
	if (siSelect.length>0)
	{
//			alert('entro if');	  				
  		for (e=0;e<siSelect.length;e++) {		
//	  		wltexto=document.getElementById('cambio').innerHTML;
			wlinput=siSelect[e].getElementsByTagName('INPUT');
//			alert('va='+wlinput[0].value+' html='+siSelect[e].innerHTML);	  		
			if (wlstr!='') { wlstr=wlstr+','; }

//			wlstr=wlstr+'new ContextItem("'+siSelect[e].value+'",function(){'+siSelect[0].options[e].value+'})';
			wlstr=wlstr+'new ContextItem("'+wlinput[0].value+'",function(){'+dame_onclick(siSelect[e].innerHTML)+'})';			
  		}
//		alert('wlstr'+wlstr);
  		wlstr='popupoptions = ['+wlstr+']';
		eval(wlstr);
   		ContextMenu.display(popupoptions)		
/*   		
   		var siTD = objtr.getElementsByTagName('TD');
  		
  		for (e=0;e<siTD.length;e++) {		
			siTD[e].className='todofoco';
  		}   		
*/  		

	}
	return false;
}
function contextForTR(objtr)
{
//	alert('entro contextForTR'+objtr.id+' inener'+objtr.outerHTML);
	var wlTR=document.getElementById(objtr.id);
	var siSelect = wlTR.getElementsByTagName('SELECT');
	var wlstr='';
//	alert('entro select length '+siSelect.length);	
	if (siSelect.length>0)
	{
  		for (e=0;e<siSelect[0].length;e++) {		
			if (wlstr!='') { wlstr=wlstr+','; }
			wlstr=wlstr+'new ContextItem("'+siSelect[0].options[e].text+'",function(){'+siSelect[0].options[e].value+'})';
  		}
  		wlstr='popupoptions = ['+wlstr+']';
		eval(wlstr);
   		ContextMenu.display(popupoptions)		
   		var siTD = objtr.getElementsByTagName('TD');
   		
  		for (e=0;e<siTD.length;e++) {		
			siTD[e].className='todofoco';
  		}   		
	}
	return false;
}
//  20070703 se habilito el popmenu y cambio de color del renglon seleccionado


function seguridad(campo)
{
//		alert('valor campo'+campo.value+ ' md5='+hex_md5(campo.value));
//		campo.value=hex_md5(campo.value);
		return false;
}

/**				//20070215
  * abre una ventana donde solicita la descripcion de un nuevo registro //20070215
  * @param idmenu  numero //20070215
  * @nombre attnum numero de campo //20070215
  * @fuente_nspname  schema de la fuente //20080115
  * @altaautomatico_idmenu  numero de menu que se abre para dar de alta un registro  //20080115
  * @fuente_campodep   campo dependiente por el cual se selecciona el campo que se acaba de dar de alta
  **/ //20070215
function altaautomatico(idmenu,attnum,dato,fuente,fuente_campodes,fuente_nspname,altaautomatico_idmenu,fuente_campodep,fuente_campofil) //20070215
{ //20070215

	var vfcf="";
	if (fuente_campofil!="")
	   if (document.getElementById("wl_"+fuente_campofil).value=="")
	   {
	       alert("Primero debe seleccionar el dato de "+document.getElementById("wlt_"+fuente_campofil).value);
	       return false;
       }
	     
	try { vfcf=	document.getElementById("wl_"+fuente_campofil).value; } catch(err) { vfcf=''; } ;
//	alert('vfcf'+fuente_campofil+"="+vfcf);	

	if (altaautomatico_idmenu=="" || altaautomatico_idmenu=="0")
 	{	
	 	//  regresa la descripcion de la alta automatica
	 	var des = showModalDialog('altaautomatica.php',document.getElementById('formpr'),' resizable:no; status:0 ; help:no; dialogHeight:150px; dialogWidth:700px '); 
		if (des!='' && des!=null) 
		{ 
        	wlurl='xmlhttp.php'
        	passData='&opcion=altaautomatico&idmenu='+idmenu+'&attnum='+attnum+"&dato="+dato+"&fuente="+fuente+"&fuente_campodes="+
        	           fuente_campodes+"&des="+des+"&fuente_nspname="+fuente_nspname+"&fuente_campofil="+fuente_campofil+"&valorfuente_campofil="+vfcf;        
	    	CargaXMLDoc();			
    	}	 	
	}
 	else
 	{	
	 	// regresa la identificacion de la alta de un registro
	 	var iden = showModalDialog('man_menus.php?idmenu='+altaautomatico_idmenu,document.getElementById('formpr'),'dialogHeight:400px; dialogWidth:800px'); 
	 	// alert('iden'+iden);
		if (iden!='' && iden!=null) 
		{ 
        	wlurl='xmlhttp.php'
        	passData='&opcion=buscaaltaautomatico&idmenu='+idmenu+'&attnum='+attnum+"&dato="+dato+"&fuente="+fuente+"&fuente_campodes="+fuente_campodes+"&iden="+iden+"&fuente_nspname="+fuente_nspname+"&fuente_campodep="+fuente_campodep;
	    	CargaXMLDoc();			
    	}	 		 	
 	} 	
// 	alert('des'+des);

} //20070215

function validafecha(wlcampos)
{
		var v = new valcomunes();
//		v.wlfecha=wlcampos;
		v.valfecha(wlcampos);
}

function muestrafecha(wlcampos)
{
	try {
	var wlnombre=wlcampos.name.replace(/fe_/,"wl_");
	var fecha=document.getElementById(wlnombre)
	if (fecha.disabled==true) {return false;} // 20090310 grecar, se agrega para que no mostrar el panel de fecha si el campo esta inhabilitado
	if (fecha.value.length)
	{
		patron=/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/
		if (!patron.test(fecha.value)) {alert ('Fecha en formato incorrecto'); fecha.value=''; fecha.focus(); return false;}
	}
//	alert('valor'+wlcampos+' si'+document.getElementById(wlnombre).value);	
//	var v = showModelessDialog('pidefecha.php',wlcampos,"scroll:no;status:no;help:no;dialogHeight:12;dialogWidth:11");
	var v = showModalDialog('pidefecha.php?valor='+document.getElementById(wlnombre).value,wlcampos,'scroll:no;status:no;help:no;dialogHeight:168px; dialogWidth:160px');
	var wlnombre=wlcampos.name.replace(/fe_/,"wl_");
//	alert('name'+wlnombre+' valor regresado'+v);	 
		if (document.getElementById(wlnombre).readonly!=true && v!=undefined)
		{	document.getElementById(wlnombre).value=v; document.getElementById(wlnombre).focus(); }
	} catch(err) { alert('error en muestrafecha '+err.description); };
//	wlcampos.value=v;
/*	
	 var d = new Date();
	 var dp = new DatePicker(d);
	 document.body.appendChild(dp.create());
	 dp.onchange = function vales() { alert('vale'+dp.getDate().getYear()+'-'+((dp.getDate().getMonth()<9) ? '0' + (dp.getDate().getMonth()+1) : (dp.getDate().getMonth()+1)) +'-'+((dp.getDate().getDate()<10) ? "0"+dp.getDate().getDate():dp.getDate().getDate()) ); };
*/
}

/** 
  *   20070301 Abre una ventana para solicita un texto
  *   @param objecto  objeto del campo
  */
function muestratexto(wlcampos)
{
	var wlnombre=wlcampos.name.replace(/txt_/,"wl_");
 	var v = showModalDialog('pidetexto.php?valor='+escape(document.getElementById(wlnombre).value),'','dialogHeight:280px; dialogWidth:800px');		
	try { 
		if (document.getElementById(wlnombre).readonly!=true && v!=undefined)
		{	document.getElementById(wlnombre).value=v; }
		}
	catch(err) 
	{ alert('error en muestratexto '+err.description); };
}	

/// 
//   20070618 Abre una ventana para solicita un dato de busqueda en campos select donde son demasiados
//  parametros wlselect, select sobre el cual se va a generar el campo select del html
//  wlfiltropadre, campos sobres el cual se va hacer el filtro para mostrar los datos
//  wlfiltrohijo,  campo hijo , el valor de la opcion en el select
//  fuentewhere, where sobre el fuente sobre todo para mostrar las opciones que aun no han sido seleccionadas   
//  fuenteevento, el evento donde se va a llenar el campo select 0=carga, 1=cambia el registro padre, 2=on focus, 3=on focus solo la primera vez
//  20070616 sololimite,  0=indica que no  1= si  sololimite quiere decir que en las opciones solo mostro el limite para no   
//  20070616               saturar el browser
//  20080117    fuente_busqueda_idmenu   numero de menu a mostrar para buscar informacion
function pidebusqueda(wlselect,wlfiltropadre,wlfiltrohijo,fuentewhere,fuenteevento,sololimite,fuente_busqueda_idmenu)
{
//	alert('fuente_busqueda_idmenu'+fuente_busqueda_idmenu);
	try 
	{ 
		if (fuente_busqueda_idmenu=="0" || fuente_busqueda_idmenu=="")
		{
 			var v = showModalDialog('pidebusqueda.php',document.getElementById('formpr'),' resizable:no; status:0 ; help:no; dialogHeight:150px; dialogWidth:700px ');
    		if (v!='' && v!=null) 
  			{ 
	   			var wlcampo=wlselect.substring(8,wlselect.indexOf(","));	  	
       			wlcampo=wlcampo+' like \''+v+'%\'';
	   			pon_Select(wlselect,wlfiltropadre,wlfiltrohijo,fuentewhere+wlcampo,fuenteevento,1)			
    		}
		}
		else
		{
	 		// regresa la identificacion de la alta de un registro
	 		var iden = showModalDialog('man_menus.php?idmenu='+fuente_busqueda_idmenu,document.getElementById('formpr'),'dialogHeight:500px; dialogWidth:800px'); 
//	 		alert('wlselect'+wlselect+' wlfiltropadre'+wlfiltropadre+' wlfiltrohijo'+' fuentewhere'+' iden='+iden);
	 		if (iden!=undefined)
	   		{ pon_Select(wlselect,wlfiltropadre,wlfiltrohijo,fuentewhere+' and '+iden,fuenteevento,1) }
		}
	}
	catch(err) 
	{ alert('error en pidebusqueda '+err.description); };
//    alert('pidebusqueda');           
    return false;
}	
	
/** 
  *   20070301 Muestra el menu que sube un archivo
  *   @param objecto  objeto del campo
  */
function subearchivo(wlcampos)
{
	try 
	{ 	
		
//		alert("vas"+document.getElementById(wlcampos.name).abr);
		var wlnombre=wlcampos.name.replace(/upl_/,"wl_");
//		if(document.getElementById(wlcampos.name).abr=='')
//		{
 			var v = showModalDialog('altaadjuntara.php',document.getElementById('formpr')," resizable=yes ; status:0; help:no; dialogHeight:180px;dialogWidth:800px");				
// 			var v = showModelessDialog('altaadjuntara.php',document.getElementById('formpr')," resizable=yes ; status:0; help:no; dialogHeight:30;dialogWidth:45");				
			if (v!='' && v!=null && v!=undefined)
			{	
				alert('El archivo se adjunto de forma exitosa con el nombre: '+v); 
				document.getElementById(wlcampos.name).abr=v;
				document.getElementById(wlnombre).value=v;				
//				alert("vas1"+document.getElementById(wlcampos.name).abr);
			}
//		}
//		else
//		{
//	 		var v = showModalDialog('upload_ficheros/'+document.getElementById(wlcampos.name).abr,document.getElementById('formpr')," resizable=yes ; status:0; help:no; dialogHeight:30;dialogWidth:45");					
//		}			
	}
	catch(err) 
	{ alert('error en subearchivo '+err.description); 
	};	
}
/* sube como archivo lo del clipboard */
function subeclb(wlcampos)
{
	try 
	{ 	
		var wlnombre=wlcampos.name.replace(/clb_/,"wl_");
		var v = showModalDialog('subeclb.php',document.getElementById('formpr')," resizable=yes ; status:0; help:no; dialogHeight:30;dialogWidth:45");				
		if (v!='' && v!=null && v!=undefined)
			{	
				alert('El archivo se adjunto de forma exitosa con el nombre: '+v); 
				document.getElementById(wlcampos.name).abr=v;
				document.getElementById(wlnombre).value=v;				
			}
	}
	catch(err) 
	{ alert('error en subearchivo '+err.description); 
	};	
}
function muestra_image(imagen)
{
	alert('debe de mostrar image'+imagen);
}

function vales()
{
	alert('vale');
}

// esta funcion abre una subvista
// recibe la hoja a abrir, y los campos de la hoja
// evento a ejecutar antes de abrir la subvista
// evento a ejecutar despues de abrir la subvista
function abre_subvista(wlhoja,wlcampos,wleventoantes,wleventodespues,idmenu,wldialogWidth,wldialogHeight,wldonde,wlventana)
{
//	alert (wlventana);
//	showModalDialog(wlhoja+'?'+wlcampos,document.getElementById('formpr'),"status:0");
//	alert('campos de las subvista'+wlcampos+' h='+wldialogHeight+' w'+wldialogWidth+' wlhoja'+wlhoja);
//	navigate(wlhoja+'?'+wlcampos);
//	showModelessDialog(wlhoja+'?'+wlcampos,document.getElementById('formpr'),"status:0");
	//alert('eventoantes'+wleventoantes+', donde:'+wldonde);
	try
	{
		if (wleventoantes!="")
		{
			if (wldonde==1)
			{
				eventos_servidor(wlhoja,wlcampos,wleventoantes,wleventodespues,idmenu,wldialogWidth,wldialogHeight)
			} else if (wldonde==0) {
				if (!eventosparticulares(null,wleventoantes)) {return false;}
			}
		}
		//else
		//{
		wlurl=wlhoja+'?'+wlcampos;		
		if (wlventana=='3')
		{
			open (wlurl,'pantallas');
		}
		else
		{
	    	if (wldialogHeight!=0 || wldialogWidth!=0)
	    	{   
		    	//wlurl=wlhoja+'?'+wlcampos;
//		    	wlurl=wlhoja+'?'+wlcampos; //20071105
//		    	passData='&'+wlcampos; //20071105		    	
//		    	alert('wlurl '+wlurl+' passdata='+passData);    		
//document.getElementById(theFormName)
//20061107				showModelessDialog(wlurl,document.getElementById('formpr'),"status:no;help:no;dialogHeight:"+wldialogHeight+";dialogWidth:"+wldialogWidth);
//20070312    se cambio el modal para que fuese resizesable y para que el status no lo despliegue
//				showModalDialog(wlurl,document.getElementById('formpr'),"status:no;help:no;dialogHeight:"+wldialogHeight+";dialogWidth:"+wldialogWidth);   //20070312
//20070523   se pusos a resizable
//20070524   la medida de resizable esta en pixeles
				showModalDialog(wlurl,document.getElementById('formpr'),"resizable=yes;status:no;help:no;dialogHeight:"+wldialogHeight+"px;dialogWidth:"+wldialogWidth+"px");
				
//20070523				showModalDialog(wlurl,document.getElementById('formpr'),"status:no;help:no;dialogHeight:"+wldialogHeight+";dialogWidth:"+wldialogWidth);				
//					navigate(wlurl);				
//				open(wlurl,'','width='+wldialogWidth+',height='+wldialogHeight+',scrollbars=no,toolbar=no');
//				propiedades='titlebar=no,status=no,'+'width='+wldialogWidth+',height='+wldialogHeight;
//				propiedades='titlebar=no,status=no,fullscreen=no';				
//				alert('propiedades'+propiedades);
//				open(wlurl,'_blank',propiedades,true);
//				z=window.showModelessDialog(wlurl,document.getElementById('formpr'),"resizable:yes;status:no;help:no;dialoghide:yes");   //20070312
			}
			else
			{
		    	//wlurl=wlhoja+'?'+wlcampos;		
//		    	alert('wlurl si'+wlurl);    		
//20061107				showModelessDialog(wlurl,document.getElementById('formpr'),"status:no;help:yes");
//20070327   se cambio el modal para que fuese resizesable
//			showModalDialog(wlurl,document.getElementById('formpr'),"status:no;help:yes");   //20070327
			showModalDialog(wlurl,document.getElementById('formpr'),"status:no;help:yes;resizable=yes");			
//				open(wlurl,'_blank','titlebar=no,status=no,'+'width='+wldialogWidth+',height='+wldialogHeight,true);
//					navigate(wlurl);								
			}
		//}
		
		if (wleventodespues!="")
		{
			if (wldonde==1)
			{
				eventos_servidor(wlhoja,wlcampos,wleventoantes,wleventodespues,idmenu,wldialogWidth,wldialogHeight)
			} else if (wldonde==0) {
				if (!eventosparticulares(null,wleventodespues)) {return false;}
			}
		}
		
		}
	}
	catch(err)
	{
		alert('error en abre_subvista'+err.description+wlurl);
	}
}	

//  ejecuta eventos en el servidor de funciones especificas de la aplicacion del usuario
function eventos_servidor(wlhoja,wlcampos,wleventoantes,wleventodespues,idmenu,wldialogWidth,wldialogHeight)	
{
//20070524		Actualizar el width y height en tiempo de dise�o 	
//20070524        wlurl='eventos_servidor.php?opcion='+wleventoantes+'&wlhoja='+
//20070524        		wlhoja+'&wlcampos='+escape(wlcampos)+buildQueryString('formpr')+
//20070524        		'&wldialogWidth='+wldialogWidth+'&wldialogHeight='+wldialogHeight;
        			
//20071105        wlurl='eventos_servidor.php?opcion='+wleventoantes+'&wlhoja='+
        wlurl='eventos_servidor.php';
        passData='&opcion='+wleventoantes+'&wlhoja='+                
        		wlhoja+'&wlcampos='+escape(wlcampos)+buildQueryString('formpr')+
        		'&wldialogWidth='+wldialogWidth+'&wldialogHeight='+wldialogHeight+'&idmenu='+idmenu+'&filtro='+escape(armaFiltro('formpr'));  //20070524
        CargaXMLDoc();
}

//  ejecuta eventos en el servidor de funciones especificas de la aplicacion del usuario
function comandos_servidor(wlhoja,wlfuncion,idmenu)	
{

        	if (checaobligatorios('formpr')==false)	
        	{
           		return;
			}           		

        	if (checanumericos('formpr')==false)	
        	{
           		return;
			}           		
        	if (checafechas('formpr')==false)	
        	{
           		return;
		}           		
        	if (checahoras('formpr')==false)	
        	{
           		return;
		}           		

//20071105        wlurl=wlhoja+'?opcion='+wlfuncion+'&wlhoja='+
        wlurl=wlhoja  //20071105
        passData='&opcion='+wlfuncion+'&wlhoja='+
        		wlhoja+buildQueryString('formpr');
//	alert('url='+wlurl+' passdata='+passData);        		
        CargaXMLDoc();
}
	
//  funcion que checa si en el cambio que boton se tecleo para mostrar los datos en los campos de captura
//  recibe el menu o vista sobre el cual va a dar manetenimiento
//  el movimiento que va a efectuar i=insert,d=delete,u=update
//  la llave con la que va a dara de baja o cambio
//  el renglon que va a dar de baja de la tabla
//  el evento para saber que boton fue tecleado del mouse
//  el numero de renglon de la tablas
//  cantidad de columnas en el renglon
function que_cambio(wlmenu,wlmovto,wlllave,wlrenglon,event,nr,nc)
{

			if (event.button==1)
	{
		mantto_tabla(wlmenu,"u",wlllave,wlrenglon);
	}
	else
	{
		muestra_cambio("formpr",nr,nc);
	}
	
}
	
	
	
// esta funcion ejecuta una consulta
// recibe la hoja a abrir, y los campos de la hoja
function abre_consulta(idvista) {
//	alert('entro por abre_consulta');
        if (hayalgundatotecleadobus('formpr')!='si')
        {
           alert ('no ha tecleado ningun dato permitido para buscar\n'+'Los datos con asterisco son donde se permiten la busqueda'); return;
        }	
  	theForm = document.getElementById('formpr');        //20071107
	filtro=document.createElement("<input>");    //20071107
    filtro.value=idvista;	//20071107
    filtro.id='_idmenu_'; //20071107
    filtro.name='_idmenu_';    //20071107
    theForm.appendChild(filtro); //20071107
    armaFiltro('formpr'); //20071107
    theForm.submit(); //20071107
//20071107    var wlurl="man_menus.php?idmenu="+idvista+"&filtro="+armaFiltro('formpr');
//    var wlurl="man_menus.php"; //20071105
//    var passData="&idmenu="+idvista+"&filtro="+armaFiltro('formpr'); //20071105       
//    alert ('wlurl '+wlurl);
//20071107    navigate(wlurl);
	}	
//  funcion que muestra el registro 2 de la tabla en los campos de pantalla
//  cuando esto sea por medio de una subvista
function hayunregistro()
{
//			alert('entro en hayunregistro'+document.getElementById('tabdinamica'));
//		alert('va a el if'+document.getElementById('tabdinamica')+' rows '+document.getElementById('tabdinamica').rows+' length '+document.getElementById('tabdinamica').rows.length+' cam0'+document.getElementById('cam0'));
		if (document.getElementById('tabdinamica')!=undefined)
		{
			//	alert('entro a no indefinido'+document.getElementById('tabdinamica').rows.length);
			if(document.getElementById('tabdinamica').rows.length==2)
			{
				try { document.getElementById('cam0').click();	} catch (err) { } ;
			}
		}			
/*		
	}		
	catch(err)
	{
		aler('error en hayunregistro');
	}
*/	
//	alert('termino en hayunregistro');
}		

//   pone el focus en el primer campo de la forma		
function pone_focus_forma(theFormName)
{
  try 
  {
	  theForm = document.getElementById(theFormName);	
//	  alert('entro en ponefocus'+theFormName);
	  for (e=0;e<theForm.elements.length;e++)
  		{
//	  		alert('name'+theForm.elements[e].name);	  		
   			if (theForm.elements[e].name!='' && theForm.elements[e].type!='hidden' && 
   		    	theForm.elements[e].type!='button' && theForm.elements[e].type!='reset'
   		    	&& theForm.elements[e].readOnly!=true
   		    	&& theForm.elements[e].disabled!=true
   		    	)
   		    {
//	   		    alert('va a poner focus');
//				if (theForm.elements[e].className=='')
//				{
		   			theForm.elements[e].focus();
		   			theForm.elements[e].className='foco';
//	   			}
//		   		var wlcn=theForm.elements[e].className;
//	  			wlcn = document.getElementById(theForm.elements[e].id);	
//	  			wlcn1=wlcn.className;		   		
//	   		    alert('clase'+wlcn1+' nombre'+wlcn.name+' id'+wlcn.id );		   		
//				window.status=theForm.elements[e].style.backgroundColor;
//				document.write(theForm.elements[e].style.backgroundColor);
		   		return true;
   			}
		}	
	}
	catch(err)
	{
		alert('error en pone_focus_forma'+err.description+' '+theForm.elements[e].name);
	}
}
	

// esta funcion arma el querystring
// la copie de utility.js
function armaFiltro(theFormName) {
  theForm = document.getElementById(theFormName);
//  theForm = document.forms[0];	  
  var qs = '';
 // tipo de dato 1=numerico, 2=caracter o fecha
  var tipo = '' ;
  for (e=0;e<theForm.elements.length;e++) {
   if (theForm.elements[e].name!=''&& theForm.elements[e].name.indexOf('bu_')>=0) {
	   var str=theForm.elements[e].name;
	   var str1=str.replace(/bu_/,"wl_");
	   var str2=str.replace(/bu_/,"nu_");	   
		x=document.getElementById(str1);
		x1=document.getElementById(str2);


    	if (x.value!='') {     
	    	try { tipo=x1.value; }
	    	catch (err) { tipo=2; } 
	    	if (tipo=2)

      		{ 
	      		qs+=(qs=='')?' ':' and ';
			if (x.value.indexOf('%')>=0)  // si el dato tiene un % quiere decier que es un like
	    	{
//   20071107  se quito el escape ya que se envia por post y no por get		    	
//   20071107	      		qs+=x.name.substring(3)+' like '+escape("'")+escape(x.value)+escape("'");
	      		qs+=x.name.substring(3)+' like \''+x.value+"'";	      		
			}
			else
			{
//   20071107	      		qs+=x.name.substring(3)+'='+escape("'")+escape(x.value)+escape("'");
	      		qs+=x.name.substring(3)+'=\''+x.value+"'"; //   20071107
			}
	      	}

  		}
      }
    }
//    alert('entro por armafiltro');
	filtro=document.createElement("<input type=hidden>");    
    filtro.value=qs;
    filtro.id='_filtro_';
    filtro.name='_filtro_';    
    theForm.appendChild(filtro);
  	return qs
}	
// funcion que pasa los datos de la forma padre a la forma hijo
// siempre y cuando el nombre de los campos padre se igual a los nombre de los campos hijos
function fijos(theForm)
{
	try
	{
  var qs = '';
//  var wlexiste = '';
  for (e=0;e<theForm.elements.length;e++)
  {
//	try { wlexiste= document.getElementById(theForm.elements[e].name) } catch (err) { alert('wlexiste'); }
//	alert(' valor'+wlexiste);
    if (theForm.elements[e].name!='' && theForm.elements[e].name.indexOf('wl_')>=0  )
    {
//		try { wlexiste= document.getElementById(theForm.elements[e].name); } catch (err) { alert('wlexiste vas'); }	    
//	    alert('entro='+theForm.elements[e].name+' wlexiste='+wlexiste);
		if (theForm.elements[e].type=='select-one')
		{
			try
			{
				switch(document.getElementById(theForm.elements[e].name).type)
				{
				case 'select-one':
					var opciones=document.getElementById(theForm.elements[e].name);
//  20061025 lo quite ya que limpiaba los campos select ????
//	         			clearSelect(opciones);
//                			appendToSelect(opciones, theForm.elements[e].value, document.createTextNode(theForm.elements[e].options[theForm.elements[e].selectedIndex].text))
//        			        opciones.selectedIndex=0;
//        			        opciones.align=true;
//        			        document.getElementById('tabcaptura').focus();
//					document.getElementById(theForm.elements[e].name).value=theForm.elements[e].value;
//					document.getElementById(theForm.elements[e].name).options(document.getElementById(theForm.elements[e].name).selectedIndex).text=theForm.elements[e].options[theForm.elements[e].selectedIndex].text;
//                                        alert('entro en fijos value'+theForm.elements[e].value);
//  20061025 checar bien esto ya que comentarice el codigo anterior y jalo perfectamente hasta ahorita
                                        
					break;
				case 'text':
					document.getElementById(theForm.elements[e].name).value=theForm.elements[e].value;				
					break;
				case 'textarea':
					document.getElementById(theForm.elements[e].name).value=theForm.elements[e].value;				
					break;					
				}
			}
			catch(err)
			{
			}	

		}
		if (theForm.elements[e].type=='text' || theForm.elements[e].type=='textarea')		
		{
			try
			{
				document.getElementById(theForm.elements[e].name).value=theForm.elements[e].value;
//				alert('name'+theForm.elements[e].name+' innerhtml'+document.getElementById(theForm.elements[e].name).innerHTML);
			}
			catch(err)
			{ }
		}
    }
}

  }
catch (err)
{ alert('error fijos'+err.description); }  
//  alert('termino el for');
  return qs
}
	

// esta funcion arma el querystring
// la copie de utility.js
function buildQueryString(theFormName) {
  theForm = document.getElementById(theFormName);
//  theForm = document.forms[0];	    
  var qs = '';
  for (e=0;e<theForm.elements.length;e++) {
    if (theForm.elements[e].name!='') {
      qs+=(qs=='')?'&':'&'
      qs+=theForm.elements[e].name+'='+escape(theForm.elements[e].value)
      }
    }
  return qs
}
/* 20070710  funcion que regresa el onclick de un td 
				esto para armar el oncontextmenu de un popup menu
*/
function dame_onclick(wlonclick)
{
	try {
		var wltextoi=wlonclick.substring(wlonclick.indexOf('onclick')+9);
		var wltextof=wltextoi.substring(0,wltextoi.indexOf('\''));		
//		alert('wltextof='+wltextof+'wltextoi='+wltextoi);
		return wltextof;
//		wltextof=wltextof.substring(wltextof.indexOf("'")+1);				
//		wltextof=wltextof.substring(wltextof.indexOf("'"));						
//		document.getElementById("cambio").innerHTML=wltextoi+' '+wlonclick+' '+wltextof;
	} catch (err) { 
			alert('error dame_onclick'+err.description);
		};	
}
///**********************************   20061102
///  funcion que el camba el onclick
function cambia_onclick(wlonclick)
{

	try {
//		alert('cambia onclick');
		wltexto=document.getElementById("cambio").innerHTML;		
//str.substring(str.indexOf(		
		var wltextoi=wltexto.substring(0,wltexto.indexOf('onclick')+9);
		wltextof=wltexto.substring(wltexto.indexOf('onclick'));		
		wltextof=wltextof.substring(wltextof.indexOf("'")+1);				
		wltextof=wltextof.substring(wltextof.indexOf("'"));						
		document.getElementById("cambio").innerHTML=wltextoi+' '+wlonclick+' '+wltextof;
//		alert('wltextoi'+wltextoi+'wltextof'+wltextof);
//		alert('innerhtml'+document.getElementById("cambio").innerHTML+' anterior '+wltexto);		
	} catch (err) { 
//		alert ('Error en funcion cambia_onclick ');
		};
}

//   pone a las imagenes unchecked, excepto cuando fue seleccionado un renglon
//   parametros pasados el nombre de la format
//				wlrenglon el numero de renglon que fue seleccionado
function pone_unchecked(wlrenglon)
{
  try 
  {
	  var theForm = document.getElementsByName('botcam');	
//	  alert('longitud'+theForm.length);
	  
	  for (e=0;e<theForm.length;e++)
  		{
//	   		alert('entro name'+theForm[e].name+' id'+theForm[e].id+' e='+e+' wlrenglon='+wlrenglon+' sstr='+theForm[e].id.substring(3));
   			if (theForm[e].id.substring(3)!=wlrenglon )
   		    {
//	   		    alert('renglon diferente'+theForm[e].id.substring(3));
			   	theForm[e].src='img/icon_enabled_checkbox_unchecked.gif';
//			   	theForm[e].checked=false;			   	
   			}
   			else
   			{
			   	theForm[e].src='img/icon_enabled_checkbox_checked.gif';	   			
//	   			alert('checked');
//			   	theForm[e].checked=true;			   		   			
   			}
		}	
	}
	catch(err)
	{
		alert('error en pone_unckecked');
	}	

}
///**********************************   20061102




// funcion que muestra un registro en el broseo en los campos de captura
// la funcion recibe el nombre de la forma, el renglon y la cantidad de columnas que contiene el renglon
// y tambien la llave del registro que va a actualizar si es que hay cambio
// el menu o tabla que va a cambiar
// evento antes de ejecutar
// evento despues de ejecutar
// movimiento que disparo el muestra_cambio u=update de registro,s=subvista,f=funcion,u1=no se (parace que es update)
// 20071113  se agrego los eventos antes que hay que ejecutar antes de insertar, select,update o delete en el cliente
// 20071113 function muestra_cambio(theFormName,r,ct,wlllave,menu,wleventoantes,wleventodespues,movto) {
function muestra_cambio(theFormName,r,ct,wlllave,menu,wleventoantes,wleventodespues,wleventoantescl,wleventodespuescl,movto) {	
  // pone el dato de img listo para efectuar el cambio

  if (movto=='B')  //Opcion de busqueda para que funcione esto debe estar definido una campo llave
			{
	           window.returnValue=wlllave;
	           window.close();
        	}    
  glr=r;
  var wlcambio="\\\"";
  wlllave=wlllave.replace(/"/g,wlcambio);
  
///**********************************   20061102  
  pone_unchecked(r);
//20071113  wlonclick="mantto_tabla(\""+menu+"\",\"u\",\""+wlllave+"\","+(r)+",\""+wleventoantes+"\",\""+wleventodespues+"\");return false"  
  wlonclick="mantto_tabla(\""+menu+"\",\"u\",\""+wlllave+"\","+(r)+",\""+wleventoantes+"\",\""+wleventodespues+"\",\""+wleventoantescl+"\",\""+wleventodespuescl+"\");return false"
//  alert('entro en muestra cambio '+wlllave+' renglon'+r+' ct'+ct+' movto='+movto+' wlonclick='+wlonclick);  
  cambia_onclick(wlonclick);
///**********************************   20061102  
  
//  alert('despues wlllave '+wlllave+'wleventoantes'+wleventoantes);
//20061102  try { if(movto=='u') { document.getElementById("cambio").innerHTML="<input type=image title='Cambia registro' "+
//20061102                                              "src='img/cambio.bmp' onclick='mantto_tabla(\""+
//20061102                                              menu+"\",\"u\",\""+wlllave+"\","+(r)+
//20061102                                              ",\""+wleventoantes+"\",\""+wleventodespues+
//20061102                                              "\");return false'></input>"; } } catch (err) {};


//	alert('despues del try ct='+ct+'innerhtml'+document.getElementById("cambio").innerHTML);                                            
  for (e=0;e<ct;e++) {
	  var el="r"+r+"c"+e; // renglon columna que se muestra en la pantalla de captura
	  var elm="cc"+e;   //  campos de la pantalla de captura
		try {
//     		alert('tyep'+document.getElementById(elm).type+' name '+document.getElementById(elm).name);
	  		if (document.getElementById(elm).type=='text')
	  		{ 
//		  		alert('elm'+elm+' value'+document.getElementById(elm).value+'el'+el+' value'+document.getElementById(el).innerText);
				var captu=document.getElementById(elm);
		  		captu.value=document.getElementById(el).innerText.trim(); 
//	   			var str=captu.name;
//	   			var str1=str.replace(/wl_/,"nc_");		  		
//	   			try { nc=document.getElementById(str1); nc.readOnly=true; captu.readOnly=true; captu.className='lee'; } catch (err) { };

		  	}
	  		if (document.getElementById(elm).type=='textarea')
	  		{ 
//		  		alert('elm'+elm+' value'+document.getElementById(elm).value+'el'+el+' value'+document.getElementById(el).innerText);
		  		document.getElementById(elm).value=document.getElementById(el).innerText.trim(); 
		  	}		  	
	  		if (document.getElementById(elm).type=='select-one')
	  		{ 
//		  		alert('name '+document.getElementById(el).id+ " ="+document.getElementById(el).abr+'tex  '+document.getElementById(el).innerText);
//					alert('value va'+document.getElementById(elm).length());
					document.getElementById(elm).value=busca_ValorOption(document.getElementById(elm),document.getElementById(el).innerText,1,document.getElementById(el).abr); 
//					alert('valor encontrado'+document.getElementById(elm).value+' '+elm+' abr='+document.getElementById(el).abr);					
		  		}
	  		if (document.getElementById(elm).type=='checkbox')
	  		{ 
//		  		alert('name'+document.getElementById(elm).name+'elm'+elm+' value'+document.getElementById(elm).value+'el'+el+' value'+document.getElementById(el).innerText);
		  		document.getElementById(elm).value=valor_checkbox_cap(el,elm); 
		  	}	
				var captu=document.getElementById(elm);		  	
	   			var str=captu.name;
	   			var str1=str.replace(/wl_/,"nc_");		  		
	   			try { nc=document.getElementById(str1); nc.readOnly=true; captu.readOnly=true; captu.disabled=true; captu.className='lee'; } catch (err) { };		  		  		
	  	}
  		catch(err)
  		{
//	  		alert('no entrontro id elm'+elm+' el'+el+' err'+err.description);
  		}
    }
    pone_focus_forma(theFormName);

	wlTR=document.getElementById('tr'+r);    
	color_renglon(wlTR);
//	alert('cambio color');
	muevea1renglon(r);   //20070808  checar porque parece que esta chafeando esta rutina
//  20070703   mueve un renglon a al primer renglon    
/*
	var b = document.getElementById('tabdinamica').insertRow(1);
   	var wlTDs = wlTR.getElementsByTagName('TD');  	
  	for (e=0;e<wlTDs.length;e++) {		
					wlele=document.createElement("<td>");
					wlele.innerHTML=wlTDs[e].innerHTML;
					wlele.className=wlTDs[e].className;
					wlele.id=wlTDs[e].id;					
					b.appendChild(wlele);					
  	}  		
  	wlid=wlTR.id;
  	wlclassName=wlTR.className;
	b.ondblclick= wlTR.ondblclick;
	b.oncontextmenu= wlTR.oncontextmenu;	
  	var rr=r+1;
	var wl=document.getElementById('tr'+r).rowIndex;
    document.getElementById('tabdinamica').deleteRow(wl);  	
    b.id=wlid;
	b.className=wlclassName;
	*/
}
/*  funcion que mueve el renglon seleccionado a el renglon 1 
     recib el numero de renglon*/
function muevea1renglon(r)
{
	wlTR=document.getElementById('tr'+r);    	
	var b = document.getElementById('tabdinamica').insertRow(1);
   	var wlTDs = wlTR.getElementsByTagName('TD');  	
  	for (e=0;e<wlTDs.length;e++) {		
//	  				alert('len'+wlTDs.length+' id'+wlTDs[e].id);
					wlele=document.createElement("<td>");
					wlele.innerHTML=wlTDs[e].innerHTML;
					wlele.className=wlTDs[e].className;
					wlele.id=wlTDs[e].id;					
					wlele.abr=wlTDs[e].abr;	//20070815 falta esta linea provocaba undefined en los campos select
					b.appendChild(wlele);					
//					if (wlele.innerHTML!=wlTDs[e].innerHTML) { alert('diferente'+wlTDs[e].innerHTML+' nuevo'+wlele.innerHTML); }
  	}  		
  	wlid=wlTR.id;
  	wlclassName=wlTR.className;
	b.ondblclick= wlTR.ondblclick;
	b.oncontextmenu= wlTR.oncontextmenu;	
  	var rr=r+1;
	var wl=document.getElementById('tr'+r).rowIndex;
    document.getElementById('tabdinamica').deleteRow(wl);  	
    b.id=wlid;
	b.className=wlclassName;	
//	if (wlTR!=b)
//	{ alert('diferente '+wlTR.innerHTML) }
}

// de acuerdo al valor de la fila pone checked o unchecked el campo de captura
function valor_checkbox_cap(el,elm)
{
	if(document.getElementById(el).innerText=='t') 
	{   
//		alert('true');
		document.getElementById(elm).checked=true;
		return 't'; 
	} 
	else { 
		document.getElementById(elm).checked=false;		
		return 'f'; 
		}; 	
}
//   pone el valor de un combo box si checked le pone t si es unchecked le pone f
function ponvalor_cb(cb)
{
	if (cb.checked==true)
	{ cb.value='t'; }
	else
	{ cb.value='f'; }	
}
//   busca el valor de una opcion de acuerdo a un texto
//   recibe opcines, que es el objeto select
//   recibe el texto que va a buscar en el objeto opciones
//   recibe wlalta que indica si no existe el texto en el objeto limpia el objeto y lo da de alta
//           wlalta debe vale 1 para darlo de alta
//   recib wlvalor , que es el valor del texto en el select
function busca_ValorOption(opciones,wltexto,wlalta,wlvalor)
{
	try 
	{
		if (wltexto!="")
		{	  	
//	  		var datos = wltexto.split("_");   // esto se hace en un campo select 
//	  		                                  // ya que a la derecha del campo viene el valor que le corresponde
	  		for (ee=0;ee<opciones.length;ee++)
	  		{
				if(opciones.options[ee].text==wltexto)
				{
				     //   alert('si encontro');
					return opciones.options[ee].value;
				}
			}
//			alert('wlalta'+wlalta+' wltexto'+wltexto);
			if (wlalta==1)
			{
	  			if (opciones.length>0	)
	    			  { clearSelect(opciones); }   // si llega a esta instancia no encontro el dato por que lo se supones que es un select que depende de otro
						               // cuando un cambio se ha mostrado puede ser que ya existan
						               // algunos valor en el select por que decidi limpiar este dato
						// 20070810  se modifico para que agregue la opcion selecciones opcion que que funcione el boton limpiar						               
// 20070810        		        appendToSelect(opciones, wlvalor, document.createTextNode(wltexto))
        		        appendToSelect(opciones, wlvalor, document.createTextNode(wltexto)) // 20070810
        		        appendToSelect(opciones, '', document.createTextNode("Seleccione opcion"),2)      // 20070810  		        
//        		alert('opcion len'+opciones.selectedIndex);
        		        opciones.selectedIndex=0;
        		        return wlvalor;
    		        }
			return -1;
		}        	
		else
		{ return ""; }
	}
	catch(err)
	{
		alert('error en busca_ValorOption '+err.description);
	}
        
}

//   busca la descripcion  de una opcion de acuerdo al valor de la opcion
function busca_DesOption(opciones,wlvalue)
{
//	  alert ('entro en busca_desoption value='+wlvalue);
	  for (ee=0;ee<opciones.length;ee++) {
			if(opciones.options[ee].value==wlvalue)
			{
				return opciones.options[ee].text;
			}
		}
		return 0;
}

// funcion que checa si hubo algun dato tecleado de los datos de busqueda
function hayalgundatotecleadobus(theFormName) {
  theForm = document.getElementById(theFormName);
//  theForm = document.forms[0];	  
  var qs = '';
  for (e=0;e<theForm.elements.length;e++) {
    if (theForm.elements[e].name!='' && theForm.elements[e].name.indexOf('bu_')>=0) {
	   var str=theForm.elements[e].name;
	   var str1=str.replace(/bu_/,"wl_");
		x=document.getElementById(str1);
       if (x.value!='' && x.type!='button' && x.type!='hidden' && x.type!='reset' ) {
	      qs='si';
       }
    }
  }
  return qs

}


// funcion que checa si hubo algun dato tecleado
function hayalgundatotecleado(theFormName) {
//	alert('length'+document.forms.length);
//  for (e=0;e<document.forms.length;e++) {	
//	  	alert('nombre'+document.forms[e].name+' e'+e);
//  }
  theForm = document.getElementById(theFormName);
//  theForm = document.forms[0];
  var qs = '';
  for (e=0;e<theForm.elements.length;e++) {
    if (theForm.elements[e].name!='') {
       if (theForm.elements[e].value!='' && theForm.elements[e].type!='button' && theForm.elements[e].type!='hidden' && theForm.elements[e].type!='reset') {
          qs='si';
       }
    }
  }
  return qs
}

// funcion que checa que existan datos tecleados en los campos obligatorios
function checaobligatorios(theFormName) {
	try
	{	
  theForm = document.getElementById(theFormName);
//  theForm = document.forms[0];	  
  var qs = '';
    for (e=0;e<theForm.elements.length;e++) {
//   alert('entro a obligatorios'+theForm.elements[e].name);	    
    if (theForm.elements[e].name!='' && theForm.elements[e].name.indexOf('ob_')>=0) {
	   var str=theForm.elements[e].name;
	   var str1=str.replace(/ob_/,"wl_");
	   var str2=str.replace(/ob_/,"wlt_");
		x=document.getElementById(str1);
		x2=document.getElementById(str2);	
		if (document.getElementById('CAMPOS DE CONTROL DEL SISTEMA'))
		{	tab=document.getElementById('CAMPOS DE CONTROL DEL SISTEMA');	
			if (tab.style.display == 'none')
			{	tab.style.display = 'block';}
		}
		//alert (tab.style.length);
       if (trim(x.value)=='' && x.type!='button' && x.type!='hidden' && x.type!='reset' ) {
			alert ('El dato "'+x2.value+'" es obligatorio ');
			x.focus();
			return false;
       }
    }
  }
    return true
}
catch(err)
{ alert('error checaobligatorios'+err.message+' e'+e+' elemento'+theForm.elements.length+' name'+str1); }
}

// funcion que checa que sean numericos los campos que deben ser numericos
function checanumericos(theFormName) {
  theForm = document.getElementById(theFormName);
  var qs = '';
  for (e=0;e<theForm.elements.length;e++) {
    if (theForm.elements[e].name!='' && theForm.elements[e].name.indexOf('nu_')>=0) {
	   var str=theForm.elements[e].name;
	   var str1=str.replace(/nu_/,"wl_");
		x=document.getElementById(str1);	    
	    var vd = new valcomunes();
	    vd.ponnumero(x)
	    if (vd.esnumerico()==false)
	    {   return false; }
    }
  }
  return true
}

// funcion que checa las fechas de los campos 
function checafechas(theFormName) {
	try
	{
  theForm = document.getElementById(theFormName);
  var qs = '';
//  alert('entro a validadar fechas');
  for (e=0;e<theForm.elements.length;e++) {
    if (theForm.elements[e].name!='' && theForm.elements[e].name.indexOf('_da_')>=0) {
	   var str=theForm.elements[e].name;
	   var str1=str.replace(/_da_/,"wl_");
//	   alert('NOMBRE'+str1+' str='+str);
	   x=document.getElementById(str1);	    
//	   alert('x'+x.id+' outer'+x.outerHTML );
// 20050227  que no valida la fecha si viene en espacios	   
		if (x.outerHTML.indexOf('readOnly')==-1)  // valida siempre y cuando no sea readonly
		{
		   if (x.value!='')   // 20050227
		   {  // 20050227
	    		var vd = new valcomunes();
		    	if (vd.valfecha(x)==false)
	    		{   return false; }
    		} // 20050227
    		else { 	return true; } // 20050227
		}    		
    }
  }
  return true
}
catch (err) { alert('error checafechas'+err.message); }
}

// funcion que checa las horas de los campos 
function checahoras(theFormName) {
	try
	{
  theForm = document.getElementById(theFormName);
  var qs = '';
//  alert('entro a validadar fechas');
  for (e=0;e<theForm.elements.length;e++) {
    if (theForm.elements[e].name!='' && theForm.elements[e].name.indexOf('_ho_')>=0) {
	   var str=theForm.elements[e].name;
	   var str1=str.replace(/_ho_/,"wl_");
//	   alert('NOMBRE'+str1+' str='+str);
	   x=document.getElementById(str1);	    
//	   alert('x'+x.id+' outer'+x.outerHTML );
// 20050227  que no valida la fecha si viene en espacios	   
		if (x.outerHTML.indexOf('readOnly')==-1)  // valida siempre y cuando no sea readonly
		{
		   if (x.value!='')   // 20050227
		   {  // 20050227
	    		var vd = new valcomunes();
		    	if (vd.valhora(x)==false)
	    		{   return false; }
    		} // 20050227
    		else { 	return true; } // 20050227
		}    		
    }
  }
  return true
}
catch (err) { alert('error checahoras'+err.message); }


// 20070804 Funcion que ejecuta un evento
//          recibe el objeto que lo disparo 
//          y la funcion a ejecutar
function eventosparticulares(x,evento)
{
//		alert('entro en eventosparticulares codigo='+x);		   
	try
	{
	   if (evento.value!='')
	   {
		   codigo="	var vd = new eve_particulares();  if (vd."+evento+"(x)==false)	{   regreso=false;  	}  	else { 	regreso=true; } ";

		   eval(codigo);
//		   alert('regreso'+regreso);
		   if (regreso==false) { if(x!=null) {x.focus();};  return false;}
	   }		   
//	alert('entro');	   
	}
	catch(err)
	{ alert('error eventosparticulares'+err.description); return false; }
	  return true
}

// funcion que checa las fechas de los campos 
function checaparticulares(theFormName) {
	try
	{
  theForm = document.getElementById(theFormName);
  var qs = '';

  for (e=0;e<theForm.elements.length;e++)
  {
    if (theForm.elements[e].name!='' && theForm.elements[e].name.indexOf('_vp_')>=0)
    {

	   var validacion=theForm.elements[e].value;
	   var regreso=false; 
	   var str=theForm.elements[e].name;
	   var str1=str.replace(/_vp_/,"wl_");
//	   alert('str1'+str1);	   
	   x=document.getElementById(str1);	    
	   if (x.value!='')
	   {
//		   alert('valor de x'+x.value);
		   codigo="	var vd = new val_particulares();  if (vd."+validacion+"(x)==false)	{   regreso=false;  	}  	else { 	regreso=true; } ";
		   eval(codigo);
//		   alert('valido'+codigo+' retorno='+regreso+' name'+x.name);
		   if (regreso==false) { x.focus();return false;}
	   }		   
    }
  }
  return true
} catch(err) { alert('error checaparticulares'+err.message); return false; }
}


function CargaXMLDoc() 
{
	try
	{
       if (window.ActiveXObject)
       {
//	       alert('entro por windows');
        	isIE = true;
         	req = new ActiveXObject("Msxml2.XMLHTTP");         	
//         	alert('despues de crear el objeto');
        	if (req)
        	{
//         		alert('if req');	        	
            	req.onreadystatechange = querespuesta;
//         		alert('longitud wlurl'+wlurl);	        	            	
            	req.open("POST", wlurl, false);  // sincrona
//            	req.open("POST", wlurl, true);   // asincrona
                req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");                   	
//         		alert('ai la lleva');	        	            	            	
            	req.send(passData);
//         		alert('despues del send');	        	            	            	            	
        	}
		}        	
		else
		{
    		if (window.XMLHttpRequest) 
    		{
        		req = new XMLHttpRequest();
        		req.onreadystatechange = querespuesta;
        		req.open("POST", wlurl, false);  // sincrona
//        		req.open("POST", wlurl, true);   // asincrona
        		req.send(null);
    		}
    	}
	}
	catch(err)
	{
		alert('error en CargaXMLDoc'+err.description+' wlurl'+wlurl+' '+passData);
	}
}
//  funcion que sirve para poner los datos en un campo select
//  parametros wlselect, select sobre el cual se va a generar el campo select del html
//  wlfiltropadre, campos sobres el cual se va hacer el filtro para mostrar los datos
//  wlfiltrohijo,  campo hijo , el valor de la opcion en el select
//  fuentewhere, where sobre el fuente sobre todo para mostrar las opciones que aun no han sido seleccionadas   
//  fuenteevento, el evento donde se va a llenar el campo select 0=carga, 1=cambia el registro padre, 2=on focus, 3=on focus solo la primera vez
//  20070616 sololimite,  0=indica que no  1= si  sololimite quiere decir que en las opciones solo mostro el limite para no   
//  20070616               saturar el browser
function pon_Select(wlselect,wlfiltropadre,wlfiltrohijo,fuentewhere,fuenteevento,sololimite)
{
	//alert('wlfiltropadre:'+wlfiltropadre+', wlfiltrohijo:'+wlfiltrohijo+', wlselect:'+wlselect+', fuentewhere:'+fuentewhere);
	// si ya esta lleno el select y el evento es 3 onfocus solo la primiera vez se sale
	// para no volver a llenar el campo select
//  20070616 if (fuenteevento==3 && document.getElementById("wl_"+wlfiltrohijo).length>=2 )
	//alert(sololimite);
	if (fuenteevento==3 && document.getElementById("wl_"+wlfiltrohijo).length>2 && sololimite==0)	
	{  return; }
//	alert('paso aqui');
	var wlvarios="";
	var wlwhere="";
	var wlcomi="";
	try
	{
	  if (wlfiltropadre!='')  // 20070615 Tronaba cuando no traia filtro padre
	  { // 20070615 Tronaba cuando no traia filtro padre
	  		var a = wlfiltropadre.split(',');

	  		for (m=0;m<a.length;m++)
	  		{	
		  		var valor="";

		  		try { valor=document.getElementById("au_"+a[m]).value } catch (err) { valor=""; }
		  		if (valor=="") { try { valor=document.getElementById("wl_"+a[m]).value } catch (err) { valor=""; } }	  		
//      			alert('despues de split'+a[m]+' valor'+valor);	  		  				  		
				if(valor!='')
				{
					//  si existe un elemento que sea nu_ es numerico en caso es un string y debe de llevar comillas
					try { if(document.getElementById("nu_"+a[m]).name!='') { wlcomi=""; } else { wlcomi=""; } } catch (err) { wlcomi="'"; }
					if (wlvarios=="")
					{ wlvarios=a[m]+"="+wlcomi+valor+wlcomi; }
					else
					{  wlvarios=wlvarios+" and "+a[m]+"="+wlcomi+valor+wlcomi; }
    			}	
    			else
    			{   clearSelect(document.getElementById("wl_"+wlfiltrohijo));
	    			return;
    		    }
			}
  	  } // 20070615 Tronaba cuando no traia filtro padre
//  	  alert('paso for1');
	  if (fuentewhere!="")
	  {	wlwhere=reemplaza_where(fuentewhere,'formpr');
	  	// 20071015
	  	if (wlwhere==false) { return; } // 20071015
	  }
	  
//	  alert ('hijo'+wlfiltrohijo+ ' varios '+wlvarios+' wlfiltropadre '+wlfiltropadre+'fuentewhre'+fuentewhere);
	  wlvs=wlselect;
	  if (wlvarios!='')
	  	{
		  	// 20101129 grecar - igual que en el else de abajo, tronaba cuando no traia filtro padre
		  	wlvs=wlvs+' where '+wlvarios+(wlwhere!='' ? ' and '+wlwhere : ''); 
//20071105	  		wlurl='xmlhttp.php?opcion=pon_select&sql='+wlvs+'&wlfiltropadre='+wlfiltropadre+'&wlfiltrohijo='+wlfiltrohijo+'&fuenteevento='+fuenteevento+'&fuentewhere='+fuentewhere;
	  		wlurl='xmlhttp.php';//20071105
	  		passData='&opcion=pon_select&sql='+wlvs+'&wlfiltropadre='+wlfiltropadre+'&wlfiltrohijo='+wlfiltrohijo+'&fuenteevento='+fuenteevento+'&fuentewhere='+fuentewhere;	  			  		
//	  		alert ('wlurl'+wlurl);
      		//CargaXMLDoc();
      		setTimeout ('CargaXMLDoc()',0);
  		}
  		else
	  	if (wlvs!='')  	// 20070615 Tronaba cuando no traia filtro padre	
  		{ 
	  		// 20101129 grecar -implemente esto porque cuando no trae filtro where el campo tronaba la funcion, con esto elimino el prefijo and de la variable wlwhere
  			x=wlwhere.replace(/^\s+/,'').split(' ');
  			//alert ('-'+x[0]+'-');
  			if (x[0]=='and') { cadena=''; } else { cadena=x[0]; }
  			for (i=1;i<x.length;i++) { cadena=cadena+' '+x[i];	}
  			//alert ('1:'+cadena+'\n2:'+wlwhere);
  			wlwhere=cadena;
  			// 20101129 grecar- aqui termina el cambop implementado
  			
  			// 20070615 Tronaba cuando no traia filtro padre
		  	wlvs=wlvs+(wlwhere!='' ? ' where '+wlwhere : '' );   		
//20071105	  		wlurl='xmlhttp.php?opcion=pon_select&sql='+wlvs+'&wlfiltropadre='+wlfiltropadre+'&wlfiltrohijo='+wlfiltrohijo+'&fuenteevento='+fuenteevento; // 20070615 Tronaba cuando no traia filtro padre
	  		wlurl='xmlhttp.php';//20071105
	  		passData='&opcion=pon_select&sql='+wlvs+'&wlfiltropadre='+wlfiltropadre+'&wlfiltrohijo='+wlfiltrohijo+'&fuenteevento='+fuenteevento; // 20070615 Tronaba cuando no traia filtro padre	  			  		
//	  		alert ('wlurl'+wlurl+'passdata='+passData);	  		
      		//CargaXMLDoc(); // 20070615 Tronaba cuando no traia filtro padre
      		setTimeout ('CargaXMLDoc()',0);
  		} // 20070615 Tronaba cuando no traia filtro padre
  		else
  		{	  		
	  		clearSelect(document.getElementById("wl_"+wlfiltrohijo));
  		}
	}  
  	catch(err)
  	{
	  	alert('error en pon_select'+err.description+' filtro padre'+wlfiltropadre);
  	}
  	
}
function reemplaza_where(fuentewhere,theFormName)
{
  theForm = document.getElementById(theFormName);
//  theForm = document.forms[0];	  
  var qs = '';
  for (e=0;e<theForm.elements.length;e++) {
    if (theForm.elements[e].name!=''&& (theForm.elements[e].name.indexOf('wl_')>=0 || theForm.elements[e].name.indexOf('au_')>=0)) {
/*	    
	   var str=theForm.elements[e].name;
	   var str1=str.replace(/bu_/,"wl_");
		x=document.getElementById(str1);
       if (x.value!='' && x.type!='button' && x.type!='hidden' && x.type!='reset' ) {
	      qs='si';
       }
*/
//		var si = "/"+theForm.elements[e].name+"/g"
		while (fuentewhere.indexOf(theForm.elements[e].name)>=0)
		{
			// 20071015   se incluyo ya que cuando en el where viene un campo de la pantalla del cual depende el select
			// 20071015   este debe ser diferente de espacio
			if (theForm.elements[e].value=='')
			{ alert('Primero debe teclear el dato '+theForm.elements[e].title);theForm.elements[e].focus;return false; }  
			fuentewhere=fuentewhere.replace(theForm.elements[e].name,theForm.elements[e].value);
		}
//		alert('fuentewhere'+theForm.elements[e].name+' fuentewhere'+fuentewhere);
    }
  }
  return fuentewhere;	
}

//  funcion que checa si fue seleccionado previamente un dato
function si_Select(wlselect,wlfiltro)
{
	try
	{
	  var a = wlfiltro.split(',');
	  for (m=0;m<a.length;m++)
	  {	
		if(document.getElementById("wl_"+a[m]).value=='')
		{
			alert('Primero debe teclear � seleccionar el dato "'+document.getElementById("wlt_"+a[m]).value+'"');
			return false;
    	}	
	  }
  	}
  	catch(err)
  	{
	  	alert('error en si_select');
  	}


}

// add item to select element the less
// elegant, but compatible way.
//  wlselected si es 1 se pone la opcion selected y defaultselected   20070810
//				si es 2 se pone la opcion defaultselected             20070810
function appendToSelect(wlselect, value, content,wlselected)  //   20070808  se incluyo el wlselected
{
	try   // inclui el try el 20070808
	{
    	var opt;
    	opt = document.createElement("option");
//    	alert('paso opt'+content);
    	opt.value = value;
    	opt.appendChild(content);
//    	alert('paso opt'+opt.value+' selected' +wlselected);    	
    	if (wlselected==1) { opt.defaultSelected=true; opt.selected=true; }  // 20070808
    	if (wlselected==2) { opt.defaultSelected=true; }    	// 20070810
    	wlselect.appendChild(opt);
	}
  	catch(err)
  	{
	  	alert('error en appendToSelect'+err.description);
  	}    	
}

function buildTopicList(wl,des,val) 
{
    var items = req.responseXML.getElementsByTagName("registro");
//    window.status='va entrar a for len'+items.length;
// 20080210  lo modifique para que si es solo un elemento lo ponga por default
    for (var i = 0; i < items.length; i++) {
// 20080210        appendToSelect(wl, getElementTextNS("", val, items[i], 0),
// 20080210            document.createTextNode(getElementTextNS("", des, items[i], 0)));
        appendToSelect(wl, getElementTextNS("", val, items[i], 0),  // 20080210
            document.createTextNode(getElementTextNS("", des, items[i], // 20080210
            0)),(items.length==1 ? 1 : 0));            // 20080210
    }
//   20070808  se incluyo que aparezca seleccione opcion     
// 20080210    appendToSelect(wl, "", document.createTextNode("Seleccione opcion"),1); //   20070808
    appendToSelect(wl, "", document.createTextNode("Seleccione opcion"),(items.length==1 ? 0 : 1)); //   20070808    
    wl.click();
}

/**   20070629
  *  ejecuta un submenu a nivel renglon
  *  lo que ejecuta viene el el value de la opcion
  **/
function submenus(opcion)
{
//	alert('si'+opcion.options[opcion.selectedIndex].value);
	var quitareturn=opcion.options[opcion.selectedIndex].value.replace(/return/,"");
	eval(quitareturn);
}

// empty Topics select list content
function clearSelect(wl) {
	try
	{
//    	while (wl.length > 0) {
//        	wl.remove(wl.length-1);
//			window.status='removiendo'+wl.length;
//    	}
//		alert('inner'+wl.innerHTML);
		wl.innerHTML="";
	}    	
    catch (err)
    {
//	    alert ('en clearselect ' + err.description);
    }
}


//  recibe el menu o vista sobre el cual va a dar mantenimiento
//  el movimiento que va a efectuar i=insert,d=delete,u=update
//  la llave con la que va a dara de baja o cambio
//  el renglon que va a dar de baja de la tabla
//20071112   se incluyo los eventos a efectuar en el cliente   wleventoantescl, wleventodespuescl
function mantto_tabla(wlmenu,wlmovto,wlllave,wlrenglon,wleventoantes,wleventodespues,wleventoantescl,wleventodespuescl)
{
	    if (wlmovto=='d' || wlmovto=='u' || wlmovto=='s' || wlmovto=='S' || wlmovto=='B' )
	    {
			wlllave=wlllave.replace(/"/g,"'");
		}			

        if (hayalgundatotecleado('formpr')!='si' && (wlmovto=='i' || wlmovto=='u' || wlmovto=='I'))
        {
           alert ('no ha tecleado ningun dato'); pone_focus_forma('formpr'); return;
        }

        if (wleventoantescl!="" && wleventoantescl!="undefined")
        {
	     	if (eventosparticulares(null,wleventoantescl)!=true)
	     	{   return false; }
        }
        
               
        if (wlmovto=='i'|| wlmovto=='u' || wlmovto=='I')
        {
        	if (checaobligatorios('formpr')==false)	
        	{
           		return;
			}           		
        }        

        if (wlmovto=='i' || wlmovto=='u' || wlmovto=='I')
        {
        	if (checanumericos('formpr')==false)	
        	{
           		return;
			}           		
        }                

        if (wlmovto=='i' || wlmovto=='u' || wlmovto=='I')
        {
        	if (checafechas('formpr')==false)	
        	{
           		return;
		}           		
        	if (checahoras('formpr')==false)	
        	{
           		return;
		}           		
        }                

        if (wlmovto=='i' || wlmovto=='u' || wlmovto=='I')
        {
        	if (checaparticulares('formpr')==false)	
        	{
           		return;
			}           		
        }                        
                
        if (wlrenglon==='' && (wlmovto=='u' || wlmovto=='d'))
        {   alert("el numero de renglon de la tabla esta vacio "+wlrenglon); return; }
        else
        {   glr = wlrenglon; }

        if (wlmovto=='d')
        {        
			if (window.confirm("Desea eliminar el registro")) 
			{ 
				if (wlllave=='') 
				{ 
					alert('La llave del registro no esta definida'); return; 
				} 
			}
			else
			{ return;};
		}			

        if (wlmovto=='u')
        {	if (window.confirm("Desea modificar el registro")) 
			{
			if (checaSiCambioAlgo(wlmenu,wlmovto,wlllave,wlrenglon,0)==false)
			{ alert('Usted no ha cambiado ningun dato'); pone_focus_forma('formpr'); return;}
			}
			else
			{ return;};
		}	

        if (wlmovto=='s' || wlmovto=='S' || wlmovto=='f' || wlmovto=='B')
        {        				
		        if (hayalgundatotecleadobus('formpr')!='si')
        		{
           			alert ('No ha tecleado ningun dato permitido para buscar\n'+'Los datos con asterisco son los filtros para la busqueda');
					pone_focus_forma('formpr');           			
           			return;
        		}	
		}        					
						
		// si es una alta en wlrenglon pone cuantos renglones hay en la tabla esto para generar el row en la alta
		if (wlmovto=='i')
		{
			if (window.confirm("Desea dar de alta el registro")) 
			{
			wlrenglon=document.getElementById('tabdinamica').rows.length;
			}
			else
			{ return;}
		}
		
//  20070831		if (wleventoantes!="")
//  20070831		{
//  20070831	        wlurl='eventos_servidor.php?opcion='+wleventoantes+'&wlmenu='+wlmenu+'&wlmovto='+wlmovto+buildQueryString('formpr')+"&wlllave="+escape(wlllave)+"&wlrenglon="+wlrenglon+"&wleventodespues="+wleventodespues;
//  20070831    	    CargaXMLDoc();			
//  20070831    	    return;
//  20070831		}
//  20070831		else
//  20070831		{
	
        //  20070831  lo cambien de posicion
        //  20080207  lo regrese a su posicion el planteamiento es que los eventos a ejecutar antes en el servidor
        //            se deben de ejecutar antes de una alta,baja,cambio, consulta ya que hubiese pasado todo el proceso
        //            previo de validacion
        //            por otro lado debe de regresar si continua con el moviento normal de o termina aqui, por default
        //            continua
		if (wleventoantes!="")
		{
			__eventocontinua=false;
	        wlurl='eventos_servidor.php';//20071105
	        passData='&opcion='+wleventoantes+'&wlmenu='+wlmenu+'&wlmovto='+wlmovto+buildQueryString('formpr')+"&wlllave="+escape(wlllave)+"&wlrenglon="+wlrenglon+"&wleventodespues="+wleventodespues+"&wleventodespuescl="+wleventodespuescl;//20071105
    	    CargaXMLDoc();		
    	    if 	( ! __eventocontinua )
	    	    return;
		}	
		

		if (wlmovto=='S')
			{
	           window.returnValue=armaFiltro('formpr');
	           window.close();
        	}
        	
            
/*        	
        	if (wlmovto=='s' || wlmovto=='S')
        	{        							
    			wlurl="man_menus.php?idmenu="+wlmenu+"&filtro="+armaFiltro('formpr');
				if (window.name=='dialog' )
				{
					showModalDialog(wlurl,document.getElementById('formpr'),"status:no;help:no");						
					window.close();							
				}
				else
				{ 	abre_consulta(wlmenu); }			
			    return;
			}			    
			else
			{			
				*/
        		wlurl='xmlhttp.php';//20071105
        		passData='&opcion=mantto_tabla&idmenu='+wlmenu+'&movto='+wlmovto+buildQueryString('formpr')+"&wlllave="+escape(wlllave)+"&wlrenglon="+wlrenglon+"&wleventodespues="+wleventodespues+"&wleventodespuescl="+wleventodespuescl+"&filtro="+escape(armaFiltro('formpr'));
//        		alert('passdata'+passData);
	        	CargaXMLDoc();			
	        	return;
        	// }
}
 
//  recibe el renglon de la tabla que se esta actualizando
//            columna del renglon de la tabla que se esta actualizando
//            objeto que se esta actualizando
//            valor del importe del ingresos que se quiere actualizar
//            la atl que quiere actualizar
//            la fecha de cobro que quiere actualizar
function caping(wlrenglon,wlcolumna,wlobjeto,valor,wlatl,wlfcobro)
{
//    alert('entro en caping');
    var vd1 = new valcomunes();
    vd1.ponnumero(wlobjeto);
    if (vd1.esmoneda()==false)
       {  return; }

    if (wlrenglon==0)
    {   alert("el numero de renglon de la tabla esta en ceros"); return; }
    else
    {   glr = wlrenglon; }
    if (valor!="")
    {
//20071105        wlurl='xmlhttp.php?opcion=caping&atl='+wlatl+'&fcobro='+wlfcobro+'&valor='+valor;
        wlurl='xmlhttp.php';        //20071105
        passData='&opcion=caping&atl='+wlatl+'&fcobro='+wlfcobro+'&valor='+valor;        
        CargaXMLDoc();
    }
}


// funcion que pone el mensaje en la siguiente celda donde se capturo el dato
// esto funciona exclusivamente para la captura de ingresos
function mensajetabla(wlmensaje)
{
// obtiene los renglones de la tabla
   var x=document.getElementById('tabdinamica').rows;
// obtiene las celdas del renglon a modificar
   var y=x[glr].cells ;
// pone el texto en la celda donde debe ir el mensaje
   y[3].innerHTML=wlmensaje;
   if (wlmensaje=='Incorrecto Usuario')
   {
       y[3].className="incorrecto";
   }
   if (wlmensaje=='Correcto Usuario')
   {
       y[3].className="correcto";
   }
}

// funcion que pone el mensaje en la siguiente celda donde se capturo el dato
function bajatabla()
{
	var wl=document.getElementById('tr'+glr).rowIndex;
      document.getElementById('tabdinamica').deleteRow(wl);
}
// funcion que da de baja todos los renglones de una tabla
function bajatodatabla()
{
//	alert('renglones'+document.getElementById('tabdinamica').rows.length);
	try
	{
	var nrows=document.getElementById('tabdinamica').rows.length;
	if (nrows>=2)
	{ 
				  for( var i=0; i<nrows-2 ; ++i ) {	              
						var wl=document.getElementById('tr'+i).rowIndex;					  
		              	document.getElementById('tabdinamica').deleteRow(wl);
//		              	alert('borro renglon'+i);
//		              	document.getElementById('tabdinamica').deleteRow(wl);
	              	}
	}
	} catch(err) { alert('error bajatodatabla '+err.message) }

//	var wl=document.getElementById('tr'+glr).rowIndex;
//      document.getElementById('tabdinamica').deleteRow(wl);
}

// funcion que cambia de color el row cuando cambian un registro
//  recibe el menu, el moviento que va hacer, la llave del cambio y el renglon de la tabla
//  cambiacolor, si es cero no cambia el color en el registro, si es 1 si cambia el color en el registor
function checaSiCambioAlgo(wlmenu,wlmovto,wlllave,wlrenglon,cambiacolor)
{

		var regresa=false;	
//		if (wlrenglon=="")
//		{ alert('el renglon no esta definido'); return; }		
		var ct=document.getElementById("tr"+wlrenglon).cells.length;
//		alert('entro enchecaSiCambioAlgo'+ct);
		for (e=0;e<ct;e++)
		{
	  		var el="r"+wlrenglon+"c"+e;
	  		var elm="cc"+e;
//			alert('type de elm '+document.getElementById(elm).type+' name '+document.getElementById(el).className);	  		
	  		try
	  		{
	  			if (document.getElementById(elm).type=='text' || document.getElementById(elm).type=='password' || document.getElementById(elm).type=='textarea')
//   20070503 se modifica para los campos de trabajo, si 	  			
//   20070503	  			{ if (document.getElementById(elm).value.trim()!=document.getElementById(el).innerText.trim())
                {  // 20070630 este inicio de { estaba a partir del siguiente if, esto hacia que no acutalizara bien
                   //          cuando cambia un dato el color, especificamente los select
                   //          se supone que el try es para la descripcion que se menciona el 20070503 
                   //          espermos que no truen por este cambio
                   // 20080310 Cuando hay un campos pwd que es un campo de trabajo este lo asume como si cambio algo
                   // 20080310 los campos de trabajo que no son de la tabla no los debe de considerar como un cambio
                   try { var valorren=document.getElementById(el).innerText.trim(); } catch (err) { var valorren=null; }
//20080412		Para autorizar un usuario no funcionaba ya que el unico dato solicitado es el pwd para autoriza y enviaba
//20080412				que no se habia cambiado ningun dato se restauro la modificacion del 20080310
//20080412				  try { var valorren=document.getElementById(el).innerText.trim(); } catch (err) { var valorren=document.getElementById(el).innerText.trim(elm); }	// 20080310
	  			 if (document.getElementById(elm).value.trim()!=valorren)
	  				{	
//		  				alert('cambio'+document.getElementById(el).id);
		  				if (cambiacolor==1)
		  				{
		  					document.getElementById(el).innerText=document.getElementById(elm).value;
		  					document.getElementById(el).className="cambiado";
						}		  				
		  				regresa=true; 
		  			}
	  			}
	  			if (document.getElementById(elm).type=='select-one')
	  			{ 
//		  			alert(' el'+el+' innertext'+document.getElementById(el).innerText+' renglon'+wlrenglon);
		  			if (document.getElementById(elm).value!=busca_ValorOption(document.getElementById(elm),document.getElementById(el).innerText,0,document.getElementById(el).abr))
	  				{ 	
		  				if (cambiacolor==1)
		  				{		  			
		  					document.getElementById(el).innerText=busca_DesOption(document.getElementById(elm),document.getElementById(elm).value);
		  					document.getElementById(el).className="cambiado";		  			
	  					}
		  				regresa=true; 
	  				}
//		  			alert(' despues de el'+el+' innertext'+document.getElementById(el).innerText);	  				
	  			}
	  			if (document.getElementById(elm).type=='checkbox')
	  			{ 
//  					alert('name='+document.getElementById(elm).name+'diferentes elm'+document.getElementById(elm).checked+'el'+nullesfalse(document.getElementById(el).innerText));		  			
		  			if (document.getElementById(elm).checked!=nullesfalse(document.getElementById(el).innerText))
	  				{	
//	  					alert('diferentes elm'+document.getElementById(elm).value+'el'+document.getElementById(el).innerText);
		  				if (cambiacolor==1)
		  				{
		  					document.getElementById(el).innerText=document.getElementById(elm).value;
		  					document.getElementById(el).className="cambiado";
						}		  				
		  				regresa=true; 
		  			}
	  			}	  			
			}	  			
			catch(err)
			{
//				alert('el'+el+' elm'+elm);
			}
    	}
    return regresa;
}

//  convierte un null a false
function nullesfalse(wlvalor)
{
//	alert('entre nullesfalse='+wlvalor);
	if (wlvalor=='')
	{	return false; }
	if (wlvalor=='f')
	{	return false; }
	if (wlvalor=='t')
	{	return true; }	
}

// Funcion que mueve el renglon que se desea modificar a los campos de captura para poder ser cambiados
function mueveCambio(wlmenu,wlmovto,wlllave,wlrenglon)
{
        alert('entro'+wlrenglon);	
        var z=document.getElementById('tr'+wlrenglon);	
        alert('cuantos elementos'+z.cells.length);
}

// Funcion que da de alta un renglon en la tabla donde muestra el registro recien dado de alta
function altatabla(wlrenglon)
{
	try
	{
        var xx = req.responseXML.getElementsByTagName("renglon");              	
//        alert('response'+xx(0).text);
        var tr=xx(0).text.split(">");        
//        alert('tr'+tr);
        var z=xx(0).text.split("</td>");
		var b = document.getElementById('tabdinamica').insertRow(1);			
		var p=0;

			
//	    b.oncontextmenu= wlTR.oncontextmenu;	

		var wlbaja = document.getElementById('baja');
		var wlcambio = document.getElementById('cambio');
//		try { if (wlbaja.name!="") { var m=b.insertCell(); m.innerHTML="<td></td>"; }}	catch(err)		{		};
//		try { if (wlcambio.name!="") { var m=b.insertCell(); m.innerHTML="<td></td>"; }}	catch(err)		{		};
//		alert('antes del for');
		for ( x in z)
		{
//			alert('x='+x+'inner'+z[x]);
			var str=z[x];
//			if (str!=null && str!='' && str.substr(0,2)=='<td')
			if (str!=null && str!='' )
			{			
//					var m=b.insertCell();
//					alert('str'+str.substring(str.indexOf("<td"))+' innerHTML'+m.innerHTML+' canhavehtml'+m.canHaveHTML);
//					alert('str'+str.substring(str.indexOf("<td")));
//					wlele=document.createElement(str.substring(str.indexOf("<td"))+"</td>");
					wlele=document.createElement("<td>");
					wlele.innerHTML=str.substring(str.indexOf("<td"));
//					alert('paso create elemente html'+wlele.innerHTML);
					b.appendChild(wlele);					
//					m.innerText=str.substring(str.indexOf("<td")+4);
//					m.innerHTML=str.substring(str.indexOf("<td"))+"</td>";
//					m.outerHTML=str.substring(str.indexOf("<td"))+"</td>";
					//  sirve para save si el campo tiene un id si es asi incrementa p que el numero de columna						
					if (str.substring(str.indexOf("<td")).indexOf("id=r")!=-1)
					{
						wlele.id='r'+wlrenglon+'c'+p;
//    	 				alert('m.id.type'+m.type);
						p=p+1;
					}    	 				
			}
		}	

		if (tr[0].indexOf("ondblclick")!=-1)
			{   
//				alert('detecto on');
				var pas=tr[0].substring(tr[0].indexOf("ondblclick")+12);
				var pas=pas.substring(0,pas.indexOf("'"));
//				alert('pas'+pas);
//				b.ondblclick=pas;
				b.ondblclick = function() { muestra_renglon(this); }
			}
			
		if (tr[0].indexOf("oncontextmenu")!=-1)
			{   
				var pas=tr[0].substring(tr[0].indexOf("oncontextmenu")+15);
				var pas=pas.substring(0,pas.indexOf("'"));
//				alert('pas'+pas);
				b.oncontextmenu= function() { muestra_renglon(this);contextForTR(this); return false; } ;
			}
						
		b.id='tr'+wlrenglon;		
		color_renglon(b);
//		alert('b.id'+b.id+' outer='+b.outerHTML);				
	} catch(err) { alert('error altatabla '+err.message) }	
	

/*				
	var all = document.getElementsByTagName("TABLE");
	alert('fina tablas'+all.length);	
	for( var i=0; i<all.length; ++i ) {

		for( var z=0; z<all[i].rows.length; ++z ) {		
			alert('tabla'+all[i].id+' row '+z+' inner'+all[i].rows[z].innerHTML);			
		}
	}				
*/	
		
}
//  revisa si hay que ejecutar un evento en el servidor despues de haber ejecutado un movimiento+
//  de mantenimiento de una tabla
function checa_eventodespues(wlrespuesta)
{
           if (req.responseText.indexOf("<wleventodespues>") != -1)
           {
	           //alert ('entro wleventodespues');
	          var iden="";  // iden contiene el numero de secuencia que le toco a un registro al dar de alta  20080216
              var items = req.responseXML.getElementsByTagName("iden");
              if (items.length>0)
              { iden="&iden="+items.item(0).text; }
              	           
              var items = req.responseXML.getElementsByTagName("wleventodespues");
              if (items.length>0)
              { 
	            if(items.item(0).text!="")
	            {
		            //alert (items.item(0).text);
//20071105        			wlurl='eventos_servidor.php?opcion='+items.item(0).text+buildQueryString('formpr');
        			wlurl='eventos_servidor.php';//20071105
        			passData='&opcion='+items.item(0).text+buildQueryString('formpr') + iden;        			        			
//		            alert('evento despues'+wlurl+' passData='+passData);        			
        			CargaXMLDoc();
    			}
	          }
              else {alert('no encontro el wleventodespues='+req.responseText)}
              return;
           }
// grecar 20101223
           if (req.responseText.indexOf("<wleventodespuescl>") != -1)
           {
	           //alert ('entro wleventodespuescl');
	          var iden="";  // iden contiene el numero de secuencia que le toco a un registro al dar de alta  20080216
              var items = req.responseXML.getElementsByTagName("iden");
              if (items.length>0)
              { iden="&iden="+items.item(0).text; }
              	           
              var items = req.responseXML.getElementsByTagName("wleventodespuescl");
              if (items.length>0)
              { 
	            if(items.item(0).text!="")
	            {
		            //alert (items.item(0).text);
        			if (eventosparticulares(null,items.item(0).text)!=true)
	     			{   return false; }
    			}
	          }
              else {alert('no encontro el wleventodespuescl='+req.responseText)}
              return;
           }
}
// funcion que maneja la respuesta que regresa el xmlhttp
function querespuesta() 
{

	try
	{
    if (req.readyState == 4)
    {
		window.status='req.readyState'+req.readyState+' req.status='+req.status;	    
        if (req.status == 200)
        {
//	        alert('regreso'+req.responseText);
           if (req.responseText.indexOf("<otrahoja>") != -1)
           {
              var items = req.responseXML.getElementsByTagName("otrahoja");
              if (items.length>0)
              { 
	              wlforma=document.getElementById('formpr');
	              wlforma.action=items.item(0).text;
	              wlforma.submit();
              }
              else {alert('no encontro otrahoja='+req.responseText)}
              return;
           } 
	        	        
           if (req.responseText.indexOf("<error>") != -1)
           {
              var items = req.responseXML.getElementsByTagName("error");
              if (items.length>0)
              { alert(items.item(0).text); }
              else {alert('no encontro el error='+req.responseText)}
              return;
           } 
           
           if (req.responseText.indexOf("<__eventocontinua>") != -1)
           {
              var items = req.responseXML.getElementsByTagName("__eventocontinua");
              if (items.length>0)
              { __eventocontinua=items.item(0).text; }
              else {alert('no encontro el __eventocontinua='+req.responseText)}
              return;
           }            
           
           if (req.responseText.indexOf("<salida>") != -1)
           {
              var items = req.responseXML.getElementsByTagName("salida");
//              alert('entro en salida');
              if (items.length>0)
              { 
	            if (items.item(0).text!='') 
	            {
			  		alert(items.item(0).text);
		  		}
              }
              
              else {alert('no encontro salida='+req.responseText)}
              self.close();
              parent.close();
//              navigate("index.php");
              return;
           }            
           
           if (req.responseText.indexOf("<bajaok>") != -1)
           {
              var items = req.responseXML.getElementsByTagName("bajaok");
              alert(items.item(0).text);
              bajatabla();
              return;
           } 
           
           if (req.responseText.indexOf("<continuamovto>") != -1)
           {
	             var desw = req.responseXML.getElementsByTagName("wlmenu");
	            var wlmenu = desw.item(0).text;	           
	            var desw = req.responseXML.getElementsByTagName("wlmovto");
	            var wlmovto = desw.item(0).text;
	            var desw = req.responseXML.getElementsByTagName("wlllave");
	            var wlllave = desw.item(0).text;	           	            	            	           	       
	            var desw = req.responseXML.getElementsByTagName("wlrenglon");
	            var wlrenglon = desw.item(0).text;
	            var desw = req.responseXML.getElementsByTagName("wleventodespues");
	            try { var wleventodespues = desw.item(0).text; } catch(err) { var wleventodespues = ""; }
//20071105	            wlurl='xmlhttp.php?opcion=mantto_tabla&idmenu='+wlmenu+'&movto='+wlmovto+buildQueryString('formpr')+"&wlllave="+escape(wlllave)+"&wlrenglon="+wlrenglon+"&wleventodespues="+wleventodespues;
//20080131      esto estaba erroneo cuando continuaba el movimiento
	            wlurl='xmlhttp.php'; //20080131
//20080131      wlurl='&opcion=mantto_tabla&idmenu='+wlmenu+'&movto='+wlmovto+buildQueryString('formpr')+"&wlllave="+escape(wlllave)+"&wlrenglon="+wlrenglon+"&wleventodespues="+wleventodespues; //20071105	            
	            passData='&opcion=mantto_tabla&idmenu='+wlmenu+'&movto='+wlmovto+buildQueryString('formpr')+"&wlllave="+escape(wlllave)+"&wlrenglon="+wlrenglon+"&wleventodespues="+wleventodespues;//20071105
        		CargaXMLDoc();			
        		return;
           }            

           if (req.responseText.indexOf("<abresubvista>") != -1)
           {
	           var desw = req.responseXML.getElementsByTagName("wlhoja");
	           var wlhoja = desw.item(0).text;
	           var desw = req.responseXML.getElementsByTagName("wlcampos");
	           var wlcampos = desw.item(0).text;
	           var desw = req.responseXML.getElementsByTagName("wldialogHeight");
	           var wldialogHeight = desw.item(0).text;
	           var desw = req.responseXML.getElementsByTagName("wldialogWidth");
	           var wldialogWidth = desw.item(0).text;	   
//	           alert(' ab wldialogHeight'+wldialogHeight+' wldialogWidth'+wldialogWidth);
	           if (wldialogHeight!=0 || wldialogWidth!=0)
	           {       
//					alert('wlcampos'+wlcampos);		           
//20061107			   		showModelessDialog(wlhoja+'?'+wlcampos,document.getElementById('formpr'),"status:no;help:no;dialogHeight:"+wldialogHeight+";dialogWidth:"+wldialogWidth);
			   		showModalDialog(wlhoja+'?'+wlcampos,document.getElementById('formpr'),"status:no;help:no;dialogHeight:"+wldialogHeight+";dialogWidth:"+wldialogWidth+";resizable:yes");
//					navigate(wlhoja+'?'+wlcampos);
		   	   }
		   	   else
		   	   {
//20061107			   		showModelessDialog(wlhoja+'?'+wlcampos,document.getElementById('formpr'),"status:no;help:no");			   		
			   		showModalDialog(wlhoja+'?'+wlcampos,document.getElementById('formpr'),"status:no;help:no;resizable:yes");			   					   		
//					navigate(wlhoja+'?'+wlcampos);
		   	   }
		   
               return;
           }
/*           
           //  20070616   cuando un select tiene demasiadas opciones el browser se pasma por que se chupa la memoria
           //  20070616   al detectar esto solicita las iniciales de la descripcion                         
           if (req.responseText.indexOf("<pon_selectpasolimite>") != -1)
           {
//	           wlselect,wlfiltropadre,wlfiltrohijo,fuentewhere,fuenteevento
	           var items = req.responseXML.getElementsByTagName("wlselect");	           
	           var wlselect = items.item(0).text;	           
	           var items = req.responseXML.getElementsByTagName("wlfiltropadre");	           
	           var wlfiltropadre = items.item(0).text;	           	           
	           var items = req.responseXML.getElementsByTagName("wlfiltrohijo");	           
	           var wlfiltrohijo = items.item(0).text;	           	           	           
	           var items = req.responseXML.getElementsByTagName("fuentewhere");	           
	           var fuentewhere = items.item(0).text;	           	           	           	           
	           var items = req.responseXML.getElementsByTagName("fuenteevento");	           
	           var fuenteevento = items.item(0).text;	    
	           var items = req.responseXML.getElementsByTagName("opciones");	           
	           var opciones = items.item(0).text;	    	           
	           var wlcampo=wlselect.substring(8,wlselect.indexOf(","));
			   var des=prompt('Las opciones son bastantes '+opciones+' favor de teclear las dos primeras letras para buscar las opciones que empiezan con estas',''); //20070215	
			   if (des!='' && des!=null) 
		    	{ 
//			   			alert('tipoof des'+des.type+' campo'+wlcampo.type);
	           			wlcampo=wlcampo+' like \''+des+'%\'';
//	           			alert('wlcampo'+wlcampo);			   			    	
						pon_Select(wlselect,wlfiltropadre,wlfiltrohijo,fuentewhere+wlcampo,fuenteevento)			
     		    }	                 	           	           	           	           
//	           alert('fuenteevento'+fuenteevento);
	           return;
           }
*/           
           if (req.responseText.indexOf("<ponselect>") != -1)
           {
//	           alert('regreso pon select');
	           var items = req.responseXML.getElementsByTagName("s_descripcion");	           
	           var desw = req.responseXML.getElementsByTagName("s_descripcion");	           
	           var des = desw.item(0).text
	           var items = req.responseXML.getElementsByTagName("s_descripcion");	           
	           var valw = req.responseXML.getElementsByTagName("s_value");	           
	           var val = valw.item(0).text	           
	           var items = req.responseXML.getElementsByTagName("wlfiltrohijo");
//	           alert('wlfiltrohijo'+items.item(0).text)
			   var wlhijos=items.item(0).text.split(',');
		       for (m=0;m<wlhijos.length;m++)
	  			{	
	           		var wl=document.getElementById('wl_'+wlhijos[m]);
//		           var items = req.responseXML.getElementsByTagName("fuenteevento");	           
//	    	       var fuenteevento=items.item(0).text;	           
//	        	   alert('fuenteevento-'+fuenteevento);
	           		clearSelect(wl);
//	           		alert('despues de clear select');
	           		buildTopicList(wl,des,val);
//	           		alert('despues de build topic');	           		
				}	           		
	           // si el evento es 3 se borra el evento click para que no lo vuelva a cargar cuandto tenga el focus
//	           if (fuenteevento==3)
//	           {  wl.onFocus()=''; }
           //  20070616   cuando un select tiene demasiadas opciones el browser se pasma por que se chupa la memoria
           //  20070616   al detectar esto solicita las iniciales de la descripcion     
/*                               
           if (req.responseText.indexOf("<pon_selectpasolimite>") != -1)
           {
//	           wlselect,wlfiltropadre,wlfiltrohijo,fuentewhere,fuenteevento
	           var items = req.responseXML.getElementsByTagName("wlselect");	           
	           var wlselect = items.item(0).text;	           
	           var items = req.responseXML.getElementsByTagName("wlfiltropadre");	           
	           var wlfiltropadre = items.item(0).text;	           	           
	           var items = req.responseXML.getElementsByTagName("wlfiltrohijo");	           
	           var wlfiltrohijo = items.item(0).text;	           	           	           
	           var items = req.responseXML.getElementsByTagName("fuentewhere");	           
	           var fuentewhere = items.item(0).text;	           	           	           	           
	           var items = req.responseXML.getElementsByTagName("fuenteevento");	           
	           var fuenteevento = items.item(0).text;	    
	           var items = req.responseXML.getElementsByTagName("opciones");	           
	           var opciones = items.item(0).text;	    	           
	           var wlcampo=wlselect.substring(8,wlselect.indexOf(","));
			   var des=pidebusqueda('Las opciones son bastantes '+opciones+' favor de teclear las primeras letras para buscar las opciones que empiezan con estas'); //20070215	
			   if (des!='' && des!=null) 
		    	{ 
//			   			alert('tipoof des'+des.type+' campo'+wlcampo.type);
	           			wlcampo=wlcampo+' like \''+des+'%\'';
//	           			alert('wlcampo'+wlcampo);			   			    	
						pon_Select(wlselect,wlfiltropadre,wlfiltrohijo,fuentewhere+wlcampo,fuenteevento,1)			
     		    }	                 	           	           	           	           
//	           alert('fuenteevento'+fuenteevento);
           }	           
*/           
               return;
           }            
                      
           if (req.responseText.indexOf("<altaok>") != -1)
           {
              var items = req.responseXML.getElementsByTagName("altaok");
              if (items.length>0)
              { alert(items.item(0).text); }
              else {alert('no encontro el altaok '+req.responseText+' elemento'+items.length)}
              var items = req.responseXML.getElementsByTagName("wlrenglon");              
              if (items.length>0)
              { var wlrenglon=items.item(0).text; 
//              	alert ('alta renglon'+wlrenglon);
              	altatabla(wlrenglon);}
              else {alert('no encontro el altaok '+req.responseText)}
//              alert('alta'+req.responseText)              
              checa_eventodespues(req.responseText);   
              var limpiaralta=true;
              var items = req.responseXML.getElementsByTagName("limpiaralta");              
              if (items.length>0)
              { limpiaralta=items.item(0).text; }              
			  pone_focus_forma("formpr");formReset("formpr",limpiaralta);   // limpia la pantalla despues de una alta              
              return;
              
           } 

           if (req.responseText.indexOf("<consulta>") != -1)
           {
				var x = req.responseXML;
                if (x.parseError.errorCode != 0)
                   {
                        alert('ups'+x.parseError.reason+' linea'+x.parseError.line+' srctext'+x.parseError.srcText );
                        return;
                 }
	           
              var items = req.responseXML.getElementsByTagName("consulta");
              if (items.length>0)
              { alert(items.item(0).text); }
              else {
	              alert('no encontro consulta lon '+items.length+' len text'+req.responseText.length);
//	              					document.body.insertAdjacentHTML('beforeEnd',req.responseText);
									alert(req.responseText.substring(1,1000)+' fin='+req.responseText.substring(req.responseText.length-1000));
	              					return;
	              }
              var items = req.responseXML.getElementsByTagName("renglones");              
              if (items.length>0)
              { 
/*	              
	              bajatodatabla();
				  for( var i=0; i<items.length ; ++i ) {	              
	              		var wlrenglon=items.item(i); 
//		              	alert ('alta renglon'+wlrenglon);              
		              	altatabla(wlrenglon);
	              	}
*/
//					alert('vientos'+items.item(0).text);
					var forma=document.all("formpr");
//					wlele=forma.createElement("<table>");
//					alert('despues de crear elemento'+items.item(0).text);					
//					var tadi=document.getElementById("tabdinamica");
//					alert('despues de get'+tadi.innerHTML);
//					tadi.innerHTML="";					
//					alert('despues de set html'+tadi.innerHTML);					
//					forma.insertAdjacentHTML('beforeEnd', items.item(0).text);
					tablas=document.body.getElementsByTagName('table');
					for( var i=0; i<tablas.length ; ++i ) {					
						if (tablas[i].outerHTML.indexOf("id=tabdinamica")!=-1)
						{ x=tablas[i]; x.parentNode.removeChild(x); }
					}
					document.body.insertAdjacentHTML('beforeEnd', items.item(0).text);
					//alert('sipi'+items.item(0).text);
					pone_sort_scroll();
					hayunregistro();									
					sumatotales();	
              }
              else {alert('no encontro renglones '+req.responseText)}
              checa_eventodespues(req.responseText);              
              try { var tadi=document.getElementById("menerror");  tadi.parentNode.removeChild(tadi); } catch (err) { }              
              return;
              
           } 
                                 
           if (req.responseText.indexOf("<altaokautomatica>") != -1)
           {	
	           //alert('altaokautomatica');
	           var desw = req.responseXML.getElementsByTagName("iden");//20080116
	           var des = desw.item(0).text;	           				   //20080116	           
	           window.returnValue=des;
	           window.close();
       		}
           
           if (req.responseText.indexOf("<altaautomatico>") != -1)        //20070215
           {//20070215
	            var desw = req.responseXML.getElementsByTagName("des");//20070215
	            var des = desw.item(0).text;	           				//20070215
	            var desw = req.responseXML.getElementsByTagName("dato");//20070215
	            var dato = desw.item(0).text;	           				//20070215	            
	            var desw = req.responseXML.getElementsByTagName("secuencia");//20070215
	            var secuencia = desw.item(0).text;	           				//20070215	            	            
        		var t=document.getElementById(dato);//20070215
        		var a = new Option(des,t.length,true,true);//20070215        		
        		a.value=secuencia;
        		t.add(a);
//        		t.options[t.length]=a;				//20070215
//				alert ('regreso altaautomatico SECUENCIA'+secuencia+' dato'+dato+' des'+des+' t.length'+t.length+' a.value'+a.value);        		//20070215
				return;	           //20070215
           }            //20070215
                      

           if (req.responseText.indexOf("<_nada_>") != -1)
           {
	           return;
           }           
                      
           if (req.responseText.indexOf("<cambiook>") != -1)
           {
	           
        	    var items = req.responseXML.getElementsByTagName("cambiook");				
	            alert(items.item(0).text);
	            var desw = req.responseXML.getElementsByTagName("wlmenu");
	            var wlmenu = desw.item(0).text;	           
	            var desw = req.responseXML.getElementsByTagName("wlmovto");
	            var wlmovto = desw.item(0).text;
	            var desw = req.responseXML.getElementsByTagName("wlllave");
	            var wlllave = desw.item(0).text;	           	            	            	           	       
	            var desw = req.responseXML.getElementsByTagName("wlrenglon");
	            var wlrenglon = desw.item(0).text;	           	            	            	           	       	            
//	            alert('va a cambiar algo wlmenu'+wlmenu+' wlmovto'+wlmovto+'wlllave'+wlllave+'wlrenglon'+wlrenglon);
				checaSiCambioAlgo(wlmenu,wlmovto,wlllave,wlrenglon,1);
//	            alert('despues de cambio algo');				
              	checa_eventodespues(req.responseText);              
    	        return;
//	            alert('despues del return');				    	        
           }            
           
           if (req.responseText.indexOf("<mensajetabla>") != -1)
           {
              var items = req.responseXML.getElementsByTagName("mensajetabla");
              mensajetabla(items.item(0).text);
              return;
           } 

			if (req.responseText.indexOf("<generatexto>") != -1)
			{
				var items = req.responseXML.getElementsByTagName("generatexto");
				if (items.length) { alert(items.item(0).text); }
				var desw = req.responseXML.getElementsByTagName("archivo");
				var wlarchivo = desw.item(0).text;
				//alert (wlarchivo);
				open(wlarchivo,'nvo');
				return;
			}
			if (req.responseText.indexOf("<generaexcel>") != -1)
			{
				var items = req.responseXML.getElementsByTagName("generaexcel");
				if (items.length) { alert(items.item(0).text); }
				var desw = req.responseXML.getElementsByTagName("archivo");
				var wlarchivo = desw.item(0).text;
				//alert (wlarchivo);
				open(wlarchivo,'nvo');
				return;
			}
			if (req.responseText.indexOf("<actualiza>") != -1)
			{
				var mensaje = req.responseXML.getElementsByTagName("mensaje");
				var menu = req.responseXML.getElementsByTagName("menu");
				if (menu.length)
				{ alert(mensaje.item(0).text); open('man_menus.php?idmenu='+menu.item(0).text,window.name); }
				return;
			}
			
			if (req.responseText.indexOf("<abremanual>") != -1)
			{
				var desw = req.responseXML.getElementsByTagName("abremanual");
				var wlarchivo = desw.item(0).text;
				open(wlarchivo,'nvo');
				//alert (wlarchivo);
				return;
			}
			
           alert("No esta progamada la respuesta que envia el servidor="+req.responseText);
          
        }
        else
        {
            alert("There was a problem retrieving the XML data1:\n"+req.statusText+" "+req.responseText);
        }
    }
	}
	catch (err)
	{
		alert("error que respuesta err.description="+err.description+" req.responseText="+req.responseText);
	}
}
 

// retrieve text of an XML document element, including
// elements using namespaces
function getElementTextNS(prefix, local, parentElem, index) {
//    alert('entro');
    var result = "";
    if (prefix && isIE) {
//	    alert ("entro iie");
        result = parentElem.getElementsByTagName(prefix + ":" + local)[index];
    } else {
//	    alert ("entro no prefijo"+parentElem.getElementsByTagName(local)[index].nodeValue);	    
        result = parentElem.getElementsByTagName(local)[index];
    }
//    alert('valor de result'+result+' local'+local);
    if (result) {
//	    alert('entro en result'+result.childNodes.length);	    
        if (result.childNodes.length > 1) {
            return result.childNodes[1].nodeValue;
        } else {
//	        alert('si firstchild');
//20070616  Tronaba cuando el registro venia es espacio la longitud era cero y tronada le inclui el if	        
        	if (result.childNodes.length == 1) 				//20070616
        	{	       										//20070616
            	return result.firstChild.nodeValue;                      
        	}												//20070616
        	else											//20070616
        	{ 	return "";      	}						//20070616
        }
    } else {
//		alert('entro en na');	    
        return "n/a";
    }
}
/**
* Muestra informacion de los campos select
*/
//function muestraInfo(wlselect,wlfiltropadre,wlfiltrohijo,fuentewhere,fuenteevento,sololimite,fuente_busqueda_idmenu)
function muestraInfo(attnum,fuente_info_idmenu,nombre)
{
	try
	{
		wlpersona=document.getElementById('wl_'+nombre);
		wltpersona=document.getElementById('wlt_'+nombre);
		if (wlpersona.value=='')
		{
			alert("Primero debe seleccionar el dato de "+wltpersona.value); wltpersona.focus();
		}
		else
		{
			filtro='id_persona='+wlpersona.value;
			//alert (attnum+' '+fuente_info_idmenu+' '+nombre+' '+wlpersona.value);
			//showModalDialog('man_menus.php?idmenu='+fuente_info_idmenu+'&filtro=id_persona='+wlpersona.value,'dialogHeight:500px; dialogWidth:800px');
			showModalDialog('man_menus.php?idmenu='+fuente_info_idmenu+'&filtro='+filtro,document.getElementById('formpr'),'dialogHeight:450px; dialogWidth:900px');
		}
	}
	catch(err) { alert('error en muestraInfo '+err.description); };
}
function actualizaRelog()
{
	try
	{	var wlencaFecha=document.getElementById('wl_encafecha');
		var wlencaHora=document.getElementById('wl_encahora');
		var fechaServidor=new Date();
		var anio=fechaServidor.getYear();
		var mes=this.mesesEspanol();
		var dia=fechaServidor.getDate();
		var hora=fechaServidor.getHours();
		var minuto=fechaServidor.getMinutes();
		var segundo=fechaServidor.getSeconds();
		// define fecha
		fechaActual='Fecha: '+((dia < 10) ? '0' : '')+ dia +' de '+mes+' de '+anio;
		wlencaFecha.value=fechaActual;
		// define hora 
		horaActual='Hora: '+((hora < 10) ? '0' : '')+ hora+''+((minuto < 10) ? ":0" : ":")+ minuto +''+((segundo < 10) ? ":0" : ":")+ segundo+'   ';
		wlencaHora.value=horaActual;
		setTimeout("actualizaRelog()",1000);
	} catch(err) { alert('error en actualizaRelog '+err.description); };
}
function mesesEspanol ()
{
	var fecha=new Date();
	var mes=new Array(12);
	mes[0]="Enero";
	mes[1]="Febrero";
	mes[2]="Marzo";
	mes[3]="Abril";
	mes[4]="Mayo";
	mes[5]="Junio";
	mes[6]="Julio";
	mes[7]="Augosto";
	mes[8]="Septiembre";
	mes[9]="Octubre";
	mes[10]="Noviembre";
	mes[11]="Diciembre";
	mesActual=mes[fecha.getMonth()];
	return mesActual;
}
 
//</script>
