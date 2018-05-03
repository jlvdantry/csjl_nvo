<?php
    echo "<html>\n";
    echo "<BODY background=\"img\bg.gif\">\n";
    echo "<center>";
    echo "<title>Seleccione el dia con click ...........</title>\n";
    echo "<link type=\"text/css\" rel=\"StyleSheet\" href=\"datepicker.css\" MEDIA=screen />\n";
    echo "<script src='datepicker.js'></script>\n";
    echo "<script type=\"text/javascript\">\n";
    echo " var d = new Date();\n";
    if ($valor=="")
    {
	echo " var dp = new DatePicker();\n";
    }
    else
    {
##        echo " d.setDate(".substr($valor,8).");\n";
##        echo " d.setMonth(".substr($valor,5,6).");\n";
        echo " d.setFullYear(".substr($valor,0,4).",".substr($valor,5,2)."-1,".substr($valor,8,2).");\n";
	echo " var dp = new DatePicker(d);\n";
    }
	//echo " document.body.appendChild(dp.create());\n";
	//echo " dp.onchange = function () { window.returnValue=dp.getDate().getYear()+'-'+((dp.getDate().getMonth()<9) ? '0' + (dp.getDate().getMonth()+1) : (dp.getDate().getMonth()+1)) +'-'+((dp.getDate().getDate()<10) ? '0'+dp.getDate().getDate():dp.getDate().getDate());window.close();  }; \n";
	echo " </script>    \n";
	echo "</center>";
    echo "</body>";    
    echo "<script type=\"text/javascript\">\n";
    echo " document.body.appendChild(dp.create());\n";
    echo " dp.onchange = function () { window.returnValue=dp.getDate().getYear()+'-'+((dp.getDate().getMonth()<9) ? '0' + (dp.getDate().getMonth()+1) : (dp.getDate().getMonth()+1)) +'-'+((dp.getDate().getDate()<10) ? '0'+dp.getDate().getDate():dp.getDate().getDate());window.close();  }; \n";
    echo " </script>    \n";
    echo "</html>";
?>
