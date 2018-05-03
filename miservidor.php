<?php
require_once('websockets.php');
class echoServer extends WebSocketServer {
  //protected $maxBufferSize = 1048576; //1MB... overkill for an echo server, but potentially plausible for other applications.
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

##$echo = new echoServer("0.0.0.0","9001");
$echo = new echoServer("0.0.0.0","9001");

try {
  $echo->interactive=false;
  $echo->run();
}
catch (Exception $e) {
  $echo->stdout($e->getMessage());
}

