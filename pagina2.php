<?php
ini_set('display_errors', -1);
echo 'inicio='.session_start().'<br>';
echo 'Bienvenido a la pagina2<br />';
echo 'nombre session:'.session_id()."<br>";

echo $_SESSION['color'];  // verde
echo $_SESSION['animal']; // gato
echo date('Y m d H:i:s', $_SESSION['instante']);

// Puede ser conveniente usar el SID aquícomo hicimos en pagina1.php
echo '<br /><a href="pagina1.php">pána 1</a>';
?>
