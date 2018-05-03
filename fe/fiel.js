       function onSuccess(data, status,x)
        {
            data = $.trim(data);
            if(x.responseText.indexOf('opciones_antn')!=-1)
            { 
              $("#notificationc").text('Conectado') ;
              $("#conectar").buttonMarkup({icon:"check"}); 
              $("#firmar").buttonMarkup({icon:"check"}); 
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
                   var co=xmlDoc.getElementsByTagName("AvisoDeTestamento")[0].getAttribute("cadenaOriginal")
                   xmlDoc.getElementsByTagName("AvisoDeTestamento")[0].setAttribute("sello",fd.firmacadena(co));
                   xmlDoc.getElementsByTagName("AvisoDeTestamento")[0].setAttribute("certificado",fd.damecertificado());
                   var des = xmlToString(xmlDoc);
                   fir = new Blob(des.split("\n"),{ type: 'application/xml'} );
                   descargarArchivo(fir,'vuat.xml');
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
            ejecutaajax('firma_digital.php','gendocto','&wl_id_escritura='+os.val());
        }

        function buscainstrumentos(id) {
            ejecutaajax('xmlhttp.php','pon_select','&sql=select num_escritura || \' \' || nombre || \' \' || ap_paterno || \' \' ap_materno, id_escritura from avitesta.v_escritura where id_notaria='+id+'&wlfiltrohijo=InsNot&fuenteevento=b');
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
     reader.readAsDataURL(evt.target.files[0]);
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


  this.leellave = function (evt)
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
      reader.readAsDataURL(evt.target.files[0]);
  }

  this.firmacadena = function(cadena)
  {
        this.rk.updateString(cadena);
        return this.rk.sign();
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
       var ku = window.KEYUTIL;
       var ce = new window.X509();
       pk=localStorage.getItem('key').substring(localStorage.getItem('key').indexOf('base64,')+7);
       pk="-----BEGIN ENCRYPTED PRIVATE KEY-----\r\n"+pk.chunkString(64)+"-----END ENCRYPTED PRIVATE KEY-----";
       try {
            this.rk = new KJUR.crypto.Signature({"alg" : "SHA256withRSA"});
            this.rk.init(pk,pwd);
           } catch (err) {
             if(err.indexOf("code:001")) { alert ('El password no corresponde con la llave privada'); return false;} else {
             return err; return false;}} ;
       return true;
  }

  this.validafiellocal = function(pwd,cadena='prueba')
  {
       if (this.validaprivada(pwd,cadena)) {
          this.rk.updateString(cadena);
          try {var firmado=this.rk.sign();} catch(err) { alert(err); return false; }
          console.log('Firmado='+firmado);
          this.cer=localStorage.getItem('cer').substring(localStorage.getItem('cer').indexOf('base64,')+7);
          this.cer1=this.cer;
          this.cer="-----BEGIN CERTIFICATE-----"+this.cer.chunkString(64)+"-----END CERTIFICATE-----";
          try {rce=X509.getPublicKeyFromCertPEM(this.cer);} catch (err) { alert ('Error al leer el certificado '+err); return false;}
          var sig2 = new KJUR.crypto.Signature({"alg": "SHA256withRSA"});
          sig2.init(rce);
          sig2.updateString(cadena);
          var esValido = sig2.verify(firmado);

          if (esValido) { 
             var ce = new X509();
             ce.readCertPEM(this.cer);
             return ce.getSubjectString().split("/")[1].substring(3);
          }

          alert ('Llave privada y publica son invalidas'); 
          return false;
      } else { return false; }
  }

  /* carga la factura electoronica */
  this.cargafael = function ()
  {
     var x = this.creainputfile("*.xml");
     x.addEventListener('change',this.leefael,false);
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
       this.rk.updateString(cadena);
       try {var firmado=this.rk.signHex(cadena);} catch(err) { alert(err); return false; }
       console.log('Firmado='+firmado);
  }

  this.validafael = function ()
  {
       fael=atob(localStorage.getItem('xml').substring(localStorage.getItem('xml').indexOf('base64,')+7));
       fael=fael.replace(/[\s\S]+<\?xml/, '<?xml');
       faelxml=StringToXMLDom(fael);
       faelxsd=loadXMLDoc("xslt/cadenaoriginal_3_3.xslt");
       var cadena=this.damecadena(faelxml,faelxsd);
       var certificado=this.damecertificado(faelxml);
       if (typeof(certificado)=='object') {
          var sig2 = new KJUR.crypto.Signature({"alg": "SHA256withRSA"});
          sig2.init(certificado);
          sig2.updateString(cadena);
          var sello=this.damesello(faelxml);
          var esValido = sig2.verify(sello);
          if (esValido) {
              var ce = new X509();
              ce.readCertPEM(this.certificado);
              return ce.getSubjectString().split("/")[1].substring(3);
          } return false;
       } else { return false; }
  }

  this.damesello = function (xml)
  {
       var sello=xml.getElementsByTagName("cfdi:Comprobante")[0].getAttribute("Sello");
       return sello;
  }

  this.damecertificado = function (xml)
  {
       var certi=xml.getElementsByTagName("cfdi:Comprobante")[0].getAttribute("Certificado");
       var cert="-----BEGIN CERTIFICATE-----"+certi.chunkString(64)+"-----END CERTIFICATE-----";
       try {rce=X509.getPublicKeyFromCertPEM(cert);} catch (err) { alert ('Error al leer el certificado de la factura electronica'+err); return false;}
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
     x.addEventListener('change',this.leellave,false);
     x.click();
     var y = this.creainputfile(".key");
     y.addEventListener('change',this.leellave,false);
     y.click();
     return false;
  }
}
