       function onSuccess(data, status,x)
        {
            data = $.trim(data);
            if(x.responseText.indexOf('opciones_antn')!=-1)
            { 
              $("#notificationc").text('Conectado') ;
              $("#conectar").buttonMarkup({icon:"check"}); 
              //$("#firmar").buttonMarkup({icon:"check"}); 
              return
           }
           //$("#conectar").buttonMarkup({icon:"forbidden"}); 
           //$("#firmar").buttonMarkup({icon:"forbidden"}); 
           if (x.responseText.indexOf("<error>") != -1)
           {
              var items = x.responseXML.getElementsByTagName("error");
              if (items.length>0)
              { 
                   alert(items[0].childNodes[0].nodeValue);
                //$("#notificationc").text(items[0].childNodes[0].nodeValue);
              }
              else {alert('no encontro el error='+req.responseText)}
              $('#wl_usuario').focus();
              return false;
           }

           if (x.responseText.indexOf("<docto>") != -1)
           {
                   var desw = x.responseXML.getElementsByTagName("docto");
                   var des = desw[0].childNodes[0].nodeValue;
                   var xmlDoc=StringToXMLDom(des);
                   faelxsd=loadXMLDoc("xslt/agn/vuat_XSL_cadena.xslt");
                   var cadena=fd.damecadena(xmlDoc,faelxsd);
                   var folioAviso=xmlDoc.getElementsByTagName("AvisoDeTestamento")[0].getAttribute("folioAviso");
                   xmlDoc.getElementsByTagName("AvisoDeTestamento")[0].setAttribute("cadenaOriginal",cadena);
                   xmlDoc.getElementsByTagName("AvisoDeTestamento")[0].setAttribute("sello",fd.firmacadena(cadena));
                   xmlDoc.getElementsByTagName("AvisoDeTestamento")[0].setAttribute("certificado",fd.damecertificado());
                   var des = xmlToString(xmlDoc);
                   fir = new Blob(des.split("\n"),{ type: 'application/xml'} );
                   descargarArchivo(fir,'vuat_'+folioAviso+'.xml');
           }

           if (x.responseText.indexOf("<ponselect>") != -1)
           {
                   var desw = x.responseXML.getElementsByTagName("s_descripcion");
                   var des = desw[0].childNodes[0].nodeValue
                   var valw = x.responseXML.getElementsByTagName("s_value");
                   var val = valw[0].childNodes[0].nodeValue
                   var items = x.responseXML.getElementsByTagName("wlfiltrohijo");
                   var wlhijos=items[0].childNodes[0].nodeValue.split(',');
                   for (m=0;m<wlhijos.length;m++)
                   {
                        var wl=document.getElementsByName('wl_'+wlhijos[m])[0];   //firefox
                        clearSelect(wl);
                        buildTopicList(wl,des,val,x);
                   }
               return;
           }
           $("#notificationc").text(x.responseText); 
        }
        /* funcion para cargar los XSLT del SAT */
        function loadXMLDoc(filename) {
	        if (window.ActiveXObject) {
        	     xhttp = new ActiveXObject("Msxml2.XMLHTTP");
        	} else {
        	     xhttp = new XMLHttpRequest();
        	}
        	xhttp.open("GET", filename, false);
        	xhttp.send("");
        	return xhttp.responseXML;
        }

        function StringToXMLDom(string){
	     var xmlDoc=null;
	     if (window.DOMParser)
	     {
		parser=new DOMParser();
		xmlDoc=parser.parseFromString(string,"text/xml");
	     }
	     else // Internet Explorer
	     {
		xmlDoc=new ActiveXObject("Microsoft.XMLDOM");
		xmlDoc.async="false";
		xmlDoc.loadXML(string);
	     }
	     return xmlDoc;
        }

        function xmlToString(xmlData) { 
           var xmlString;
           if (window.ActiveXObject){
              xmlString = xmlData.xml;
           }
           else{
            xmlString = (new XMLSerializer()).serializeToString(xmlData);
           }
           return xmlString;
       }   

        function clearSelect(wl) {
           try
           {
                wl.innerHTML="";
           } catch (err) { alert ('en clearselect ' + err.description); }
        }
        function buildTopicList(wl,des,val,x) {
               var items = x.responseXML.getElementsByTagName("registro");
               for (var i = 0; i < items.length; i++) {
                 appendToSelect(wl, getElementTextNS("", val, items[i], 0),
                 document.createTextNode(getElementTextNS("", des, items[i],
                   0)),(items.length==1 ? 1 : 0));            // 20080210
              }
              appendToSelect(wl, "", document.createTextNode("Selecciona una Opci\u00f3n"),(items.length==1 ? 0 : 1)); //   20070808
              wl.click;
        }

        function descargarArchivo(contenidoEnBlob, nombreArchivo) {
              var reader = new FileReader();
              reader.onload = function (event) {
                 var save = document.createElement('a');
                 save.href = event.target.result;
                 save.target = '_blank';
                 save.download = nombreArchivo || 'archivo.xml';
                 var clicEvent = new MouseEvent('click', {
                       'view': window,
                       'bubbles': true,
                       'cancelable': true
                 });
                 save.dispatchEvent(clicEvent);
                 (window.URL || window.webkitURL).revokeObjectURL(save.href);
              };
              reader.readAsDataURL(contenidoEnBlob);
        };

        // add item to select element the less
        // elegant, but compatible way.
        //  wlselected si es 1 se pone la opcion selected y defaultselected   20070810
        //                              si es 2 se pone la opcion defaultselected             20070810
        function appendToSelect(wlselect, value, content,wlselected)  //   20070808  se incluyo el wlselected
        {
           try   // inclui el try el 20070808
           {
             var opt;
             opt = document.createElement("option");
             opt.value = value;
             opt.appendChild(content);
             if (wlselected==1) { opt.defaultSelected=true; opt.selected=true; }  // 20070808
             if (wlselected==2) { opt.defaultSelected=true; }        // 20070810
             wlselect.appendChild(opt);
           } catch(err) { }
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


        function buscanotarias() {
            ejecutaajax('xmlhttp.php','pon_select','&sql=select numero,id_notaria from avitesta.v_dame_notarias&wlfiltrohijo=NotSol&fuenteevento=b');
        }

        function firmar_at() {
            os=$('#wl_InsNot');
            ejecutaajax('firma_digital.php','gendocto','&wl_folioaviso='+os.val());
        }

        function buscainstrumentos(id) {
            ejecutaajax('xmlhttp.php','pon_select','&sql=select num_escritura || \' \' || nombre || \' \' || ap_paterno || \' \' ap_materno, folioaviso from avitesta.v_escritura where id_notaria='+id+'&wlfiltrohijo=InsNot&fuenteevento=b');
        }

        function ejecutaajax(url,opcion,campos) {
                var formData = $("#formpr").serialize();
                formData=formData+"&opcion="+opcion+campos;
                $.ajax({
                    type: "POST",
                    url: url,
                    dataType: "xml",
                    cache: false,
                    data: formData,
                    success: onSuccess,
                    error: onError
                });
        }

        function onError(data, status)
        {
            // handle an error
        }

        $(document).ready(function() {
            $("#validarup").click(function(){
                ejecutaajax('seguridad_class.php','validausuario','');
                return false;
            });
            $('#wl_usuario').bind("keydown", function(e) {
              if (e.which == 13)
              {
                  $('#wl_password').focus();
              }
            });
            $('#wl_password').bind("keydown", function(e) {
              if (e.which == 13)
              {
                  $('#validarup').focus();
              }
            });


        });


function fiel()
{

  this.wlrfc = ""; // rfc
  this.llaves ;
  this.rk ;
  this.cer ;
  this.cer1 ;
  this.creainputfile = function (ext) {
        var doc = document;
        _desc = doc.createElement( "input" );
        _desc.className = "foco";
        _desc.setAttribute("type", "file");
        _desc.setAttribute("title", "hola");
        _desc.setAttribute("name", "ficheroin");
        _desc.setAttribute("id", "ficheroin");
        _desc.setAttribute("value", "jala");
        _desc.setAttribute("accept", ext);
        _desc.setAttribute("size", "30");
        return _desc;
  }

  this.leefael = function (evt)
  {
     var reader = new FileReader();
     reader.onload = (function(theFile) {
          return function(e) {
          if (theFile.name.toLowerCase().indexOf(".xml")!=-1) {
             localStorage.setItem("xml",e.target.result);
          }  else {
             alert('La factura electronica debe de contar con extension xml');
             return false;
          }};
         })(evt.target.files[0]);

      reader.onloadend = function () {
          cargofael();
      }

     reader.readAsDataURL(evt.target.files[0]);
  }

  this.leeatel = function (evt)
  {
     var reader = new FileReader();
     reader.onload = (function(theFile) {
          return function(e) {
          if (theFile.name.toLowerCase().indexOf(".xml")!=-1) {
             localStorage.setItem("xml",e.target.result);
          }  else {
             alert('El Aviso de Testamento elecronico debe de contar con extension xml');
             return false;
          }};
         })(evt.target.files[0]);

      reader.onloadend = function () {
          cargoatel();
      }

     //reader.readAsDataURL(evt.target.files[0]);
     reader.readAsText(evt.target.files[0],"UTF-8");
  }


  this.leefael_xslt = function (evt)
  {
     var reader = new FileReader();
     reader.onload = (function(theFile) {
          return function(e) {
          if (theFile.name.toLowerCase().indexOf(".xslt")!=-1) {
             localStorage.setItem("xslt",e.target.result);
          }  else {
             alert('El Xslt de la factura electronica debe de contar con extension xslt');
             return false;
          }};
         })('cadenaoriginal_3_3.xslt');
     reader.readAsText('cadenaoriginal_3_3.xslt');
  }


  this.leefiel = function (evt)
  {
     var reader = new FileReader();
     reader.onload = (function(theFile) {
          return function(e) {
          if (theFile.name.indexOf(".cer")!=-1) {
             localStorage.setItem("cer",e.target.result);
          } else {
          if (theFile.name.indexOf(".key")!=-1) {
             localStorage.setItem("key",e.target.result);
          } else {
             alert('La firma digital debe de contar con extension cer y key');
             return false;
          }};
        };
      })(evt.target.files[0]);

      reader.onloadend = function () {
          cargofiel();       
      }
      reader.readAsDataURL(evt.target.files[0]);
  }

  this.firmacadena = function(cadena)
  {
          var md = forge.md.sha256.create();
          md.update(cadena);
          try {var firmado=btoa(this.rk.sign(md));} catch(err) { alert(err); return false; }
        return firmado;
  }

  this.damecertificado = function()
  {
        return this.cer1;
  }

  this.validaprivada = function(pwd,cadena='prueba')
  {
       if(pwd=="")
       { alert('El password es obligatorio'); return false; }
       if(localStorage.getItem('key')==null)
       { alert('La llave privada no esta definida'); return false; }
       if(localStorage.getItem('cer')==null)
       { alert('El certificado no esta definido'); return false; }
       var pki = forge.pki;
       pk=localStorage.getItem('key').substring(localStorage.getItem('key').indexOf('base64,')+7);
       pk="-----BEGIN ENCRYPTED PRIVATE KEY-----\r\n"+pk.chunkString(64)+"-----END ENCRYPTED PRIVATE KEY-----";
       try {
            this.rk=pki.decryptRsaPrivateKey(pk,pwd);
           } catch (err) {
             return false;} ;
       if (!this.rk) return false ;
       return true;
  }

  this.validafiellocal = function(pwd,cadena='prueba')
  {
       if (this.validaprivada(pwd,cadena)) {
          var md = forge.md.sha256.create();
          md.update(cadena);
          try {var firmado=btoa(this.rk.sign(md));} catch(err) { alert(err); return false; }
          console.log('Firmado='+firmado);
          var certificado=this.damecertificadofiel();
          if (typeof(certificado)=='object') {
             var md = forge.md.sha256.create();
             md.update(cadena);
             var esValido = certificado.publicKey.verify(md.digest().bytes(),atob(firmado));
             if (esValido) {
                return { 'ok'  : true, "msg" : certificado.subject.attributes[0].value };
             } return { 'ok'  : false, "msg" : "La Firma electronica es incorrecta" };
          } else { return { 'ok'  : false, "msg" : "El certificado es erroneo" }; }
      } else { return  { 'ok'  : false, "msg" : "El password de la llave privada es erronea "}; }
  }

  /* carga la factura electoronica */
  this.cargafael = function ()
  {
     var x = this.creainputfile("*.xml");
     x.addEventListener('change',this.leefael,false);
     x.click();
     return true;
  }

  this.cargaatel = function ()
  {
     var x = this.creainputfile("*.xml");
     x.addEventListener('change',this.leeatel,false);
     x.click();
     return true;
  }


  this.firmafael = function ()
  {
       fael=atob(localStorage.getItem('xml').substring(localStorage.getItem('xml').indexOf('base64,')+7));
       fael=fael.replace(/[\s\S]+<\?xml/, '<?xml');
       faelxml=StringToXMLDom(fael);
       faelxsd=loadXMLDoc("xslt/cadenaoriginal_3_3.xslt");
       var cadena=this.damecadena(faelxml,faelxsd); 
       this.validaprivada('888aDantryR',cadena);
       var md = forge.md.sha256.create();
       md.update(cadena);
       try {var firmado=btoa(this.rk.sign(md));} catch(err) { alert(err); return false; }
       alert('Firmado='+firmado);
  }

  this.damefaelxml = function () {
       fael=atob(localStorage.getItem('xml').substring(localStorage.getItem('xml').indexOf('base64,')+7));
       fael=fael.replace(/[\s\S]+<\?xml/, '<?xml');
       faelxml=StringToXMLDom(fael);
       return faelxml;
  }

  this.dameatelxml = function () {
       fael=localStorage.getItem('xml');
       fael=fael.replace(/[\s\S]+<\?xml/, '<?xml');
       faelxml=StringToXMLDom(fael);
       return faelxml;
  }

  this.validafael = function ()
  {
    try {
       faelxsd=loadXMLDoc("xslt/cadenaoriginal_3_3.xslt");
       faelxml=this.damefaelxml();
       var cadena=this.damecadena(faelxml,faelxsd);
       var certificado=this.damecertificadofael();
       if (typeof(certificado)=='object') {
          var md = forge.md.sha256.create();
          md.update(cadena); 
          var sello=this.damesello(faelxml);
          var esValido = certificado.publicKey.verify(md.digest().bytes(),atob(sello));
          if (esValido) {
               return { 'ok' : true, 'msg' : "factura Electronica Valida" }; 
           } return { 'ok' : false, 'msg' : "factura Electronica No Valida" };
       } else { return { 'ok' : false, 'msg' : "No puede leer el certificado" };  }
    } catch (err) { return { 'ok' : false, 'msg' : err.message };}
  }

  this.validaatel = function ()
  {
    try {
       faelxsd=loadXMLDoc("xslt/agn/vuat_XSL_cadena.xslt");
       faelxml=this.dameatelxml();
       var cadena=this.damecadena(faelxml,faelxsd);
       var certificado=this.damecertificadoatel();
       if (typeof(certificado)=='object') {
          var md = forge.md.sha256.create();
          md.update(cadena);
          var sello=this.dameselloat(faelxml);
          var esValido = certificado.publicKey.verify(md.digest().bytes(),atob(sello));
          if (esValido) {
               return { 'ok' : true, 'msg' : "Aviso de Testamento Electronico valido" };
           } return { 'ok' : false, 'msg' : "Aviso de Testamento Electronico valido" };
       } else { return { 'ok' : false, 'msg' : "No puede leer el certificado" };  }
    } catch (err) { return { 'ok' : false, 'msg' : err.message };}
  }


  this.damesello = function (xml)
  {
       var sello=xml.getElementsByTagName("cfdi:Comprobante")[0].getAttribute("Sello");
       return sello;
  }

  this.dameselloat = function (xml)
  {
       var sello=xml.getElementsByTagName("AvisoDeTestamento")[0].getAttribute("sello");
       return sello;
  }


  this.damecertificadofael = function ()
  {
       fael=atob(localStorage.getItem('xml').substring(localStorage.getItem('xml').indexOf('base64,')+7));
       fael=fael.replace(/[\s\S]+<\?xml/, '<?xml');
       xml=StringToXMLDom(fael);
       var certi=xml.getElementsByTagName("cfdi:Comprobante")[0].getAttribute("Certificado");
       var cert="-----BEGIN CERTIFICATE-----"+certi.chunkString(64)+"-----END CERTIFICATE-----";
       var pki = forge.pki;
       try {rce=pki.certificateFromPem(cert);} catch (err) { alert ('Error al leer el certificado de la factura electronica'+err); return false;}
       return rce;
  }

  this.damecertificadoatel = function ()
  {
       fael=localStorage.getItem('xml');
       fael=fael.replace(/[\s\S]+<\?xml/, '<?xml');
       xml=StringToXMLDom(fael);
       var certi=xml.getElementsByTagName("AvisoDeTestamento")[0].getAttribute("certificado");
       var cert="-----BEGIN CERTIFICATE-----"+certi.chunkString(64)+"-----END CERTIFICATE-----";
       var pki = forge.pki;
       try {rce=pki.certificateFromPem(cert);} catch (err) { alert ('Error al leer el certificado del Aviso de Testamento electronico'+err); return false;}
       return rce;
  }


  this.damecertificadofiel = function ()
  {
       this.cer=localStorage.getItem('cer').substring(localStorage.getItem('cer').indexOf('base64,')+7);
       this.cer1=this.cer;
       this.cer="-----BEGIN CERTIFICATE-----"+this.cer.chunkString(64)+"-----END CERTIFICATE-----";
       var pki = forge.pki;
       try {rce=pki.certificateFromPem(this.cer);} catch (err) { alert ('Error al leer el certificado de la firma electronica'+err); return false;}
       return rce;
  }



  this.damecadena = function (xml,xsd)
  {
     if (window.ActiveXObject || "ActiveXObject" in window) {
        this.ie();
     } else {
       if (document.implementation && document.implementation.createDocument) {
         xsltProcessor = new XSLTProcessor();
         xsltProcessor.importStylesheet(xsd);
         resultDocument = xsltProcessor.transformToDocument(xml, document);
         var serializer = new XMLSerializer();
         //var transformed = serializer.serializeToString(resultDocument.documentElement);
         //alert(transformed);
         return resultDocument.documentElement.innerText.trim();
      }
    }
  }

  this.cargafiellocal = function ()
  {
     var x = this.creainputfile(".cer");
     x.addEventListener('change',this.leefiel,false);
     x.click();
     var y = this.creainputfile(".key");
     y.addEventListener('change',this.leefiel,false);
     y.click();
     return false;
  }
}
