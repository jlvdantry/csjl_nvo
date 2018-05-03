//<script>
// variables globales
var isIE = '\v'=='v';;
var req;
var wlurl;
var glr=0 ;  // variable donde se gurdar el renglon a actualizar
var passData ; // variable donde se guardan todos los datos de la format
var __eventocontinua = false;   // resultado o respuesta de haber llamado un objeto en el servidor
var Cambiosize = 0;
var autocomplete = "";
var _aa_ ;  // variable para la altaautomatica
var _aad_ ;  // variable para altaadjuntar

     window.showModalDialog = function (url,w,h,titulo,wlventana) 
     { 
       if (!wlventana) { wlventana=0; }
       if (wlventana==0)
       { return dhtmlmodal.open(titulo, 'iframe', url, titulo, 'width='+w+'px,height='+h+'px,center=1,resize=1,scrolling=1')  }
       if (wlventana==1)
       { return dhtmlwindow.open(titulo, 'iframe', url, titulo, 'width='+w+'px,height='+h+'px,center=1,resize=1,scrolling=1')  }
       if (wlventana==2)
       { window.open(url, '_blank',  'width='+w+'px,height='+h+'px,center=1,resize=1,scrolling=1'); return true;  }
     }
/*
   quita la function del string
  */
function quitaf(str) { 
   x=str.substring(str.indexOf('{')+1);
   x=x.substring(0,x.length-2);
  return x; 
} 
   
/*
   funcion para quitar el enter en los campos texto y de un click en el primer boton
  */
function quitaenter(field) {
        if (isIE)
        {
           if (event.keyCode==13) {event.keyCode=9; return event.keyCode }
           return event.keyCode;
        }

        var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
        if (keyCode == 13) {
                var i;
                for (i = 0; i < field.form.elements.length; i++)
                {
                        if (field == field.form.elements[i])
                                break;
                }
                //i = (i + 1) % field.form.elements.length;
                for (i =  i +1 ; i < field.form.elements.length; i++)
                {
                     console.log('id='+field.form.elements[i].id+' tabindex='+field.form.elements[i].tabIndex+' name='+field.form.elements[i].name);
                     if (field.form.elements[i].tabIndex>=0 && field.form.elements[i].tabIndex!='undefined' 
                         && (field.form.elements[i].name.indexOf('wl_')>=0 || field.form.elements[i].type=="button"))
                     {
                         field.form.elements[i].focus();
                         break;
                     }
                }
                return false;
        }
        else
        return event.keyCode;
}

/*
    funcion para salir del sistema
   */
function salir() {
       mensajee= window.confirm('Desea salir del sistema?' );
       if (mensajee){   
           if (window.navigate) {  window.navigate('index.php'); }
           else { location.assign('index.php'); }
       }
       else { return;}
} 

function armaImgPdf (archivo)
{
        archivoarr=archivo.split('.');
        if (archivoarr[1]=='jpg')
        {
                eventos_servidor("",0,"armaImgPdf","","",document.body.clientWidth,document.body.clientHeight);
        }       else    {
                open ('upload_ficheros/'+archivo,'x');
        }
}

if(typeof HTMLElement!='undefined'&&!HTMLElement.prototype.click)  // por firefox para simular un click
{
	HTMLElement.prototype.click=function(){
	var evt = this.ownerDocument.createEvent('MouseEvents');
	evt.initMouseEvent('click', true, true, this.ownerDocument.defaultView, 1, 0, 0, 0, 0, false, false, false, false, 0, null);
	this.dispatchEvent(evt);
	}
}

function formReset(wlforma,limpiaralta)
{
	if (limpiaralta=='t')
  		document.getElementById(wlforma).reset();
  theForm = document.getElementById(wlforma);
  var qs = '';
  for (e=0;e<theForm.elements.length;e++) {
    if (theForm.elements[e].name!='' && theForm.elements[e].name.indexOf('nc_')>=0) {
	   var str=theForm.elements[e].name;
       var wl=str.replace(/nc_/,"wl_");		  		
	   try { objwl=document.getElementByID(wl); objnc=document.getElementById(str); 
	          objnc.readOnly=false; objwl.readOnly=false; objwl.disabled=false; objwl.className=''; 
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
    	if(document.getElementById(divid).style.display == 'none'){
      		document.getElementById(divid).style.display = 'block';
                objeto.src='images/xp/Lminus.gif';
    	}else{
      		document.getElementById(divid).style.display = 'none';
                objeto.src='images/xp/Lplus.gif';
    	}
	}
	catch(err) 
	{ alert('error en toggleDiv '+err.description); };    
        return false;
  }

/*  20070524
	Funcion que solamente indica que la pantalla sufrio un cambio de pantalla,
	que al cerrarse la ventana manda a actualizar a el servidor el size de la pantalla
	Parametro recibido numero de menu
*/
function Cambiasize(idmenu)
{	stickhead(); // esta line va ligado con sortable_otro.js 20070524
        Cambiosize=idmenu; 
        //eventos_servidor("cambio de size",0,"cambiotamano","",idmenu,document.body.clientWidth,document.body.clientHeight);
}

/*    20071029
 *    se cambio para que en vez de utilizar el onchange se utilizar el keyup
 *    para cambiar de mayusculas a minusculas y viceversa ya que el onchange no funciona
 *    cuando se cambia varias veces en la misma session de mayusculas a minusculas
 */  
function mayusculas(objeto,evento)    
{
	if (evento.keyCode=='37' || evento.keyCode=='36' || evento.keyCode=='8' || evento.keyCode=='46' || evento.keyCode=='39')
	{ }
	else
	{   objeto.value=objeto.value.toUpperCase();  return false; }
}

function minusculas(objeto,evento)    
{
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
}

function sumatotales(theFormName) {
	try
	{
	    var tfoot = '';
		var tabla=document.getElementById('tabdinamica');
		if (tabla.rows.length>1)
		{
			var renglon=tabla.insertRow(-1);  // firefox
			header=document.getElementById('tabdinamica').rows[0];
			for( var i=0; i<header.cells.length ; ++i ) {
				if (header.cells[i].id=='totales')
				{
					var x=renglon.insertCell(i);
					x.innerHTML=dametotalcolumna(i);
					x.style.fontWeight="bold";
					x.style.borderTop="thin solid #0000FF";
				}
				else
				{
					var x=renglon.insertCell(i);
					header.cells[i].name=='noimprime' ? x.name='noimprime' : x.name="";
				}
			}
		}  
	}
	catch(err) { alert('error en sumatotales'+err.description); }
}

function dametotalcolumna(colu)
{
	try
	{
		var vart = 0;
		for( var i=1; i<document.getElementById('tabdinamica').rows.length ; ++i ) {
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
  for (e=0;e<theForm.elements.length;e++) {
    if (theForm.elements[e].name!='' && (theForm.elements[e].name.indexOf('_np_')>=0 || theForm.elements[e].name=='noimprime') ){
	   var str=theForm.elements[e].name;
	   var str1=str.replace(/_np_/,"wl_");
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
  return true	
	
}

/*  20070707
    Funcion que la impresion se ve en patalla
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
		document.body.insertAdjacentHTML('beforeEnd', sWebBrowser); 		
		oWebBrowser = document.getElementById("WebBrowser1");
		oWebBrowser.ExecWB(7,-1);		
		quecamponoimprime('formpr','');
		oWebBrowser.outerHTML='';
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

/*
	Funcion que se ejecuta que acualiza el tano de la forma
*/    
function Cambiatamano(idmenu,height,width)
{
		eventos_servidor("cambio de size",0,"cambiotamano","",idmenu,width,height)		;
}    
        
/*  20070524
	Funcion que se ejecuta al cerrar la ventana y lo unico que hace es actualizar el size de la misma
	si esta fue cambiada
*/    
function Cierraforma()
{
	if (Cambiosize!=0)
	{
		eventos_servidor("cambio de size",0,"cambiotamano","",Cambiosize,document.body.clientWidth,document.body.clientHeight)		;
	}
}    

//  quita los espacios del lado izquierdo y derecho de un string
String.prototype.trim = function()
{
   return this.replace(/(^\s*)|(\s*$)/g, "");
}
//pads left
String.prototype.lpad = function(padString, length) {
        var str = this;
    while (str.length < length)
        str = padString + str;
    return str;
}

//pads right
String.prototype.rpad = function(padString, length) {
        var str = this;
    while (str.length < length)
        str = str + padString;
    return str;
}

// para simula el click en ff
if(typeof HTMLElement!='undefined'&&!HTMLElement.prototype.click)
HTMLElement.prototype.click=function(){
var evt = this.ownerDocument.createEvent('MouseEvents');
evt.initMouseEvent('click', true, true, this.ownerDocument.defaultView, 1, 0, 0, 0, 0, false, false, false, false, 0, null);
this.dispatchEvent(evt);
}



//  20070703 se habilito el popmenu y cambio de color del renglon seleccionado
function muestra_renglon(wlrenglon)
{
   	var wlid = 	'cam'+wlrenglon.id.substring(2);
   	try { document.getElementById(wlid).click() } catch (err) { };   	
}

/* funcion que cambia el color del renglon seleccionado
*/
function color_renglon(wlrenglon)
{
   	color_renglon.poneclase(wlrenglon,'todofoco');
  	var wltabladinamica = document.getElementById('tabdinamica');
   	var wlTRs = wltabladinamica.getElementsByTagName('tr');
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
   	var siTD = wlrenglon.getElementsByTagName('td');
   	for (ex=0;ex<siTD.length;ex++) {		
			siTD[ex].className=wlclase;
  	}
}

/*
 funcion que muestra el contextmenu en la tabla de capura
 */
function contextForTABLE(objtabla)
{
	var wlTABLE=document.getElementById("tabbotones");
	var siSelect = wlTABLE.getElementsByTagName('TD');
	var wlstr='';
	if (siSelect.length>0)
	{
  		for (e=0;e<siSelect.length;e++) {		
			wlinput=siSelect[e].getElementsByTagName('INPUT');
			if (wlstr!='') { wlstr=wlstr+','; }
                        var ecode=eval(wlinput[0].onclick) + "_";
			wlstr=wlstr+'new ContextItem("'+wlinput[0].value+'",function(){'+quitaf(ecode)+'})';			
  		}
	        if (wlstr!='') { wlstr=wlstr+','; }
                wlstr=wlstr+'new ContextItem("Salir",function() { salir(); })';
  		wlstr='popupoptions = ['+wlstr+']';
                wlstr=wlstr.replace(/\&quot\;/g,'\'');
		eval(wlstr);
   		ContextMenu.display(popupoptions)		

	}
	return false;
}
/*
   muestra el conextmenu en la tabla de los reglones 
  */
function contextForTR(objtr)
{
	var wlTR=document.getElementById(objtr.id);
	var siSelect = wlTR.getElementsByTagName('SELECT');
	var wlstr='';
	if (siSelect.length>0)
	{
  		for (e=0;e<siSelect[0].length;e++) {		
			if (wlstr!='') { wlstr=wlstr+','; }
			wlstr=wlstr+'new ContextItem("'+siSelect[0].options[e].text+'",function(){'+siSelect[0].options[e].value+'})';
  		}
	}
	var siSelect = wlTR.getElementsByTagName('input');
	if (siSelect.length>0)
	{
  		for (e=0;e<siSelect.length;e++) {		
			if (wlstr!='') { wlstr=wlstr+','; }
                        var ecode=eval(siSelect[e].onclick) + "_";
                        if (siSelect[e].title!='')
			{ wlstr=wlstr+'new ContextItem("'+siSelect[e].title+'",function(){'+quitaf(ecode)+'})'; }
                        else
			{ wlstr=wlstr+'new ContextItem("'+siSelect[e].value+'",function(){'+quitaf(ecode)+'})'; }
  		}
	}
	if (wlstr!='') { wlstr=wlstr+','; }
        wlstr=wlstr+'new ContextItem("Salir",function() { salir(); })';
        wlstr='popupoptions = ['+wlstr+']';
        wlstr=wlstr.replace(/\&quot\;/g,'\'');
        eval(wlstr);
        color_renglon(wlTR);
        ContextMenu.display(popupoptions)		
        var siTD = objtr.getElementsByTagName('TD');
	return false;
}
//  20070703 se habilito el popmenu y cambio de color del renglon seleccionado


function seguridad(campo)
{
		return false;
}

/**				//20070215
  * abre una ventana donde solicita la descripcion de un nuevo registro //20070215
  * @param idmenu  numero //20070215
  * @nombre attnum numero de campo //20070215
  * @fuente_nspname  schema de la fuente //20080115
  * @altaautomatico_idmenu  numero de menu que se abre para dar de alta un registro  //20080115
  * @fuente_campodep   campo dependiente por el cual se selecciona el campo que se acaba de dar de alta
  * @obj  objeto que mando a ejecutar esta funcion
  **/ //20070215
function altaautomatico(idmenu,attnum,dato,fuente,fuente_campodes,fuente_nspname,altaautomatico_idmenu,fuente_campodep,fuente_campofil,obj) //20070215
{ //20070215
try {
	var vfcf="";
	if (fuente_campofil!="")
	   if (document.getElementByID("wl_"+fuente_campofil).value=="")
	   {
	       alert("Primero debe seleccionar el dato de "+document.getElementByID("wlt_"+fuente_campofil).value);
	       return false;
           }
	     
	try { vfcf=	document.getElementByID("wl_"+fuente_campofil).value; } catch(err) { vfcf=''; } ;


	if (altaautomatico_idmenu=="" || altaautomatico_idmenu=="0")
 	{	
                dapi=document.getElementById("altaautomatica_");
                if (dapi!=undefined || dapi!=null)
                { obj.parentNode.removeChild(dapi); return }
	 	//  regresa la descripcion de la alta automatica
                var aa = new altaautomatica();
                obj.parentNode.appendChild(aa.create());
                aa.focus();
                dapi=document.getElementById("altaautomatica_");
                aa.change = function () { des=aa.valor(); 
		                          if (des!='' && des!=null && des!='TECLEE AQUI UNA NUEVA OPCION' && des.trim()!='Teclee aqui una nueva opcion') { 
        	                            wlurl='xmlhttp.php'
        	                            passData='&opcion=altaautomatico&idmenu='+idmenu+'&attnum='+attnum+"&dato="+dato+"&fuente="+fuente+"&fuente_campodes="+
        	                                    fuente_campodes+"&des="+des+"&fuente_nspname="+fuente_nspname+"&fuente_campofil="+fuente_campofil+
                                                    "&valorfuente_campofil="+vfcf;        
	    	                            CargaXMLDoc();			
    	                                  }	 	
                                          obj.parentNode.removeChild(dapi) ;
                                        }
	}
 	else
 	{	
                //var url='man_menus.php?idmenu='+altaautomatico_idmenu+"&fuente_campofil="+fuente_campofil+"&valorfuente_campofil="+vfcf;
                var url='man_menus.php?idmenu='+altaautomatico_idmenu+"&filtro="+fuente_campofil+"="+vfcf;
                _aa_=dhtmlmodal.open("altaautomatico", "iframe", url, "Alta de una opcion", "width=590px,height=450px,center=1,resize=1,scrolling=0", "recal")
                _aa_.close = function (iden) 
                    {
                      //iden=_aa_.regresa(url);
                      if (iden!='' && iden!=null)
                      {
                         wlurl='xmlhttp.php'
                         passData='&opcion=buscaaltaautomatico&idmenu='+idmenu+'&attnum='+attnum+"&dato="+dato+"&fuente="+fuente+"&fuente_campodes="+fuente_campodes+"&iden="+iden+"&fuente_nspname="+fuente_nspname+"&fuente_campodep="+fuente_campodep;
                         CargaXMLDoc();
                      }
                      _aa_.hide();
                      return true;
                    } 

 	} 	
    } catch(err) { alert('error en altaautomatico '+err.description); };

} //20070215

function validafecha(wlcampos)
{
		var v = new valcomunes();
		v.valfecha(wlcampos);
}
function validaEmail(wlcampos)
{
                var v = new valcomunes();
                return v.validarEmail(wlcampos);
}
function validahora(wlcampos)
{
                var v = new valcomunes();
                v.valhora(wlcampos);
}

function despleid()
{
var str='';
var listitems= document.getElementsByTagName("div")
for (i=0; i<listitems.length; i++)
{  str= str + 'div ' + i + ' id='+listitems[i].id + ' name=' + listitems[i].name + '\n'; }
return str;
}

function muestrafecha(wlcampos)
{
	try {
	 var wlnombre=wlcampos.name.replace(/fe_/,"wl_");
         x=document.getElementsByName(wlnombre)[0]; //firefox
         dapi=document.getElementById('datePicker_');
         if (dapi!=undefined || dapi!=null)
         { x.parentNode.removeChild(dapi); return }
         var d = new Date();
         if (x.value=='')
         { var dp = new DatePicker(d); } else { 
                 d.setFullYear(x.value.substring(0,4),x.value.substring(5,7)-1,x.value.substring(8,10)); 
                 var dp = new DatePicker(d); }
	 x.parentNode.appendChild(dp.create()); 
         dapi=document.getElementById('datePicker_');
	 dp.onchange = function vales() { x.value = dp.getDate().getFullYear()+'-'+((dp.getDate().getMonth()<9) ? '0' + (dp.getDate().getMonth()+1) : (dp.getDate().getMonth()+1)) +'-'+((dp.getDate().getDate()<10) ? "0"+dp.getDate().getDate():dp.getDate().getDate()); return x.parentNode.removeChild(dapi);};
	} catch(err) { alert('error en muestrafecha '+err.description); };
}

/** 
  *   20070301 Abre una ventana para solicita un texto
  *   @param objecto  objeto del campo
  */
function muestratexto(wlcampos)
{
	var wlnombre=wlcampos.name.replace(/txt_/,"wl_");
        x=document.getElementsByName(wlnombre)[0];
 	var v = showModalDialog('pidetexto.php?valor='+escape(x.value),12,11);		
	try { 
		if (x.readonly!=true && v!=undefined)
		{	x.value=v; }
		}
	catch(err) 
	{ alert('error en muestrafecha '+err.description); };
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
	try 
	{ 
		if (fuente_busqueda_idmenu=="0" || fuente_busqueda_idmenu=="")
		{
 			var v = showModalDialog('pidebusqueda.php',400,100,'Solicita datos de busqueda');
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
	 		var iden = showModalDialog('man_menus.php?idmenu='+fuente_busqueda_idmenu,400,400,'Solicita datos de busqueda'); 
                        iden.close = function (llave) 
                        {
	 		   if (llave!=undefined)
	   		   { pon_Select(wlselect,wlfiltropadre,wlfiltrohijo,fuentewhere+(fuentewhere=='' ? ' ' : ' and ')+llave,fuenteevento,1); }
                           iden.hide();
                        }

		}
	}
	catch(err) 
	{ alert('error en pidebusqueda '+err.description); };
    return false;
}	
	
/** 
  *   20070301 Muestra el menu que sube un archivo
  *   @param objecto  objeto del campo
  */
function subearchivo(obj)
{
	try 
	{ 	
		var wlnombre=obj.name.replace(/upl_/,"wl_");
		var wlnombreh=obj.name.replace(/upl_/,"uplh_");
                dapi=document.getElementById("altaadjuntara_");
                if (dapi!=undefined || dapi!=null)
                { obj.parentNode.removeChild(dapi); return }
                aa = new altaadjuntara();
                obj.parentNode.appendChild(aa.create());
                dapi=document.getElementById("altaadjuntara_");
                aa.change = function () { des=aa.valor();
	                            	  if (des!='' && des!=null && des!=undefined)
		                          {	
                                               x=des.split(';');
			                       alert('El archivo se adjunto de forma exitosa con el nombre: '+x[1]); 
			                       obj.abbr=x[0];
			                       document.getElementsByName(wlnombre)[0].value=x[0];
			                       document.getElementsByName(wlnombreh)[0].value=x[1];
			                       //document.getElementsByName(wlnombre)[0].onchange(des);				
		                          }
                                          obj.parentNode.removeChild(dapi) ;
                                        }
                aa.dclick();
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
		var v = showModalDialog('subeclb.php',45,30,'Sube a archivo lo que esta en memoria');				
		if (v!='' && v!=null && v!=undefined)
			{	
				alert('El archivo se adjunto de forma exitosa con el nombre: '+v); 
				document.getElementById(wlcampos.name).abbr=v;
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
function abre_subvista(wlhoja,wlcampos,wleventoantes,wleventodespues,idmenu,wldialogWidth,wldialogHeight,wltitulo)
{
	try
	{
		if (wleventoantes!="")
		{
			eventos_servidor(wlhoja,wlcampos,wleventoantes,wleventodespues,idmenu,wldialogWidth,wldialogHeight)
		}
		else
		{
	    		if (wldialogHeight!=0 || wldialogWidth!=0)
	    	        {  
		    	        wlurl=wlhoja+'?'+wlcampos;
				//var x=showModalDialog(wlurl,wldialogWidth,wldialogHeight,'Subvista');
                                _aa_=dhtmlmodal.open(idmenu, 'iframe', wlurl, wltitulo, 'width='+wldialogWidth+'px,height='+wldialogHeight+'px,center=1,resize=1,scrolling=1',"recal");
                                if (wlcampos.indexOf("idmenu")!=-1)
                                {   
                                     _aa_.title=wlcampos.substr(wlcampos.indexOf("idmenu")+7);
                                     if (_aa_.title.indexOf("&")!=1) { _aa_.title=_aa_.title.substr(0,_aa_.title.indexOf("&")); } 
                                } else { _aa_.title=0; }
                                _aa_.close=function() {
                                       Cambiatamano(_aa_.title,_aa_.contentarea.clientHeight,_aa_.contentarea.clientWidth); 
                                       return true;
                                 }
	                }
		        else
		        {
		    	        wlurl=wlhoja+'?'+wlcampos;		
			        _aa_=showModalDialog(wlurl,100,300,'');			
                                _aa_.onclose=function() {
                                       Cierraforma(); 
                                       return true;
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
        wlurl='eventos_servidor.php';
        passData='&opcion='+wleventoantes+'&wlhoja='+                
        		wlhoja+'&wlcampos='+escape(wlcampos)+buildQueryString('formpr')+
        		'&wldialogWidth='+wldialogWidth+'&wldialogHeight='+wldialogHeight+'&idmenu='+idmenu+"&filtro="+escape(armaFiltro('formpr'));  //20070524
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

        wlurl=wlhoja  //20071105
        passData='&opcion='+wlfuncion+'&wlhoja='+
        		wlhoja+buildQueryString('formpr')+"&filtro="+escape(armaFiltro('formpr'));
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
}	
//  funcion que muestra el registro 2 de la tabla en los campos de pantalla
//  cuando esto sea por medio de una subvista
function hayunregistro()
{
		if (document.getElementById('tabdinamica')!=undefined)
		{
                        if(document.getElementById('tabdinamica').rows.length>=2)
                        {
                                try { document.getElementById('tabdinamica').style.visibility='visible';        } catch (err) { } ;
                        }
			if(document.getElementById('tabdinamica').rows.length==2)
			{
				try { document.getElementById('cam0').click();	} catch (err) { } ;
			}
		}			
}		

//   pone el focus en el primer campo de la forma		
function pone_focus_forma(theFormName)
{
  var ele='';
  try 
  {
	  theForm = document.getElementById(theFormName);	
	  for (e=0;e<theForm.elements.length;e++)
  		{
                        ele=theForm.elements[e];
   			if (ele.name!='' && ele.type!='hidden' && 
   		    	ele.type!='button' && ele.type!='reset'
   		    	&& ele.readOnly!=true
   		    	&& ele.disabled!=true
                        )
   		        {
		   		ele.focus();
		   		ele.className='foco';
		   		return true;
   			}
		}	
	}
	catch(err)
	{
                alert('error en pone_focus_forma en el campo '+ele.name+' '+err.message);
	}
}
	

// esta funcion arma el querystring
// la copie de utility.js
function armaFiltro(theFormName) {
try {
  theForm = document.getElementById(theFormName);
  var qs = '';
  var tipo = '' ;
  for (e=0;e<theForm.elements.length;e++) {
   if (theForm.elements[e].name!=''&& theForm.elements[e].name.indexOf('bu_')>=0) {
	   var str=theForm.elements[e].name;
	   var str1=str.replace(/bu_/,"wl_");
	   var str2=str.replace(/bu_/,"nu_");	   

		x=document.getElementsByName(str1)[0];
		x1=document.getElementsByName(str2)[0];

    	if (x.value!='') {     
	    	try { tipo=x1.value; }
	    	catch (err) { tipo=2; } 
	    	if (tipo=2)

      		{ 
	      		qs+=(qs=='')?' ':' and ';
			if (x.value.indexOf('%')>=0)  // si el dato tiene un % quiere decier que es un like
	    	{
	      		qs+=x.name.substring(3)+' like \''+x.value+"'";	      		
			}
			else
			{
	      		qs+=x.name.substring(3)+'=\''+x.value+"'"; //   20071107
			}
	      	}

  		}
      }
    }
	filtro=document.createElement("input");    
    filtro.type='hidden';   ///  firefox
    filtro.value=qs;
    filtro.id='_filtro_';
    filtro.name='_filtro_';    
    theForm.appendChild(filtro);
  	return qs
} catch (err) { alert('error armaFiltro='+err); }
}	
// funcion que pasa los datos de la forma padre a la forma hijo
// siempre y cuando el nombre de los campos padre se igual a los nombre de los campos hijos
function fijos(theForm)
{
	try
	{
  var qs = '';
  for (e=0;e<theForm.elements.length;e++)
  {
    if (theForm.elements[e].name!='' && theForm.elements[e].name.indexOf('wl_')>=0  )
    {
		if (theForm.elements[e].type=='select-one')
		{
			try
			{
				switch(document.getElementById(theForm.elements[e].name).type)
				{
				case 'select-one':
					var opciones=document.getElementById(theForm.elements[e].name);
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
			}
			catch(err)
			{ }
		}
    }
}

  }
catch (err)
{ //alert('error fijos'+err.description); 
}  
  return qs
}
	

// esta funcion arma el querystring
// la copie de utility.js
function buildQueryString(theFormName) {
  theForm = document.getElementById(theFormName);
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
		var s=wlonclick.substring(wlonclick.indexOf('onclick')+8,wlonclick.indexOf('onclick')+9);
		var wltextof=wltextoi.substring(0,wltextoi.indexOf(s));		
		return wltextof;
	} catch (err) { 
			alert('error dame_onclick'+err.description);
		};	
}
///**********************************   20061102
///  funcion que el camba el onclick
function cambia_onclick(wlonclick)
{

	try {
/*
		wltexto=document.getElementById("cambio").innerHTML;		
		var wltextoi=wltexto.substring(0,wltexto.indexOf('onclick')+9);
		wltextof=wltexto.substring(wltexto.indexOf('onclick'));		
		wltextof=wltextof.substring(wltextof.indexOf("'")+1);				
		wltextof=wltextof.substring(wltextof.indexOf("'"));						
		var obj=document.getElementById("cambio").innerHTML=wltextoi+' '+wlonclick+' '+wltextof;
*/
		var obj=document.getElementById("iCambio");
                if (obj.className='hidden') { obj.className='visible'; } 
                str=' obj.onclick = function () { ' + wlonclick + ' }';
                eval(str);
	} catch (err) { 
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
	  
	  for (e=0;e<theForm.length;e++)
  		{
   			if (theForm[e].id.substring(3)!=wlrenglon )
   		    {
			   	theForm[e].src='img/icon_enabled_checkbox_unchecked.gif';
   			}
   			else
   			{
			   	theForm[e].src='img/icon_enabled_checkbox_checked.gif';	   			
   			}
		}	
	}
	catch(err)
	{
		alert('error en pone_unckecked');
	}	

}




// funcion que muestra un registro en el broseo en los campos de captura
// la funcion recibe el nombre de la forma, el renglon y la cantidad de columnas que contiene el renglon
// y tambien la llave del registro que va a actualizar si es que hay cambio
// el menu o tabla que va a cambiar
// evento antes de ejecutar
// evento despues de ejecutar
// movimiento que disparo el muestra_cambio u=update de registro,s=subvista,f=funcion,u1=no se (parace que es update)
// 20071113  se agrego los eventos antes que hay que ejecutar antes de insertar, select,update o delete en el cliente
// 20071113 function muestra_cambio(theFormName,r,ct,wlllave,menu,wleventoantes,wleventodespues,movto) {
function muestra_cambio(theFormName,r,ct,wlllave,menu,wleventoantes,wleventodespues,wleventoantescl,wleventodespuescl,movto,noconfirmamovtos) {	
  // pone el dato de img listo para efectuar el cambio

  if (movto=='B')  //Opcion de busqueda para que funcione esto debe estar definido una campo llave
	{
           var docx=window.parent.document;
           var bus=docx.getElementById("Solicita datos de busqueda");
           bus.close(wlllave)
           return;
       	}    
  glr=r;
  var wlcambio="\\\"";
  wlllave=wlllave.replace(/"/g,wlcambio);
  
  pone_unchecked(r);
  wlonclick="mantto_tabla(\""+menu+"\",\"u\",\""+wlllave+"\","+(r)+",\""+wleventoantes+"\",\""+wleventodespues+"\",\""+wleventoantescl+"\",\""+wleventodespuescl+"\",\""+noconfirmamovtos+"\");return false;";
  cambia_onclick(wlonclick);


  for (e=0;e<ct;e++) {
	  var el="r"+r+"c"+e; // renglon columna que se muestra en la pantalla de captura
	  var elm="cc"+e;   //  campos de la pantalla de captura
		try {
                        xelm=document.getElementById(elm);
                        xel=document.getElementById(el);
	  		if (xelm.type=='text')
	  		{ 
				var captu=document.getElementById(elm);
		  		captu.value=document.getElementById(el).innerText.trim(); 

		  	}
	  		if (xelm.type=='textarea')
	  		{ 
		  		document.getElementById(elm).value=document.getElementById(el).innerText.trim(); 
		  	}		  	
	  		if (xelm.type=='select-one')
	  		{ 
					document.getElementById(elm).value=busca_ValorOption(document.getElementById(elm),document.getElementById(el).innerText,1,document.getElementById(el).abbr); 
		  		}
	  		if (xelm.type=='checkbox')
	  		{ 
		  		document.getElementById(elm).value=valor_checkbox_cap(el,elm); 
		  	}	
				var captu=document.getElementById(elm);		  	
	   			var str=captu.name;
	   			var str1=str.replace(/wl_/,"nc_");		  		
	   			try { nc=document.getElementById(str1); nc.readOnly=true; captu.readOnly=true; captu.disabled=true; captu.className='lee'; } catch (err) { };		  		  		
	  	}
  		catch(err)
  		{
  		}
    }
    pone_focus_forma(theFormName);

	wlTR=document.getElementById('tr'+r);    
	color_renglon(wlTR);
	muevea1renglon(r);   //20070808  checar porque parece que esta chafeando esta rutina
}
/*  funcion que mueve el renglon seleccionado a el renglon 1 
     recib el numero de renglon*/
function muevea1renglon(r)
{
	wlTR=document.getElementById('tr'+r);    	
	var b = document.getElementById('tabdinamica').insertRow(1);
   	var wlTDs = wlTR.getElementsByTagName('TD');  	
  	for (e=0;e<wlTDs.length;e++) {		
					wlele=document.createElement("td");   //  al td le quite <td> ya que tronada ff
					wlele.innerHTML=wlTDs[e].innerHTML;
					wlele.className=wlTDs[e].className;
					wlele.id=wlTDs[e].id;					
					wlele.abbr=wlTDs[e].abbr;	//20070815 falta esta linea provocaba undefined en los campos select
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
}

// de acuerdo al valor de la fila pone checked o unchecked el campo de captura
function valor_checkbox_cap(el,elm)
{
	if(document.getElementById(el).innerText=='t') 
	{   
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
	  		for (ee=0;ee<opciones.length;ee++)
	  		{
				if(opciones.options[ee].text==wltexto)
				{
				     //   alert('si encontro');
					return opciones.options[ee].value;
				}
			}
			if (wlalta==1)
			{
	  			if (opciones.length>0	)
	    			  { clearSelect(opciones); }   // si llega a esta instancia no encontro el dato por que lo se supones que es un select que depende de otro
						               // cuando un cambio se ha mostrado puede ser que ya existan
						               // algunos valor en el select por que decidi limpiar este dato
						// 20070810  se modifico para que agregue la opcion selecciones opcion que que funcione el boton limpiar						               
        		        appendToSelect(opciones, wlvalor, document.createTextNode(wltexto)) // 20070810
        		        appendToSelect(opciones, '', document.createTextNode("Selecciona una Opci\u00f3n"),2)      // 20070810  		        
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
try {
  theForm = document.getElementById(theFormName);
  var qs = '';
  for (e=0;e<theForm.elements.length;e++) {
    if (theForm.elements[e].name!='' && theForm.elements[e].name.indexOf('bu_')>=0) {
	   var str=theForm.elements[e].name;
	   var str1=str.replace(/bu_/,"wl_");
		x=document.getElementsByName(str1);
       if (x.value!='' && x.type!='button' && x.type!='hidden' && x.type!='reset' ) {
	      qs='si';
       }
    }
  }
  return qs
} catch (err) { alert('error hayalgundatotecleadobus'+err.message); }

}


// funcion que checa si hubo algun dato tecleado
function hayalgundatotecleado(theFormName) {
  theForm = document.getElementById(theFormName);
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
  var qs = '';
    for (e=0;e<theForm.elements.length;e++) {
    if (theForm.elements[e].name!='' && theForm.elements[e].name.indexOf('ob_')>=0) {
	   var str=theForm.elements[e].name;
	   var str1=str.replace(/ob_/,"wl_");
	   var str2=str.replace(/ob_/,"wlt_");
		x=document.getElementsByName(str1)[0];
       if (trim(x.value)=='' && x.type!='button' && x.type!='hidden' && x.type!='reset' ) {
		x2=document.getElementsByName(str2)[0];	
          alert ('El dato "'+x2.abbr+'" es obligatorio ');
          x.focus();
          return false;
       }
    }
  }
    return true
}
catch(err)
{ alert('error checaobligatorios='+err+' e='+e+' elementos='+theForm.elements.length+' name='+str1); }
}

// funcion que checa que sean numericos los campos que deben ser numericos
function checanumericos(theFormName) {
  theForm = document.getElementById(theFormName);
  var qs = '';
  for (e=0;e<theForm.elements.length;e++) {
    if (theForm.elements[e].name!='' && theForm.elements[e].name.indexOf('nu_')>=0) {
	   var str=theForm.elements[e].name;
	   var str1=str.replace(/nu_/,"wl_");
		x=document.getElementsByName(str1)[0];	    
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
  for (e=0;e<theForm.elements.length;e++) {
    if (theForm.elements[e].name!='' && theForm.elements[e].name.indexOf('_da_')>=0) {
	   var str=theForm.elements[e].name;
	   var str1=str.replace(/_da_/,"wl_");
	   x=document.getElementsByName(str1)[0];	    
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


// 20070804 Funcion que ejecuta un evento
//          recibe el objeto que lo disparo 
//          y la funcion a ejecutar
function eventosparticulares(x,evento)
{
	try
	{
	   if (evento.value!='')
	   {
		   codigo="	var vd = new eve_particulares();  if (vd."+evento+"(x)==false)	{   regreso=false;  	}  	else { 	regreso=true; } ";
		   eval(codigo);
		   if (regreso==false) { if(x!=null) {x.focus();};  return false;}
	   }		   
	}
	catch(err)
	{ alert('error eventosparticulares '+err.description+' en evento='+evento); return false; }
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
	   x=document.getElementById(str1);	    
	   if (x.value!='')
	   {
		   codigo="	var vd = new val_particulares();  if (vd."+validacion+"(x)==false)	{   regreso=false;  	}  	else { 	regreso=true; } ";
		   eval(codigo);
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
        	isIE = true;
         	req = new ActiveXObject("Msxml2.XMLHTTP");         	
        	if (req)
        	{
            	req.onreadystatechange = querespuesta;
            	req.open("POST", wlurl, false);  // sincrona
                req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");                   	
            	req.send(passData);
        	}
		}        	
		else
		{
    		if (window.XMLHttpRequest) 
    		{
        		req = new XMLHttpRequest();
        		req.open("POST", wlurl, false);  // sincrona
                req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        		req.send(passData);
        		querespuesta();
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
	// si ya esta lleno el select y el evento es 3 onfocus solo la primiera vez se sale
	// para no volver a llenar el campo select
	if (fuenteevento==3 && document.getElementsByName("wl_"+wlfiltrohijo)[0].length>=2 && sololimite==0)	
	{  return; }
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
		  		try { valor=document.getElementsByName("au_"+a[m])[0].value } catch (err) { valor=""; }
		  		if (valor=="") { try { valor=document.getElementsByName("wl_"+a[m])[0].value } catch (err) { valor=""; } }	  		
				if(valor!='')
				{
					//  si existe un elemento que sea nu_ es numerico en caso es un string y debe de llevar comillas
					try { if(document.getElementsByName("nu_"+a[m])[0].name!='') { wlcomi=""; } else { wlcomi=""; } } catch (err) { wlcomi="'"; }
					if (wlvarios=="")
					{ wlvarios=a[m]+"="+wlcomi+valor+wlcomi; }
					else
					{  wlvarios=wlvarios+" and "+a[m]+"="+wlcomi+valor+wlcomi; }
    			}	
    			else
    			{   clearSelect(document.getElementsByName("wl_"+wlfiltrohijo)[0]);
	    			return;
    		    }
			}
  	  } // 20070615 Tronaba cuando no traia filtro padre
	  if (fuentewhere!="")
	  {	wlwhere=reemplaza_where(fuentewhere,'formpr');
	  	// 20071015
	  	if (wlwhere==false) { return; } // 20071015
	  }
	  
	  wlvs=wlselect;
	  if (wlvarios!='')
	  	{
                        wlvs=wlvs+' where '+wlvarios+(wlwhere.trim()=='' ? '' : ' and '+wlwhere);
	  		wlurl='xmlhttp.php';//20071105
	  		passData='&opcion=pon_select&sql='+wlvs+'&wlfiltropadre='+wlfiltropadre+'&wlfiltrohijo='+wlfiltrohijo+'&fuenteevento='+fuenteevento+'&fuentewhere='+fuentewhere;	  			  		
      		CargaXMLDoc();		  	
  		}
  		else
	  	if (wlvs!='')  	// 20070615 Tronaba cuando no traia filtro padre	
  		{ // 20070615 Tronaba cuando no traia filtro padre
		  	wlvs=wlvs+(wlwhere!='' ? ' where '+wlwhere : '' );   		
	  		wlurl='xmlhttp.php';//20071105
	  		passData='&opcion=pon_select&sql='+wlvs+'&wlfiltropadre='+wlfiltropadre+'&wlfiltrohijo='+wlfiltrohijo+'&fuenteevento='+fuenteevento; // 20070615 Tronaba cuando no traia filtro padre	  			  		
      		CargaXMLDoc(); // 20070615 Tronaba cuando no traia filtro padre
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
  var qs = '';
  for (e=0;e<theForm.elements.length;e++) {
    if (theForm.elements[e].name!=''&& (theForm.elements[e].name.indexOf('wl_')>=0 || theForm.elements[e].name.indexOf('au_')>=0)) {
		while (fuentewhere.indexOf(theForm.elements[e].name)>=0)
		{
			if (theForm.elements[e].value=='')
			{ alert('Primero debe teclear el dato '+theForm.elements[e].title);theForm.elements[e].focus;return false; }  
			fuentewhere=fuentewhere.replace(theForm.elements[e].name,theForm.elements[e].value);
		}
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
		if(document.getElementsByName("wl_"+a[m])[0].value=='')
		{
			alert('Primero debe teclear o seleccionar el dato "'+document.getElementById("wlt_"+a[m]).value+'"');
			return false;
    	}	
	  }
  	}
  	catch(err)
  	{
	  	alert('error en si_select '+wlselect+ ' filtro '+wlfiltro); return false;
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
    	opt.value = value;
    	opt.appendChild(content);
    	if (wlselected==1) { opt.defaultSelected=true; opt.selected=true; }  // 20070808
    	if (wlselected==2) { opt.defaultSelected=true; }    	// 20070810
    	wlselect.appendChild(opt);
	}
  	catch(err)
  	{
  	}    	
}

function buildTopicList(wl,des,val) 
{
    var items = req.responseXML.getElementsByTagName("registro");
    for (var i = 0; i < items.length; i++) {
        appendToSelect(wl, getElementTextNS("", val, items[i], 0),  
        document.createTextNode(getElementTextNS("", des, items[i], 
            0)),(items.length==1 ? 1 : 0));            // 20080210
    }
    appendToSelect(wl, "", document.createTextNode("Selecciona una Opci\u00f3n"),(items.length==1 ? 0 : 1)); //   20070808    
    wl.click;
}

/**   20070629
  *  ejecuta un submenu a nivel renglon
  *  lo que ejecuta viene el el value de la opcion
  **/
function submenus(opcion)
{
	var quitareturn=opcion.options[opcion.selectedIndex].value.replace(/return/,"");
	eval(quitareturn);
}

// empty Topics select list content
function clearSelect(wl) {
	try
	{
		wl.innerHTML="";
	}    	
    catch (err)
    {
	    alert ('en clearselect ' + err.description);
    }
}


//  recibe el menu o vista sobre el cual va a dar mantenimiento
//  el movimiento que va a efectuar i=insert,d=delete,u=update
//  la llave con la que va a dara de baja o cambio
//  el renglon que va a dar de baja de la tabla
//20071112   se incluyo los eventos a efectuar en el cliente   wleventoantescl, wleventodespuescl
function mantto_tabla(wlmenu,wlmovto,wlllave,wlrenglon,wleventoantes,wleventodespues,wleventoantescl,wleventodespuescl,noconfirmamovto)
{
	    if (wlmovto=='d' || wlmovto=='u' || wlmovto=='s' || wlmovto=='S' || wlmovto=='B' )
	    {
			wlllave=wlllave.replace(/"/g,"'");
		}			

        if (hayalgundatotecleado('formpr')!='si' && (wlmovto=='i' || wlmovto=='u' || wlmovto=='I'))
        {
           alert ('no ha tecleado ningun dato'); pone_focus_forma('formpr'); return;
        }

        if (wleventoantescl!="" && wleventoantescl!=undefined)
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
           if (noconfirmamovto.indexOf("d")!=-1)
           { }
           else
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
        }


        if (wlmovto=='u')
        {
           if (noconfirmamovto.indexOf("u")!=-1)
           {
              if (checaSiCambioAlgo(wlmenu,wlmovto,wlllave,wlrenglon,0)==false)
                 { alert('Usted no ha cambiado ningun dato'); pone_focus_forma('formpr'); return;}
           }
           else
           {
                if (window.confirm("Desea modificar el registro"))
                {
                        if (checaSiCambioAlgo(wlmenu,wlmovto,wlllave,wlrenglon,0)==false)
                        { alert('Usted no ha cambiado ningun dato'); pone_focus_forma('formpr'); return;}
                }
                else
                { return;};
            }
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
                   if (noconfirmamovto.indexOf("i")!=-1)
                   {    wlrenglon=document.getElementById('tabdinamica').rows.length; }
                   else
                   {
                        if (window.confirm("Desea dar de alta el registro"))
                        {
                        wlrenglon=document.getElementById('tabdinamica').rows.length;
                        }
                        else
                        { return;}
                   }

                }
		
	
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
	        passData='&opcion='+wleventoantes+'&wlmenu='+wlmenu+'&wlmovto='+wlmovto+buildQueryString('formpr')+"&wlllave="+escape(wlllave)+"&wlrenglon="+wlrenglon+"&wleventodespues="+wleventodespues;//20071105
    	    CargaXMLDoc();		
    	    if 	( ! __eventocontinua )
	    	    return;
		}	
		

		if (wlmovto=='S')
			{
	           window.returnValue=armaFiltro('formpr');
	           window.close();
                   //SubMod._ReturnVal=armaFiltro('formpr');
                   //document.getElementById("popCloseBox").click();
        	}
        	
            
        		wlurl='xmlhttp.php';//20071105
        		passData='&opcion=mantto_tabla&idmenu='+wlmenu+'&movto='+wlmovto+buildQueryString('formpr')+"&wlllave="+escape(wlllave)+"&wlrenglon="+wlrenglon+"&wleventodespues="+wleventodespues+"&filtro="+escape(armaFiltro('formpr'))+"&noconfirmamovto="+escape(noconfirmamovto);
	        	CargaXMLDoc();			
	        	return;
}
 
//  recibe el renglon de la tabla que se esta actualizando
//            columna del renglon de la tabla que se esta actualizando
//            objeto que se esta actualizando
//            valor del importe del ingresos que se quiere actualizar
//            la atl que quiere actualizar
//            la fecha de cobro que quiere actualizar
function caping(wlrenglon,wlcolumna,wlobjeto,valor,wlatl,wlfcobro)
{
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
        wlurl='xmlhttp.php';        //20071105
        passData='&opcion=caping&atl='+wlatl+'&fcobro='+wlfcobro+'&valor='+valor;        
        CargaXMLDoc();
    }
}


// funcion que pone el mensaje en la siguiente celda donde se capturo el dato
// esto funciona exclusivamente para la captura de ingresos
function mensajetabla(wlmensaje)
{
   var x=document.getElementById('tabdinamica').rows; // obtiene los renglones de la tabla
   var y=x[glr].cells ; // obtiene las celdas del renglon a modificar
   y[3].innerHTML=wlmensaje; // pone el texto en la celda donde debe ir el mensaje
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
	try
	{
	var nrows=document.getElementById('tabdinamica').rows.length;
	if (nrows>=2)
	{ 
				  for( var i=0; i<nrows-2 ; ++i ) {	              
						var wl=document.getElementById('tr'+i).rowIndex;					  
		              	document.getElementById('tabdinamica').deleteRow(wl);
	              	}
	}
	} catch(err) { alert('error bajatodatabla '+err.message) }

}

// funcion que cambia de color el row cuando cambian un registro
//  recibe el menu, el moviento que va hacer, la llave del cambio y el renglon de la tabla
//  cambiacolor, si es cero no cambia el color en el registro, si es 1 si cambia el color en el registor
function checaSiCambioAlgo(wlmenu,wlmovto,wlllave,wlrenglon,cambiacolor)
{
try {  // firefox

		var regresa=false;	
		var ct=document.getElementById("tr"+wlrenglon).cells.length;
		for (e=0;e<ct;e++)
		{
	  		var el="r"+wlrenglon+"c"+e;
	  		var elm="cc"+e;
	  		try
	  		{
	  			if (document.getElementById(elm).type=='text' || document.getElementById(elm).type=='password' || document.getElementById(elm).type=='textarea')
                {  // 20070630 este inicio de { estaba a partir del siguiente if, esto hacia que no acutalizara bien
                   try { var valorren=document.getElementById(el).innerText.trim(); } catch (err) { var valorren=null; }
	  			 if (document.getElementById(elm).value.trim()!=valorren)
	  				{	
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
		  			if (document.getElementById(elm).value!=busca_ValorOption(document.getElementById(elm),document.getElementById(el).innerText,0,document.getElementById(el).abbr))
	  				{ 	
		  				if (cambiacolor==1)
		  				{		  			
		  					document.getElementById(el).innerText=busca_DesOption(document.getElementById(elm),document.getElementById(elm).value);
		  					document.getElementById(el).className="cambiado";		  			
	  					}
		  				regresa=true; 
	  				}
	  			}
	  			if (document.getElementById(elm).type=='checkbox')
	  			{ 
		  			if (document.getElementById(elm).checked!=nullesfalse(document.getElementById(el).innerText))
	  				{	
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
			}
    	}
    return regresa;
} catch (err) { alert ('error checaSiCambioAlgo='+err) } // firefox
}

//  convierte un null a false
function nullesfalse(wlvalor)
{
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
        var z=document.getElementById('tr'+wlrenglon);	
}

// Funcion que da de alta un renglon en la tabla donde muestra el registro recien dado de alta
function altatabla(wlrenglon)
{
	try
	{
        var xx = req.responseXML.getElementsByTagName("renglon");              	
        var tr=xx[0].childNodes[0].nodeValue.split(">");        
        var z=xx[0].childNodes[0].nodeValue.split("</td>");
		var b = document.getElementById('tabdinamica').insertRow(1);			
                try { document.getElementById('tabdinamica').style.visibility='visible';        } catch (err) { } ;
		var p=0;

			

		var wlbaja = document.getElementById('baja');
		var wlcambio = document.getElementById('cambio');
		for ( x in z)
		{
			var str=z[x];
			if (str!=null && str!='' && str.indexOf("<td")!=-1)
			{			
					wlele=document.createElement("td");
					wlele.innerHTML=str.substring(str.indexOf("<td"));
					b.appendChild(wlele);					
					//  sirve para save si el campo tiene un id si es asi incrementa p que el numero de columna						
					if (str.substring(str.indexOf("<td")).indexOf("id=r")!=-1)
					{
						wlele.id='r'+wlrenglon+'c'+p;
						p=p+1;
					}    	 				
			}
		}	

		if (tr[0].indexOf("ondblclick")!=-1)
			{   
				var pas=tr[0].substring(tr[0].indexOf("ondblclick")+12);
				var pas=pas.substring(0,pas.indexOf("'"));
				b.ondblclick = function() { muestra_renglon(this); }
			}
			
		if (tr[0].indexOf("oncontextmenu")!=-1)
			{   
				var pas=tr[0].substring(tr[0].indexOf("oncontextmenu")+15);
				var pas=pas.substring(0,pas.indexOf("'"));
				b.oncontextmenu= function() { contextForTR(this); return false; } ;
			}
						
		b.id='tr'+wlrenglon;		
		color_renglon(b);
	} catch(err) { alert('error altatabla '+err.message) }	
	

		
}
//  revisa si hay que ejecutar un evento en el servidor despues de haber ejecutado un movimiento+
//  de mantenimiento de una tabla
function checa_eventodespues(wlrespuesta)
{
  try {
           if (req.responseText.indexOf("<wleventodespues>") != -1)
           {
	          var iden="";  // iden contiene el numero de secuencia que le toco a un registro al dar de alta  20080216
              var items = req.responseXML.getElementsByTagName("iden");
              if (items.length>0 && items[0].childNodes.length>0) // firefox
              { iden="&iden="+items[0].childNodes[0].nodeValue; }
              	           
              var items = req.responseXML.getElementsByTagName("wleventodespues");
              if (items.length>0 && items[0].childNodes.length>0)
              { 
	            if(items[0].childNodes[0].nodeValue!="")
	            {
        			wlurl='eventos_servidor.php';//20071105
        			passData='&opcion='+items[0].childNodes[0].nodeValue+buildQueryString('formpr') + iden+"&filtro="+escape(armaFiltro('formpr'));
        			CargaXMLDoc();	              
    			}
	          }
              else {
                   }
              return;
           } 	
     } catch (err) { alert(" error checa_eventodespues="+err+"="+req.responseText); }
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
           if (req.responseText.indexOf("<otrahoja>") != -1)
           {
		var x = req.responseXML;
              var items = req.responseXML.getElementsByTagName("otrahoja");
              if (items.length>0)
              { 
	              wlforma=document.getElementById('formpr');
	              wlforma.action=items[0].childNodes[0].nodeValue;
	              wlforma.submit();
              }
              else {alert('no encontro otrahoja='+req.responseText)}
              return;
           } 
	        	        
           if (req.responseText.indexOf("<error>") != -1)
           {
              var items = req.responseXML.getElementsByTagName("error");
              if (items.length>0)
              { alert(items[0].childNodes[0].nodeValue); }
              else {alert('no encontro el error='+req.responseText)}
              return;
           } 
           
           if (req.responseText.indexOf("<__eventocontinua>") != -1)
           {
              var items = req.responseXML.getElementsByTagName("__eventocontinua");
              if (items.length>0)
              { __eventocontinua=items[0].childNodes[0].nodeValue; }
              else {alert('no encontro el __eventocontinua='+req.responseText)}
              return;
           }            
           
           if (req.responseText.indexOf("<salida>") != -1)
           {
              var items = req.responseXML.getElementsByTagName("salida");
              if (items.length>0)
              { 
	            if (items[0].childNodes[0].nodeValue!='') 
	            {
			  		alert(items[0].childNodes[0].nodeValue);
		  		}
              }
              
              else {alert('no encontro salida='+req.responseText)}
              self.close();
              parent.close();
              return;
           }            
           
           if (req.responseText.indexOf("<bajaok>") != -1)
           {
              var items = req.responseXML.getElementsByTagName("bajaok");
              alert(items[0].childNodes[0].nodeValue);
              bajatabla();
              return;
           } 
           
           if (req.responseText.indexOf("<continuamovto>") != -1)
           {
	             var desw = req.responseXML.getElementsByTagName("wlmenu");
	            var wlmenu = desw[0].childNodes[0].nodeValue;	           
	            var desw = req.responseXML.getElementsByTagName("wlmovto");
	            var wlmovto = desw[0].childNodes[0].nodeValue;
	            var desw = req.responseXML.getElementsByTagName("wlllave");
	            try { var wlllave = desw[0].childNodes[0].nodeValue; } catch(err) { var wllave = ""; }
	            var desw = req.responseXML.getElementsByTagName("wlrenglon");
	            var wlrenglon = desw[0].childNodes[0].nodeValue;
	            var desw = req.responseXML.getElementsByTagName("wleventodespues");
	            try { var wleventodespues = desw[0].childNodes[0].nodeValue; } catch(err) { var wleventodespues = ""; }
	            wlurl='xmlhttp.php'; //20080131
	            passData='&opcion=mantto_tabla&idmenu='+wlmenu+'&movto='+wlmovto+buildQueryString('formpr')+"&wlllave="+escape(wlllave)+"&wlrenglon="+wlrenglon+"&wleventodespues="+wleventodespues;//20071105
        		CargaXMLDoc();			
        		return;
           }            

           if (req.responseText.indexOf("<abresubvista>") != -1)
           {
	           var desw = req.responseXML.getElementsByTagName("wlhoja");
	           var wlhoja = desw[0].childNodes[0].nodeValue;
	           var desw = req.responseXML.getElementsByTagName("wlcampos");
	           var wlcampos = desw[0].childNodes[0].nodeValue;
	           var desw = req.responseXML.getElementsByTagName("wldialogHeight");
	           var wldialogHeight = desw[0].childNodes[0].nodeValue;
	           var desw = req.responseXML.getElementsByTagName("wldialogWidth");
	           var wldialogWidth = desw[0].childNodes[0].nodeValue;	   
                   var desw = req.responseXML.getElementsByTagName("ventana");
                   try { var wlventana = desw[0].childNodes[0].nodeValue; } catch (err) { var wlventana=0; };
                   var desw = req.responseXML.getElementsByTagName("titulo");
                   try { var wltitulo = desw[0].childNodes[0].nodeValue; } catch (err) { var wltitulo='Subvista'; };
	           if (wldialogHeight!=0 || wldialogWidth!=0)
	           {       
			   		showModalDialog(wlhoja+'?'+wlcampos,wldialogWidth,wldialogHeight,wltitulo,wlventana);
		   	   }
		   	   else
		   	   {
			   		showModalDialog(wlhoja+'?'+wlcampos,100,300,'Subvista');
		   	   }
		   
               return;
           }
/*           
           //  20070616   cuando un select tiene demasiadas opciones el browser se pasma por que se chupa la memoria
           //  20070616   al detectar esto solicita las iniciales de la descripcion                         
           if (req.responseText.indexOf("<pon_selectpasolimite>") != -1)
           {
	           var items = req.responseXML.getElementsByTagName("wlselect");	           
	           var wlselect = items[0].childNodes[0].nodeValue;	           
	           var items = req.responseXML.getElementsByTagName("wlfiltropadre");	           
	           var wlfiltropadre = items[0].childNodes[0].nodeValue;	           	           
	           var items = req.responseXML.getElementsByTagName("wlfiltrohijo");	           
	           var wlfiltrohijo = items[0].childNodes[0].nodeValue;	           	           	           
	           var items = req.responseXML.getElementsByTagName("fuentewhere");	           
	           var fuentewhere = items[0].childNodes[0].nodeValue;	           	           	           	           
	           var items = req.responseXML.getElementsByTagName("fuenteevento");	           
	           var fuenteevento = items[0].childNodes[0].nodeValue;	    
	           var items = req.responseXML.getElementsByTagName("opciones");	           
	           var opciones = items[0].childNodes[0].nodeValue;	    	           
	           var wlcampo=wlselect.substring(8,wlselect.indexOf(","));
			   var des=prompt('Las opciones son bastantes '+opciones+' favor de teclear las dos primeras letras para buscar las opciones que empiezan con estas',''); //20070215	
			   if (des!='' && des!=null) 
		    	{ 
	           			wlcampo=wlcampo+' like \''+des+'%\'';
						pon_Select(wlselect,wlfiltropadre,wlfiltrohijo,fuentewhere+wlcampo,fuenteevento)			
     		    }	                 	           	           	           	           
	           return;
           }
*/           
           if (req.responseText.indexOf("<ponselect>") != -1)
           {
	           var desw = req.responseXML.getElementsByTagName("s_descripcion");	           
	           var des = desw[0].childNodes[0].nodeValue
	           var valw = req.responseXML.getElementsByTagName("s_value");	           
	           var val = valw[0].childNodes[0].nodeValue	           
	           var items = req.responseXML.getElementsByTagName("wlfiltrohijo");
			   var wlhijos=items[0].childNodes[0].nodeValue.split(',');
		       for (m=0;m<wlhijos.length;m++)
	  			{	
            		var wl=document.getElementsByName('wl_'+wlhijos[m])[0];   //firefox
            		clearSelect(wl);
	           		buildTopicList(wl,des,val);
				}	           		
               return;
           }            
                      
           if (req.responseText.indexOf("<altaok>") != -1)
           {
              var items = req.responseXML.getElementsByTagName("noconfirma");
              try { var noconfirma=items[0].childNodes[0].nodeValue } catch (err) { var noconfirma=false; };
              if (!noconfirma)
              {
                 var items = req.responseXML.getElementsByTagName("altaok");
                 if (items.length>0)
                 { alert(items[0].childNodes[0].nodeValue); }
                 else {alert('no encontro el altaok '+req.responseText+' elemento'+items.length)}
              }
              var items = req.responseXML.getElementsByTagName("wlrenglon");              
              if (items.length>0)
              { var wlrenglon=items[0].childNodes[0].nodeValue; 
              	altatabla(wlrenglon);}
              else {alert('no encontro el altaok '+req.responseText)}
              var limpiaralta=true;
              var items = req.responseXML.getElementsByTagName("limpiaralta");              
              if (items.length>0)
              { limpiaralta=items[0].childNodes[0].nodeValue; }              
              checa_eventodespues(req.responseText);   
              formReset("formpr",limpiaralta);
	      pone_focus_forma("formpr");   // limpia la pantalla despues de una alta              
              return;
           } 
           if (req.responseText.indexOf("<copiaok>") != -1)
           {
              var items = req.responseXML.getElementsByTagName("copiaok");
              if (items.length>0)
              { alert(items[0].childNodes[0].nodeValue); }
              else {alert('no encontro el copia '+req.responseText+' elemento'+items.length)}
              var items = req.responseXML.getElementsByTagName("wlrenglon");              
              if (items.length>0)
              { var wlrenglon=items[0].childNodes[0].nodeValue; 
              	altatabla(wlrenglon);}
              else {alert('no encontro el altaok '+req.responseText)}
              var limpiaralta=true;
              var items = req.responseXML.getElementsByTagName("limpiaralta");              
              if (items.length>0)
              { limpiaralta=items[0].childNodes[0].nodeValue; }              
              checa_eventodespues(req.responseText);   
	      pone_focus_forma("formpr");formReset("formpr",limpiaralta);   // limpia la pantalla despues de una alta              
              return;
           } 

           if (req.responseText.indexOf("<consulta>") != -1)
           {
		var x = req.responseXML;
	           
              var items = req.responseXML.getElementsByTagName("consulta");
              if (items.length>0)
              { alert(items[0].childNodes[0].nodeValue); }
              else {
	              alert('no encontro consulta lon '+items.length+' len text'+req.responseText.length);
	              alert(req.responseText.substring(1,1000)+' fin='+req.responseText.substring(req.responseText.length-1000));
	              return;
	           }
              var items = req.responseXML.getElementsByTagName("renglones");              
              if (items.length>0)
              { 
					tablas=document.body.getElementsByTagName('table');
					for( var i=0; i<tablas.length ; ++i ) {					
						if (tablas[i].outerHTML.indexOf("tabdinamica")!=-1)
						{ 
                                                     x=tablas[i]; 
                                                     x.parentNode.removeChild(x); 
                                                }
					}
                                        if (isIE)
					{ document.body.insertAdjacentHTML('beforeEnd', items[0].childNodes[0].nodeValue); }  //  IE
                                        else
                                        { document.body.insertAdjacentHTML('beforeEnd', items[0].textContent); }  //ff
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
	           var desw = req.responseXML.getElementsByTagName("iden");//20080116
	           var des = desw[0].childNodes[0].nodeValue;	           				   //20080116	           
                   var docx=window.parent.document;
                   var dh=docx.getElementById("altaautomatico");
                   dh.close(des);
                   dh.focus();
                   return;
       		}
           
           if (req.responseText.indexOf("<altaautomatico>") != -1)        //20070215
           {//20070215
	            var desw = req.responseXML.getElementsByTagName("des");//20070215
	            var des = desw[0].childNodes[0].nodeValue;	           				//20070215
	            var desw = req.responseXML.getElementsByTagName("dato");//20070215
	            var dato = desw[0].childNodes[0].nodeValue;	           				//20070215	            
	            var desw = req.responseXML.getElementsByTagName("secuencia");//20070215
	            var secuencia = desw[0].childNodes[0].nodeValue;	           				//20070215	            	            
        		var t=document.getElementById(dato);//20070215
        		var a = new Option(des,t.length,true,true);//20070215        		
        		a.value=secuencia;
        		t.add(a);
                        t.focus();
				return;	           //20070215
           }            //20070215
                      

           if (req.responseText.indexOf("<_nada_>") != -1)
           {
	           return;
           }           
                      
           if (req.responseText.indexOf("<cambiook>") != -1)
           {
	            var wlmenu=""
                    var wlmovto="" 
                    var wlllave="" 
                    var wlrenglon=""
        	    var items = req.responseXML.getElementsByTagName("cambiook");				
	            alert(items[0].childNodes[0].nodeValue);
	            var desw = req.responseXML.getElementsByTagName("wlmenu");
                    if (desw[0].childNodes.length>0) //firefox
	                wlmenu = desw[0].childNodes[0].nodeValue;	           
	            var desw = req.responseXML.getElementsByTagName("wlmovto");
                    if (desw[0].childNodes.length>0) //firefox
	                wlmovto = desw[0].childNodes[0].nodeValue;
	            var desw = req.responseXML.getElementsByTagName("wlllave");
                    if (desw[0].childNodes.length>0) //firefox
	                wlllave = desw[0].childNodes[0].nodeValue;	           	            	            	           	       
	            var desw = req.responseXML.getElementsByTagName("wlrenglon");
                    if (desw[0].childNodes.length>0) //firefox
	                wlrenglon = desw[0].childNodes[0].nodeValue;	           	            	            	           	       	            
				checaSiCambioAlgo(wlmenu,wlmovto,wlllave,wlrenglon,1);
              	checa_eventodespues(req.responseText);              
    	        return;
           }            
           
           if (req.responseText.indexOf("<mensajetabla>") != -1)
           {
              var items = req.responseXML.getElementsByTagName("mensajetabla");
              mensajetabla(items[0].childNodes[0].nodeValue);
              return;
           } 

			if (req.responseText.indexOf("<generatexto>") != -1)
			{
				var items = req.responseXML.getElementsByTagName("generatexto");
				if (items.length) { alert(items[0].childNodes[0].nodeValue); }
				var desw = req.responseXML.getElementsByTagName("archivo");
				var wlarchivo = desw[0].childNodes[0].nodeValue;
				open(wlarchivo,'nvo');
				return;
			}
                        if (req.responseText.indexOf("<generaexcel>") != -1)
                        {
                                var items = req.responseXML.getElementsByTagName("generaexcel");
                                if (items.length) { alert(items[0].childNodes[0].nodeValue); }
                                var desw = req.responseXML.getElementsByTagName("archivo");
                                var wlarchivo = desw[0].childNodes[0].nodeValue;
                                //alert (wlarchivo);
                                open(wlarchivo,'nvo');
                                return;
                        }
                        if (req.responseText.indexOf("<actualiza>") != -1)
                        {
                                var mensaje = req.responseXML.getElementsByTagName("mensaje");
                                var menu = req.responseXML.getElementsByTagName("menu");
                                if (menu.length)
                                { alert(mensaje[0].childNodes[0].nodeValue); open('man_menus.php?idmenu='+menu[0].childNodes[0].nodeValue,window.name); }
                                return;
                        }

                        if (req.responseText.indexOf("<abremanual>") != -1)
                        {
                                var desw = req.responseXML.getElementsByTagName("abremanual");
                                var wlarchivo = desw[0].childNodes[0].nodeValue;
                                open(wlarchivo,'nvo');
                                //alert (wlarchivo);
                                return;
                        }
           //eventos_servidor('',"No esta progamada la respuesta que envia el servidor="+req.responseText,'Enviaemail','','',0,0);
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
                //eventos_servidor('',"_error que respuesta1 err.description="+err+" req.responseText="+req.responseText,'Enviaemail','','',0,0);
		alert("_error que respuesta1 err.description="+err.message+" req.responseText="+req.responseText);
	}
}
 

// retrieve text of an XML document element, including
// elements using namespaces
function getElementTextNS(prefix, local, parentElem, index) {
    var result = "";
    if (prefix && isIE) {
        result = parentElem.getElementsByTagName(prefix + ":" + local)[index];
    } else {
        result = parentElem.getElementsByTagName(local)[index];
    }
    if (result) {
        if (result.childNodes.length > 1) {
            return result.childNodes[1].nodeValue;
        } else {
        	if (result.childNodes.length == 1) 
        	{ return result.firstChild.nodeValue;  }							
        	else						
        	{ return ""; }
        }
    } else {
        return "n/a";
    }
}
/**
* Muestra informacion de los campos select
*/
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
                        showModalDialog('man_menus.php?idmenu='+fuente_info_idmenu+'&filtro='+filtro,900,450,'Muestra informacion');
                }
        }
        catch(err) { alert('error en muestraInfo '+err.description); };
}
function actualizaRelog()
{
        try
        {       var wlencaFecha=document.getElementById('wl_encafecha');
                var wlencaHora=document.getElementById('wl_encahora');
                var fechaServidor=new Date();
                var anio=fechaServidor.getFullYear();
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
        mes[7]="Agosto";
        mes[8]="Septiembre";
        mes[9]="Octubre";
        mes[10]="Noviembre";
        mes[11]="Diciembre";
        mesActual=mes[fecha.getMonth()];
        return mesActual;
}



