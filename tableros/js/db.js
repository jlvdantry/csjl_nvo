var DBNAME='forapi1.1';
var DBVERSION='8';
var openDatabasex = function(dbName, dbVersion) {
        return new Promise(function (resolve, reject) {
                console.log('[db.js] entro a abrir la base de datos version='+dbVersion);
                if (!self.indexedDB) {
                reject('IndexedDB not supported');
                }
                var request = self.indexedDB.open(dbName, dbVersion);

                request.onerror = function(event) {
                    reject('[db.js] Database error: ' + event.target.error);
                };

                request.onupgradeneeded = function(event) {
                   console.log('[db.js] entro a actualizar la base de datos version='+dbVersion);
                   var db = event.target.result;

                   if(!db.objectStoreNames.contains('encaptura')) {
                        console.log('[db.js] Va a crear el objeto encaptura');
                        var objectStore = db.createObjectStore('encaptura', { autoIncrement : true });
                        objectStore.createIndex('hora', 'hora', { unique: false });
			objectStore.createIndex('minuto', 'minuto', { unique: false });
			objectStore.createIndex('dia', 'dia', { unique: false });
			objectStore.createIndex('mes', 'mes', { unique: false });
			objectStore.createIndex('ano', 'ano', { unique: false });
			objectStore.createIndex('estado', 'estado', { unique: false });
			objectStore.createIndex('idmenu', 'idmenu', { unique: false });
			objectStore.createIndex('usename', 'usename', { unique: false });
                    };

                   if(!db.objectStoreNames.contains('request')) {
                        console.log('[db.js] va a crear el objeto request');
                        var objectStore = db.createObjectStore('request', { autoIncrement : true });
                        objectStore.createIndex('url', 'url', { unique: false });
                        objectStore.createIndex('passdata', 'passdata', { unique: false });
                        objectStore.createIndex('hora', 'hora', { unique: false });
                        objectStore.createIndex('minuto', 'minuto', { unique: false });
                        objectStore.createIndex('dia', 'dia', { unique: false });
                        objectStore.createIndex('mes', 'mes', { unique: false });
                        objectStore.createIndex('ano', 'ano', { unique: false });
                        objectStore.createIndex('estado', 'estado', { unique: false });
                        objectStore.createIndex('idmenu', 'idmenu', { unique: false });
                        objectStore.createIndex('usename', 'usename', { unique: false });
                    };

                   if(!db.objectStoreNames.contains('catalogos')) {
                        console.log('[db.js] va a crear el objeto catalogos');
                        var objectStore = db.createObjectStore('catalogos', { autoIncrement : true });
                        objectStore.createIndex('hora', 'hora', { unique: false });
                        objectStore.createIndex('minuto', 'minuto', { unique: false });
                        objectStore.createIndex('dia', 'dia', { unique: false });
                        objectStore.createIndex('mes', 'mes', { unique: false });
                        objectStore.createIndex('ano', 'ano', { unique: false });
                        objectStore.createIndex('usename', 'usename', { unique: false });
                        objectStore.createIndex('catalogo', 'catalogo', { unique: false });
                        objectStore.createIndex('ID', 'ID', { unique: false });
                    };

               };

               request.onsuccess = function(event) {
                        resolve(event.target.result);
               };
        });
};

var openObjectStore = function(db, storeName, transactionMode) {
        return new Promise(function (resolve, reject) {
                var objectStore = db
                .transaction(storeName, transactionMode)
                .objectStore(storeName);
                resolve(objectStore);
        });
};

/* agrega un objeto a una tabla
   objectStore objecto o tabla a adcionar un registro
   object registo a almacenar 
   regresa el id insertado que es un consecutivo
   */
var addObject = function(objectStore, object) {
        return new Promise(function (resolve, reject) {
        var request = objectStore.add(object);
        request.onsuccess = function (event) {
                console.log('[db.js] inserto el objeto='+event.target.result);
                resolve(event.target.result);
             }
        });
};

/* selecciona un especifico objeto 
   objectStore=objeto
   idmenu=identificador del menu o forma
   estado= estado del objeto */
var selObject = function(objectStore, idmenu, estado) {
        return new Promise(function (resolve, reject) {
        var index = objectStore.index("estado");
        var request = index.get(idmenu+"_"+estado);
        request.onsuccess = function (event) {
               resolve(event);
        };
        request.onerror = reject;
        });
};

/* obtiene un arreglo de objetos 
   de acuerdo al indice y su valor 
   objectStores object a buscar registros
   indexname    nombre del indice a buscar
   indexvalue   valor del indice a buscar
   si llegan valida indexname e indexvalue se regresan todos los valores del objeto
   */ 
var selObjects = function(objectStore, indexname, indexvalue) {
        return new Promise(function (resolve, reject) {
        var objects = [];
        var cursor;
        if (indexname!==undefined && indexvalue!==undefined) {
           cursor  = objectStore.index(indexname).openCursor(indexvalue);
        } else {
           cursor = objectStore.openCursor();
        }
        cursor.onsuccess = function(event) {
            var cursor1 = event.target.result;
            var json = { };
            if (cursor1) {
               console.log('[db.js] selObjects key='+cursor1.primaryKey+' value='+cursor1.value);
               json.valor=cursor1.value;
               json.key  =cursor1.primaryKey;
               objects.push( json );
               cursor1.continue();
            } else {
            if (objects.length > 0) {
               resolve(objects); }
            };
        };
    });
};

var delObject = function(objectStore, idmenu, estado) {
        return new Promise(function (resolve, reject) {
        var index = objectStore.index("estado");
        var pdestroy = index.openKeyCursor(IDBKeyRange.only(idmenu+"_"+estado)); 
        pdestroy.onsuccess = function() {
            var cursor = pdestroy.result;
            if (cursor) {
                objectStore.delete(cursor.primaryKey);
                console.log('[db.js] borro='+cursor.primaryKey);
                cursor.continue;
            }
            resolve(event);
        }
        pdestroy.onerror = reject;
        });
};

/* actualiza un objeto de acuerdo a su llave
   objectStore= objeto a actualiza
   object     = datos a actualizar
   id         = id del objeto a actualizar
   */
var updObject_01 = function(objectStore, object, id) {
        var now = new Date();
        var tiempo = now.getTime();
        return new Promise(function (resolve, reject) {
		console.log('[db.js] '+tiempo+' va a actualizar registro con id='+id+' objeto='+JSON.stringify(object));
		var upd=objectStore.put(object,id);
		upd.onsuccess = function () { console.log('[db.js] '+tiempo+' actualizo registro con id='+id); resolve(); };
		upd.onerror = function () { console.log('[db.js] '+tiempo+' error al actualizar el registro con id='+id); reject(); }
	});
};

/* actualiza un objeto 
   objectoStore=tabl en la base de datos
   object=Contiene los datos a actualizar
   idmenu=id de menu que va actualizar
   estado=estado a actualizar
   */
var updObject = function(objectStore, object, idmenu, estado) {
        return new Promise(function (resolve, reject) {
        console.log('[db.js]  creo promesa updObject='+idmenu);
        var index = objectStore.index("estado");
        var request = index.get(idmenu+"_"+estado);
        console.log('[db.js] leyo='+idmenu+"_"+estado);

        request.onsuccess = function(event) {
               data=object;
               console.log('[db.js]  va a actualizar='+idmenu+' data='+JSON.stringify(data));
               var requestput = objectStore.put(data,idmenu); 
               requestput.onerror = function (event) {
                 console.log('[db.js] error no pudo actualizar');
                 reject(event);
               }
               requestput.onsuccess = function (event) {
                 console.log('[db.js] objecto actualizado'+event.target.result);
                 resolve(event);
               }
            //} else { console.log('no actualizo '); reject; }
        };

        request.onerror = function(event) {
            var requestadd = objectStore.add(object);
            requestadd.onerror = function (event) {
                 console.log('[db.js] error no pudo actualizar');
                 requestadd.onsuccess = reject;
            }
            requestadd.onsuccess = function (event) {
                 console.log('[db.js] objecto agregado al no existir');
                 requestadd.onsuccess = resolve;
            }
        };

        });
};

