<?php
session_start();
    include 'WebClientPrint.php';
    use Neodynamic\SDK\Web\WebClientPrint;
    use Neodynamic\SDK\Web\Utils;
    use Neodynamic\SDK\Web\DefaultPrinter;
    use Neodynamic\SDK\Web\InstalledPrinter;
    use Neodynamic\SDK\Web\ClientPrintJob;

            $cmds = $esc . "@"; //Initializes the printer (ESC @)
            $cmds .= $newLine . 'I'.$newLine;
            $cmds .= $esc . '!' . '0x38'; //Emphasized + Double-height + Double-width mode selected (ESC ! (8 + 16 + 32)) 56 dec => 38 hex
            $cmds .= 'CDMX '; //text to print
            $cmds .= $newLine . $newLine;
            $cmds .= $esc . '!' . '0x00'; //Character font A selected (ESC ! 0)
            $cmds .= 'MODULO: '.$_SESSION["wl_desmodulo"];
            $cmds .= $newLine;
            $cmds .= 'FILA: '.$_SESSION["fila"];
            $cmds .= $newLine;
            $cmds .= 'TURNO: '.$_SESSION["turnogrupo"];
            $cmds .= $newLine . $newLine;
            $cmds .= $Row["fecha_alta"];
            $cmds .= $newLine .'F'.$newLine;
            $cpj = new ClientPrintJob();
            $cpj->printerCommands = $cmds;
            $cpj->formatHexValues = true;
            //if ($useDefaultPrinter || $printerName === 'null'){
                $cpj->clientPrinter = new DefaultPrinter();
            //}else{
            //    $cpj->clientPrinter = new InstalledPrinter($printerName);
            //}

            //Send ClientPrintJob back to the client
            ob_clean();
            echo $cpj->sendToClient();
            ob_end_flush();
            exit();
?>
