var uploader = '';
var http;
var t;

function createRequestObject() {
    var obj;
    var browser = navigator.appName;
    
    if(browser == "Microsoft Internet Explorer"){
        obj = new ActiveXObject("Microsoft.XMLHTTP");
    }
    else{
        obj = new XMLHttpRequest();
    }
    return obj;    
}

function traceUpload(uploadDir) {
   http = createRequestObject();
   alert('entro en traceUpload='+http.readyState);		
   http.onreadystatechange = handleResponse;
   alert('despues de ready='+http.readyState);		
   http.open("GET", 'imageupload.php?uploadDir='+uploadDir+'&uploader='+uploader); 
   alert('despues de get'+http.readyState);		
   http.send(null);   
   alert('despues de send'+http.readyState);		
}

function handleResponse() {

	if(http.readyState == 4){
//	alert('termino'+http.responseText);
        document.getElementById(uploaderId).innerHTML = http.responseText;
	if (http.responseText=="")
	{   alert("responsetext");t=setTimeout(traceUpload("si"),5000); http=null;}
	else
	{   alter('entro en cleartimeout');clearTimeout(t); }
        //window.location.reload(true);
    }
    else {
        alert('http.readyState'+http.readyState);
    	document.getElementById(uploaderId).innerHTML = "Uploading File. Please wait...";
    }
}

function uploadFile(obj) {
	var uploadDir = obj.value;
	uploaderId = 'uploader'+obj.name;
	uploader = obj.name;
	
	document.getElementById('formName'+obj.name).submit();
	traceUpload(uploadDir, obj.name);	
}
