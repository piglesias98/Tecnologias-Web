<?php

function dbConnection(){
	$db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWD, DB_NAME);
	if ($db) {
		echo "<p>Conexión con éxito</p>";
		mysqli_set_charset($db, "utf8");
		return $db;
	}else{
		return "Error de conexión en la base de datos (".mysqli_connect_errno().") : ".mysqli_connect_error();
	}
}

function dbDisconnection($db){
	mysqli_close($db);
}

function dbGetRecetas($db){
	$res = mysqli_query($db. "SELECT * FROM receta");
	if ($res){
		if (mysqli_num_rows($res)>0){
			$tabla = mysqli_fetch_all($res, MYSQL_ASSOC);
		}else{
			$tabla = [];
		}
		// Libero la memoria de la consulta
		mysqli_free_result($res);
	}else{
		$tabla = false;
	}
	return $tabla;
}

?>
