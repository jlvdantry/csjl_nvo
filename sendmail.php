<?PHP

ini_set('SMTP',"127.0.0.1");

##$envio=mail ('grecar.82@gmail.com','prueba','prueba','From:grecar.82@gmail.com');
$envio=mail ('jlvdanty@hotmail.com','prueba','prueba','From:grecar.82@gmail.com');
echo "<br>envio:".$envio."<br>";
if ($envio==1)
{
echo "envio ok";
}
else
{echo "envio er";}

?>
