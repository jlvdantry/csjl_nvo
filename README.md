# csjl_nvo
Repositorio traido de STYFE, se modifica para que las tablas de forapi queden en un esquema por separado

sobre este repositorio se va a crear un instalador de forapi


Relación de tablas que componen la base de datos de FORAPI

Esquema	Nombre	              Descripción
Public	Menus	                Catálogo de menús o vista
Public	Menus_campos	        Campos que componen un Menús
Public	Menus_subvistas	      Subvistas e la que componen un vitas
Public	Menus_campos_eventos	Aquí se definen los eventos en la que esta compuestos los campos
Public	Menus_eventos	        Eventos en la que está compuesta un menú
Public	Menus_html_table	    Agrupación de los campos de un visa
Public	Menus_movtos	        Se utiliza para poner una texto o imagen en específico al evento de alta,baja,cambio, consulta etc..
Public	Menus_pg_tables	      Se defines los permisos de base de datos que se necesitan para utilizar la vista
Public	Menus_seguimiento	    Se utiliza para definir si se lleva un log de esta subvista, con el objetivo de conocer si es utilizada esta opción
Public	Menus_log	            Se consulta el log de las operaciones efectuadas con la vista
Public	Menus_tiempos	        Catálogo de fechas
Public	Menus_tipoarchivos	  Catálogo de tipos de archivos que se suben al servidor
Public	His_menus_pg_group	  Histórico de cambios efectuados a la tabla pg_group
Public	His_menus	            Histórico de cambios efectuados a la tabla de menus
Public	His_menus_pg_tables	  Histórico de cambios efectuados a la tabla menus_pg_tables
Public	Menus_archivos	      Tabla que se utiliza para controlar el archivos que se suben al servidor
		
		
Public	cat_usuarios	        Catálogo de usuarios
Public	estados_usuarios	    Estatus del usuario
Public	his_cat_usuarios	    Histórico del catálogo de usuarios
Public	cat_usuarios_pg_group	Indica que usuario pertenecen a un grupo o grupos
Public	Menus_pg_group	      Se define en que grupo o perfil pertenece una  vista
Pg_catalog	Pg_group	        Catálogo de grupos o perfiles


Componentes de forapi.
Nombre	                      Descripcion
Index.php	                    Arma la entrada al sistema
titulos.php	                  Muestra los títulos de aplicativo
Entrada.php	                  Muestra el login al sistema
img/Logo_CDMX.png	            Logotipo de la CDMX
img/Logo_Dependencia.png	    Logotipo de la dependencia
conneccion.php	              Se conecta a la base de datos
soldatos.php	                Arma el html de acuerdo a la definición en las tablas de menus
mensajes.php	                Funciones que arman un mensaje de error o mensaje de estar correcto
class.phpmailer.php	          Classes que se utilizar para enviar un email
menudata.php	                Arma el metada de una vista de acuerdo a la definición en las tablas menús
class_logmenus.php	          Registra un log en la base de datos
Man_menus.php	                Ejecuta soldatos.php
xmlhttp_class.php	            Se encarga de darle mantenimiento a las tablas de acuerdo a los datos recibidos por el cliente 
Classes/PHPExcel.php	        Se encarga en generar la información a Excel de acuerdo a los datos contenidos en la vista.
	
	
dhtmlwindow.js	              Se utiliza para armar las pantalla subvistas En el aplicativo
modal.js	                    Se utiliza para armar las pantallas subvistas
common.js	                    Funciones comunes para el manejo de eventos
subModal.js	                  Se utiliza para armar las pantalla subvistas En el aplicativo
cookies1.js	                  Checar yo creo que esto no funciona
val_comunes.js	              Contiene funciones comunes que se utilizan el cliente
val_particulares.js	          Contiene funciones de validaciones particulares de campos como si es predial,
eve_particulares.js	          Definición de funciones utilizadas en los eventos de los campos
md5.js	                      Checar yo creo que esto no funciona
altaautomatica.js	            Cuando una opción no existe en un combo este se encarga de dar de alta
altaadjuntara.js	            Estas funciones se encargan de subir un archivo al servidor
leearchivo.js	                Checar yo creo que esto no funciona
dom-drag.js	                  Función para move elementos pero no se utiliza
jsrsasign-latest-all-min.js	  Funciones que se utilizan para checar la firma electrónica del SAT
Broseaing.js	                Le da mantenimiento en la parte de cliente y envía por Ajax las instrucciones necesarias para actualizar
                              la base de datos

