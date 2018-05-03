var Shell = new ActiveXObject("WScript.Shell");
var fso = new ActiveXObject("Scripting.FileSystemObject")
var objArgs = WScript.Arguments;
var file = fso.CreateTextFile("revisaConexion.bat", true);
file.WriteLine ("echo Creando Log de Ping...  (Presione Ctrl C para Salir) ");
file.WriteLine ("echo inicio %time% %date% > revisaConexion.log");
file.WriteLine ("ping 10.36.0.82 -n 20 >> revisaConexion.log ");
file.WriteLine ("echo >> revisaConexion.log");
file.WriteLine ("echo fin %time% %date% >> revisaConexion.log");
file.WriteLine ("notepad.exe revisaConexion.log");
file.close();
pipe = Shell.run ('revisaConexion.bat',3,true);

fso.DeleteFile("revisaConexion.log");
fso.DeleteFile("revisaConexion.bat");