<?php
ini_set('display_errors', -1);
session_start();

echo 'Bienvenido a la página #1<br>';
echo 'session '.session_id();

$_SESSION['color']  = 'verde';
$_SESSION['animal'] = 'gato';
$_SESSION['instante']   = time();

// Funciona si la cookie de sesióue aceptada
echo '<br /><a href="pagina2.php">pagina 2</a>';

// O quizápasar el id de sesiósi fuera necesario
echo '<br /><a href="pagina2.php?' . SID . '">pagina 2</a>';
?>
