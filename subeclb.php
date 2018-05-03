<html>
<head>
<title>csXImage Select, Resize and Upload Demo</title>
</head>
<SCRIPT LANGUAGE="JavaScript">
<!--

  var Success;
  var FileName;
  var FileExt;
  var FileType=2;

  function Initialisation()
  {
  }
  
  mypath=document.location.pathname.split('/');
  numitems=parseFloat(mypath.length)-1;
  myFullPath=location.protocol+'//'+location.hostname;
  for (i=0 ; i < numitems ; i++) { myFullPath=myFullPath+'/'+mypath[i]; }
  
  function ClbClick()
  {
	      if (csxi.Paste())
    		{

    			Success = csxi.PostImage(myFullPath+'/filesave.php', 'clb.jpg', 'userfile', FileType);

    			if (Success)
    			{
      				alert('Image Uploaded');
      				self.close();
    			}
    			else
    			{
      				alert('Upload Failed')
    			} 	    		
    		}
    		else
    		{ 
				Success = false;	    		
    			Success = csxi.PostImage(myFullPath+'/altaadjuntara.php?wlopcion=altaadjuntara', 'clb2.jpg', 'ficheroin', FileType);
    			if (Success)
    			{
//    			alert ('rf'+csxi.PostReturnFile);
    			numero=csxi.PostReturnFile.substring(csxi.PostReturnFile.indexOf('carga')+10);
    			
/*    			numeroimg=numero.substring(0,numero.indexOf('"'));
    			extenimg=numero.substring(numero.indexOf('","')+3);
    			extenimg=extenimg.substring(0,numero.indexOf('"'));*/
//    			alert('extenimg='+extenimg);
//    			alert('numeroimg='+numeroimg);
//    			alert('numero'+numero);

				//  200904224 grecar : remplace esto porque lo enterior tronaca cuando el nombre del archivo era menor a tres posiciones
    			split1=numero.split('","');
    			numeroimg=split1[0];
    			split2=split1[1].split('"');
    			extenimg=split2[0];
    			
    			if (Success)
    			{
      				alert('Se subio la imagen de clipboard');
      				window.returnValue=numeroimg+"."+extenimg;
      				self.close();      				
    			}
    			else
    			{
      				alert('No se pudo subir imagen de clipboard')
    			} 	    		    		
				} else {window.close()}
    		}
    }





//-->
</script>
<body onLoad="ClbClick();">

<form name=form1 >
</form>

<!-- This first object tag tells the browser where to find the licence information needed to allow csXImage to run.  
The file csximage.lpk should be copied to the web server in the same directory as the .ocx file.  -->

<object classid="clsid:5220cb21-c88d-11cf-b347-00aa00a28331"><param name="LPKPath" value="csximage.lpk"></object>

<!-- A second object tag identifies the csXImage control itself and allows the size of the control as displayed in the browser to be set.  -->

<object id="csxi" classid="clsid:62e57fc5-1ccd-11d7-8344-00c1261173f0" codebase="csximage.ocx" width="800" height="600"></object>

</body>
</html>