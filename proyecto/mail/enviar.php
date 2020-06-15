<?php

/*
En enviar.php se utilizan las clases de PHPMailer para mandar un correo de confirmación
a los usuarios invitados que se registran en el sistema.
Se ha utilizado una cuenta de gmail creada exclusivamente para este fin:
pauladesarrolladora@gmail.com y se han desactivado algunas opciones de seguridad en gmail
para poder utilizarla.
*/


require_once('mail/PHPMailer.php') ;
require_once('mail/SMTP.php');
require_once('mail/Exception.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// require_once "mail/autoload.php";

function enviarEmail($enviar_a, $vkey){
  // Instantiation and passing `true` enables exceptions
  $mail = new PHPMailer(true);

  try {
      //Server settings
      // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
      $mail->isSMTP();                                            // Send using SMTP
      $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
      $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
      $mail->Username   = 'pauladesarrolladora@gmail.com';                     // SMTP username
      $mail->Password   = 'desarrollophp';                               // SMTP password
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
      $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

      //Recipients
      $mail->setFrom('pauladesarrolladora@gmail.com', 'Delicias Sencillas');
      $mail->addAddress($enviar_a, 'Nuevo usuario');     // Add a recipient
      $mail->addReplyTo('pauladesarrolladora@gmail.com', 'Delicias Sencillas');

      if (DESPLIEGUE=='void'){
        $enlace = 'https://void.ugr.es/~piglesias1920/proyecto/index.php?p=confirmacion&vkey=$vkey';
      }else{
        $enlace = 'http://localhost/tw/proyecto/index.php?p=confirmacion&vkey=$vkey';
      }
      // Content
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = 'Activa tu cuenta en delicias sencillas';
      $mail->Body    = "Estás a solo un paso de tener una cuenta en <b>Delicias sencillas</b>
                        Tan solo tienes que acceder a este enlace para acceder
                        <a href=$enlace>Confirmar cuenta</a>";
      $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
      $mail->send();
      echo 'El correo ha sido enviado correctamente';
  } catch (Exception $e) {
      echo "El correo no ha podido ser enviado: {$mail->ErrorInfo}";
  }
}
