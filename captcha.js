
    function ReDrawCaptcha(x)
    {
        nombre=x.name.replace(/_bot/,"_img");
        document.getElementById(nombre).value='';
        DrawCaptcha(x);
    }
    function DrawCaptcha(x)
    {
        if (x.name.indexOf('_bot')==-1)
        { nombre=x.name+'_img'; }
        else { nombre=x.name.replace(/_bot/,"_img"); }
        if (document.getElementById(nombre).value!='') return;
        var a = Math.ceil(Math.random() * 10)+ '';
        var b = Math.ceil(Math.random() * 10)+ '';       
        var c = Math.ceil(Math.random() * 10)+ '';  
        var d = Math.ceil(Math.random() * 10)+ '';  
        var e = Math.ceil(Math.random() * 10)+ '';  
        var f = Math.ceil(Math.random() * 10)+ '';  
        var g = Math.ceil(Math.random() * 10)+ '';  
        var code = a + ' ' + b + ' ' + ' ' + c + ' ' + d + ' ' + e + ' '+ f + ' ' + g;
        document.getElementById(nombre).value = code;
    }

    // Validate the Entered input aganist the generated security code function   
    function ValidCaptcha(x){
        var str1 = removeSpaces(document.getElementById(x.name+'_img').value.trim());
        var str2 = x.value.trim();
        if (str1 != str2)         
        { alert("El captcha tecleado no es el mismo"); x.value=''; x.focus(); return false ; }
        return true;
    }
    // Remove the spaces from the entered and generated code
    function removeSpaces(string)
    {
        return string.split(' ').join('');
    }

