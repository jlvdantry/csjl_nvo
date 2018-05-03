<?php
putenv("TZ=America/Mexico_City");
require_once("class.phpmailer.php");
function EnviaCitaemail($wlemail,$error)
{
   $mail = new PHPMailer;
   $mail->IsSMTP();                                      // Set mailer to use SMTP
   $mail->Host = 'smtp.live.com';  // Specify main and backup server
   ##$mail->Host = 'smtp.mail.yahoo.com';  // Specify main and backup server
   $mail->SMTPAuth = true;                               // Enable SMTP authentication
##   $mail->SMTPDebug = true;
   $mail->Username = 'jlvdantry@hotmail.com';                            // SMTP username
   $mail->Password = '888aDantryR';                           // SMTP password
   $mail->Port = '465';                           // SMTP password
   $mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
   $mail->From = $wlemail;
   $mail->FromName = 'Jose Luis Vasquez Barbosa';
   $mail->AddAddress($wlemail);               // Name is optional
   $mail->AddReplyTo($wlemail, 'Information');
   $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
##   $mail->AddAttachment('temp/cita_102.pdf', 'cita102');    // Optional name
   $mail->IsHTML(true);                                  // Set email format to HTML
   $mail->Subject = 'Error en el aplicativo de ventanilla';
   $mail->Body    = $error;
   if(!$mail->Send()) {
##      echo 'Message could not be sent.';
##      echo 'Mailer Error: ' . $mail->ErrorInfo;
      exit;
   }
##   echo 'Message has been sent';
}
EnviaCitaemail('jlvdantry@hotmail.com','error de prueba');
?>

