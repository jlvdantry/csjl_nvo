  document.getElementByID = function(x)
   {
     var y;
     try {
        y=document.getElementById(x);
        if (y==null)
        y=document.getElementsByName(x)[0];
         }
     catch (err) { alert('error en document.getElementByID'); }
     return y;
  }


function eve_particulares() 
{

  this.wlrfc = ""; // rfc
  this.llaves ;
  this.creainputfile = function () {
        var doc = document;
        _desc = doc.createElement( "input" );
        _desc.className = "foco";
        _desc.setAttribute("type", "file");
        _desc.setAttribute("name", "ficheroin");
        _desc.setAttribute("id", "ficheroin");
        _desc.setAttribute("value", "jala");
        _desc.setAttribute("size", "30");
        return _desc;
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

  this.validafiellocal = function(event)
  {
       if(event.target.value=="")
       { alert('El password es obligatorio'); return false; }
       var ku = window.KEYUTIL;
       var ce = window.X509;
       pk=localStorage.getItem('key').substring(13);
       pk="-----BEGIN ENCRYPTED PRIVATE KEY-----"+pk.chunkString(64)+"-----END ENCRYPTED PRIVATE KEY-----";
       try {rk=ku.getKeyFromEncryptedPKCS8PEM(pk,event.target.value);} catch (err) { 
             if(err.indexOf("code:001")) { alert('El password no corresponde con la llave privada') } else {
             alert(err); return false; }} ;
       try {firmado=rk.signString('prueba','sha512');} catch(err) { alert(err); return false; }
       alert('LLave privada es valida');
       cer=localStorage.getItem('cer').substring(39);
       cer="-----BEGIN CERTIFICATE-----"+cer.chunkString(64)+"-----END CERTIFICATE-----";
       try {rce=ce.getPublicKeyFromCertPEM(cer);} catch (err) { alert('Error al leer el certificado '+err); }
       try {irce=ce.getPublicKeyInfoPropOfCertPEM(cer);} catch (err) { alert('Error al leer el certificado '+err); }
       //try {urce=ce.getExtKeyUsageString(cer);} catch (err) { alert('Error al leer el certificado '+err); }
       esValido=rce.verifyString('prueba',firmado);
       if (esValido) { alert('Llave privada y publica son validas'); }
       else { alert('Llave privada y publica son invalidas'); }
       return false;
  }

  this.cargafiellocal = function ()
  {
     var x = this.creainputfile();
     x.addEventListener('change',this.leellave,false);
     x.click();
     var y = this.creainputfile();
     y.addEventListener('change',this.leellave,false);
     y.click();
     return false;
  }

  this.confirmarpresencia = function ()
  {
       //alert('entro a confirmar presencia');
       document.getElementByID('iBusca').click();
       //alert('va'+document.getElementByID('wl_idcita').value);
       if (document.getElementByID('wl_idcita').value!="")
       {   document.getElementByID('wl_idestatus').value=5; document.getElementByID('iCambio').click(); }
       //eventos_servidor("",0,"confirmarpresencia","","",document.body.clientWidth,document.body.clientHeight);
  }

  this.muevefinal   = function ()
  {
      try { document.getElementByID('wl_folio_final').value=document.getElementByID('wl_folio_inicial').value; } catch (e) { };
      return true;
  }

  this.validafinal   = function ()
  {
      try { if (parseInt(document.getElementByID('wl_folio_final').value)<parseInt(document.getElementByID('wl_folio_inicial').value)) 
           { alert('El folio final debe ser menor o igual que el inicial'); return false; }
      } catch (e) { };
      return true;
  }

  this.protegecita   = function ()
  {
       document.getElementByID('tabbotones').style.display='block';
       document.getElementByID('iAlta').style.display='none';
       document.getElementByID('iBusca').style.display='block';
       document.getElementByID('DATOS NECESARIOS PARA REALIZAR UNA CITA:').style.display='none';
       document.getElementByID('2006').style.display='none';
       document.getElementByID('tabdinamica').style.display='none';
       try { ReDrawCaptcha(document.getElementByID('wl_codigodeseguridad_bot')); } catch (err) { };
       try { DrawCaptcha(document.getElementByID('wl_codigodeseguridad')); } catch (err) { };
  }

  this.escondecontrol = function ()
  {
       document.getElementByID('CAMPOS DE CONTROL DEL SISTEMA').style.display='none';
       document.getElementByID('2002').style.display='none';
  }

  this.protegetramite   = function ()
  {
       document.getElementByID('tabbotones').style.display='block';
       document.getElementByID('iAlta').style.display='block';
       document.getElementByID('iBusca').style.display='none';
       document.getElementByID('DATOS NECESARIOS PARA REALIZAR UNA CITA:').style.display='none';
       document.getElementByID('DATOS DEL INTERESADO (PERSONA FÍSICA)').style.display='none';
       document.getElementByID('DATOS DEL INTERESADO (PERSONA MORAL)').style.display='none';
       document.getElementByID('Acta constitutiva o póliza').style.display='none';
       document.getElementByID('DATOS DEL REPRESENTANTE LEGAL').style.display='none';
       document.getElementByID('INSTRUMENTO SOLICITADO').style.display='none';
       document.getElementByID('2006').style.display='none';
       document.getElementByID('2007').style.display='none';
       document.getElementByID('2008').style.display='none';
       document.getElementByID('2011').style.display='none';
       document.getElementByID('2012').style.display='none';
       document.getElementByID('2013').style.display='none';
       document.getElementByID('tabdinamica').style.display='none';
       try { ReDrawCaptcha(document.getElementByID('wl_codigodeseguridad_bot')); } catch (err) { };
       try { DrawCaptcha(document.getElementByID('wl_codigodeseguridad')); } catch (err) { };
  }
  this.desprotegetramite   = function ()
  {
      wlpersona=document.getElementByID('wl_id_tipopersona');
      if (wlpersona.value==1)
      {
       document.getElementByID('DATOS NECESARIOS PARA REALIZAR UNA CITA:').style.display='block';
       document.getElementByID('DATOS DEL INTERESADO (PERSONA FÍSICA)').style.display='block';
       document.getElementByID('INSTRUMENTO SOLICITADO').style.display='block';
       document.getElementByID('2013').style.display='block';
       document.getElementByID('2006').style.display='block';
       document.getElementByID('2007').style.display='block';
       return true;
      }
      if (wlpersona.value==2)
      {
       document.getElementByID('DATOS NECESARIOS PARA REALIZAR UNA CITA:').style.display='block';
       document.getElementByID('INSTRUMENTO SOLICITADO').style.display='block';
       document.getElementByID('DATOS DEL INTERESADO (PERSONA MORAL)').style.display='block';
       document.getElementByID('DATOS DEL REPRESENTANTE LEGAL').style.display='block';
       try {document.getElementByID('Acta constitutiva o póliza').style.display='block';  } catch (err) { };
       document.getElementByID('2006').style.display='block';
       document.getElementByID('2008').style.display='block';
       document.getElementByID('2013').style.display='block';
       document.getElementByID('2012').style.display='block';
       document.getElementByID('2011').style.display='block';
       return true;
      }
      this.protegetramite();
  }

  this.desprotegecita   = function ()
  {
       document.getElementByID('iAlta').style.display='block';
       document.getElementByID('iBusca').style.display='none';
       document.getElementByID('DATOS NECESARIOS PARA REALIZAR UNA CITA:').style.display='block';
       document.getElementByID('2006').style.display='block';
       //document.getElementByID('tabbotones').style.visibility='none';
  }

  this.buscacalificacion = function ()
  {
     if (document.getElementByID('wl_foliocal').value=='' )
     {   alert('se necesita el folio de la calificacion'); document.getElementByID('wl_foliocal').focus();return false; }
     if (document.getElementByID('wl_anocal').value=='' )
     {   alert('se necesita el año de la calificacion'); document.getElementByID('wl_foliocal').focus();return false; }
     eventos_servidor("",0,"buscacalificacion","","",document.body.clientWidth,document.body.clientHeight);
     if (req.responseText.indexOf("Encontro la calificacion") == -1 )
     {
         alert('No encontro la calificacion'); document.getElementByID('wl_foliocal').focus(); return false;
     }
     try  { var items = req.responseXML.getElementsByTagName("nombre"); x=items[0].childNodes[0].nodeValue; document.getElementByID('wl_nombre').value=x; } catch (err) { }
     try  { var items = req.responseXML.getElementsByTagName("apepat"); x=items[0].childNodes[0].nodeValue; document.getElementByID('wl_apepat').value=x; } catch (err) { }
     try  { var items = req.responseXML.getElementsByTagName("apemat"); x=items[0].childNodes[0].nodeValue; document.getElementByID('wl_apemat').value=x; } catch (err) { }
     try  { var items = req.responseXML.getElementsByTagName("escr"); x=items[0].childNodes[0].nodeValue; document.getElementByID('wl_escr').value=x; } catch (err) { }
     try  { var items = req.responseXML.getElementsByTagName("vol"); x=items[0].childNodes[0].nodeValue; document.getElementByID('wl_vol').value=x; } catch (err) { }
     try  { var items = req.responseXML.getElementsByTagName("nota_libro"); x=items[0].childNodes[0].nodeValue; document.getElementByID('wl_nota').value=x; } catch (err) { }
     try  { var items = req.responseXML.getElementsByTagName("edad"); x=items[0].childNodes[0].nodeValue; document.getElementByID('wl_edad').value=x; } catch (err) { }
     try  { var items = req.responseXML.getElementsByTagName("idsexo"); x=items[0].childNodes[0].nodeValue; document.getElementByID('wl_idsexo').value=x; } catch (err) { }
     try  { var items = req.responseXML.getElementsByTagName("condicionv"); x=items[0].childNodes[0].nodeValue; document.getElementByID('wl_condicionv').value=x; } catch (err) { }


  }
  this.buscafolioint = function ()
  {
                    if (document.getElementByID('wl_id_tipotramite').value!='' && document.getElementByID('wl_folio').value!='' && document.getElementByID('wl_fecharecibo').value!='' && document.getElementByID('wl_codigodeseguridad').value!='')
                    {
                        if (!ValidCaptcha(document.getElementByID('wl_codigodeseguridad'))) { document.getElementByID('wl_codigodeseguridad').focus();return true; }
                        eventos_servidor("",0,"buscafolioint","","",document.body.clientWidth,document.body.clientHeight);
                        if (req.responseText.indexOf("Pase por") != -1 || req.responseText.indexOf("en proceso") != -1)
                        {
                                var items = req.responseXML.getElementsByTagName("_nada_");
                                if (document.getElementByID('DATOS NECESARIOS PARA REALIZAR UNA CITA:').style.display=='none')
                                { mensaje=window.confirm(items.item(0).textContent); }
                                if (document.getElementByID('DATOS NECESARIOS PARA REALIZAR UNA CITA:').style.display=='block' || mensaje)
                                {
                                        this.desprotegecita();
                                        if (document.getElementByID('wl_email').value!='' && document.getElementByID('wl_fecha_cita').value!='' && document.getElementByID('wl_hora_cita').value!='') {        
                                            document.getElementByID('tabbotones').style.display='none'; return true; 
                                        }
                                        document.getElementByID("wl_email").focus();
                                        return true;
                                } else { document.getElementByID('iLimpiar').click();return false; }
                        }else{
                                var items = req.responseXML.getElementsByTagName("_nada_");
                                mensaje=items.item(0).textContent;
                                alert(mensaje); document.getElementByID('iLimpiar').click();document.getElementByID('wl_id_tipotramite').focus(); return true;
                        }
                    }
  }
  this.imprimecita = function ()
  { 
       document.getElementByID('tabbotones').style.visibility='none';
       eventos_servidor("",0,"ImprimeCita","","",document.body.clientWidth,document.body.clientHeight);
  }

  this.armabarras = function (wlbarra)
  {
      barra=document.getElementByID('wl_barras');
      tramite=document.getElementByID('wl_id_cveasunto');
      folio=document.getElementByID('wl_folio');
      ano=document.getElementByID('wl_ano');
      barra.value=tramite.options[tramite.selectedIndex].text+folio.value.lpad('0',6)+ano.value+'00';
  }
  
  this.leebarras = function (wlbarra)
  {
     var barra=wlbarra.value;
     var docto=barra.substring(barra.length - 2, barra.length);
     var barra0=barra.substring(0,barra.length - 2);
     var ano=barra0.substring(barra0.length - 4, barra0.length);
     var barra1=barra0.substring(0,barra0.length - 4);
     var folio=barra1.substring(barra1.length - 6,barra1.length);
     var asunto=barra1.substring(0,barra1.length - 6);
     //movto.value=2;
     eventos_servidor("",0,"altabarra","","",document.body.clientWidth,document.body.clientHeight);
     if (req.responseText.indexOf("<error>") != -1)
     {
         return false;
     }
                       if (req.responseText.indexOf("<_nada_>") != -1)
                        {
                                var items = req.responseXML.getElementsByTagName("_nada_");
                                folioconsecutivo=items[0].childNodes[0].nodeValue;
                                document.getElementByID('wl_folioconsecutivo').value=folioconsecutivo;
                                var items = req.responseXML.getElementsByTagName("fecharecibo");
                                x=items[0].childNodes[0].nodeValue;
                                document.getElementByID('wl_fecharecibo').value=x;
                                wlbarra.value=asunto+"-"+folio;
                                document.getElementByID('iBusca').click()
                                //wlbarra.focus();
                                return true;
                        }
     alert('lei barra'+barra+' ano'+ano+' folio'+folio+' asunto'+asunto);
     return false;
  }

  this.presencia  = function ()
  {
     if (document.getElementByID('wl_folioconsecutivo').value=="")
     {   alert('Primero debe seleccionar el tramite para crear una presencia'); return false; }
     var comen = prompt("Teclee comentario que quiera agregar a la presencia");
     document.getElementByID('wl_asunto').value=comen;
     eventos_servidor("",0,"presencia","","",document.body.clientWidth,document.body.clientHeight);
     if (req.responseText.indexOf("<error>") != -1)
     {
         return false;
     }
     alert('Presencia Registrada');
     return false;
  }

  this.altabarras = function ()
  {
   document.getElementByID('wl_barras').value=document.getElementByID('wl_barras').value.toUpperCase();
   var folio=document.getElementByID('wl_folioconsecutivo');
   var barra=document.getElementByID('wl_barras');
   if (folio.value=='' && barra.value!='')
   {
     var movto=document.getElementByID('wl_id_tipotra');
     var barra=document.getElementByID('wl_barras').value;
     var docto=barra.substring(barra.length - 2, barra.length);
     var barra0=barra.substring(0,barra.length - 2);
     var ano=barra0.substring(barra0.length - 4, barra0.length);
     var barra1=barra0.substring(0,barra0.length - 4);
     var folio=barra1.substring(barra1.length - 6,barra1.length);
     var asunto=barra1.substring(0,barra1.length - 6);
     //movto.value=2;
     eventos_servidor("",0,"altabarra","","",document.body.clientWidth,document.body.clientHeight);
     if (req.responseText.indexOf("<error>") != -1)
     {
         return false;
     }
                       if (req.responseText.indexOf("<_nada_>") != -1)
                        {
                                var items = req.responseXML.getElementsByTagName("_nada_");
                                folioconsecutivo=items[0].childNodes[0].nodeValue;
                                document.getElementByID('wl_folioconsecutivo').value=folioconsecutivo;
                            try {
                                var items = req.responseXML.getElementsByTagName("fecharecibo");
                                valor=items[0].childNodes[0].nodeValue;
                                document.getElementByID('wl_fecharecibo').value=valor;
                            } catch (err) { }
                            try {
                                var items = req.responseXML.getElementsByTagName("nota_libro");
                                valor=items[0].childNodes[0].nodeValue;
                                document.getElementByID('wl_nota_libro').value=valor;
                            } catch (err) { }
                            try {
                                var items = req.responseXML.getElementsByTagName("vol");
                                valor=items[0].childNodes[0].nodeValue;
                                document.getElementByID('wl_vol').value=valor;
                            } catch (err) { }
                            try {
                                var items = req.responseXML.getElementsByTagName("escr");
                                valor=items[0].childNodes[0].nodeValue;
                                document.getElementByID('wl_escr').value=valor;
                            } catch (err) { }

                            try {
                                var items = req.responseXML.getElementsByTagName("monto");
                                valor=items[0].childNodes[0].nodeValue;
                                document.getElementByID('wl_monto_real').value=valor;
                            } catch (err) { }

                            try {
                                var items = req.responseXML.getElementsByTagName("folio");
                                wlfolio=items[0].childNodes[0].nodeValue;
                            } catch (err) { }

                            try {
                                var items = req.responseXML.getElementsByTagName("estatus");
                                valor=items[0].childNodes[0].nodeValue;
                            } catch (err) { }

                            if (valor=="3" && movto.value==12)
                            { alert("El tramite "+wlfolio+" ya fue entregado al solicitante o esta listo para entregar")
                              formReset("formpr","t");
                              try { document.getElementByID('wl_folio').focus(); } catch(err) { }
                              return false; }
                            document.getElementByID('iAlta').click()
                            return true;
                        }
     alert('lei barra'+barra+' ano'+ano+' folio'+folio+' asunto'+asunto);
     return false;
   }
  }

  this.validamovto = function ()
  {
      //alert('entro en validamovto');
      var movto=document.getElementByID('wl_id_tipotra');
      var persona=document.getElementByID('wl_id_persona');
      return true;
  }

  this.salidalibro = function ()
  {
       eventos_servidor("",0,"salidalibro","","",document.body.clientWidth,document.body.clientHeight);
       if (req.responseText.indexOf("<error>") != -1)
       {
           return false;
       }
       return true;
  }

  this.caratula = function ()
  {
       eventos_servidor("",0,"caratula","","",document.body.clientWidth,document.body.clientHeight);
       if (req.responseText.indexOf("<error>") != -1)
       {
           return false;
       }
       return true;

  }

  this.altapresencia = function ()
  {
     return true;
  }


  this.altagestion = function ()
  {
       //if (!this.validamovto())
       //{   return false; }
       var movto=document.getElementByID('wl_id_tipotra');
       var persona=document.getElementByID('wl_id_persona');
       if (movto.value==2 && persona.value==0)
       { alert('Debe seleccionar la persona a turnar'); persona.focus(); return false; }
       eventos_servidor("",0,"altagestion","","",document.body.clientWidth,document.body.clientHeight);
       if (req.responseText.indexOf("<error>") != -1)
       {
           document.getElementByID('iBusca').click()
           return false;
       }
       return true;
  }
  this.entregainfjus = function ()
  {
       //if (!this.validamovto())
       //{   return false; }
       var movto=document.getElementByID('wl_id_tipotra');
       var persona=document.getElementByID('wl_id_persona');
       if (movto.value==2 && persona.value==0)
       { alert('Debe seleccionar la persona a turnar'); persona.focus(); return false; }
       eventos_servidor("",0,"entregainfjus","","",document.body.clientWidth,document.body.clientHeight);
       if (req.responseText.indexOf("<error>") != -1)
       {
           document.getElementByID('iBusca').click()
           return false;
       }
       return true;
  }


  this.daalta = function ()
  {
       var folio=document.getElementByID('wl_folioconsecutivo').value;
       if (folio=='')
       {  document.getElementByID('iAlta').click();
       }
       return true;
  }

  this.daaltamas = function ()
  {
       var folio=document.getElementByID('wl_id_turnado_mas').value;
       if (folio=='')
       {  document.getElementByID('iAlta').click();
       }
       return true;
  }
  this.folioini = function()
  {
       document.getElementByID('wl_folio_final').value=document.getElementByID('wl_folio_inicial').value;
       document.getElementByID('wl_totalfoliosturn').value=1;
  }
  this.foliofin = function()
  {
       if (document.getElementByID('wl_folio_final').value<document.getElementByID('wl_folio_inicial').value)
       {   alert('El folio final no puede ser menor que el inicial'); document.getElementByID('wl_folio_final').focus(); }
       //document.getElementByID('wl_foliofin').value=document.getElementByID('wl_folioini').value;
       document.getElementByID('wl_totalfoliosturn').value=document.getElementByID('wl_folio_final').value-document.getElementByID('wl_folio_inicial').value+1;
  }
  this.cambio = function ()
  {
       document.getElementByID('iCambio').click();
       return true;
  }

  this.dacambio = function ()
  {
       this.validapago();
       document.getElementByID('iCambio').click();
       document.getElementByID('iLimpiar').click();
       return true;
  }
  this.buscafolio = function ()
  {
    if (document.getElementByID('wl_folio').value!='' && document.getElementByID('wl_folioconsecutivo').value=='')
    {
       if (document.getElementByID('wl_folio').value.length<11)
       {
         document.getElementByID('wl_folio').value='%'+document.getElementByID('wl_folio').value.lpad('0',6);
         document.getElementByID('iBusca').click();
       } else
       {
         this.leebarras(document.getElementByID('wl_folio'));
       }
    }
       return true;
  }

  this.cancelafolio = function ()
  {
    if (document.getElementByID('wl_id_cveasunto').value=='')
    {  alert('Primero debe seleccionar un tipo de tramite'); 
       document.getElementByID('wl_id_cveasunto').focus();
       return true; }
    if (this.buscafolio() && document.getElementByID('wl_folioconsecutivo').value!='')
    {  
       if (document.getElementByID('wl_estatus').value==3)
       { alert('El folio ya esta entregado no lo puede cancelar'); return false; }
       if (document.getElementByID('wl_estatus').value==4)
       { alert('El folio ya esta cancelado'); return false; }
       document.getElementByID('wl_estatus').value=4;
       document.getElementByID('iCambio').click();
       document.getElementByID('iLimpiar').click();
     }
  }
  this.entregafolio = function ()
  {
    if (document.getElementByID('wl_id_cveasunto').value=='')
    {  alert('Primero debe seleccionar un tipo de tramite'); 
       document.getElementByID('wl_id_cveasunto').focus();
       return true; }
    if (this.buscafolio() && document.getElementByID('wl_folioconsecutivo').value!='')
    {  
       //if (document.getElementByID('wl_estatus').value==3)
       //{ alert('El folio ya esta entregado '); return false; }
       //if (document.getElementByID('wl_estatus').value==4)
       //{ alert('El folio esta cancelado'); return false; }
       //if (confirm('Seguro que quiere entregar el folio '+document.getElementByID('wl_folio').value)==true)
       //{
         //document.getElementByID('wl_estatus').value=3;
         //document.getElementByID('wl_fecha_termino').focus();
         //document.getElementByID('iCambio').click();
         //document.getElementByID('iLimpiar').click();
       //}
     }
  }
  this.entregafolio_f = function()
  {
        wlant=document.getElementByID('wl_id_cveasunto').value;
        document.getElementByID('iCambio').click();
        document.getElementByID('iLimpiar').click();
        document.getElementByID('wl_id_cveasunto').value=wlant;
        document.getElementByID('wl_folio').focus();
  }

  this.validapago = function ()
  {
       var lc=document.getElementByID('wl_lc').value;
       if (lc!='' && lc.length!=20)
       {  alert('La linea de captura debe ser de 20 posiciones');
          return false;
       }
       if (lc!='')
       {
         eventos_servidor("",0,"validapago","","",document.body.clientWidth,document.body.clientHeight);
         if (req.responseText.indexOf("<_nada_>") != -1)
         { 
             var items = req.responseXML.getElementsByTagName("_nada_");
             document.getElementByID('wl_val_lc').value=2; // valida en finanzas
         }
         if (req.responseText.indexOf("ocupada") != -1)
         { 
             document.getElementByID('wl_val_lc').value=3; // ocupada por otro tramite
         }
         if (req.responseText.indexOf("No existe") != -1)
         { 
             document.getElementByID('wl_val_lc').value=4; // No valida en finanzas
         }
       }
       return true;
  }

  this.turnarmasiva = function ()
  {
   try
   { 		
   		var pr=document.getElementByID('wl_idpersona_recibe');
		if (pr.value=='')
			{
			 { alert('No ha seleccionado la persona que va a turnar'); pe.value=''; return false; }
			} 
			eventos_servidor("",0,"turnarmasiva","","",document.body.clientWidth,document.body.clientHeight);
			
			if (req.responseText.indexOf("<_nada_>") != -1)
			{
				var items = req.responseXML.getElementsByTagName("_nada_");
				mensaje=window.confirm(items[0].childNodes[0].nodeValue);
				if (mensaje)
				{	eventos_servidor("",0,"turnarmasivaok","","",document.body.clientWidth,document.body.clientHeight); }
				//wlfecha=items.item(0).text
			}else{
					alert('No encontro la respuesta'); return false;
			}
	
		return true;
      } catch (err) { alert('error turnarmasiva '+err.description); }	   	  
  }  
  this.mueveanomes = function (fcobro)
  {
    try {
      str=fcobro.value;
      wlano=document.getElementByID('wl_ano');      
      wlano.value=str.substring(0,4);
      wlmes=document.getElementByID('wl_mes');      
      wlmes.value=str.substring(5,7);      
      } catch (err) { alert('error anook '+err.description); }
  }  
	/**
	* valida que la persona que envia y reciba no sean igual, contra
	*/
	this.checapersonaenvia = function()
	{
	try
	{
		var pe=document.getElementByID('wl_idpersona_envia');
		var pr=document.getElementByID('wl_idpersona_recibe');
		if (pe.value!='' || pr.value!='')
			{
				if (pe.value==pr.value) { alert('La persona que envia no puede ser igual a la que recibe'); pe.value=''; return false; }
			} 
		return true;
  	} catch (err) { alert('error   eve_particulares.checapersonaenvia'+err.description); }		        			  
	}
	/**
	* valida la fecha del documento, contra
	*/
	this.checafechadocumento = function()
	{
	try
	{	  
		var fecha=document.getElementByID('wl_fechadocumento');
		var fecharec=document.getElementByID('wl_fecharecibo');
		
		if (fecha.value.length)
		{
			eventos_servidor("",0,"fechaServidor","","",document.body.clientWidth,document.body.clientHeight);
			if (req.responseText.indexOf("<fechaactual>") != -1)
			{
				var items = req.responseXML.getElementsByTagName("fechaactual");
				wlfecha=items.item(0).text
			}else{
					alert('No encontro la respuesta'); return false;
			}
		    if (fecha.value>wlfecha) {  alert('La fecha del documento debe ser menor o igual a la actual'); fecha.value=''; fecha.focus(); return false; }
		    if (fecha.value>fecharec.value && fecharec.value.length) { alert('La fecha del documento debe ser menor o igual a la de recepcion'); fecha.value=''; fecha.focus(); return false; }			
		}
	} catch (err) { alert('error   eve_particulares.checafechadocumento'+err.description); }		        			  
	}
	/**
	* valida la fecha de recibio, contra
	*/
	this.checafecharecibo = function()
	{
	try
	{	  
		var fecha=document.getElementByID('wl_fecharecibo');
		//var fechadoc=document.getElementByID('wl_fechadocumento');		
		if (fecha.value.length>0)
		{
			eventos_servidor("",0,"fechaServidor","","",document.body.clientWidth,document.body.clientHeight);
			if (req.responseText.indexOf("<fechaactual>") != -1)
			{
				var items = req.responseXML.getElementsByTagName("fechaactual");
				wlfecha=items.item(0).text
			}else{
					alert('No encontro la respuesta'); return false;
			}
		    if (fecha.value>wlfecha) { alert('La fecha de recepcion no puede ser mayor a la actual'); fecha.value=''; fecha.focus(); return false; }
			//if (fecha.value<fechadoc.value) { alert('La fecha de recepcion debe ser mayor o igual a la del documento'); fecha.value=''; fecha.focus(); return false; }			
		}	  
	} catch (err) { alert('error   eve_particulares.checafecharecibo2'+err.description); }		        			  
	}
	/**
	* funcion que valida que el tipo de tramite no sea inicio, contra
	*/
	this.validaTramiteTurnado = function ()
	{
	try
	{
		var persona=document.getElementByID('wl_id_persona');
		if (persona.value==0){ alert('Seleccione la persona'); persona.focus(); return false; }
		var tramite=document.getElementByID('wl_id_tipotra');
		if (tramite.value==1) { alert('El tipo de tramite seleccionado es controlado por el sistema'); tramite.focus(); return false; }
		else 
		{
			eventos_servidor("",0,"validaTurnoSeguimiento","","",document.body.clientWidth,document.body.clientHeight);
			if (req.responseText.indexOf("<enviavalor>") != -1)
			{
				var items = req.responseXML.getElementsByTagName("enviavalor");
				if (items.item(0).text=='0')
				{
					alert ('Imposible generar turnos de tramites no asignados'); return false;
				}
			}else{
					alert('Error al validar tramites de la persona'); return false;
			}
			eventos_servidor("",0,"validaTramiteTurnado","","",document.body.clientWidth,document.body.clientHeight);
			if (req.responseText.indexOf("<enviavalor>") != -1)
			{
				var items = req.responseXML.getElementsByTagName("enviavalor");
				if (items.item(0).text>0)
				{
					alert ('La persona a turnar aun tiene tramites sin liberar'); return false;
				}
			}else{
					alert('Error al validar tramites de la persona'); return false;
			}
			//eventos_servidor("",0,"actuEstatusGestion","","",document.body.clientWidth,document.body.clientHeight);
		}
		this.limpiaFechaUsuario();
		
	} catch (err) { alert('error validaTramiteTurnado '+err.description); }		        			  
	}
	/**
	* funcion que valida el alta un nuevo turno para ventanilla, contra
	*/
	this.validaTramiteTurnadoVentanilla = function ()
	{
	try
	{
		var persona=document.getElementByID('wl_id_persona');
		if (persona.value==0){ alert('Seleccione la persona'); persona.focus(); return false; }
		var tramite=document.getElementByID('wl_id_tipotra');
		if (tramite.value==1) { alert('El tipo de tramite seleccionado es controlado por el sistema'); tramite.focus(); return false; }
		else 
		{
			eventos_servidor("",0,"validaTramiteTurnado","","",document.body.clientWidth,document.body.clientHeight);
			if (req.responseText.indexOf("<enviavalor>") != -1)
			{
				var items = req.responseXML.getElementsByTagName("enviavalor");
				if (items.item(0).text>0)
				{
					alert ('La persona a turnar aun tiene tramites sin liberar'); return false;
				}
			}else{
					alert('Error al validar tramites de la persona'); return false;
			}
			//eventos_servidor("",0,"actuEstatusGestion","","",document.body.clientWidth,document.body.clientHeight);
		}
		this.limpiaFechaUsuario();
		
	} catch (err) { alert('error validaTramiteTurnado '+err.description); }		        			  
	}
	/**
	* funcion que valida que el tipo de tramite no sea inicio, contra
	*/
	this.validaTramiteTurnadoInterno = function ()
	{
	try
	{
		var tramite=document.getElementByID('wl_id_tipotra');
		if (tramite.value==1) { alert('El tipo de tramite seleccionado es controlado por el sistema'); tramite.focus(); return false; }
		else 
		{
			eventos_servidor("",0,"validaTurnoSeguimientoInterno","","",document.body.clientWidth,document.body.clientHeight);
			if (req.responseText.indexOf("<enviavalor>") != -1)
			{
				var items = req.responseXML.getElementsByTagName("enviavalor");
				if (items.item(0).text=='0')
				{
					alert ('Imposible generar turnos de tramites no asignados'); return false;
				}
			}else{
					alert('Error al validar tramites de la persona'); return false;
			}
			eventos_servidor("",0,"validaTramiteTurnadoInterno","","",document.body.clientWidth,document.body.clientHeight);
			
			if (req.responseText.indexOf("<enviavalor>") != -1)
			{
				var items = req.responseXML.getElementsByTagName("enviavalor");
				if (items.item(0).text>0)
				{
					alert ('La persona a turnar aun tiene tramites sin liberar'); return false;
				}
			}else{
					alert('Error al validar tramites de la persona'); return false;
			}
			//eventos_servidor("",0,"actuEstatusGestionInterno","","",document.body.clientWidth,document.body.clientHeight);
		}
		this.limpiaFechaUsuario();
		
	} catch (err) { alert('error validaTramiteTurnadoInterno '+err.description); }		        			  
	}
	/**
	* funcion que valida que el puesto padre no sea el mismo que el hijo, contra
	*/
	this.validaPuestoPadre = function ()
	{
	try
	{
		var puestopadre=document.getElementByID('wl_id_puesto_padre');
		var puesto=document.getElementByID('wl_id_puesto');
		if (puestopadre.value.length && puesto.value.length)
		{
		//if (puestopadre.value==puesto.value) { alert('El puesto padre no puede ser el mismo'); puestopadre.value=''; puestopadre.focus(); return false; }
		eventos_servidor("",0,"validaPuestoPadre","","",document.body.clientWidth,document.body.clientHeight);
		if (req.responseText.indexOf("<enviavalor>") != -1)
		{
			var items = req.responseXML.getElementsByTagName("enviavalor");
			var wlcount=items.item(0).text;
			if (wlcount>0) {alert('El puesto padre seleccionado es un puesto dependiente'); puestopadre.value=''; puestopadre.focus(); return false; }
		}else{
			alert('No encontro la respuesta'); return false;
		}
		}
	} catch (err) { alert('error validaPuestoPadre '+err.description); }		        			  
	}
	/**
	* funcion que valida que la baja de un turno sea diferente al tramite 1
	*/
	this.validaBajaTramite = function ()
	{
	try
	{
		var tipotra=document.getElementByID('wl_id_tipotra');
		if (tipotra.value==1) { alert('El tramite Inicio no se puede dar de baja'); return false; }
	} catch (err) { alert('error validaBajaTramite '+err.description); }
	}


  
this.valida_cp = function (x)
  {	  	  
	try {	  	  	  
			if(x.value.length)
			{
			if (x.value.length!=5) { alert('La longitud del codigo postal es de 5 posiciones');	return false;	}
			eventos_servidor("existecp",x.value,"existecp","",Cambiosize,document.body.clientWidth,document.body.clientHeight);
			}
  		}
	catch (err) { alert('error   eve_particulares.valida_cp '+err.description); }		        			  
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
	/**
	* funcion para validar el fromato del telefono
	*/
	this.val_telefono = function (valor)
	{
	try
	{
		var telefono=valor.value;
		var patron;
		if (telefono.length)
		{
		patron=/([\000-\057]|[\072-\177])/;
		if (patron.test(telefono)) { alert ('El telefono es un campo numerco'); return false; }
		if (telefono.length<8 || telefono.length==9) { alert ('El telefono solo puede ser de 8 o 10 posiciones'); return false; }
		}
	} catch (err) { alert('error val_telefono '+err.description); }
	}
	/**
	 *  funcion para validar datos alfanumericos
	*/
	this.SoloAlfanumerico = function ()
	{
		try
		{
		evt=window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if ((charCode > 47 && charCode < 58) || (charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || (charCode==38) || (charCode==64) || (charCode==32) || (charCode==46) || (charCode==241) || (charCode==209) )
			return true;
			return false;
		} catch (err) { alert('error SoloAlfanumerico '+err.description); }
	}
	/**
	 *  funcion para validar datos numericos
	*/
	this.SoloNumerico = function ()
	{
		try
		{	
		evt=window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57))
			return false;
      		return true;
  		} catch (err) { alert('error SoloAlfanumerico '+err.description); }
	}
	/**
	 *  funcion para validar datos numericos en el campo
	*/
	this.revisaNumerico = function (x)
	{
		try
		{	
		// patron del tipo de solo nueros
		patron=/^[0-9]+$/;
		if (!patron.test(x.value) && x.value.length) { alert ('El tipo de dato para este campo es numerico!'); x.value=''; return false;}
		} catch (err) { alert('error revisaNumerico '+err.description); }
	}
	/**
	* funcion que regresa la descipcion del tipo de placa
	*/
	this.desTipoPlaca = function (tipo)
	{
		try
		{
		var wldes;
		if (tipo.value=='A' || tipo=='A'){wldes='AUTO PARTICULAR';}
		if (tipo.value=='B' || tipo=='B'){wldes='CARGA MERCANTIL';}
		if (tipo.value=='C' || tipo=='C'){wldes='OMNIBUS PARTICULAR';}
		if (tipo.value=='D' || tipo=='D'){wldes='REMOLQUES';}
		if (tipo.value=='E' || tipo=='E'){wldes='ALQUILER/COLECTIVO/TAXI';}
		if (tipo.value=='F' || tipo=='F'){wldes='CARGA GENERAL';}
		if (tipo.value=='H' || tipo=='H'){wldes='OMNIBUS URBANO DE PASAJEROS';}
		if (tipo.value=='I' || tipo=='I'){wldes='DIPLOMATICOS';}
		if (tipo.value=='J' || tipo=='J'){wldes='DEMOSTRADORAS';}
		if (tipo.value=='K' || tipo=='K'){wldes='AUTO ANTIGUO';}
		if (tipo.value=='L' || tipo=='L'){wldes='S.P.F. CARGA';}
		if (tipo.value=='M' || tipo=='M'){wldes='SERV. PUB. FED. PASAJE';}
		if (tipo.value=='N' || tipo=='N'){wldes='SERV. PUB. FED. TURISMO';}
		if (tipo.value=='P' || tipo=='P'){wldes='S.P.F. ARREND. PARTICULAR';}
		if (tipo.value=='Q' || tipo=='Q'){wldes='S.P.F. ARREND. PASAJE';}
		if (tipo.value=='R' || tipo=='R'){wldes='S.P.F. ARREND. TURISMO';}
		if (tipo.value=='S' || tipo=='S'){wldes='S.P.F REMOLQUES';}
		if (tipo.value=='T' || tipo=='T'){wldes='S.P.F. ARREND. REMOLQUES';}
		if (tipo.value=='Y' || tipo=='Y'){wldes='MOTOCICLETAS';}
		if (tipo.value=='Z' || tipo=='Z'){wldes='PER. C/DISCAPACIDAD';}
		if (tipo.value=='W' || tipo=='W'){wldes='SUSTITUCION DE UNIDA';}
		return wldes;
		} catch (err) { alert('error desTipoPlaca '+err.description); }
	}
	/**
	* funcion para validar que una fecha no sea mayor a la actual
	*/
	this.revisaFecha = function(fecha)
	{
	try
	{
		alert('entro en funcion'+fecha);
			var d = new Date();
			var wlfecha=d.getFullYear() + '-' + ((d.getMonth()+1)>9 ? (d.getMonth()+1) : '0' + (d.getMonth()+1)) + '-'	+ ((d.getDate())>9 ? (d.getDate()) : '0' + (d.getDate())) ;
		    if (fecha.value>wlfecha) {  alert('La fecha debe ser menor o igual a la fecha actual'); fecha.value=''; fecha.focus(); return false; }
	} catch (err) { alert('error   eve_particulares.revisaFecha '+err.description); }		        			  
	}
	/**
	* funcion para validar la solicitud de consulta
	*/
	this.limpiaFechaUsuario = function()
	{
	try
	{
		if (document.getElementByID('wl_fecha_alta')) { var wlfalta = document.getElementByID('wl_fecha_alta'); wlfalta.value='';}
		if (document.getElementByID('wl_fecha_modifico')) { var wlfmodi = document.getElementByID('wl_fecha_modifico'); wlfmodi.value='';}
		if (document.getElementByID('wl_usuario_alta')) { var wlualta = document.getElementByID('wl_usuario_alta'); wlualta.value=''; }
		if (document.getElementByID('wl_usuario_modifico')) { var wlumodi = document.getElementByID('wl_usuario_modifico'); wlumodi.value=''; }
	} catch (err) { alert('error   eve_particulares.limpiaFechaUsuario '+err.description); }
	}
	/**
	* funcion para validar el usuario que modifica o elimina el turno
	*/
	this.validaMovtoTramite = function()
	{
	try
	{
		var usuarioalta=document.getElementByID('wl_usuario_alta');
		var liberado=document.getElementByID('wl_liberado');
		var tramite=document.getElementByID('wl_id_tipotra');
		// revisa el estado del tremite
		if (liberado.value=='S') { var msj='liberado'; }
		if (liberado.value=='C') { var msj='cerrado'; }
		if (liberado.value!='N') { alert ('Operacion no permitida, el turno ya fue '+msj); return false; }
		// revisa el tipo de tremite del turno
		eventos_servidor("",0,"tramiteTurno","","",document.body.clientWidth,document.body.clientHeight);
		if (req.responseText.indexOf("<enviavalor>") != -1)
		{	var items = req.responseXML.getElementsByTagName("enviavalor");
			if (items.item(0).text=='1') { alert('El tipo de tramite seleccionado es controlado por el sistema'); return false; }	}
		if (tramite.value==1) { alert('El tipo de tramite seleccionado es controlado por el sistema'); tramite.focus(); return false; }
		// revisa que el usuario sea quien registro el turno
		if (this.validaMovtoUsuario()==false) {return false;}
		// revisa si el turno ya tiene turnos
		eventos_servidor("",0,"turnosTurno","","",document.body.clientWidth,document.body.clientHeight);
		if (req.responseText.indexOf("<enviavalor>") != -1)
		{	var items = req.responseXML.getElementsByTagName("enviavalor");
			if (items.item(0).text>0) { alert ('El turno ya asigno turnos');	return false; }	}
	} catch (err) { alert('error eve_particulares.validaMovtoTramite '+err.description); }
	}
	/**
	* funcion para validar el usuario que modifica o elimina el turno interno
	*/
	this.validaMovtoTramiteInterno = function()
	{
	try
	{
		var usuarioalta=document.getElementByID('wl_usuario_alta');
		var liberado=document.getElementByID('wl_liberado');
		var tramite=document.getElementByID('wl_id_tipotra');
		// revisa el estado del tremite
		if (liberado.value=='S') { var msj='liberado'; }
		if (liberado.value=='C') { var msj='cerrado'; }
		if (liberado.value!='N') { alert ('Operacion no permitida, el turno ya fue '+msj); return false; }
		// revisa el tipo de tremite del turno
		eventos_servidor("",0,"tramiteTurnoInterno","","",document.body.clientWidth,document.body.clientHeight);
		if (req.responseText.indexOf("<enviavalor>") != -1)
		{	var items = req.responseXML.getElementsByTagName("enviavalor");
			if (items.item(0).text=='1') { alert('El tipo de tramite seleccionado es controlado por el sistema'); return false; }	}
		if (tramite.value==1) { alert('El tipo de tramite seleccionado es controlado por el sistema'); tramite.focus(); return false; }
		// revisa que el usuario sea quien registro el turno
		if (this.validaMovtoUsuario()==false) {return false;}
		// revisa si el turno ya tiene turnos
		eventos_servidor("",0,"turnosTurnoInterno","","",document.body.clientWidth,document.body.clientHeight);
		if (req.responseText.indexOf("<enviavalor>") != -1)
		{	var items = req.responseXML.getElementsByTagName("enviavalor");
			if (items.item(0).text>0) { alert ('El turno ya asigno turnos');	return false; }	}
	} catch (err) { alert('error eve_particulares.validaMovtoTramiteInterno '+err.description); }
	}
	/**
	* funcion para validar el usuario que modifica o elimina el turno del grupo contra_ventanilla_admon
	*/
	this.validaMovtoTramiteAdmon = function()
	{
	try
	{
		var usuarioalta=document.getElementByID('wl_usuario_alta');
		var liberado=document.getElementByID('wl_liberado');
		var tramite=document.getElementByID('wl_id_tipotra');
		// revisa el estado del tremite
		/*if (liberado.value=='S') { var msj='liberado'; }
		if (liberado.value=='C') { var msj='cerrado'; }
		if (liberado.value!='N') { alert ('Operacion no permitida, el turno ya fue '+msj); return false; }*/
		eventos_servidor("",0,"estatusTramite","","",document.body.clientWidth,document.body.clientHeight);
		if (req.responseText.indexOf("<enviavalor>") != -1)
		{	var items = req.responseXML.getElementsByTagName("enviavalor");
			if (items.item(0).text=='3') { alert('Operacion no permitida, el tramite ya fue cerrado'); return false; }	}
		
		// revisa el tipo de tremite del turno
		eventos_servidor("",0,"tramiteTurno","","",document.body.clientWidth,document.body.clientHeight);
		if (req.responseText.indexOf("<enviavalor>") != -1)
		{	var items = req.responseXML.getElementsByTagName("enviavalor");
			if (items.item(0).text=='1') { alert('El tipo de tramite seleccionado es controlado por el sistema'); return false; }	}
		if (tramite.value==1) { alert('El tipo de tramite seleccionado es controlado por el sistema'); tramite.focus(); return false; }
		/*
		// revisa que el usuario sea quien registro el turno
		if (this.validaMovtoUsuario()==false) {return false;}
		// revisa si el turno ya tiene turnos
		eventos_servidor("",0,"turnosTurno","","",document.body.clientWidth,document.body.clientHeight);
		if (req.responseText.indexOf("<enviavalor>") != -1)
		{	var items = req.responseXML.getElementsByTagName("enviavalor");
			if (items.item(0).text>0) { alert ('El turno ya asigno turnos');	return false; }	}*/
	} catch (err) { alert('error eve_particulares.validaMovtoTramiteAdmon '+err.description); }
	}
	/**
	* funcion para validar el usuario que modifica un regisro en contra
	*/
	this.validaMovtoUsuario = function()
	{
	try
	{
		var usuarioalta=document.getElementByID('wl_usuario_alta');
		// revisa que el usuario sea quien dio de alta el registro
		eventos_servidor("",0,"turnarmasiva","","",document.body.clientWidth,document.body.clientHeight);
		/*
		if (req.responseText.indexOf("<enviavalor>") != -1)
		{	var items = req.responseXML.getElementsByTagName("enviavalor");
			if (items.item(0).text!=usuarioalta.value && items.item(0).text!='admin' ) { alert ('El usuario no puede manipular el registro');	return false; }
				}
*/
							
	} catch (err) { alert('error eve_particulares.validaMovtoUsuario '+err.description); }
	}
	/**
	* funcion para asignar gestion sin folio
	*/
	this.sinFolio = function()
	{
	try
	{
		var folio=document.getElementByID('wl_folio');
		mensaje=window.confirm('Desea capturar el numero de folio?');
		if (mensaje)
			{	folio.value='';
				folio.focus(); }
			else
			{	folio.value='S/F';
				folio.disabled=true;
				folio.className='lee';
			}
	} catch (err) { alert('error eve_particulares.sinFolio '+err.description); }
	}
	/**
	* funcion para validar que el numero de referencia no exista en la base de datos
	*/
	this.validaReferencia = function()
	{
	try
	{
		var referencia=document.getElementByID('wl_referencia');
		var persona=document.getElementByID('wl_idpersona_envia');
		if (referencia.value!='' && persona.value!='' )
		{
			eventos_servidor("",0,"validaReferencia","","",document.body.clientWidth,document.body.clientHeight);
			if (req.responseText.indexOf("<enviavalor>") != -1)
			{	var items = req.responseXML.getElementsByTagName("enviavalor");
				if (items.item(0).text>0) { alert ('El numero de oficio '+referencia.value+' ya existe en el sistema'); referencia.focus(); return false; } }
		}
	} catch (err) { alert('error eve_particulares.validaReferencia '+err.description); }
	}
	/**
	* funcion para validar que el tremite no exista en la base de datos
	*/
	this.validaDuplicado = function()
	{
	try
	{
		var referencia=document.getElementByID('wl_referencia');
		var personae=document.getElementByID('wl_idpersona_envia');
		var personar=document.getElementByID('wl_idpersona_recibe');
		var fecha=document.getElementByID('wl_fechadocumento');
		var docto=document.getElementByID('wl_id_tipodocto');
		var asunto=document.getElementByID('wl_id_cveasunto');	
		if (referencia.value!='' && personae.value!='' && personar.value!='' && fecha.value!='' && docto.value!='' && asunto.value!='')
		{
			eventos_servidor("",0,"validaDuplicado","","",document.body.clientWidth,document.body.clientHeight);
			if (req.responseText.indexOf("<enviavalor>") != -1)
			{	var items = req.responseXML.getElementsByTagName("enviavalor");
				if (items.item(0).text>0) { alert ('El tramite ya existe en el sistema'); return false; } }
		}
	} catch (err) { alert('error eve_particulares.validaDuplicado '+err.description); }
	}
	/**
	* funcion para validar que el numero de referencia no exista en la base de datos en tramites internos
	*/
	this.validaReferenciaInterno = function()
	{
	try
	{
		var d = new Date();
		var persona=document.getElementByID('wl_idpersona_envia');
		var referencia=document.getElementByID('wl_referencia');
		if (referencia.value!='' && persona.value!='' )
		{
		eventos_servidor("",0,"validaReferenciaInterno","","",document.body.clientWidth,document.body.clientHeight);
		if (req.responseText.indexOf("<enviavalor>") != -1)
		{	var items = req.responseXML.getElementsByTagName("enviavalor");
			if (items.item(0).text>0) { alert ('El numero de oficio '+referencia.value+' ya existe en el sistema!'); referencia.focus(); return false; } }
		}
	} catch (err) { alert('error eve_particulares.validaReferencia '+err.description); }
	}
	/**
	* funcion para validar que el tremite no exista en la base de datos en tramites internos
	*/
	this.validaDuplicadoInterno = function()
	{
	try
	{
		var referencia=document.getElementByID('wl_referencia');
		var personae=document.getElementByID('wl_idpersona_envia');
		var personar=document.getElementByID('wl_idpersona_recibe');
		var fecha=document.getElementByID('wl_fechadocumento');
		var docto=document.getElementByID('wl_id_tipodocto');
		var asunto=document.getElementByID('wl_id_cveasunto');	
		if (referencia.value!='' && personae.value!='' && personar.value!='' && fecha.value!='' && docto.value!='' && asunto.value!='')
		{
			eventos_servidor("",0,"validaDuplicadoInterno","","",document.body.clientWidth,document.body.clientHeight);
			if (req.responseText.indexOf("<enviavalor>") != -1)
			{	var items = req.responseXML.getElementsByTagName("enviavalor");
				if (items.item(0).text>0) { alert ('El tramite ya existe en el sistema'); return false; } }
		}
	} catch (err) { alert('error eve_particulares.validaDuplicadoInterno '+err.description); }
	}
	/**
	* funcion para validar si el tramite es relevante o no
	*/
	this.validaRelevante = function()
	{
	try
	{
		
		var relevante=document.getElementByID('wl_relevante');
		if (relevante.checked==false)
		{
			if (window.confirm('Desea marcar el tramite como relevante?'))
			{	relevante.value='t'
				relevante.checked=true;	}
			else
			{	relevante.value='f'
				relevante.checked=false;	}
		}
		//alert ('salio');
	} catch (err) { alert('error eve_particulares.validaRelevante '+err.description); }
	}
	/**
	* funcion para validar el alta de un tramite
	*/
	this.validaAltaTramite = function()
	{
	try
	{
		var folio=document.getElementByID('wl_folio');
		folio.value='';
		if(this.validaRelevante()==false) {return false;};
		if(this.validaReferencia()==false) {return false;};
		//if(this.validaDuplicado()==false) {return false;};
	} catch (err) { alert('error eve_particulares.validaAltaTramite '+err.description); }
	}
	/**
	* funcion para validar el alta de un tramite
	*/
	this.validaCambioTramite = function()
	{
	try
	{
		if(this.validaReferencia()==false) {return false;};
		if(this.validaDuplicado()==false) {return false;};
	} catch (err) { alert('error eve_particulares.validaCambioTramite '+err.description); }
	}
	/**
	* funcion para validar el alta de un tramite interno
	*/
	this.validaAltaTramiteInterno = function()
	{
	try
	{
		var folio=document.getElementByID('wl_folio');
		folio.value='';
		if(this.validaRelevante()==false) {return false;};
		if(this.validaReferenciaInterno()==false) {return false;};
		if(this.validaDuplicadoInterno()==false) {return false;};
		
		
	} catch (err) { alert('error eve_particulares.validaAltaTramite '+err.description); }
	}
	/**
	* funcion para validar el alta de un tramite interno
	*/
	this.validaCambioTramiteInterno = function()
	{
	try
	{
		if(this.validaReferenciaInterno()==false) {return false;};
		if(this.validaDuplicadoInterno()==false) {return false;};
	} catch (err) { alert('error eve_particulares.validaCambioTramiteInterno '+err.description); }
	}
	/**
	* funcion que regresa el codigo postal
	*/
	/**
	* funcion que regresa el codigo postal
	*/
	this.dameCP = function()
	{
	try
	{
		var idcolonia=document.getElementByID('wl_idcolonia');
		var cp=document.getElementByID('wl_cp');
		if (idcolonia.value.length)
		{			
			eventos_servidor("",0,"dameCP","","",document.body.clientWidth,document.body.clientHeight);
			if (req.responseText.indexOf("<enviavalor>") != -1)
			{	var items = req.responseXML.getElementsByTagName("enviavalor");
				if (items.item(0).text>0) {	cp.value=items.item(0).text;	}
			}
		} else { cp.value='';}
		
		
	} catch (err) { alert('error eve_particulares.damecp '+err.description); }
	}
	/**
	* funcion que valida la cuenta catastral
	*/
	this.validaCTAcatastral = function()
	{
	try
	{
		if (document.getElementByID('wl_ctacatastral') && document.getElementByID('wl_ctacatastral').value.length)
		{
			var ctacatastral=document.getElementByID('wl_ctacatastral');
			if (this.revisaNumerico (ctacatastral)==false) {ctacatastral.focus(); return false;}
			if (this.validaLargo(ctacatastral,'8')==false) {ctacatastral.focus(); return false;}
			rg=ctacatastral.value.substring(0,3);
			mz=ctacatastral.value.substring(3,6),
			lt=ctacatastral.value.substring(6,8);
			if(parseFloat(rg)==0) {alert ('La región tiene que ser mayor a cero'); ctacatastral.focus(); return false;}
			if(parseFloat(mz)==0) {alert ('La manzana tiene que ser mayor a cero'); ctacatastral.focus(); return false;}
			if(parseFloat(lt)==0) {alert ('El lote tiene que ser mayor a cero'); ctacatastral.focus(); return false;}
			mz=ctacatastral.value.substring(3,6),
			lt=ctacatastral.value.substring(6,8);
			if (document.getElementByID('wl_reg')) {document.getElementByID('wl_reg').value=rg;}
			if (document.getElementByID('wl_mz')) {document.getElementByID('wl_mz').value=mz;}
			if (document.getElementByID('wl_lote')) {document.getElementByID('wl_lote').value=lt;}
			this.validaCTApredial ();
		}	else	{
			if (document.getElementByID('wl_reg')) {document.getElementByID('wl_reg').value='';}
			if (document.getElementByID('wl_mz')) {document.getElementByID('wl_mz').value='';}
			if (document.getElementByID('wl_lote')) {document.getElementByID('wl_lote').value='';}
		}
	} catch (err) { alert('error eve_particulares.validaCTAcatastral '+err.description); }
	}
	/**
	* funcion que valida la cuenta predial
	*/
	this.validaCTApredial = function()
	{
	try
	{
		if (document.getElementByID('wl_ctapredial') && document.getElementByID('wl_ctapredial').value.length)
		{
			var ctacatastral=document.getElementByID('wl_ctacatastral');
			var ctapredial=document.getElementByID('wl_ctapredial');
			if (ctacatastral.value!=ctapredial.value.substring(0,8)) {alert ('Los primeros 8 digitos de la cuenta predial deben conicidir con la cta catastral'); ctapredial.focus(); return false;}
			if (this.revisaNumerico (ctapredial)==false) {ctapredial.focus(); return false;}
			if (this.validaLargo(ctapredial,'12')==false) {ctapredial.focus(); return false;}
			rg=ctapredial.value.substring(0,3);
			mz=ctapredial.value.substring(3,6),
			lt=ctapredial.value.substring(6,8);
			cd=ctapredial.value.substring(8,11);
			//if(parseFloat(rg)==0) {alert ('La región tiene que ser mayor a cero'); ctapredial.focus(); return false;}
			//if(parseFloat(mz)==0) {alert ('La manzana tiene que ser mayor a cero'); ctapredial.focus(); return false;}
			//if(parseFloat(lt)==0) {alert ('El lote tiene que ser mayor a cero'); ctapredial.focus(); return false;}
			if(parseFloat(cd)==0) {alert ('El condominio tiene que ser mayor a cero'); ctapredial.focus(); return false;}
			//if (document.getElementByID('wl_reg')) {document.getElementByID('wl_reg').value=rg;}
			//if (document.getElementByID('wl_mz')) {document.getElementByID('wl_mz').value=mz;}
			//if (document.getElementByID('wl_lote')) {document.getElementByID('wl_lote').value=lt;}
			if (document.getElementByID('wl_condominio')) {document.getElementByID('wl_condominio').value=cd;}
		}	else	{
			//if (document.getElementByID('wl_reg')) {document.getElementByID('wl_reg').value='';}
			//if (document.getElementByID('wl_mz')) {document.getElementByID('wl_mz').value='';}
			//if (document.getElementByID('wl_lote')) {document.getElementByID('wl_lote').value='';}
			if (document.getElementByID('wl_condominio')) {document.getElementByID('wl_condominio').value='';}
		}
	} catch (err) { alert('error eve_particulares.validaCTA '+err.description); }
	}
	/**
	* funcion que valida la cuenta agua
	*/
	this.validaCTAagua = function()
	{
	try
	{
		if (document.getElementByID('wl_ctagua') && document.getElementByID('wl_ctagua').value.length)
		{
			var ctagua=document.getElementByID('wl_ctagua');
			if(parseFloat(ctagua.value)==0) {alert ('La cuenta agua tiene que ser mayor a cero'); ctagua.focus(); return false;}
			if (this.revisaNumerico (ctagua)==false) {ctagua.focus(); return false;}
			if (this.validaLargo(ctagua,'16')==false) {ctagua.focus(); return false;}
		}
	} catch (err) { alert('error eve_particulares.validaCTA '+err.description); }
	}
	/**
	* funcion para validar el largo de una cadena
	*/
	this.validaLargo = function(txt,largo)
	{
	try
	{
		largotxt=txt.value.length;
		if (parseFloat(largotxt)!=parseFloat(largo)) { alert ('La longitud del campo es de '+largo+' posiciones!'); return false;	} else { return true;}
	} catch (err) { alert('error eve_particulares.validaLargo '+err.description); }
	}
	/**
	* funcion para validar la superfice de terreno vs construccion
	*/
	this.validaSuperficie = function()
	{
	try
	{
		var wlsuperficieterreno=document.getElementByID('wl_superficieterreno');
		var wlsuperficieconstruccion=document.getElementByID('wl_superficieconstruccion');
		if (parseFloat(wlsuperficieterreno.value)==0) 
		{
			alert ('La superfice de terreno no puede ser 0'); 
			wlsuperficieterreno.value='';
			wlsuperficieterreno.focus();
			return false;
		}
	} catch (err) { alert('error eve_particulares.validaSuperficie '+err.description); }
	} 
	/**
	* valida la fecha de escritura, sicop
	*/
	this.checafechaescritura = function()
	{
	try
	{	  
		this.checafecha(document.getElementByID('wl_fecha'));
	} catch (err) { alert('error   eve_particulares.checafechaescritura'+err.description); }		        			  
	}
	/**
	* valida la fecha del rpp, sicop
	*/
	this.checafecharpp = function()
	{
	try
	{	  
		this.checafecha(document.getElementByID('wl_fecha'));
	} catch (err) { alert('error   eve_particulares.checafecharpp'+err.description); }		        			  
	}
	/**
	* valida la fecha del convenio, sicop
	*/
	this.checafechaconvenio = function()
	{
	try
	{	  
		this.checafecha(document.getElementByID('wl_fecha'));
	} catch (err) { alert('error   eve_particulares.checafechaconvenio'+err.description); }		        			  
	}
	/**
	* valida la fecha del contrato, sicop
	*/
	this.checafechacontrato = function()
	{
	try
	{	  
		this.checafecha(document.getElementByID('wl_fecha'));
	} catch (err) { alert('error   eve_particulares.checafechacontrato'+err.description); }		        			  
	}
	/**
	* valida la fecha del contrato, sicop
	*/
	this.checafechadecreto = function()
	{
	try
	{	  
		this.checafecha(document.getElementByID('wl_fecha'));
	} catch (err) { alert('error   eve_particulares.checafechadecreto'+err.description); }		        			  
	}
	/**
	* valida la fecha de una visita, sicop
	*/
	this.checafechavisita = function()
	{
	try
	{	  
		this.checafecha(document.getElementByID('wl_fecha_visita'));
	} catch (err) { alert('error   eve_particulares.checafechavisita'+err.description); }		        			  
	}
	/**
	* valida las fechas de una asignacion, sicop
	*/
	this.checafechaasignacion = function()
	{
	try
	{	  
		wlfa=document.getElementByID('wl_fecha_asignacion');
		wlfv=document.getElementByID('wl_fecha_vigencia');
		this.checafecha(wlfa);
		this.checafechamenos(wlfv);
		if (wlfv.value.length && wlfa.value>=wlfv.value)
		{	alert ('la fecha de vigencia debe ser mayor a la fecha de asignacion');
			wlfv.value='';
			wlfv.focus();
			return false;	}
	} catch (err) { alert('error   eve_particulares.checafechaasignacion'+err.description); }		        			  
	}
	/**
	* valida las fechas del P.A.T.R, sicop
	*/
	this.checafechapatr = function()
	{
	try
	{	  
		wlfp=document.getElementByID('wl_fecha_patr');
		wlfv=document.getElementByID('wl_fecha_vigencia');
		this.checafecha(wlfp);
		this.checafechamenos(wlfv);
		if (wlfv.value.length && wlfp.value>=wlfv.value)
		{	alert ('la fecha de vigencia debe ser mayor a la fecha del P.A.T.R');
			wlfv.value='';
			wlfv.focus();
			return false;	}
	} catch (err) { alert('error   eve_particulares.checafechapatr'+err.description); }		        			  
	}
	/**
	* valida las fechas del avaluo, sicop
	*/
	this.checafechaavaluo = function()
	{
	try
	{	  
		wlfa=document.getElementByID('wl_fecha_avaluo');
		wlfr=document.getElementByID('wl_fecha_recepcion');
		this.checafecha(wlfa);
		this.checafecha(wlfr);
		if (wlfr.value.length && wlfa.value>=wlfr.value)
		{	alert ('la fecha de recepcion debe ser mayor a la fecha del avaluo');
			wlfr.value='';
			wlfr.focus();
			return false;	}
	} catch (err) { alert('error eve_particulares.checafechaavaluo'+err.description); }		        			  
	}
	/**
	* valida la fecha de un avaluo, sicop
	*/
	this.checafechaavaluoactu = function()
	{
	try
	{	  
		this.checafecha(document.getElementByID('wl_fecha_actualizacion'));
	} catch (err) { alert('error   eve_particulares.checafechaavaluoactu'+err.description); }		        			  
	}
	/**
	* valida las fechas de un prestamo, sicop
	*/
	this.checafechaprestamo = function()
	{
	try
	{	
		wlfp=document.getElementByID('wl_fecha_prestamo');  
		wlfv=document.getElementByID('wl_fecha_vigencia');  
		wlfd=document.getElementByID('wl_fecha_devolucion');  
		//this.checafecha(wlfp);
		this.checafechamenos(wlfv);
		this.checafecha(wlfd);
		if (wlfv.value.length && wlfp.value>=wlfv.value)
		{	alert ('la fecha de vigencia debe ser mayor a la fecha de prestamo');
			wlfv.value='';
			wlfv.focus();
			return false;	}
		if (wlfd.value.length && wlfp.value>=wlfd.value)
		{	alert ('la fecha de entrega debe ser mayor a la fecha de prestamo');
			wlfd.value='';
			wlfd.focus();
			return false;	}
	} catch (err) { alert('error   eve_particulares.checafechaprestamo'+err.description); }		        			  
	}
	/**
	* valida la fecha de un pago de patr, sicop
	*/
	this.checafechapagopatr = function()
	{
	try
	{	  
		this.checafecha(document.getElementByID('wl_fecha_pago'));
	} catch (err) { alert('error   eve_particulares.checafechapagopatr'+err.description); }		        			  
	}
	/**
	* valida que la fecha no sea mayor a la actual
	*/
	this.checafecha = function(x)
	{
	try
	{	  
		var fecha=x;
		if (fecha.value.length>0)
		{
			eventos_servidor("",0,"fechaServidor","","",document.body.clientWidth,document.body.clientHeight);
			if (req.responseText.indexOf("<fechaactual>") != -1)
			{
				var items = req.responseXML.getElementsByTagName("fechaactual");
				wlfecha=items.item(0).text
			}else{
					alert('No encontro la respuesta'); return false;
			}
		    if (fecha.value>wlfecha) { alert('La fecha no puede ser mayor a la actual'); fecha.value=''; fecha.focus(); return false; }
		}	  
	} catch (err) { alert('error   eve_particulares.checafecha'+err.description); }		        			  
	}
	/**
	* valida que la fecha no sea maenor a la actual
	*/
	this.checafechamenos = function(x)
	{
	try
	{	  
		var fecha=x;
		if (fecha.value.length>0)
		{
			eventos_servidor("",0,"fechaServidor","","",document.body.clientWidth,document.body.clientHeight);
			if (req.responseText.indexOf("<fechaactual>") != -1)
			{
				var items = req.responseXML.getElementsByTagName("fechaactual");
				wlfecha=items.item(0).text
			}else{
					alert('No encontro la respuesta'); return false;
			}
		    if (fecha.value<=wlfecha) { alert('La fecha tiene que ser mayor a la actual'); fecha.value=''; fecha.focus(); return false; }
		}	  
	} catch (err) { alert('error   eve_particulares.checafecha'+err.description); }		        			  
	}
	/**
	* activa el campo de gaceta, sicop
	*/
	this.activaGaceta = function()
	{
	try
	{	  
		wlidtipopublicacion=document.getElementByID('wl_idtipopublicacion');
		wlgaceta=document.getElementByID('wl_gaceta');
		if (wlidtipopublicacion.value==2)
		{
				
				wlgaceta.disabled=false;
				wlgaceta.className='';
		} else {
				wlgaceta.value='';
				wlgaceta.disabled=true;
				wlgaceta.className='lee';
		}
		
	} catch (err) { alert('error eve_particulares.activaGaceta'+err.description); }		        			  
	}
	/**
	* valida el campo de gaceta, sicop
	*/
	this.validaGaceta = function()
	{
	try
	{
		wlidtipopublicacion=document.getElementByID('wl_idtipopublicacion');
		wlgaceta=document.getElementByID('wl_gaceta');
		//alert (wlidtipopublicacion.value+' '+wlgaceta.value.length);
		if (wlidtipopublicacion.value==2 && wlgaceta.value.length==0)
		{	alert ('El campo "Gaceta" es obligatorio');
			wlgaceta.disabled=false;
			wlgaceta.className='';
			wlgaceta.focus();
			return false;
		} else if (wlidtipopublicacion.value!=2)
		{
			wlgaceta.value='';
			wlgaceta.disabled=true;
			wlgaceta.className='lee';
		}
	} catch (err) { alert('error eve_particulares.validaGaceta'+err.description); }
	}
	/**
	* activa los campos correspondientes para una peprsona moral
	*/
	this.validaCamposPersona = function()
	{
	try
	{
		wlnombre=document.getElementByID('wl_nombre');
		wlapepat=document.getElementByID('wl_apepat');
		wlapemat=document.getElementByID('wl_apemat');
		wlmoral=document.getElementByID('wl_moral');
		wldependencia=document.getElementByID('wl_dependencia');
		if (wlmoral.value=='t' || wldependencia.value=='t')
		{
			wlapepat.disabled=true;
			wlapepat.className='lee';
			wlapepat.value='';
			wlapemat.disabled=true;
			wlapemat.className='lee';
			wlapemat.value='';
		} else {
			wlapepat.disabled=false;
			wlapepat.className='';
			wlapemat.disabled=false;
			wlapemat.className='';
		}
	} catch (err) { alert('error eve_particulares.validaCamposPersona'+err.description); }
	}
	/**
	* valida que la medida correspondea a una visita fiscia
	*/
	this.validaSubVisita = function()
	{
	try
	{
		wlvisita=document.getElementByID('wl_idvisita');
		if (wlvisita.value=='0' || wlvisita.value=='')
		{
			alert ('La medida no corresponde a una visita');
			return false;
		}
		
	} catch (err) { alert('error eve_particulares.validaSubVisita'+err.description); }
	}
	/**
	* valida si el documento que se registra es original
	*/
	this.validaOriginal = function()
	{
	try
	{
		wloriginal=document.getElementByID('wl_original');
		if (wloriginal.value=='f')
		{	
			if(window.confirm('Desea marcar como origial de documento que intenta registrar?'))
			{
				wloriginal.value='t';
			}	else	{
				wloriginal.value='f';
			}			
		}
	} catch (err) { alert('error eve_particulares.validaOriginal'+err.description); }
	}
	/**
	* valida el alta de un decreto
	*/
	this.validaAltaDecreto = function()
	{
	try
	{
		if(this.validaGaceta()==false) {return false;};
		//this.validaOriginal();
		this.limpiaFechaUsuario();
	} catch (err) { alert('error eve_particulares.validaAltaDecreto'+err.description); }
	}
	/**
	* valida el alta de un contrato
	*/
	this.validaAltaContrato = function()
	{
	try
	{
		//this.validaOriginal();
		this.limpiaFechaUsuario();
	} catch (err) { alert('error eve_particulares.validaAltaContrato'+err.description); }
	}
	/**
	* me dal el valor de un valor avaluo
	*/
	this.dameValorAvaluo = function()
	{
	try
	{
		wlsuperficie=document.getElementByID('wl_superficie');
		wlnumero=document.getElementByID('wl_numero');
		wlvaloru=document.getElementByID('wl_valor_unitario');
		wlvalor=document.getElementByID('wl_valor');
		if (wlvaloru.value<1) {alert ('El campo "Valor unitario" tiene que ser mayor a 0'); wlvaloru.focus(); return false;}
		//if (wlnumero.value==0) {alert ('El campo "Numero" no puede ser 0'); wlnumero.focus(); return false;}
		
		wlvalor.value=((wlsuperficie.value>0) ? wlsuperficie.value : '1')*((wlnumero.value>0) ? wlnumero.value : '1')*wlvaloru.value;
		
	} catch (err) { alert('error eve_particulares.dameValorAvaluo'+err.description); }
	}
	/**
	*	valida la extencion del un archivo
	*/
	this.validaExtArchivo = function()
	{
	try
	{	
		wlarchivo=document.getElementByID('wl_archivo');
		wlidtipoarchivo=document.getElementByID('wl_idtipoarchivo');
		if (wlidtipoarchivo.value=='') {	alert('Primero seleccione el tipo de archivo'); return false; wlarchivo.value='';wlidtipoarchivo.focus(); return false;	}
		wlarray=wlarchivo.value.split('.');
		wlext=wlarray['1'];
		eventos_servidor("",0,"validaExtArchivo","","",document.body.clientWidth,document.body.clientHeight);
		if (req.responseText.indexOf("<enviavalor>") != -1)
		{	var items = req.responseXML.getElementsByTagName("enviavalor");
			//alert ('-'+wlext+'-'+items.item(0).text+'-'); 
			if (items.item(0).text=='t' && wlext!='jpg' )
			{	alert('El archivo que intenta adjuntar no es una imagen'); return false; wlarchivo.value='';wlarchivo.focus();	}
		}
	} catch (err) { alert('error eve_particulares.validaExtArchivo'+err.description); }
	}
	/**
	*	valida el alta de un archivo adjunto
	*/
	this.validaAltaAdjunto = function()
	{
	try
	{	this.limpiaFechaUsuario();
		if(this.validaExtArchivo()==false) {return false;};
	} catch (err) { alert('error eve_particulares.validaAltaAdjunto'+err.description); }
	}
	/**
	*	valida el cambio de un archivo adjunto
	*/
	this.validaCambioAdjunto = function()
	{
	try
	{
		if(this.validaMovtoUsuario()==false) {return false;};
		if(this.validaExtArchivo()==false) {return false;};
	} catch (err) { alert('error eve_particulares.validaCambioAdjunto'+err.description); }
	}
	/**
	*	valida el el numero de fojas devueltas contra las prestadas
	*/
	this.validaFojas = function()
	{
	try
	{
		wlfojasp=document.getElementByID('wl_fojas_prestamo');
		wlfojasd=document.getElementByID('wl_fojas_devolucion');
		wlestatus=document.getElementByID('wl_idestatus');
		if (wlfojasp.value<wlfojasd.value && wlfojasd.value!='') {	alert ('El numero de fojas devueltas no puede ser mayor numero de fojas prestadas'); wlfojasd.value=''; return false;}
		if (wlfojasp.value==wlfojasd.value && wlfojasd.value!='') {	wlestatus.value=2;}
		if (wlfojasp.value>wlfojasd.value && wlfojasd.value!='') {	wlestatus.value=3;}
	} catch (err) { alert('error eve_particulares.validaFojas'+err.description); }
	}
	/**
	*	regresa el nuevo DGPI despues de un cambio
	*/
	this.dameInventario = function()
	{
	try
	{
		//alert ('entro clinte');
		wlinventario=document.getElementByID('wl_inventario');
		eventos_servidor("",0,"dameInventario","","",document.body.clientWidth,document.body.clientHeight);
		if (req.responseText.indexOf("<enviavalor>") != -1)
		{	var items = req.responseXML.getElementsByTagName("enviavalor");
			wlinventario.value=items.item(0).text;
		}
	} catch (err) { alert('error eve_particulares.dameInventario'+err.description); }
	}
	/**
	*	valida el si el tramite lleva numero de oficio
	*/
	this.validaOficio = function()
	{
	try
	{
		wlreferencia=document.getElementByID('wl_referencia');
		wloficio=document.getElementByID('wl_oficio');
		if (wloficio.value=='f') 
		{
			wlreferencia.disabled=true;
			wlreferencia.className='lee';
			wlreferencia.value='S/N';
		}	else	{
			wlreferencia.disabled=false;
			wlreferencia.className='';
		}
		
	} catch (err) { alert('error eve_particulares.validaOficio'+err.description); }
	}
	/**
	*	valida el si el domicilio lleva numero interior
	*/
	this.validaInterior = function()
	{
	try
	{
		wlconint=document.getElementByID('wl_conint');
		wlnumint=document.getElementByID('wl_numint');
		if (wlconint.value=='f') 
		{
			wlnumint.disabled=true;
			wlnumint.className='lee';
			wlnumint.value='S/N';
		}	else	{
			wlnumint.disabled=false;
			wlnumint.value='';
			wlnumint.className='';
		}
		
	} catch (err) { alert('error eve_particulares.validaInterior'+err.description); }
	}
	/**
	*	valida el si el domicilio lleva numero interior
	*/
	this.validaExterior = function()
	{
	try
	{
		wlconext=document.getElementByID('wl_conext');
		wlnumext=document.getElementByID('wl_numext');
		if (wlconext.value=='f') 
		{
			wlnumext.disabled=true;
			wlnumext.className='lee';
			wlnumext.value='S/N';
		}	else	{
			wlnumext.disabled=false;
			wlnumext.value='';
			wlnumext.className='';
		}
		
	} catch (err) { alert('error eve_particulares.validaExterior'+err.description); }
	}	
	/**
	* valida las fechas de un reporte, contra
	*/
	this.checafechareporte = function()
	{
	try
	{	
		wlfri=document.getElementByID('wl_fecharecibo');  
		wlfrf=document.getElementByID('wl_fecharecibo_fin');  
		this.checafecha(wlfri);
		this.checafecha(wlfrf);
		if (wlfrf.value.length && wlfrf.value<wlfri.value)
		{	alert ('La fecha de recepcion final no puede ser menor a la fecha inicial');
			wlfrf.value='';
			wlfrf.focus();
			return false;	}
	} catch (err) { alert('error   eve_particulares.checafechareporte'+err.description); }		        			  
	}
	/**
	* valida los campos obligatorios de un reporte
	*/
	this.validaObligatoriosReporte = function()
	{
	try
	{	
		wlidtiporeporte=document.getElementByID('wl_idtiporeporte');
		wlpuesto=document.getElementByID('wl_id_puesto');
		//wlpersona=document.getElementByID('wl_id_persona');
		//alert ('wlidtiporeporte:'+wlidtiporeporte.value+', wlpuesto:'+wlpuesto.value+', wlpersona'+wlpersona.value);
		if (wlidtiporeporte.value==2)
		{
				if (wlpuesto.value=='') {alert ('El campo puesto es obligatorio para el tipo de reporte seleccionado'); wlpuesto.focus(); return false;}
				//if (wlpersona.value=='') {alert ('El campo persona es obligatorio para el tipo de reporte seleccionado'); wlpersona.focus(); return false;}
		}
	} catch (err) { alert('error   eve_particulares.validaObligatoriosReporte'+err.description); }		        			  
	}
	/**
	* valida los campos obligatorios de un reporte
	*/
	this.validaCatastro = function()
	{
	try
	{
			rg=document.getElementByID('wl_reg');
			mz=document.getElementByID('wl_mz');
			lt=document.getElementByID('wl_lote');
			cd=document.getElementByID('wl_condominio');
			dv=document.getElementByID('wl_dv');
			cat=document.getElementByID('wl_ctacatastral');
			pre=document.getElementByID('wl_ctapredial');
			
			/*if (rg.value.length) {	if (this.revisaNumerico (rg)==false) {rg.focus(); return false;}
									if (this.validaLargo(rg,'3')==false) {rg.value=''; rg.focus(); return false;}
									if(parseFloat(rg.value)==0) {alert ('La región tiene que ser mayor a cero'); rg.value=''; rg.focus(); return false;}
			}
			if (mz.value.length) {	if (this.revisaNumerico (mz)==false) {mz.focus(); return false;}
									if (this.validaLargo(mz,'3')==false) {mz.value=''; mz.focus(); return false;}
									if(parseFloat(rg.value.length)==0) {alert ('Primero digite la región'); mz.value=''; mz.focus(); return false;}
									if(parseFloat(mz.value)==0) {alert ('La manzana tiene que ser mayor a cero'); mz.value=''; mz.focus(); return false;}
			}
			if (lt.value.length) {	if (this.revisaNumerico (lt)==false) {lt.focus(); return false;}
									if (this.validaLargo(lt,'2')==false) {lt.value=''; lt.focus(); return false;}
									if(parseFloat(mz.value.length)==0) {alert ('Primero digite la manzana'); lt.value=''; lt.focus(); return false;}
									if(parseFloat(lt.value)==0) {alert ('El lote tiene que ser mayor a cero'); lt.value=''; lt.focus(); return false;}
			}
			if (cd.value.length) {	if (this.revisaNumerico (cd)==false) {cd.focus(); return false;}
									if (this.validaLargo(cd,'3')==false) {cd.value=''; cd.focus(); return false;}
									if(parseFloat(lt.value.length)==0) {alert ('Primero digite el lote'); cd.value=''; cd.focus(); return false;}
									if(parseFloat(cd.value)==0) {alert ('El lote tiene que ser mayor a cero'); cd.value=''; cd.focus(); return false;}
			}*/
			
			cat.value=rg.value+''+mz.value+''+lt.value;
			pre.value=rg.value+''+mz.value+''+lt.value+''+cd.value;
			//alert (pre.value);
			dv.value=this.dameDV(pre);
			pre.value=rg.value+''+mz.value+''+lt.value+''+cd.value+''+dv.value;
			
	} catch (err) { alert('error   eve_particulares.validaCatastro'+err.description); }
	}
	/**
	* regresa el digito verificador de una cuenta
	*/
	this.dameDV = function(x)
	{
	try
	{
		var dv=0;
		var dvok=0;
		var dvok2=0;
		var dvok3=0;
		var dmul=2;
		str=x.value;
    	if (str.length && str.length==11)
    	{
			for (var i = 0; i < 10; i++)
			{
				var ch=str.substring(i, i + 1);
				dv=( ch * dmul );
				if (dv>9)
				{	var n1=dv.toString().substring(0,1);
					var n2=dv.toString().substring(1,2);
					dv=(parseFloat(n1)+parseFloat(n2));	}
				dvok=( dvok	 + dv );
				if (dmul==2)
				{	dmul=1;	}
				else
				{	dmul=2;	}
			}
			if (dvok.toString().length==2)
			{	diven=dvok.toString().substring(2,1); diven=10-diven; }
			else
			{ diven=10-dvok; }
			if (diven == 10)
			{ diven=0 } 
			return diven;
		} else { return ''; }
	} catch (err) { alert('error   eve_particulares.dameDV'+err.description); }
	}
	/**
	* activa anio de referencia
	*/
	this.activaAnioReferencia = function()
	{
	try
	{
		wltiporef=document.getElementByID('wl_id_tiporef');
		wlvalor=document.getElementByID('wl_valor');
		wlanio=document.getElementByID('wl_anio');
		if (wltiporef.value=='4')
		{
			//alert (wltiporef.value+' '+wlanio.value.length+' '+wlvalor.value.length);
			wlanio.disabled=false;
			wlanio.className='';
			if (wlanio.value.length)
			{
				//alert (wlanio.value);
				if (this.revisaNumerico (wlanio)==false) {wlanio.focus(); return false;}	
				if (this.validaLargo (wlanio,'4')==false) {wlanio.focus(); return false;}
			}
			if (wlvalor.value.length)
			{
				//alert (wlvalor.value);
				if (this.revisaNumerico (wlvalor)==false) {wlvalor.focus(); return false;}	
			}
		}	else	{
			wlanio.disabled=true;
			wlanio.className='lee';
			wlanio.value='';
		}
		
		
	} catch (err) { alert('error eve_particulares.activaAnioReferencia'+err.description); }			
	}
	/**
	* valida el alta de una referencia
	*/
	this.validaAltaReferencia = function()
	{
	try
	{
		wltiporef=document.getElementByID('wl_id_tiporef');
		wlvalor=document.getElementByID('wl_valor');
		wlanio=document.getElementByID('wl_anio');
		if (wltiporef.value==4)
		{	
			this.activaAnioReferencia();
			eventos_servidor("",0,"fechaServidor","","",document.body.clientWidth,document.body.clientHeight);
			if (req.responseText.indexOf("<fechaactual>") != -1)
			{
				var items = req.responseXML.getElementsByTagName("fechaactual");
				wlfecha=items.item(0).text.split('-');
			}else{
					alert('No encontro la respuesta'); return false;
			}
			var wlanios=wlfecha[0];
			if (wlanio.value=='')
			{	alert ('El año es obligatorio para el tipo de referencia selecionado'); wlanio.focus(); return false;	}
			if (wlanio.value<2011 || wlanio.value>wlanios)
			{	alert ('El año esta fuera de rengo, rango: 2011 - '+wlanios); wlanio.focus(); return false;	}
		}
		if (wltiporef.value==4 && wlvalor.value!='' && wlanio.value!='')
		{			
			eventos_servidor("",0,"validaAltaReferencia","","",document.body.clientWidth,document.body.clientHeight);
			if (req.responseText.indexOf("<enviavalor>") != -1)
			{	var items = req.responseXML.getElementsByTagName("enviavalor");
				if (items.item(0).text==0) { alert ('El folio no existe en el sistema'); wlvalor.focus(); return false; } }

			eventos_servidor("",0,"validaAltaReferenciaCerrado","","",document.body.clientWidth,document.body.clientHeight);
			if (req.responseText.indexOf("<enviavalor>") != -1)
			{	var items = req.responseXML.getElementsByTagName("enviavalor");
				if (items.item(0).text>0) { alert ('El folio se encuentra cerrado'); wlvalor.focus(); return false; } }
		}
		this.limpiaFechaUsuario();
	} catch (err) { alert('error   eve_particulares.validaAltaReferencia'+err.description); }			
	}
	/**
	* valida el alta de una referencia interna
	*/
	this.validaAltaReferenciaInterno = function()
	{
	try
	{
		wltiporef=document.getElementByID('wl_id_tiporef');
		wlvalor=document.getElementByID('wl_valor');
		wlanio=document.getElementByID('wl_anio');
		if (wltiporef.value==4)
		{	
			this.activaAnioReferencia();
			eventos_servidor("",0,"fechaServidor","","",document.body.clientWidth,document.body.clientHeight);
			if (req.responseText.indexOf("<fechaactual>") != -1)
			{
				var items = req.responseXML.getElementsByTagName("fechaactual");
				wlfecha=items.item(0).text.split('-');
			}else{
					alert('No encontro la respuesta'); return false;
			}
			var wlanios=wlfecha[0];
			if (wlanio.value=='')
			{	alert ('El año es obligatorio para el tipo de referencia selecionado'); wlanio.focus(); return false;	}
			if (wlanio.value<2011 || wlanio.value>wlanios)
			{	alert ('El año esta fuera de rengo, rango: 2011 - '+wlanios); wlanio.focus(); return false;	}
		}
		if (wltiporef.value==4 && wlvalor.value!='' && wlanio.value!='')
		{			
			eventos_servidor("",0,"validaAltaReferenciaInterno","","",document.body.clientWidth,document.body.clientHeight);
			if (req.responseText.indexOf("<enviavalor>") != -1)
			{	var items = req.responseXML.getElementsByTagName("enviavalor");
				if (items.item(0).text==0) { alert ('El folio no existe en el sistema'); wlvalor.focus(); return false; } }

			eventos_servidor("",0,"validaAltaReferenciaCerradoInterno","","",document.body.clientWidth,document.body.clientHeight);
			if (req.responseText.indexOf("<enviavalor>") != -1)
			{	var items = req.responseXML.getElementsByTagName("enviavalor");
				if (items.item(0).text>0) { alert ('El folio se encuentra cerrado'); wlvalor.focus(); return false; } }
		}
		this.limpiaFechaUsuario();
	} catch (err) { alert('error   eve_particulares.validaAltaReferenciaInterno'+err.description); }			
	}
	/**
	* valida los folios relacionados que se van a cerrar con el cierre del tramite.
	*/
	this.cierraGestion = function()
	{
	try
	{	
		eventos_servidor("",0,"dameReferencias","","",document.body.clientWidth,document.body.clientHeight);
		if (req.responseText.indexOf("<enviavalor>") != -1)
		{	var items = req.responseXML.getElementsByTagName("enviavalor"); }
		if (items.item(0).text!='')
		{
			if(window.confirm('Desea cerrar los folios relaciondos: '+items.item(0).text))
			{ 	} else { return false; }
		}
	} catch (err) { alert('error   eve_particulares.cierraGestion'+err.description); }
	}
	/**
	* valida los folios relacionados que se van a cerrar con el cierre del tramite interno.
	*/
	this.cierraGestionInterno = function()
	{
	try
	{	
		eventos_servidor("",0,"dameReferenciasInterno","","",document.body.clientWidth,document.body.clientHeight);
		if (req.responseText.indexOf("<enviavalor>") != -1)
		{	var items = req.responseXML.getElementsByTagName("enviavalor"); }
		if (items.item(0).text!='')
		{
			if(window.confirm('Desea cerrar los folios relaciondos: '+items.item(0).text))
			{ 	} else { return false; }
		}
	} catch (err) { alert('error   eve_particulares.cierraGestionInterno'+err.description); }
	}
	/**
	* funcion para limpiar una forma
	*/
	this.limpiaForma = function()
	{
	try
	{
		//alert ('entro');
		formReset("formpr","t");
	} catch (err) { alert('error   eve_particulares.limpiaForma '+err.description); }
	}
	/**
	* funcion para actualizar el oficio de contestacion
	*/
	this.actualizaOficio = function()
	{
	try
	{
		wlreferencia_c=document.getElementByID('wl_referencia_c');
		eventos_servidor("",0,"actualizaOficio","","",document.body.clientWidth,document.body.clientHeight);
		if (req.responseText.indexOf("<enviavalor>") != -1)
		{	var items = req.responseXML.getElementsByTagName("enviavalor"); }
		if (items.item(0).text!='')
		{
			wlreferencia_c.value=items.item(0).text;
		}
	} catch (err) { alert('error   eve_particulares.actualizaOficio '+err.description); }
	}

	/**
	* valida la fecha de agenda, agenda
	*/
	this.checaFechaAgenda = function()
	{
	try
	{	  
		var fecha=document.getElementByID('wl_fecha');
		if (fecha.value.length)
		{
			eventos_servidor("",0,"fechaServidor","","",document.body.clientWidth,document.body.clientHeight);
			if (req.responseText.indexOf("<fechaactual>") != -1)
			{
				var items = req.responseXML.getElementsByTagName("fechaactual");
				wlfecha=items.item(0).text
			}else{
					alert('No encontro la respuesta'); return false;
			}
		    if (fecha.value<wlfecha) {  alert('La fecha no puede ser menor a la actual'); fecha.value=''; fecha.focus(); return false; }
		}
	} catch (err) { alert('error   eve_particulares.checaFechaAgenda'+err.description); }		        			  
	}
	/**
	* Libera un tramite de forma automatica despues de realizar el registro de un turno
	*/
	this.liberaTurno = function()
	{
	try
	{
		if (window.confirm('Desea liberar el tramite?'))
			{
				eventos_servidor("",0,"liberatramite","","",document.body.clientWidth,document.body.clientHeight);
				self.close();
				parent.close();
			} else {
				document.getElementByID('iLimpiar').click();
				document.getElementByID('formpr').focus();
			}
	} catch (err) { alert('error   eve_particulares.liberaTurno'+err.description); }		        			  
	}
}

