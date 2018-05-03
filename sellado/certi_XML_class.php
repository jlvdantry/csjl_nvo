<?php
define("XMLNS", "http://forapi.dyndns-work.com:82/csjl_nvo20130705/wsesiscor");
define("XSI", "http://www.w3.org/2001/XMLSchema-instance");
define("XSILOC", "http://forapi.dyndns-work.com:82/csjl_nvo20130705/wsesiscor  http://forapi.dyndns-work.com:82/csjl_nvo20130705/wsesiscor/certi.xsd");
class certi_XML_class
{
     var $xml;
     var $arr;
     var $docto;

     function certi_XML_class()
     {
        $this->xml = new DOMdocument("1.0","UTF-8");
     }

     function genera_xml()
     {
         $this->generales();
         $this->cobros();
         return $this->xml->saveXML();
     }

     function generales()
     {
         $root = $this->xml->createElement("Certificacion");
         $this->docto = $this->xml->appendChild($root);
         $this->cargaAtt($this->docto,
                 array("xmlns"=>XMLNS,
                       "xmlns:xsi"=>XSI,
                       "xsi:schemaLocation"=>XSILOC)
                        );
         $this->cargaAtt($this->docto,
                 array(
                       "certificado"=>$this->arr["certificado"],
                       "NoFirma"=>$this->arr["NoFirma"],
                       "sello"=>$this->arr["sello"],
                       "FolioCertificado"=>$this->arr["FolioCertificado"],
                       "Concepto"=>$this->arr["Concepto"],
                       "NombreDelContribuyente"=>$this->arr["NombreDelContribuyente"],
                       "LineaDeCaptura"=>$this->arr["LineaDeCaptura"],
                       "Cuenta"=>$this->arr["Cuenta"],
                       "FechaEmision"=>$this->arr["FechaEmision"],
                       )
                  );
     }
     function cobros()
     {
        $cobros = $this->xml->createElement("Cobros");
        $cobros = $this->docto->appendChild($cobros);
        for ($i=1; $i<=sizeof($this->arr['Cobros']); $i++) {
             $cobro = $this->xml->createElement("Cobro");
             $cobro = $cobros->appendChild($cobro);
             $this->cargaAtt($cobro, array(
                              "PuntoDeRecaudacion"=>$this->arr['Cobros'][$i]['PuntoDeRecaudacion'],
                              "FechaDeCobro"=>$this->arr['Cobros'][$i]['FechaDeCobro'],
                              "Caja"=>$this->arr['Cobros'][$i]['Caja'],
                              "Partida"=>$this->arr['Cobros'][$i]['Partida'],
                              "Periodos"=>$this->arr['Cobros'][$i]['Periodos'],
                              "LineaDeCaptura"=>$this->arr['Cobros'][$i]['LineaDeCaptura'],
                              "TotalDelCobro"=>$this->arr['Cobros'][$i]['TotalDelCobro'],
                   )
                );
        }
     }
     function cargaAtt(&$nodo, $attr)
     {
         $quitar = array('sello'=>1,'noCertificado'=>1,'certificado'=>1);
         foreach ($attr as $key => $val) {
           $val = preg_replace('/\s\s+/', ' ', $val);   // Regla 5a y 5c
           $val = trim($val);                           // Regla 5b
           if (strlen($val)>0) {   // Regla 6
              $val = utf8_encode(str_replace("|","/",$val)); // Regla 1
              $nodo->setAttribute($key,$val);
           }
         }
     }
}
?>
