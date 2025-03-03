<?php

/*
En backup.php se encuentran las funciones relativas a las copias de seguridad y
restaurado de la base de datos, a las que tiene acceso el administrador,
excepto la última que se ejecuta de manera automática si no hay tablas en la BBDD.
*/


function dbBackup($db){
  $tablas = array();
  $result = mysqli_query($db, 'SHOW TABLES');
  while($row = mysqli_fetch_row($result)){
    $tablas[] = $row[0];
  }


  // Salvar cada tabla
  $salida ='';
  foreach($tablas as $tab){
    $result = mysqli_query($db, 'SELECT * FROM '.$tab);
    $num = mysqli_num_fields($result);
    $salida .= 'DROP TABLE '.$tab.';';
    $row2 = mysqli_fetch_row(mysqli_query($db, 'SHOW CREATE TABLE '.$tab));
    $salida .= "\n\n".$row2[1].";\n\n"; //row2[0]=nombre de la tabla
    while ($row = mysqli_fetch_row($result)){
      $salida .= 'INSERT INTO '.$tab.' VALUES(';
      for ($j=0; $j<$num; $j++){
        $row[$j] = addslashes($row[$j]);
        $row[$j] = preg_replace("/\n/", "\\n", $row[$j]);
        if(isset($row[$j]))
          $salida .= '"'.$row[$j].'"';
        else {
          $salida .= '""';
        }
        if ($j < ($num-1)) $salida .= ',';
      }
      $salida .= ");\n";
    }
    $salida .= "\n\n\n";
  }

  return $salida;
}


function dbRestore($db, $fichero){
  // Procedemos a la restauración de la Base de Datos
  // Deshabilitamos las restricciones de las claves externas
  mysqli_query($db, 'SET FOREIGN_KEY_CHECKS=0');
  // Borramos la base de datos
  $result = mysqli_query($db, 'SHOW TABLES');
  while($row = mysqli_fetch_row($result))
    mysqli_query($db, 'DELETE * FROM '.$row[0]);

  $error='';
  $sql = file_get_contents($fichero);
  $queries = explode(';',$sql);
  foreach ($queries as $q){
    if(!mysqli_query($db, $q))
      $error .= mysqli_error($db);
  }

  mysqli_query($db, 'SET FOREIGN_KEY_CHECKS=1');
}

function dbBorradoCompleto($db){
  // Procedemos a la restauración de la Base de Datos
  // Deshabilitamos las restricciones de las claves externas
  mysqli_query($db, 'SET FOREIGN_KEY_CHECKS=0');
  // Borramos la base de datos
  $result = mysqli_query($db, 'SHOW TABLES');

  while($row = mysqli_fetch_row($result)){
    // Tenemos cuidado con no borrar a los usuarios colaboradores
    if ($row[0] == 'usuarios'){
      $query = "DELETE FROM ".$row[0]." WHERE tipo='colaborador'";
    }else{
      $query = 'DELETE FROM '.$row[0];

    }
    mysqli_query($db, $query);
  }

  mysqli_query($db, 'SET FOREIGN_KEY_CHECKS=1');
}

function dbCheckFirstTime($db){
  // Veamos si hay tablas
  $result = mysqli_query($db, 'SHOW TABLES');
  $result_array = mysqli_fetch_all($result);
  if (sizeof($result_array) == 0){
    echo "<p> No hay tablas, se procede a la autoinstalación</p>";
    mysqli_query($db, 'SET FOREIGN_KEY_CHECKS=0');
    $sql = file_get_contents('database/initial_db.sql');
    $queries = explode(';',$sql);
    foreach ($queries as $q){
      if(!mysqli_query($db, $q))
        $error .= mysqli_error($db);
    }

    mysqli_query($db, 'SET FOREIGN_KEY_CHECKS=1');
  }

}



 ?>
