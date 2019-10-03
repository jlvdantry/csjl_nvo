<?php
##error_reporting(E_ALL);
##ini_set('display_errors', 1);
class WebsocketClient
{
        private $_Socket = null;
        private $local = "https://solin.com";
        public  $conectado = false;
        public function __construct($host, $port)
        {
                $this->_connect($host, $port);
        }

        public function __destruct()
        {
                $this->_disconnect();
        }

        private function _connect($host, $port, $cert)
        {
        $host1=$host.":".$port;
        $head = "GET / HTTP/1.1"."\r\n".
        "Upgrade: websocket"."\r\n".
        "Connection: Upgrade"."\r\n".
        "Origin: $this->local"."\r\n".
        "Host: localhost:9001"."\r\n".
        "Sec-WebSocket-Key: ookA6DFrcn7q2JOVHpoyrQ=="."\r\n".
        "sec-websocket-Version: 13\r\n".
        "Content-Length: 100\r\n\r\n";
        ##$this->_Socket = fsockopen($host, $port, $errno, $errstr, 2);
        $context = stream_context_create();
        stream_context_set_option($context, "ssl", "peer_name", 'solin.com');
        stream_context_set_option($context, "ssl", "verify_peer", false);
        stream_context_set_option($context, "ssl", "allow_self_signed", true);
        stream_context_set_option($context, "ssl", "local_cert", "solin.pem");
        stream_context_set_option($context, "ssl", "disable_compression", true);
        stream_context_set_option($context, "ssl", "SNI_enabled", true);
        stream_context_set_option($context, "ssl", "ciphers", 'ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-AES256-GCM-SHA384:DHE-RSA-AES128-GCM-SHA256:DHE-DSS-AES128-GCM-SHA256:kEDH+AESGCM:ECDHE-RSA-AES128-SHA256:ECDHE-ECDSA-AES128-SHA256:ECDHE-RSA-AES128-SHA:ECDHE-ECDSA-AES128-SHA:ECDHE-RSA-AES256-SHA384:ECDHE-ECDSA-AES256-SHA384:ECDHE-RSA-AES256-SHA:ECDHE-ECDSA-AES256-SHA:DHE-RSA-AES128-SHA256:DHE-RSA-AES128-SHA:DHE-DSS-AES128-SHA256:DHE-RSA-AES256-SHA256:DHE-DSS-AES256-SHA:DHE-RSA-AES256-SHA:AES128-GCM-SHA256:AES256-GCM-SHA384:ECDHE-RSA-RC4-SHA:ECDHE-ECDSA-RC4-SHA:AES128:AES256:RC4-SHA:HIGH:!aNULL:!eNULL:!EXPORT:!DES:!3DES:!MD5:!PSK');

        $this->_Socket = stream_socket_client($host1, $errno, $errstr, 30,STREAM_CLIENT_CONNECT, $context);
        if ($this->_Socket === false) {
            $this->log("No se conecto");
        }

        $this->log("cliente socket errno".$errno." errstr=".$errstr);
        if ($errstr=="")
        {
            $this->log("cliente socket se fue por cierto=".$head);
            fwrite($this->_Socket, $head );
            $this->log("cliente socket paso fwrite");
            $headers = fread($this->_Socket, 2000);
            $this->log("cliente socket paso fread");
            $this->conectado=true;
        } else {
            $this->log("cliente socket se fue por falso");
            $this->conectado=false;
        }
         }

        public function sendData($data)
        {
            fwrite($this->_Socket, $this->hybi10Encode($data)) or die('error:'.$errno.':'.$errstr);
            $wsdata = fread($this->_Socket, 2000);
        }

        private function _disconnect()
        {
                fclose($this->_Socket);
        }



        private function hybi10Decode($data)
{
    $bytes = $data;
    $dataLength = '';
    $mask = '';
    $coded_data = '';
    $decodedData = '';
    $secondByte = sprintf('%08b', ord($bytes[1]));
    $masked = ($secondByte[0] == '1') ? true : false;
    $dataLength = ($masked === true) ? ord($bytes[1]) & 127 : ord($bytes[1]);

    if($masked === true)
    {
        if($dataLength === 126)
        {
           $mask = substr($bytes, 4, 4);
           $coded_data = substr($bytes, 8);
        }
        elseif($dataLength === 127)
        {
            $mask = substr($bytes, 10, 4);
            $coded_data = substr($bytes, 14);
        }
        else
        {
            $mask = substr($bytes, 2, 4);       
            $coded_data = substr($bytes, 6);        
        }   
        for($i = 0; $i < strlen($coded_data); $i++)
        {       
            $decodedData .= $coded_data[$i] ^ $mask[$i % 4];
        }
    }
    else
    {
        if($dataLength === 126)
        {          
           $decodedData = substr($bytes, 4);
        }
        elseif($dataLength === 127)
        {           
            $decodedData = substr($bytes, 10);
        }
        else
        {               
            $decodedData = substr($bytes, 2);       
        }       
    }   

    return $decodedData;
}


private function hybi10Encode($payload, $type = 'text', $masked = true) {
    $frameHead = array();
    $frame = '';
    $payloadLength = strlen($payload);

    switch ($type) {
        case 'text':
            // first byte indicates FIN, Text-Frame (10000001):
            $frameHead[0] = 129;
            break;

        case 'close':
            // first byte indicates FIN, Close Frame(10001000):
            $frameHead[0] = 136;
            break;

        case 'ping':
            // first byte indicates FIN, Ping frame (10001001):
            $frameHead[0] = 137;
            break;

        case 'pong':
            // first byte indicates FIN, Pong frame (10001010):
            $frameHead[0] = 138;
            break;
    }

    // set mask and payload length (using 1, 3 or 9 bytes)
    if ($payloadLength > 65535) {
        $payloadLengthBin = str_split(sprintf('%064b', $payloadLength), 8);
        $frameHead[1] = ($masked === true) ? 255 : 127;
        for ($i = 0; $i < 8; $i++) {
            $frameHead[$i + 2] = bindec($payloadLengthBin[$i]);
        }

        // most significant bit MUST be 0 (close connection if frame too big)
        if ($frameHead[2] > 127) {
            $this->close(1004);
            return false;
        }
    } elseif ($payloadLength > 125) {
        $payloadLengthBin = str_split(sprintf('%016b', $payloadLength), 8);
        $frameHead[1] = ($masked === true) ? 254 : 126;
        $frameHead[2] = bindec($payloadLengthBin[0]);
        $frameHead[3] = bindec($payloadLengthBin[1]);
    } else {
        $frameHead[1] = ($masked === true) ? $payloadLength + 128 : $payloadLength;
    }

    // convert frame-head to string:
    foreach (array_keys($frameHead) as $i) {
        $frameHead[$i] = chr($frameHead[$i]);
    }

    if ($masked === true) {
        // generate a random mask:
        $mask = array();
        for ($i = 0; $i < 4; $i++) {
            $mask[$i] = chr(rand(0, 255));
        }

        $frameHead = array_merge($frameHead, $mask);
    }
    $frame = implode('', $frameHead);
    // append payload to frame:
    for ($i = 0; $i < $payloadLength; $i++) {
        $frame .= ($masked === true) ? $payload[$i] ^ $mask[$i % 4] : $payload[$i];
    }

    return $frame;
}
/*
 *      graba en el log
 */
        function log($str)
        {
            $dt = date("Y-m-d H:i:s ");
            $dia = date("Ymd");
            error_log("$dt $str session_id=".session_id()."\n",3,"turnos".$dia.".log");
        }

}

?>
