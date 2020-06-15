<?php

/*
backup.php genera una copia de seguridad de la base de datos,
ha de estar en un fichero aparte para que no genere código html
*/

session_start();
require_once('database/database.php');
require_once('database/dbbackup.php');

if(isset($_SESSION['admin']) and $_SESSION['admin']==true){
  if (isset($_GET['download'])){
    if (!is_string($db=dbConnection())){
      header('Content-Type: applicaction/octet-stream');
      header('Content-Disposition: attachment; filename="db_backup.sql"');
      echo dbBackup($db);
      dbDisconnection($db);
    }
  }else{
    echo "<p class='error'> Debe mandar un parámetro GET</p>";
  }
}else{
  echo "<p class='error'> No estás autorizado para acceder a esta página</p>";
}



 ?>
