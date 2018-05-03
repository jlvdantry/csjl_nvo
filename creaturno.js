var isIE = '\v'=='v';
var req;
var wlurl;
 var  timerID = null;
 var  passData = null;


function CargaXMLDoc(x)
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
                        querespuesta(x);
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
var llamados=0;
var posicion=0;


function hayquellamar()
{
         for (var i = 0; i < arr.length; i++) {
            posicion=arr[i] % document.getElementById("turnos").innerHTML;
            if (posicion==0) { posicion=document.getElementById("turnos").innerHTML; }
            if (creallamado(msga[i].fila,msga[i].turnogrupo,msga[i].desmodulo,arr[i],posicion,msga[i].comentarios))
            {
                    jsWebClientPrint.print('useDefaultPrinter=' + '' + '&printerName=null&fila='+msga[i].fila+'&turnogrupo='+msga[i].turnogrupo+'&wl_desmodulo='+msga[i].desmodulo);
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
        hayquellamar();
}
/*
function llamaturno(evt)
    alert('llama turno'); 
}
*/
function marcaturno(id)
{
    document.getElementById("rect0").style.display="none";
    try {
        wlurl='eventos_servidor.php';
        wlmodulo=document.getElementById("modulo").innerHTML;
        wldesmodulo=document.getElementById("desmodulo").innerHTML;
        passData='&opcion=creaturno&wl_idgrupo='+wlmodulo+'&wl_desmodulo='+wldesmodulo;
        CargaXMLDoc(id);
   } catch(err) { document.getElementById("rect0").style.display="block"; }
}

function borrallamado(llamados,posicion)
{
        document.getElementById("rect0").style.display="block";
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
        rect.style.stroke="#DF1874";
        rect.style.strokeWidth=3;
        rect.appendChild(ani);
        t.appendChild(svg);
        var svg0=document.getElementById("svg0");
        svg0.style.zIndex="";

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


function creallamado(wlfila,wlturno,wlgrupo,llamados,posicion,wlcomentario)
{
        var div=document.getElementById("llamado");
        div.style.visibility="visible";

        document.body.appendChild(div);
        var player=document.getElementById("wlaudio");
        var svg0=document.getElementById("svg0");
        svg0.style.zIndex=-1;
        player.currentTime = 0;

        var svg=document.createElementNS("http://www.w3.org/2000/svg","svg");
        var g=document.createElementNS("http://www.w3.org/2000/svg","g");
        var rect=document.createElementNS("http://www.w3.org/2000/svg","rect");
        rect.setAttribute("x","0");
        rect.setAttribute("y","0");
        rect.setAttribute("width","100%");
        rect.setAttribute("height","100%");
        rect.setAttribute("rx","10");
        rect.setAttribute("class","creado");
        rect.setAttribute("id","rect"+llamados);

        g.appendChild(rect);
        var fila=document.createElementNS("http://www.w3.org/2000/svg","text");
        fila.setAttribute("x","5%");
        fila.setAttribute("y","30%");
        fila.setAttribute("class","fila")
        fila.textContent = wlfila ;


        var turno=document.createElementNS("http://www.w3.org/2000/svg","text");
        turno.setAttribute("x","40%");
        turno.setAttribute("y","30%");
        turno.setAttribute("class","turno")
        turno.textContent = wlturno ;

        var comentario=document.createElementNS("http://www.w3.org/2000/svg","text");
        comentario.setAttribute("x","5%");
        comentario.setAttribute("y","75%");
        comentario.setAttribute("class","comentario")
        comentario.textContent = wlcomentario ;


        document.body.appendChild(player);
        g.appendChild(fila);
        g.appendChild(turno);
        g.appendChild(comentario);
        svg.setAttribute("id","svg");
        svg.appendChild(g);
        div.appendChild(svg);
        player.play();

        timerID = setTimeout("borrallamado(" + llamados + "," + posicion + ")", 2000);
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
p
              var t=document.getElementById("mensaje");
         //     t.textContent=men + " " + wlmensaje;
}
function querespuesta(x)
{
 try
 {
    if (req.readyState == 4)
    {
        window.status='req.readyState'+req.readyState+' req.status='+req.status;
        if (req.status == 200)
        {
           var wlfila=''; var wlturno=''; var wlgrupo='';
           if (req.responseText.indexOf("Creo turno") != -1)
           {
              var items = req.responseXML.getElementsByTagName("_fila_");
              try { wlfila=' Fila ' + items[0].childNodes[0].nodeValue; } catch (err) { };
              var items = req.responseXML.getElementsByTagName("_turnogrupo_");
              try { wlturno=' Turno ' + items[0].childNodes[0].nodeValue; } catch (err) { };
              var items = req.responseXML.getElementsByTagName("_desmodulo_");
              try { wlgrupo=items[0].childNodes[0].nodeValue + " "; } catch (err) { };
              var items = req.responseXML.getElementsByTagName("_comentarios_");
              try { wlcomentarios=items[0].childNodes[0].nodeValue + " "; } catch (err) { wlcomentarios="Sin comentarios"; };
              msgx = { "fila" : wlfila, "turnogrupo" : wlturno, "desmodulo" : wlgrupo , "comentarios" : wlcomentarios};
              llamados=llamados+1;
              arr.push(llamados);
              msga.push(msgx);
              return;
           }

           var men="Error1="+req.responseText;
              msgx = { "fila" : men, "turnogrupo" : "", "desmodulo" : "" };
              llamados=llamados+1;
              arr.push(llamados);
              msga.push(msgx);
              mensaje(men,req);
        }
        else
        {
           var men="Error2="+req.responseText;
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
