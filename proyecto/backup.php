<?php
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
      exit();
    }
  }else{
    echo "<p class='error'> Debe rellenar el parámetro error</p>";
  }
}else{
  echo "<p class='error'> No estás autorizado para acceder a esta página</p>";
}



 ?>
