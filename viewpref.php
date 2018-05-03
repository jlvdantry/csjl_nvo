<?php
define('FPDF_FONTPATH','font/');
require('fpdf.php');

class PDF_viewpref extends FPDF {

var $DisplayPreferences='';

function DisplayPreferences($preferences) {
    $this->DisplayPreferences.=$preferences;
}

function _putcatalog()
{
    parent::_putcatalog();
    if(is_int(strpos($this->DisplayPreferences,'FullScreen')))
        $this->_out('/PageMode /FullScreen');
    if($this->DisplayPreferences) {
        $this->_out('/ViewerPreferences<<');
        if(is_int(strpos($this->DisplayPreferences,'HideMenubar')))
            $this->_out('/HideMenubar true');
        if(is_int(strpos($this->DisplayPreferences,'HideToolbar')))
            $this->_out('/	hideToolbarButton("Save")');
        //    $this->_out('/HideToolbar true	');
        if(is_int(strpos($this->DisplayPreferences,'HideWindowUI')))
            $this->_out('/HideWindowUI true');
        if(is_int(strpos($this->DisplayPreferences,'DisplayDocTitle')))
            $this->_out('/DisplayDocTitle true');
        if(is_int(strpos($this->DisplayPreferences,'CenterWindow')))
            $this->_out('/CenterWindow true');
        if(is_int(strpos($this->DisplayPreferences,'FitWindow')))
            $this->_out('/FitWindow true');
		if(is_int(strpos($this->DisplayPreferences,'printButtonCmd')))           
            $this->_out('/printButtonCmd.label true');
        $this->_out('>>');
    }
}
}
?>