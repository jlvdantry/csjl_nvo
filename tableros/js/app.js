(function() {
  'use strict';
if ('serviceWorker' in navigator) {
    navigator.serviceWorker
             .register('service-worker.js')
             .then(function() { console.log('[app.js] Service Worker Registered'); });
  }
})();

/* actualiza el estatus en el cliente */
navigator.serviceWorker.addEventListener("message", function (event) {
	var data = event.data;
	if (data.action === "update-request") {
                try { 
                           console.log('[app.js] estado'+data.estado+' Registro='+data.request.key);
                           var x=data.estado;
                           var obj=document.getElementById('ests_'+data.request.key);
                           if (x==0 || x==1 || x==7 || x==8) { 
                              obj=document.getElementById('estc_'+data.request.key);
                           } else {
                              obj=document.getElementById('ests_'+data.request.key);
                           }
                           obj.setAttribute("class", "_es_"+x);
                    } catch (err) { };
	}
        if (data.action === "continuamovto") {
           parser = new DOMParser();
           var responseXML = parser.parseFromString(data.respuesta,"text/xml");
           console.log('[app.js] El cliente recibio mensaje para continuar el movimiento continuamovto ')
                    var desw = responseXML.getElementsByTagName("wlmenu");
                    var wlmenu = desw[0].childNodes[0].nodeValue;
                    var desw = responseXML.getElementsByTagName("wlmovto");
                    var wlmovto = desw[0].childNodes[0].nodeValue;
                    var desw = responseXML.getElementsByTagName("wlllave");
                    try { var wlllave = desw[0].childNodes[0].nodeValue; } catch(err) { var wllave = ""; }
                    var desw = responseXML.getElementsByTagName("wlrenglon");
                    var wlrenglon = desw[0].childNodes[0].nodeValue;
                    var desw = responseXML.getElementsByTagName("wleventodespues");
                    try { var wleventodespues = desw[0].childNodes[0].nodeValue; } catch(err) { var wleventodespues = ""; }
                    wlurl='xmlhttp.php'; 
                    passData='&opcion=mantto_tabla&idmenu='+wlmenu+'&movto='+wlmovto+buildQueryString('formpr_'+wlmenu)+"&wlllave="+escape(wlllave)+"&wlrenglon="+wlrenglon+"&wleventodespues="+wleventodespues;
                    var forma=dame_forma(wlmenu);
                    actualiza_request(wlurl,passData,wlmenu,forma,data.estado,data.request.key)
        }
});

function quetamano() {
     console.log('window.outerWidth='+window.outerWidth+' window.innerWidth='+window.innerWidth);
}

function cargaversiones(forma) {
     envia_mensaje_a_sw('dame_versiones').then(function(event) {
                    console.log('datos recibido'+event.data);
           var sw = document.getElementById('version_sw');
           sw.innerHTML='Versi&oacuten del aplicativo: '+event.data;
     } );
}


function envia_mensaje_a_sw(msg){
    return new Promise(function(resolve, reject){
        // Create a Message Channel
        var msg_chan = new MessageChannel();

        // Handler for recieving message reply from service worker
        msg_chan.port1.onmessage = function(event){
            console.log('recibio mensaje en cliente');
            if(event.data.error){
                console.log('error en el dato');
                reject(event.data.error);
            }else{
                console.log('dato correcto');
                resolve(event);
            }
        };
        // Send message to service worker along with port for reply
        navigator.serviceWorker.controller.postMessage(msg, [msg_chan.port2]);
    });
}

