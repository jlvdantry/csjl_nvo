// global flag;
var isIE = false;
var req;
var itemse ; // variable para para los items de la persona que envia en  la hoja de alta de tramites
var itemsr ; // variable para para los items de la persona que recibe en  la hoja de alta de tramites
var itemst ; // variable para para los items de la persona que recibe en  la hoja de alta de tramites
// retrieve XML document (reusable generic function);
// parameter is URL string (relative or complete) to
// an .xml file whose Content-Type is a valid XML
// type, such as text/xml; XML source must be from
// same domain as HTML file
var respuesta;
var wlselect; // variable donde va a poner los datos de la busqueda por persona
var glrenglon; // variable que contiene el renglon de la tabla a borrar
var gltabla; // variable que contiene el nombre de la tabla donde va a borrar el renglon
function loadXMLDoc(url,wlcampo) {
    // branch for native XMLHttpRequest object
//    alert('entro en loadXMLDoc');
    if (window.XMLHttpRequest) {
        req = new XMLHttpRequest();
        req.onreadystatechange = processReqChange;
        req.open("GET", url, true);
        req.send(null);
    // branch for IE/Windows ActiveX version
    } else if (window.ActiveXObject) {
        isIE = true;
        req = new ActiveXObject("Microsoft.XMLHTTP");
        reqr = new ActiveXObject("Microsoft.XMLHTTP");
        if (req) {
            req.onreadystatechange = processReqChange;
            req.open("GET", url, true);
            req.send();
        }
    }
}

function baja_persona(wlid_persona) {
    var url='baja_persona.php?wlid_persona='+wlid_persona;
    if (window.XMLHttpRequest) {
        req = new XMLHttpRequest();
        req.onreadystatechange = cambioestadobaja;
        req.open("GET", url, true);
        req.send(null);
    } else if (window.ActiveXObject) {
        isIE = true;
        req = new ActiveXObject("Microsoft.XMLHTTP");
        if (req) {
            req.onreadystatechange = cambioestadobaja;
            req.open("GET", url, true);
            req.send();
        }
    }
}

function baja_registro(wlurl) {
//	alert('entro en baja registro');
    if (window.XMLHttpRequest) {
        req = new XMLHttpRequest();
        req.onreadystatechange = cambioestadobaja;
        req.open("GET", wlurl, true);
        req.send(null);
    } else if (window.ActiveXObject) {
        isIE = true;
        req = new ActiveXObject("Microsoft.XMLHTTP");
        if (req) {
            req.onreadystatechange = cambioestadobaja;
            req.open("GET", wlurl, true);
            req.send();
        }
    }
}


function cambioestadobaja() {
    if (req.readyState == 4) {
        if (req.status == 200) {
//            alert('entro sibaja');
            sibaja();
         } else {
            alert('error al querer dar de baja un registro '+req.statusText);
         }
    }
}

function sibaja()
{
    var items = req.responseXMl.getElementsByTagName("respuesta");
//    alert('obtuvo elemento');
    if (items.item(0).text!="")
    {
        alert(items.item(0).text);
    }
    else
    {
            alert('Registro dado de baja');
            document.getElementById(gltabla).deleteRow(glrenglon);
    }
}

function siexiste()
{
    var items = req.responseXMl.getElementsByTagName("respuesta");
    if (items.item(0).text=="Ya existe una persona")
    {
        alert(items.item(0).text);
    }
    else
    {
        var re=/ /g;
        document.forms[0].wlopcion.value = "altapersona" ;
//        alert('a ver que'+document.forms[0].wlnombre.value.replace(re,'_'));
        var wlurl="altapersona.php?wlnombre="+document.forms[0].wlnombre.value.replace(re,'_')+"&wlopcion=altapersona&wlapepat="+document.forms[0].wlapepat.value.replace(re,'_')+"&wlapemat="+document.forms[0].wlapemat.value.replace(re,'_')+"&wltitulo="+document.forms[0].wltitulo.value.replace(re,'_')+"&wlidpuesto="+document.forms[0].wlidpuesto.value+"&wlidorganizacion="+document.forms[0].wlidorganizacion.value+"&wlcorreoe="+document.forms[0].wlcorreoe.value+"&wltelefono="+document.forms[0].wltelefono.value;
//        alert('otro url'+wlurl);
        window.showModalDialog(wlurl,window);
        self.close();
    }
}



function existe_persona(wlnombre,wlapepat,wlapemat) {
    var re=/ /g
    var wlnombre1='';
    var url="existe_persona.php?wlnombre="+wlnombre.replace(re,'_')+"&wlapepat="+wlapepat.replace(re,'_')+"&wlapemat="+wlapemat.replace(re,'_');
    if (window.XMLHttpRequest) {
        req = new XMLHttpRequest();
        req.onreadystatechange = cambioestadobp;
        req.open("GET", url, true);
        req.send(null);
    // branch for IE/Windows ActiveX version
    } else if (window.ActiveXObject) {
        isIE = true;
        req = new ActiveXObject("Microsoft.XMLHTTP");
        if (req) {
            req.onreadystatechange = cambioestadobp;
            req.open("GET", url, true);
            req.send();
        }
    }
}


// funcion para liberar un folio
function liberar(wlfolioconsecutivo) {
    // branch for native XMLHttpRequest object
    var url='liberar.php?wlfolioconsecutivo='+wlfolioconsecutivo;
    if (window.XMLHttpRequest) {
        req = new XMLHttpRequest();
        req.onreadystatechange = cambioestado;
        req.open("GET", url, true);
        req.send(null);
    // branch for IE/Windows ActiveX version
    } else if (window.ActiveXObject) {
        isIE = true;
        req = new ActiveXObject("Microsoft.XMLHTTP");
        if (req) {
            req.onreadystatechange = cambioestado;
            req.open("GET", url, true);
            req.send();
        }
    }
}

// funcion para liberar un folio
function busca_persona(wlnombre) {
    // branch for native XMLHttpRequest object
//    alert('entro en busca persona');
    var url='buscar_persona.php?wlnombre='+wlnombre;
    if (window.XMLHttpRequest) {
        req = new XMLHttpRequest();
        req.onreadystatechange = cambioestado;
        req.open("GET", url, true);
        req.send(null);
    // branch for IE/Windows ActiveX version
    } else if (window.ActiveXObject) {
        isIE = true;
        req = new ActiveXObject("Microsoft.XMLHTTP");
        if (req) {
            req.onreadystatechange = cambioestado;
            req.open("GET", url, true);
            req.send();
        }
    }
}

// funcion para liberar un folio
function enviahttp(url) {
    // branch for native XMLHttpRequest object
//    alert('entro en envia');
//    alert('url'+url);
    if (window.XMLHttpRequest) {
        req = new XMLHttpRequest();
        req.onreadystatechange = revisaestado;
        req.open("GET", url, true);
        req.send(null);
    // branch for IE/Windows ActiveX version
    } else if (window.ActiveXObject) {
        isIE = true;
        req = new ActiveXObject("Microsoft.XMLHTTP");
        if (req) {
            req.onreadystatechange = revisaestado;
            req.open("GET", url, true);
            req.send();
        }
    }
}

function revisaestado() {
    if (req.readyState == 4) {
        if (req.status == 200) {
//	        alert('entro en que regreso');
            queregreso();
         } else {
            alert('error en reviso estado'+req.statusText);
         }
    }
}
 
function cambioestado() {
    if (req.readyState == 4) {
        if (req.status == 200) {
            silibero();
         } else {
            alert('error al querer liberar el tramite'+req.statusText);
         }
    }
}

function cambioestadobp() {
    if (req.readyState == 4) {
        if (req.status == 200) {
            siexiste();
         } else {
            alert('error al querer validar si ya existe una persona '+req.statusText);
         }
    }
}

// handle onreadystatechange event of req object
function processReqChange() {
    // only if req shows "loaded"
//    alert('entro el processReqChange');
    if (req.readyState == 4) {
        // only if "OK"
        if (req.status == 200) {
//            alert('antes de clear');
            clearTopicList();
            buildTopicList();
         } else {
            alert("There was a problem retrieving the XML data1:\n"+req.statusText);
         }
    }
//    alert('paso el processReqChange');
}

function loadDoc() {
      if (document.forms[0].wlidpuesto.selectedIndex == -1 || document.forms[0].wlidpuesto.selectedIndex == 0 ) {
        window.alert('No ha seleccionado el puesto o area');
        document.forms[0].wlidpuesto.focus();
        return;
      }
      clearTopicList();
      buscandopersonasList();
      loadXMLDoc("carga_perxarea.php?wlidpuesto="+document.forms[0].wlidpuesto.value);
}

function loadDoc1(wlnombre,wlapepat,wlapemat,wlbidpuesto) {
//      alert('excelente'+wlcampo.value);
//      wlselect=wlcampo
      clearTopicList();
      buscandopersonasList();
//      alert('antes de load');
      loadXMLDoc("buscar_persona.php?wlnombre="+wlnombre+"&wlapepat="+wlapepat+"&wlapemat="+wlapemat+"&wlbidpuesto="+wlbidpuesto);
}


 

// retrieve text of an XML document element, including
// elements using namespaces
function getElementTextNS(prefix, local, parentElem, index) {
//    alert('entro');
    var result = "";
    if (prefix && isIE) {
        // IE/Windows way of handling namespaces
        result = parentElem.getElementsByTagName(prefix + ":" + local)[index];
    } else {
        // the namespace versions of this method 
        // (getElementsByTagNameNS()) operate
        // differently in Safari and Mozilla, but both
        // return value with just local name, provided 
        // there aren't conflicts with non-namespace element
        // names
//        alert(' antes de result entro');
        result = parentElem.getElementsByTagName(local)[index];
    }
    if (result) {
        // get text, accounting for possible
        // whitespace (carriage return) text nodes 
        if (result.childNodes.length > 1) {
            return result.childNodes[1].nodeValue;
        } else {
            return result.firstChild.nodeValue;                      
        }
    } else {
        return "n/a";
    }
}

// empty Topics select list content
function clearTopicList() {
//    alert('entro en clear');
//    var select = document.getElementById("wlidpersona_turnar");
    while (wlselect.length > 0) {
        wlselect.remove(0);
    }
}

 

// add item to select element the less
// elegant, but compatible way.
function appendToSelect(select, value, content) {
    var opt;
    opt = document.createElement("option");
    opt.value = value;
    opt.appendChild(content);
    select.appendChild(opt);
}

 

// fill Topics select list with items from
// the current XML document
function buildTopicList() {
//    var select = document.getElementById("wlidpersona_turnar");
    var items = req.responseXMl.getElementsByTagName("persona");
//    alert('va'+req.responseText);
//    alert('va'+items.length);
    for (var i = 0; i < items.length; i++) {
        appendToSelect(wlselect, getElementTextNS("", "id_persona", items[i], 0),
            document.createTextNode(getElementTextNS("", "nombre", items[i], 0)));
    }
   if(wlselect.name=='wlidpersona_envia')
   {
//     alert('sale entro reqe');
     itemse=req.responseXMl.getElementsByTagName("persona");
//     alert('longitud'+itemse.length+'typeof'+typeof(itemse));
   }
   if(wlselect.name=='wlidpersona_recibe')
   {
     itemsr=req.responseXMl.getElementsByTagName("persona");
   }
   if(wlselect.name=='wlidpersona_turnada')
   {
     itemst=req.responseXMl.getElementsByTagName("persona");
   }
   wlselect.focus();
}

function buscandopersonasList() {
//    var select = document.getElementById("wlidpersona_turnar");
//        alert('entro en buscandopersonasList');
        appendToSelect(wlselect, 0, document.createTextNode('BUSCANDO PERSONAS'));
}

function silibero() 
{
    var items = req.responseXMl.getElementsByTagName("respuesta");
    alert(items.item(0).text);
    if (items.item(0).text=="tramite liberado")
    {
           setCookie("borrarenglon",0);
           self.close();
    }
}

function siexiste() 
{
//    alert('entro en si existe');
    var items = req.responseXMl.getElementsByTagName("respuesta");
    if (items.item(0).text=="Ya existe una persona")
    {
        alert(items.item(0).text);
    }
    else
    {
        var re=/ /g;
        document.forms[0].wlopcion.value = "altapersona" ;
//        alert('a ver que'+document.forms[0].wlnombre.value.replace(re,'_'));
        var wlurl="altapersona.php?wlnombre="+document.forms[0].wlnombre.value.replace(re,'_')+"&wlopcion=altapersona&wlapepat="+document.forms[0].wlapepat.value.replace(re,'_')+"&wlapemat="+document.forms[0].wlapemat.value.replace(re,'_')+"&wltitulo="+document.forms[0].wltitulo.value.replace(re,'_')+"&wlidpuesto="+document.forms[0].wlidpuesto.value+"&wlidorganizacion="+document.forms[0].wlidorganizacion.value+"&wlcorreoe="+document.forms[0].wlcorreoe.value+"&wltelefono="+document.forms[0].wltelefono.value;
//        alert('otro url'+wlurl);
        window.showModalDialog(wlurl,window);
        self.close();
    }
}

function queregreso() 
{

    var items = req.responseXMl.getElementsByTagName("respuesta");
    if (items.item(0).text!="ok")
    {
        alert(items.item(0).text);
        return false;
    }
    else
    {
       if (validacampos()) 
       {
//	       alert('donde quedo la bolita');
          if (getCookie('idpersona')!=null) 
          {
            document.forms[0].wlidpersona.value=getCookie('idpersona');
            document.forms[0].wlopcion.value='altausuario';
            document.forms[0].submit();
          } ;
          else
          {
	           alert('falta teclear los Datos Personales');
    	       return false;
          }
          
       }
     }

}

// display details retrieved from XML document
function showDetail(evt) {
    evt = (evt) ? evt : ((window.event) ? window.event : null);
    var item, content, div;
    if (evt) {
        var select = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
        if (select && select.options.length > 1) {
            // copy <content:encoded> element text for
            // the selected item
            item = req.responseXML.getElementsByTagName("item")[select.value];
            content = getElementTextNS("content", "encoded", item, 0);
            div = document.getElementById("details");
            div.innerHTML = "";
            // blast new HTML content into "details" <div>
            div.innerHTML = content;
        }
    }
}


