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


<?php showReceta($receta, $id);?>
</div>
