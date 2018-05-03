<?php
//require_once('FileSystem.php');

class FileSystem {
    public static function copyr($source, $dest, $filter=''){
        // Simple copy for a file
        if (is_file($source)){
                if(eregi(pathinfo($source, PATHINFO_EXTENSION), $filter)){
                        return false;
                }
                return copy($source, $dest);
        }

        // Make destination directory
        if (!is_dir($dest)) {
            mkdir($dest);
        }

        // Loop through the folder
        $dir = dir($source);
        while (false !== $entry = $dir->read()) {
            // Skip pointers
            if ($entry == '.' || $entry == '..') {
                continue;
            }
            // Deep copy directories
            if ($dest !== "$source/$entry") {
                self::copyr("$source/$entry", "$dest/$entry", $filter);
            }
        }
        // Clean up
        $dir->close();
        return true;
    }

    public static function rmdir($dir){
        $dh = opendir($dir);
        while(($file = readdir($dh)) !== false){
            if($file == '.' || $file == '..'){
                continue;
            }elseif(is_dir($dir.'/'.$file)){
                self::rmdir($dir.'/'.$file);
            }else{
                if(!unlink($dir.'/'.$file)){
                    throw new Exception("Can't delete the $dir/$file file. Check your permissions.");
                }
            }
        }
        closedir($dh);
        if(!rmdir($dir)){
            throw new Exception("Can't delete the $dir directory. Check your permissions.");
        }
        return true;
    }

    public static function mapPath($path){
        $includePath = explode(PATH_SEPARATOR, get_include_path());
                foreach($includePath as $prefix){
                    $potentialPath = $prefix.DIRECTORY_SEPARATOR.$path;
                    if(file_exists($potentialPath)){
                        return($potentialPath);
                    }
                }
        return false;
    }
}

define('LIBXML_OPTIONS', LIBXML_DTDLOAD | LIBXML_NOENT | LIBXML_DTDATTR | LIBXML_NOCDATA);
/*
 * @TODO: check the reference counting (just in case)
 */
class XSLTProc{

    function transformToDoc($xml, $xsl){
        debugea("entro en transformToDoc xml=".$xml." xsl=".$xsl);
        $xml = self::getDOMDocument($xml);
        debugea("paso get xml");
        $xsl = self::getDOMDocument($xsl);
        debugea("paso get xsl");
        $xsltProc = new XSLTProcessor();
        debugea("paso get xslprocesso");
        $xsltProc->importStyleSheet($xsl);
        debugea("paso importStyleSheet");
        return $xsltProc->transformToDoc($xml);
    }
    
    function transformToXML($xml, $xsl){
        $xml = self::getDOMDocument($xml);
        $xsl = self::getDOMDocument($xsl);
        $xsltProc = new XSLTProcessor();
        $xsltProc->importStyleSheet($xsl);
        return $xsltProc->transformToXML($xml);
    }

    function getDOMDocument($xml){
        debugea("entro en getDOMDocument xml=".$xml);
        if($xml instanceof DOMDocument){
            debugea("entro en instnceof");
            return $xml;
        }elseif($path = FileSystem::mapPath($xml)){
           debugea("entro en path=".FileSystem::mapPath($xml)."-LIBXML_OPTIONS=".LIBXML_OPTIONS."-path=".$path);
           $x=DOMDocument::load($path, LIBXML_OPTIONS);
           debugea("paso load=");
           return DOMDocument::load($path, LIBXML_OPTIONS);
        }elseif(is_string($xml)){
            return DOMDocument::loadXML($xml, LIBXML_OPTIONS);
        }else{
            throw new InvalidParameterException($xml);
        }

    }
}
?>
