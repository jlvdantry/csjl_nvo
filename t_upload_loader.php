<?php
// Load JsHttpRequest backend.
//require_once "../../lib/JsHttpRequest/JsHttpRequest.php";
require_once "JsHttpRequest.php";
// Create main library object. You MUST specify page encoding!
$JsHttpRequest =& new JsHttpRequest("windows-1251");
// Store resulting data in $_RESULT array (will appear in req.responseJs).
$_RESULT = array(
  "name"   => @$_FILES['file']['name'],
    "size"   => @filesize($_FILES['file']['tmp_name']),
    ); 
    // Below is unparsed stream data (will appear in req.responseText).
    ?>
    <pre>
    <b>Request method:</b> <?=$_SERVER['REQUEST_METHOD'] . "\n"?>
    <b>Loader used:</b> <?=$JsHttpRequest->LOADER . "\n"?>
    <b>_FILES:</b> <?=print_r($_FILES, 1)?>
    </pre>
