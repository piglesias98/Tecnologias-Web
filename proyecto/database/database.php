<?php
require_once('dbcredenciales.php');

function dbConnection(){
	$db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWD, DB_NAME);
	if ($db) {
		mysqli_set_charset($db, "utf8");
		return $db;
	}else{
		echo "<p>ERROR EN LA CONEXIÓN</p>";
		return "Error de conexión en la base de datos (".mysqli_connect_errno().") : ".mysqli_connect_error();
	}
}


function dbDisconnection($db){
	mysqli_close($db);
}


function dbInsertLog($db, $descripcion){
	$date = date('Y-m-d H:i:s');
	$query = "INSERT INTO log (descripcion, fecha)
														VALUES ('$descripcion', '$date')";
	$res = mysqli_query($db, $query);
	if (!$res){
		$info = " Error en la inserción del log";
		echo "<p class = 'error'> Error en la inserción del log </p>".mysqli_error($db);
	}
}

function dbGetNumLog($db){
	$query = "SELECT COUNT(*) FROM log";
	$res = mysqli_query($db, $query);
	$num = mysqli_fetch_row($res)[0];
	$num = (int)$num;
	mysqli_free_result($res);
	return $num;
}

function dbGetLogs($db){
	$query = "SELECT fecha, descripcion FROM log ORDER BY fecha";
	$res = mysqli_query($db, $query);
	if ($res){
		if (mysqli_num_rows($res)>0){
			$tabla = mysqli_fetch_all($res, MYSQLI_ASSOC);
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
