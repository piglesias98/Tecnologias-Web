<?php

/*
Página inicial por defecto. Muestra la última receta visitada en la sesión
anterior y si se trata de la primera visita una receta al azar.
También comprueba que la base de datos esté populada y en el caso de no estarlo
genera la base de datos a partir del archivo initial_db.sql
*/

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
