<?php
class cal_fechas
{
	#=========================================
	# a function to subtract from or add  seconds or minutes or hours or days or months or weeks or years to a specified date and return the updated date
	#=========================================
	function dateAdd($interval,$number,$dateTime) {
	        
	    $dateTime = (strtotime($dateTime) != -1) ? strtotime($dateTime) : $dateTime;       
	    $dateTimeArr=getdate($dateTime);
	                
	    $yr=$dateTimeArr[year];
	    $mon=$dateTimeArr[mon];
	    $day=$dateTimeArr[mday];
	    $hr=$dateTimeArr[hours];
	    $min=$dateTimeArr[minutes];
	    $sec=$dateTimeArr[seconds];
	
	    switch($interval) {
	        case "s"://seconds
	            $sec += $number; 
	            break;
	
	        case "n"://minutes
	            $min += $number; 
	            break;
	
	        case "h"://hours
	            $hr += $number; 
	            break;
	
	        case "d"://days
	            $day += $number; 
	            break;
	
	        case "ww"://Week
	            $day += ($number * 7); 
	            break;
	
	        case "m": //similar result "m" dateDiff Microsoft
	            $mon += $number; 
	            break;
	
	        case "yyyy": //similar result "yyyy" dateDiff Microsoft
	            $yr += $number; 
	            break;
	
	        default:
	            $day += $number; 
	         }       
	                
	        $dateTime = mktime($hr,$min,$sec,$mon,$day,$yr);
	        $dateTimeArr=getdate($dateTime);
	        
	        $nosecmin = 0;
	        $min=$dateTimeArr[minutes];
	        $sec=$dateTimeArr[seconds];
	
	        if ($hr==0){$nosecmin += 1;}
	        if ($min==0){$nosecmin += 1;}
	        if ($sec==0){$nosecmin += 1;}
	        
	        if ($nosecmin>2){     return(date("Y-m-d",$dateTime));} else {     return(date("Y-m-d G:i:s",$dateTime));}
	} 
}
?>
