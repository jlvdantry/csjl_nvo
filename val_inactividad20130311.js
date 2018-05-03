                var numeroClicks = 0;
                var pasada = 0;
                function inicia() {
//                        for (var i = 1 ; i < 6 ; i++)
//                        var funcion = setTimeout('despliegaMensaje()',i*9*60*1000);
                              var funcion = setTimeout('despliegaMensaje()',60*60*1000);
                }

                function despliegaMensaje() {
	                	pasada++;
//	                	window.status='entro a revisar click_'+numeroClicks+' pasadas'+pasada;
                        if (numeroClicks == 0) {
                                alert("Estimado Usuario:\n\nPor su seguridad y debido a que se ha excedido el periodo de inactividad permitido, el servicio del Sistema ha terminado su sesión, si desea continuar utilizándolo por favor ingrese nuevamente su Usuario y Password .");
//                                top.closing = false;
								window.close();
                                parent.location.replace('index.php');
//								window.close();
								
                        } else {
                                numeroClicks = 0;
                              	var funcion = setTimeout('despliegaMensaje()',60*60*1000);                                
                        }
                }
				function sumaclicks() {
					numeroClicks++;
//	                window.status='sumo clicks'+numeroClicks;					
				}