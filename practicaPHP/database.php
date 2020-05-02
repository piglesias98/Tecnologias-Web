<?php
$host="localhost";
$port=3306;
$socket="";
$user="root";
$password="paula";
$dbname="receta";

$con = new mysqli($host, $user, $password, $dbname, $port, $socket)
	or die ('Could not connect to the database server' . mysqli_connect_error());

  $query = "SELECT titulo, autor, categoria, descripcion, ingredientes, preparacion, fotografia FROM receta";


  if ($stmt = $con->prepare($query)) {
      $stmt->execute();
      $stmt->bind_result($titulo, $autor, $categoria, $descripcion, $ingredientes, $preparacion, $fotografia);
      while ($stmt->fetch()) {
          printf("%s, %s, %s, %s, %s, %s, %s\n", $titulo, $autor, $categoria, $descripcion, $ingredientes, $preparacion, $fotografia);
      }
      $stmt->close();
  }

//$con->close();
?>
