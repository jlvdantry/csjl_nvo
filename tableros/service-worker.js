importScripts("js/db.js");
importScripts("js/fToC.js");
var cacheName = 'tableros_1.1';

self.addEventListener('install', function(e) {
  console.log('[ServiceWorker] Install cacheName'+cacheName);
  e.waitUntil(
    caches.open(cacheName).then(function(cache) {
      console.log('[ServiceWorker] Caching app shell');
      return cache.addAll(filesToCache);
    })
  );
});

self.addEventListener('activate', function(e) {
  console.log('[ServiceWorker] Activate');
  e.waitUntil(
    caches.keys().then(function(keyList) {
      console.log('[ServiceWorker] entro en caches.keys elementos='+keyList.length);
      return Promise.all(keyList.map(function(key) {
        if (key !== cacheName) {
          console.log('[ServiceWorker] Removio el cache anterior', key);
          return caches.delete(key);
        }
      }));
    })
  );
  return self.clients.claim();
});

var responseContent = "<html>" +
"<body>" +
"<style>" +
"body {text-align: center; background-color: #333; color: #eee;}" +
"</style>" +
"<h1>Soluciones Inteligentes</h1>" +
"<p>Parece que hay un problema con tu conexion.</p>" +
"</body>" +
"</html>";

self.addEventListener('fetch', function(e) {
  e.respondWith(
    caches.match(e.request).then(function(response) {
      console.log('[ServiceWorker] cache o servidor de='+response.url);
      if (response) { console.log('[ServiceWorker] regreso cache ', response.url); return response; }
      console.log('[ServiceWorker] regreso servidor ',e.request.url);
      return fetch(e.request);
    })
    .catch(function(err) { 
        console.log('[ServiceWorker] error catch '+e.request.url+' '+err); 
        return new Response(responseContent, {headers: {"Content-Type": "text/html"}});
                         })
  );
});

self.addEventListener('message', function(event){
        console.log('[ServiceWorker] recibio mensaje de cliente ' + event.data);
        if (event.data=='dame_versiones') {
           event.ports[0].postMessage(cacheName);
        }
        console.log('No reconocio mensaje');
    });

self.addEventListener("sync", function(event) {
    console.log('[ServiceWorker]  entro en sync'+event);
    if (event.tag === "sync-servidor") {
       console.log('[ServiceWorker] va a sincronicar '+event.tag);
       event.waitUntil(syncRequest(0));
       event.waitUntil(syncRequest(8));
    };
});

var syncRequest = function(estado) {
    openDatabasex(DBNAME, DBVERSION).then(function(db) {
          return openObjectStore(db, 'request', "readonly");
           }).then(function(objectStore) {
            	   var objects=selObjects(objectStore, "estado", estado);
            	   return objects;
	   }).then(function(requests) {
               	   return Promise.all(
               		  requests.map(function(request) {
                                console.log('[ServiceWorker] syncRequest va hacer request de '+request.valor.url+' llave='+request.key+' passdata='+request.valor.passdata);
				fetch(request.valor.url,{
					   method : 'post',
					   headers: { "Content-Type": "application/x-www-form-urlencoded" },
					   body   : request.valor.passdata,
                                           credentials: 'include'
					    }
				  )
				.then(response => { return response.text(); })
				.then(function(response)  { 
                                          if(request.valor.url=='eventos_servidor.php') {
                                              updestado(request,7); return response; 
                                          } else {
                                              updestado(request,1); return response; 
                                          }
                                    })
                                .then(function(response) { querespuesta(request,response); return Promise.resolve(); })
				.catch(function(err)  { 
                                                    return Promise.reject(err); 
                                                      });
		  })
    	     );
	   });
};

var updestado = function (request,estado,repuesta) {
        return new Promise(function (resolve, reject) {
            var now = new Date();
            console.log( '[ServiceWorker]  '+now.getTime()+' updestado key='+request.key+' Estado='+estado);
            openDatabasex(DBNAME, DBVERSION).then(function(db) {
                  return openObjectStore(db, 'request', "readwrite");
                   }).then(function(objectStore) {
                           request.valor.estado=estado;
                           return updObject_01(objectStore, request.valor, request.key);
                   }).then(function(objectStore) {
                           console.log('[ServiceWorker] debe de actualizar la forma');
                           postRequestUpd(request,estado,"update-request",respuesta);
                  })
                    .catch(function(err)  {
                           return Promise.reject(err);
                  });
            resolve('ok');
        });
};

var addCatalogos = function (request,respuesta) {
        return new Promise(function (resolve, reject) {
            parser = new DOMParser();
           // var responseXML = parser.parseFromString(responseText,"text/xml");
            var now = new Date();
            console.log( '[ServiceWorker]  '+now.getTime()+' addCatalogos key='+request.key+' Estado='+estado);
            openDatabasex(DBNAME, DBVERSION).then(function(db) {
                  return openObjectStore(db, 'catalogos', "readwrite");
            resolve('ok');
            });
        });
}


var querespuesta = function(request,respuesta) {
      console.log('[ServiceWorker] respuesta recibida del servidor='+respuesta);
         if(respuesta.indexOf('opciones_antn.php')!=-1) {
            updestado(request,4,respuesta);
            return;
         }
         if(respuesta.indexOf('<error>')!=-1) {
            updestado(request,5);
            return;
         }
         if(respuesta.indexOf('404 Not Found')!=-1) {
            updestado(request,6,respuesta);
            return;
         }
         if(respuesta.indexOf('<altaok>')!=-1) {
            updestado(request,4,respuesta);
            return;
         }
         if(respuesta.indexOf('<ponselect>')!=-1) {
            updestado(request,4,respuesta);
            return;
         }
         if(respuesta.indexOf('<continuamovto>')!=-1) {
            postRequestUpd(request,8,"continuamovto",respuesta); 
            return;
         }
         updestado(request,99,respuesta);
};

var postRequestUpd = function(request,estado,accion,respuesta) {
	self.clients.matchAll({ includeUncontrolled: true }).then(function(clients) {
		clients.forEach(function(client) {
                        console.log('[ServiceWorker] envia mensaje al cliente en cliente '+client.id+' accion='+accion+' request='+JSON.stringify(request));
			client.postMessage(
				{action: accion, request: request, estado: estado, respuesta: respuesta}
			);
		});
	});
};
