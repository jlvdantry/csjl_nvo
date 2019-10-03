<?php
require_once('websockets.php');
class echoServer extends WebSocketServer {
  protected function process ($user, $message) {
  try {
       $this->stdout('mensaje recibido user='.$user->id);
       $this->stdout('mensaje ='.$message);
       $this->stdout('type ='.gettype($message));
       $msg = json_decode($message);
      if ($msg->type=="tablero")
      {
          echo "recibio tablero el modulo es ".$msg->modulo."\n";
          $this->send($user,"recibi tablero");
          echo "paso send";
          if ($this->identificatablero($user->socket,$msg->modulo))
          {
             $this->send($user,"se identifico el tablero");
             return;
          }
          $this->send($user,"no se pudo identificar el tablero ".$msg->modulo);
          return;
      }
      if ($msg->type=="llamar")
      {
          echo "recibio llamar";
          $this->send($user, "recibi llamar");
          $this->dametablero($msg);
          return;
      }
      $this->send($user, $message);
   }
   catch (Exception $e) {
        $this->stdout($e->getMessage());
        $this->send($e->getMessage());
    }
  }

  protected function setupConnection() {
                $errno = $errstr = null;
                $options = array(
                        'ssl' => array(
                                'peer_name' => 'solin.com',
                                'verify_peer' => false,
                                'local_cert' => '/root/certs/solin.pem',
                                /* 'local_pk' => '/root/certs/solin.key', */
                                'disable_compression' => true,
                                /* 'passphrase' => 'comet', */
                                'SNI_enabled' => true,
                                'allow_self_signed' => true,
                                'ciphers' => 'ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-AES256-GCM-SHA384:DHE-RSA-AES128-GCM-SHA256:DHE-DSS-AES128-GCM-SHA256:kEDH+AESGCM:ECDHE-RSA-AES128-SHA256:ECDHE-ECDSA-AES128-SHA256:ECDHE-RSA-AES128-SHA:ECDHE-ECDSA-AES128-SHA:ECDHE-RSA-AES256-SHA384:ECDHE-ECDSA-AES256-SHA384:ECDHE-RSA-AES256-SHA:ECDHE-ECDSA-AES256-SHA:DHE-RSA-AES128-SHA256:DHE-RSA-AES128-SHA:DHE-DSS-AES128-SHA256:DHE-RSA-AES256-SHA256:DHE-DSS-AES256-SHA:DHE-RSA-AES256-SHA:AES128-GCM-SHA256:AES256-GCM-SHA384:ECDHE-RSA-RC4-SHA:ECDHE-ECDSA-RC4-SHA:AES128:AES256:RC4-SHA:HIGH:!aNULL:!eNULL:!EXPORT:!DES:!3DES:!MD5:!PSK',
                        )
                );
                $this->stdout("entro en setupConnection");
                $context = stream_context_create($options);
                $this->master = stream_socket_server(
                        'tls://' . $this->listenAddress . ':' . $this->listenPort,
                        $errno,
                        $errstr,
                        STREAM_SERVER_BIND | STREAM_SERVER_LISTEN,
                        $context
                );
                $this->stdout("errno=".$errno." errstr=".$errstr);
                if (!$this->master) {
                   echo "$errstr ($errno)<br />\n";
                }
 }


  protected function dametablero($msg1) {
    foreach ($this->users as $user1) {
      if ($user1->modulo == $msg1->modulo) {
        echo "dametablero identifico el modulo ".$user1->id."\n";
        $this->send($user1, json_encode($msg1));
      }
    }
    return false;
  }

  protected function identificatablero($socket,$modulo) {
    foreach ($this->users as $user) {
      if ($user->socket == $socket) {
        $this->users[$user->id]->modulo=$modulo;
        echo "identificatablero identifico el modulo".$modulo." en users=".$this->users[$user->id]->modulo."\n";
        return true;
      }
    }
    return false;
  }


  protected function connected ($user) {
  }

  protected function closed ($user) {
  }
}

$echo = new echoServer("0.0.0.0","9001");

try {
##  $echo->interactive=false;
  $echo->run();
}
catch (Exception $e) {
  $echo->stdout($e->getMessage());
}

