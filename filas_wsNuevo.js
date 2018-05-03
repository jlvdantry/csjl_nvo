var isIE = '\v'=='v';
var req;
var wlurl;
 var  timerID = null;
 var  passData = null;


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
           var men="Error en que respuesta="+err.description+' wlurl'+wlurl+' '+passData;
           mensaje(men,req);
        }
}

function log(msg){ $("log").innerHTML+="<br>"+msg; }
function $(id){ return document.getElementById(id); }
var arr=[];
var msga=[];
var msg="";

function hayquellamar()
{
         for (var i = 0; i < arr.length; i++) {
            posicion=arr[i] % document.getElementById("turnos").innerHTML;
            if (posicion==0) { posicion=document.getElementById("turnos").innerHTML; }
            if (creallamado(msga[i].fila,msga[i].turnogrupo,msga[i].desmodulo,arr[i],posicion))
            {
                    marcallamado(msga[i].id);
            }
            arr.splice(i,1);
            msga.splice(i,1);
         };
         timerID = setTimeout("hayquellamar()", 2000);
}

function empieza(fi,co)
{
        try { top.document.getElementById("izquierdo").style.height=0; top.document.getElementById("izquierdo").style.width=0; } catch (err) { }
        try { top.document.getElementById("titulos").style.height=0; top.document.getElementById("titulos").style.width=0; } catch (err) { }
        try { top.document.getElementById("derecho").style.width="100%"; } catch (err) { }
        try { top.document.getElementById("dderecho").style.height="100%"; } catch (err) { }
        //try { x=top.document.getElementById("derecho");        x.setAttribute("width","100%"); } catch (err) { }
        //var host = "ws://189.135.61.84:9001"; // SET THIS TO YOUR SERVER
        var host = "ws://"+location.hostname+":9001"; // SET THIS TO YOUR SERVER
        if (location.hostname=="187.141.41.182")
        { host = "ws://187.141.41.183:9001"; }
        try {
                socket = new WebSocket(host);
                var llamados=0;
                var posicion=0;
                log('WebSocket - status '+socket.readyState);
                socket.onopen    = function(msg) {
                                                           cone=document.getElementById("conexionimg");
                                                           cone.setAttribute("src","img/connection_established.png");
                                                           cone.setAttribute("width","3%");
                                                           cone.setAttribute("height","3%");
                                                           log("Bienvenido - status "+this.readyState+" msg="+msg);
                                                           msg = {
                                                               type: 'tablero',
                                                               modulo: document.getElementById("modulo").innerHTML
                                                                };
                                                           socket.send(JSON.stringify(msg));
                                                           hayquellamar();
                                                   };
                socket.onmessage = function(msg) {
                                                           log("Recibido: "+msg.data);
                                                           msg = JSON.parse(msg.data);
                                                           llamados=llamados+1;
                                                           arr.push(llamados);
                                                           msga.push(msg);
                                                   };
                socket.onclose   = function(msg) {
                                                           cone=document.getElementById("conexionimg");
                                                           cone.setAttribute("src","img/disconnect.png");
                                                           log("Desconectado - status "+this.readyState+" Servidor:"+host);
                                                           window.location.reload()
                                                   };
        }
        catch(ex){
                log(ex);
        }
}


function marcallamado(id)
{
        wlurl='eventos_servidor.php';
        wlmodulo=document.getElementById("modulo").innerHTML;
        passData='&opcion=marcallamado&id='+id;
        CargaXMLDoc();
}

function muestralog()
{
   log1=document.getElementById("log");
   if (log1.style.display=='none' || log1.style.display=='')
   { 
       log1.style.display='block'; 
       log1.style.visibility=''; 
       log1.style.width='80%'; 
       log1.style.height='80%'
       // log.setAttribute("style","display:'block';vibility:'';width:'80%';height:'80%'");
        //rect.setAttribute("height","80%");

   }
   else { log1.style.display='none'; }
}

function borrallamado(llamados,posicion)
{
        var t=document.getElementById("llamado");
        t.style.visibility="hidden";
        var ss=document.getElementById("svg_"+posicion);
        if (ss)
        { ss.parentNode.removeChild(ss); }
        var svg=document.getElementById("svg");
        svg.setAttribute("id","svg_"+posicion);
        t=document.getElementById("div"+posicion);
        var rect=document.getElementById("rect"+llamados);
        var ani=document.createElementNS("http://www.w3.org/2000/svg","animate");
        ani.setAttribute("attributeName","stroke-width");
        ani.setAttribute("to","12");
        ani.setAttribute("dur","1s");
        ani.setAttribute("repeatCount","indefinite");
        ani.setAttribute("id","ani_"+llamados);
        rect.style.stroke="black";
        rect.style.strokeWidth=3;
        rect.appendChild(ani);
        t.appendChild(svg);

        if (llamados>1)
        {
          var rect1=document.getElementById("rect"+(llamados-1));
          rect1.style.stroke="";
          rect1.style.strokeWidth=0;
          var ani1=document.getElementById("ani_"+(llamados-1));
          ani1.parentNode.removeChild(ani1);
        }
        log('termino borrallamado');
}

function habla(wlfila,wlturno)
{
   if ('speechSynthesis' in window) {
      var msg = new SpeechSynthesisUtterance();
      var voices = window.speechSynthesis.getVoices();
      msg.voice = voices[3]; // Note: some voices don't support altering params
      msg.voiceURI = 'native';
      msg.volume = 1; // 0 to 1
      msg.rate = 1; // 0.1 to 10
      msg.pitch = 2; //0 to 2
      msg.text = wlfila+' '+wlturno;
      msg.lang = 'es-MX';
      speechSynthesis.speak(msg);
   }
}

function creallamado(wlfila,wlturno,wlgrupo,llamados,posicion)
{
        var div=document.getElementById("llamado");
        div.style.visibility="visible";

        document.body.appendChild(div);
        var player=document.getElementById("wlaudio");
        player.currentTime = 0;

        var svg=document.createElementNS("http://www.w3.org/2000/svg","svg");
        var g=document.createElementNS("http://www.w3.org/2000/svg","g");
        var rect=document.createElementNS("http://www.w3.org/2000/svg","rect");
        rect.setAttribute("x","0");
        rect.setAttribute("y","0");
        rect.setAttribute("width","100%");
        rect.setAttribute("height","100%");
        rect.setAttribute("rx","10");
        rect.setAttribute("class","llama_rect");
        rect.setAttribute("id","rect"+llamados);

        g.appendChild(rect);
        var fila=document.createElementNS("http://www.w3.org/2000/svg","text");
        fila.setAttribute("x","5%");
        fila.setAttribute("y","25%");
        fila.setAttribute("class","fila")
        fila.textContent = wlfila ;


        var turno=document.createElementNS("http://www.w3.org/2000/svg","text");
        turno.setAttribute("x","5%");
        turno.setAttribute("y","75%");
        turno.setAttribute("class","turno")
        turno.textContent = wlturno ;


        document.body.appendChild(player);
        g.appendChild(fila);
        g.appendChild(turno);
        svg.setAttribute("id","svg");
        svg.appendChild(g);
        div.appendChild(svg);
        if (player.innerHTML.indexOf("texto")==-1)
        { player.play(); } else { habla(wlfila,wlturno); }

        //borrallamado();
        timerID = setTimeout("borrallamado(" + llamados + "," + posicion + ")", 2000);
        //borrallamado(llamados,posicion);
        log('termino creallamado');
        return true;
}

function mensaje(men,wlreq)
{
              var wlmensaje="";
              if (wlreq.responseXML)
              {
              var items = wlreq.responseXML.getElementsByTagName("_fecha_");
              try { wlmensaje= items[0].childNodes[0].nodeValue; } catch (err) { wlmensaje='' };
              }
              var t=document.getElementById("mensaje");
         //     t.textContent=men + " " + wlmensaje;
}
function querespuesta()
{
 try
 {
    if (req.readyState == 4)
    {
        window.status='req.readyState'+req.readyState+' req.status='+req.status;
        if (req.status == 200)
        {
           var wlfila=''; var wlturno=''; var wlgrupo='';
           if (req.responseText.indexOf("Encontro turno") != -1)
           {
              var items = req.responseXML.getElementsByTagName("fila");
              try { wlfila=' Fila ' + items[0].childNodes[0].nodeValue; } catch (err) { };
              var items = req.responseXML.getElementsByTagName("turnogrupo");
              try { wlturno=' Turno ' + items[0].childNodes[0].nodeValue; } catch (err) { };
              var items = req.responseXML.getElementsByTagName("grupo");
              try { wlgrupo=items[0].childNodes[0].nodeValue + " "; } catch (err) { };
              creallamado(wlfila,wlturno,wlgrupo);
              return;
           }
           if (req.responseText.indexOf("Encontro cita") != -1)
           {
              var items = req.responseXML.getElementsByTagName("fila");
              try { wlfila=' Fila ' + items[0].childNodes[0].nodeValue; } catch (err) { };
              var items = req.responseXML.getElementsByTagName("idcita");
              try { wlturno=' Cita ' + items[0].childNodes[0].nodeValue; } catch (err) { };
              var items = req.responseXML.getElementsByTagName("grupo");
              try { wlgrupo=items[0].childNodes[0].nodeValue; } catch (err) { };
              creallamado(wlfila,wlturno,wlgrupo);
              return;
           }

           if (req.responseText.indexOf("No se encontraron turnos ") != -1)
           {
              var men='No se encontraron turnos a ser llamado ';
              mensaje(men,req);
              timerID = setTimeout ("revisa()", 1000); return;
           }
           var men="No esta progamada la respuesta que envia el servidor="+req.responseText;
           mensaje(men,req);
        }
        else
        {
           var men="No esta progamada la respuesta que envia el servidor="+req.responseText;
           mensaje(men,req);
        }
    }
  }
  catch (err)
  {
           var men="Error en que respuesta="+err.description;
           mensaje(men,req);
  }
}
