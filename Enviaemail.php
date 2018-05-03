<?php
    function Enviaemail($error)
    {
   $wlemail='jlvdantry@hotmail.com';
   $wlemailk='kevin.solis@outlook.com';
   $mail = new PHPMailer;
   ##echo "paso new";
   $mail->IsSMTP();                                      // Set mailer to use SMTP
   $mail->Host = 'smtp.live.com';  // Specify main and backup server
   ##$mail->Host = 'smtp.mail.yahoo.com';  // Specify main and backup server
   $mail->SMTPAuth = true;                               // Enable SMTP authentication
   ##$mail->SMTPDebug = true;
   $mail->Username = 'jlvdantry@hotmail.com';                            // SMTP username
   $mail->Password = '888aDantryR';                           // SMTP password
   $mail->Port = '25';                           // SMTP password
   $mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
   $mail->From = $wlemail;
   $mail->FromName = 'Jose Luis Vasquez Barbosa';
   $mail->AddAddress($wlemail);               // Name is optional
   $mail->AddAddress($wlemailk);               // Name is optional
   $mail->AddReplyTo($wlemail, 'Information');
   $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
   $mail->IsHTML(true);                                  // Set email format to HTML
   $mail->Subject = 'Error en el aplicativo de ventanilla';
   $mail->Body    = $error."<br>IP: ".$_SERVER['REMOTE_ADDR'];
   if(!$mail->Send()) {
      exit;
                      }
    }
?>
