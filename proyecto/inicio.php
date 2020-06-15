<?php
require_once 'database/database.php';
require_once 'recetas/database_receta.php';
require_once 'recetas/formulario_receta.php';
require_once 'database/dbbackup.php';

$db = dbConnection();
dbCheckFirstTime($db);
dbDisconnection($db);


if (isset($_COOKIE['ultima_receta'])){
  $id = $_COOKIE['ultima_receta'];
  $db = dbConnection();
  $receta = dbGetReceta($db, $id);
  if ($receta == -1){
    $ids = dbGetIdsReceta($db);
    $random =  $ids[array_rand($ids)];
    $receta = dbGetReceta($db, $random);
  }
  dbDisconnection($db);
}else{
  $db = dbConnection();
  $ids = dbGetIdsReceta($db);
  $random =  $ids[array_rand($ids)];
  $receta = dbGetReceta($db, $random);
  dbDisconnection($db);
}

showReceta($receta, $id);

 ?>


<?php ?>
</div>
