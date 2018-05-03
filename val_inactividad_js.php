		var numeroClicks = 0;
		function inicia() {
			for (var i = 1 ; i < 6 ; i++)
			var funcion = setTimeout('despliegaMensaje()',i*9*60*1000);
//				var funcion = setTimeout('despliegaMensaje()',60*1000);
		}
		
		function despliegaMensaje() {
                      // window.location.href
	               if (numeroClicks == 0 && window.location.href.indexOf("index")!=-1) {
	//		if (numeroClicks == 0 ) {
				alert("Estimado Usuario:\n\nPor su seguridad y debido a que se ha excedido el periodo de inactividad permitido, el servicio del Sistema ha terminado su sesión, si desea continuar utilizándolo por favor ingrese nuevamente su Usuario y Password .");
				top.closing = false;
				parent.location.replace('index.php');
			} else {
				numeroClicks = 0;
			}
		}
