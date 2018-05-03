<?php
define("XMLNS", "http://10.250.103.116/htdocs/contra/csjl_nvo/csjl_nvo/sellado");
define("XSI", "http://www.w3.org/2001/XMLSchema-instance");
define("XSILOC", "http://10.250.103.116/htdocs/contra/csjl_nvo/csjl_nvo/sellado  http://10.250.103.116/htdocs/contra/csjl_nvo/csjl_nvo/sellado/vuat.xsd");
class vuat_XML_class
{
     var $xml;
     var $arr;
     var $docto;

     function vuat_XML_class()
     {
        $this->xml = new DOMdocument("1.0","UTF-8");
     }

     function genera_xml()
     {
         $this->generales();
         $this->testador();
         $this->instrumento();
         $this->padres();
         $this->domicilio();
         return $this->xml->saveXML();
     }

     function generales()
     {
         $root = $this->xml->createElement("AvisoDeTestamento");
         $this->docto = $this->xml->appendChild($root);
         $this->cargaAtt($this->docto,
                 array("xmlns:vuat"=>XMLNS,
                       "xmlns:xsi"=>XSI,
                       "xsi:schemaLocation"=>XSILOC)
                        );
         $this->cargaAtt($this->docto,
                 array(
                       "certificado"=>$this->arr["certificado"],
                       "noFirma"=>$this->arr["noFirma"],
                       "sello"=>$this->arr["sello"],
                       "folioCertificado"=>$this->arr["folioCertificado"],
                       "tramite"=>$this->arr["tramite"],
                       "lineaDeCaptura"=>$this->arr["lineaDeCaptura"],
                       "fechaEmision"=>$this->arr["fechaEmision"],
                       "cadenaOriginal"=>$this->arr["cadenaOriginal"],
                       "numeroNotario"=>$this->arr["numeroNotario"],
                       "nombreNotario"=>$this->arr["nombreNotario"],
                       "notasDelAviso"=>$this->arr["notasDelAviso"],
                       "folioAviso"=>$this->arr["folioAviso"],
                       )
                  );
     }
     function testador()
     {
        $testa = $this->xml->createElement("Testador");
        $testa = $this->docto->appendChild($testa);
             $this->cargaAtt($testa, array(
                              "nombre"=>$this->arr['testador']['nombre'],
                              "apellidoPaterno"=>$this->arr['testador']['apellidoPaterno'],
                              "apellidoMaterno"=>$this->arr['testador']['apellidoMaterno'],
                              "apellidoConyuge"=>$this->arr['testador']['apellidoConyuge'],
                              "tambienConocidoComo"=>$this->arr['testador']['tambienConocidoComo'],
                              "nacionalidad"=>$this->arr['testador']['nacionalidad'],
                              "fechaDeNacimiento"=>$this->arr['testador']['fechaDeNacimiento'],
                              "estadoCivil"=>$this->arr['testador']['estadoCivil']
                   )
                );
     }

     function instrumento()
     {
        $testa = $this->xml->createElement("Instrumento");
        $testa = $this->docto->appendChild($testa);
             $this->cargaAtt($testa, array(
                              "tipodeTestamento"=>$this->arr['Instrumento']['tipodeTestamento'],
                              "escritura"=>$this->arr['Instrumento']['escritura'],
                              "volumen"=>$this->arr['Instrumento']['volumen'],
                              "fechaDeEscritura"=>$this->arr['Instrumento']['fechaDeEscritura'],
                              "fechaDeOtorgamiento"=>$this->arr['Instrumento']['fechaDeOtorgamiento'],
                   )
                );
     }

     function padres()
     {
        $testa = $this->xml->createElement("Padres");
        $testa = $this->docto->appendChild($testa);
             $this->cargaAtt($testa, array(
                              "nombrePadre"=>$this->arr['Padres']['nombrePadre'],
                              "paternoPadre"=>$this->arr['Padres']['paternoPadre'],
                              "maternoPadre"=>$this->arr['Padres']['maternoPadre'],
                              "nombreMadre"=>$this->arr['Padres']['nombreMadre'],
                              "paternoMadre"=>$this->arr['Padres']['paternoMadre'],
                              "maternoMadre"=>$this->arr['Padres']['maternoMadre'],
                              "apellidoConyugeMadre"=>$this->arr['Padres']['apellidoConyugeMadre'],
                   )
                );
     }

     function domicilio()
     {
        $testa = $this->xml->createElement("Domicilio");
        $testa = $this->docto->appendChild($testa);
             $this->cargaAtt($testa, array(
                              "calleYNumero"=>$this->arr['Domicilio']['calleYNumero'],
                              "colonia"=>$this->arr['Domicilio']['colonia'],
                              "delegacionMunicipio"=>$this->arr['Domicilio']['delegacionMunicipio'],
                              "entidad"=>$this->arr['Domicilio']['entidad'],
                              "codigoPostal"=>$this->arr['Domicilio']['codigoPostal'],
                   )
                );
     }

     function cargaAtt(&$nodo, $attr)
     {
         $quitar = array('sello'=>1,'noCertificado'=>1,'certificado'=>1);
         foreach ($attr as $key => $val) {
           $val = preg_replace('/\s\s+/', ' ', $val);   // Regla 5a y 5c
           $val = trim($val);                           // Regla 5b
           if (strlen($val)>0) {   // Regla 6
              //$val = utf8_encode(str_replace("|","/",$val)); // Regla 1
              $val = utf8_encode($val); // Regla 1
              $nodo->setAttribute($key,$val);
           }
         }
     }
}
?>
