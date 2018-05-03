<?php
include("Function.php");
//$_GET["Id"]=$argv[1];
$dir_abs=PATH."/";
$post_data = array();
//****************
//Usuario y contraseña del RENAT
//****************
$post_data['txtUser'] = RENATUSER;
$post_data['txtPass'] = RENATPASS;


//*****************
// Iniciamos sesion
//*****************
$URL="https://renat.segob.gob.mx/AppRNT/validacion/valida.php";
$ch = curl_init($URL);

//
// Establecemos sesiones
curl_setopt($ch, CURLOPT_POST, 1 );
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
// Establecemos ubicacion del certificado
curl_setopt($ch, CURLOPT_CAINFO, $dir_abs.CERT);
curl_setopt($ch, CURLOPT_SSLCERT, $dir_abs.CERT);
curl_setopt($ch, CURLOPT_SSLCERTPASSWD, CERTPASSWORD);//contra del certificado
//curl_setopt($ch, CURLOPT_SSLCERTPASSWD, "Nj65FgFW");//contra del certificado
curl_setopt($ch, CURLOPT_TIMEOUT, 120);

curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $qky = fopen($dir_abs."cookieFileName", 'wb');
    if ($qky == FALSE){
       print "Error al abrir el archivo<br>";
       exit;
    }
curl_setopt($ch, CURLOPT_COOKIEJAR, $dir_abs."cookieFileName");//ubicacion de la cookie que se genera del inicio de sesion

// ejecutamos el inicio de sesion
curl_exec($ch);
curl_close($ch);


//inicio del ciclo de busqueda de avisos.

$DB=Connect();

//hacemos la busqueda de todos los oficios o de uno individuAL
if(strlen($_GET["Id"])>0)
  $SQL="SELECT * FROM busqueda,funcionario WHERE busqueda.id_funcionario=funcionario.id_funcionario  AND busqueda.id_busqueda='".$_GET["Id"]."'";
else
  $SQL="SELECT * FROM busqueda,funcionario WHERE busqueda.id_funcionario=funcionario.id_funcionario  AND (busqueda.nombre_renat LIKE '%sin%' OR busqueda.nombre_renat=NULL)";

  //echo $SQL."<br>\n";
$Datos=$DB->Execute($SQL);
while(!$Datos->EOF)
{



  //Sacamos los nombres de este registro
  $Nombres=explode("|",trim($Datos->fields["nombres"],"|"));
  $ReportesRENAT="";//variable que almacena los nombres de archivo
  for($k=0;$k<count($Nombres);$k++)//recorro nombre por nombre
  {
    $Nombre=$Nombres[$k];
    $Nombre=explode(",",trim($Nombre,","));

    //****************************
    //voy a la pagina de busqueda
    //una vez iniciada la sesion
    //****************************


    //arreglo que contiene as variables que se envian al formulario de busqueda
    //*************
    //Estos datos podran ser sacado de una DB o de cualquier arreglo.
    //*************
    $post_data = array();
     $post_data['apPaterno'] = $Nombre[0];
     $post_data['apMaterno'] = $Nombre[1];
     $post_data['Nombre'] = $Nombre[2];
     $post_data['apConyuge'] = $Nombre[3];
     $post_data['solicita'] = "solicitar";
     $post_data['idUsr'] = "13";
     $post_data['decujus'] = $Nombre[2]." ".$Nombre[0]." ".$Nombre[1];
     $post_data['acreditar'] = $Datos->fields["tipo"];
     $post_data['tipoJuez'] = "FAMILIAR";
     $post_data['estatus'] = "Pendiente";
     $post_data['nomPadre'] = $Datos->fields["nombre_padre"];
     $post_data['nomMadre'] = $Datos->fields["nombre_madre"];
     $post_data['apPatPadre'] = $Datos->fields["ap_paterno_padre"];
     $post_data['apPatMadre'] = $Datos->fields["ap_paterno_madre"];
     $post_data['apMatPadre'] = $Datos->fields["ap_materno_padre"];
     $post_data['apMatMadre'] = $Datos->fields["ap_materno_madre"];
     $post_data['munJuez'] = $Datos->fields["municipio"];
     $post_data['numeroNot'] = $Datos->fields["numero"];//si es notario
     $post_data['munNot'] = $Datos->fields["municipio"];//si es notario
     $post_data['expJuez'] = $Datos->fields["expediente"];;
     $post_data['ciudadano'] = $Datos->fields["nombre_funcionario"];
     $post_data['mes'] = "-----------------";
     $post_data['dia'] = "--";
     //print_r($post_data);
     //echo "<br><br><br><br><br>";

    //iniciamos la busqueda en forma

    // Initiate Session
    $URL="https://renat.segob.gob.mx/AppRNT/solrun.php";
    $ch = curl_init($URL);

     curl_setopt($ch, CURLOPT_POST, 1 );
     curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
     curl_setopt($ch, CURLOPT_HEADER, 0);

    // Establecemos sesiones
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    // Establecemos ubicacion del certificado
    curl_setopt($ch, CURLOPT_CAINFO, $dir_abs.CERT);
    curl_setopt($ch, CURLOPT_SSLCERT, $dir_abs.CERT);
    curl_setopt($ch, CURLOPT_SSLCERTPASSWD, CERTPASSWORD);//contra del certificado
//    curl_setopt($ch, CURLOPT_SSLCERTPASSWD, "Nj65FgFW");//contra del certificado
    curl_setopt($ch, CURLOPT_TIMEOUT, 120);
   
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $dir_abs."cookieFileName"); //de aqui sacamos la sesion para continuar
    $solnum= curl_exec($ch);
    //echo "solnum original ".$solnum."<br>\n";
    curl_close($ch);
    //obtenemos el numero de reporte
    $pos=strpos($solnum,"./autoriza.php?idUsr=13&qr=");
    $solnum=substr($solnum,$pos,35);
    $pos=strpos($solnum,"'");
    $solnum=substr($solnum,27,7);
    echo $solnum." ".$Datos->fields["fecha_recepcion"]." ".$Datos->fields["folio"]."<br>\n";
    //fin de benemos el numero de reporte

  //**************************
  //ventana de autorizacion
  //*************************

     
    // Initiate Session
    $URL="https://renat.segob.gob.mx/AppRNT/autoriza.php?idUsr=13&qr=".$solnum;
    $ch = curl_init($URL);

    curl_setopt($ch, CURLOPT_HEADER, 0);

    // Establecemos sesiones
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    // Establecemos ubicacion del certificado
    curl_setopt($ch, CURLOPT_CAINFO, $dir_abs.CERT);
    curl_setopt($ch, CURLOPT_SSLCERT, $dir_abs.CERT);
    curl_setopt($ch, CURLOPT_SSLCERTPASSWD, CERTPASSWORD);//contra del certificado
    curl_setopt($ch, CURLOPT_TIMEOUT, 120);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $dir_abs."cookieFileName"); //de aqui sacamos la sesion para continuar
    curl_exec($ch);
    curl_close($ch);

  //***********************************
  //Ventana de  respuesta
  //***********************************

    // Initiate Session
     $URL="https://renat.segob.gob.mx/AppRNT/respuesta.php?idUsr=13&qr=".$solnum;
     $ch = curl_init($URL);

     curl_setopt($ch, CURLOPT_HEADER, 0);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // Establecemos sesiones
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    // Establecemos ubicacion del certificado
    curl_setopt($ch, CURLOPT_CAINFO, $dir_abs.CERT);
    curl_setopt($ch, CURLOPT_SSLCERT, $dir_abs.CERT);
    curl_setopt($ch, CURLOPT_SSLCERTPASSWD, CERTPASSWORD);//contra del certificado
//    curl_setopt($ch, CURLOPT_SSLCERTPASSWD, "Nj65FgFW");//contra del certificado
    curl_setopt($ch, CURLOPT_TIMEOUT, 120);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $dir_abs."cookieFileName"); //de aqui sacamos la sesion para continuar

    curl_exec($ch);
    curl_close($ch);

  //***********************************
  //Ventana de descarga del PDF
  //***********************************
    // Initiate Session
    $URL="https://renat.segob.gob.mx/AppRNT/pasover.php?liga=./AppDPDF/frep".$solnum.".pdf&idUsr=13&id=".$solnum."&ver=VER";
    $URL="https://renat.segob.gob.mx/AppRNT/AppDPDF/frep".$solnum.".pdf";
    echo $URL."\n";
    $ch = curl_init();

    // Establecemos sesiones
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    // Establecemos ubicacion del certificado
    curl_setopt($ch, CURLOPT_CAINFO, $dir_abs.CERT);
    curl_setopt($ch, CURLOPT_SSLCERT, $dir_abs.CERT);
    curl_setopt($ch, CURLOPT_SSLCERTPASSWD, CERTPASSWORD);//contra del certificado
//    curl_setopt($ch, CURLOPT_SSLCERTPASSWD, "Nj65FgFW");//contra del certificado
    curl_setopt($ch, CURLOPT_COOKIEFILE, $dir_abs."cookieFileName"); //de aqui sacamos la sesion para continuar

    //Iniciamos la descarga
      //Preparamos el archivo local
    $out = fopen($dir_abs."pdfs/frep".$solnum.".pdf", 'wb');
    if ($out == FALSE){
       print "Error al abrir el archivo<br>";
       exit;
    }

    curl_setopt($ch, CURLOPT_FILE, $out);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_REFERER,"https://renat.segob.gob.mx/AppRNT/listarespuestas.php");
    curl_setopt($ch, CURLOPT_URL, $URL);

    if (! $result = curl_exec($ch))
    { echo "error ".curl_error($ch); }
    //$ch = curl_init();
      //if(strlen(($ch))==0)
      //if(strlen($solnum))
      if(filesize($dir_abs."pdfs/frep".$solnum.".pdf")>1000&&file_exists($dir_abs."pdfs/frep".$solnum.".pdf"))
      {
        $ReportesRENAT.="frep".$solnum.".pdf,";
        echo "Se ha realizado con &eacute;xito la b&uacute;squeda de 
              <a href='pdfs/frep".$solnum.".pdf' target='_blank'>".$post_data['decujus']."</a><br>\n";
      }
      else
      {
        echo "<font color=red>Error</font> Realizando  la b&uacute;squeda de ".$post_data['decujus']." Intente de nuevo<br>\n";
        $ReportesRENAT.="sin,";
      }
    curl_close($ch);


  }

  $ReportesRENAT=trim($ReportesRENAT,",");
  //Actualizo el campo nombre_renat en la base de datos
  $DB1=Connect();
  $SQL1="UPDATE busqueda SET nombre_renat='".$ReportesRENAT."' WHERE id_busqueda='".$Datos->fields["id_busqueda"]."'";
  $Datos1=$DB1->Execute($SQL1);
  $Datos->MoveNext();//avanzo al sig registro
}
//  eliminamos la cookie
//unlink($dir_abs."cookieFileName");
?>
