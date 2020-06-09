<?php
require_once 'database/database.php';
require_once 'recetas/database_receta.php';
require_once 'recetas/formulario_receta.php';

$db = dbConnection();
if (isset($_COOKIE['ultima_receta'])){
  $id = $_COOKIE['ultima_receta'];
}else{
  $num_recetas = dbGetNumRecetas($db);
  $id = mt_rand(1, $num_recetas);
}
$receta = dbGetReceta($db, $id);
 ?>

<div class="contenido">
<h3>Bienvenido!</h3>
<?php showReceta($receta, $id);?>
</div>
</div>
