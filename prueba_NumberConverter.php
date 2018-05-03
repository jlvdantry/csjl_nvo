<?php
include('NumberConverter.php');
$a= new NumberConverter();
for ($x = 1; $x <= 9999; $x++) {
    echo "The number ".$x." is: ".$a->convert($x,"O")."<br>";
} 
?>
