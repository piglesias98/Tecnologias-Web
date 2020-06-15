<?php
require_once('dbcredenciales.php');

/*
En database.php encontramos las funciones relativas a la base de datos,
entre ellas las más básicas como la conexión y desconexión y las que no son propias
de usuarios o recetas, que se encuentran en sus ficheros correspondientes.
*/


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
	$query = "SELECT fecha, descripcion FROM log ORDER BY fecha DESC";
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


function dbGetListaCategorias($db){
	$query = "SELECT id, nombre FROM lista_categorias";
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

function dbEditarCategoria($db, $id, $nombre){
	$query = "UPDATE lista_categorias
						SET nombre = '".mysqli_real_escape_string($db, $nombre)."'
	 					WHERE id=$id";
	$res = mysqli_query($db, $query);
	if (!$res){
		$info = " Error en la actualización de la categoría";
		echo "<p class = 'error'> Error en la actualización de la categoría </p>".mysqli_error($db);
		dbInsertLog($db, 'El usuario con email '.$_SESSION['email'].' ha tenido un error
								en la actualización de la categoría'.mysqli_error($db));
	}else{
		dbInsertLog($db, 'El usuario con email '.$_SESSION['email'].' ha actualizado la categoría'.$id);
	}
	return $res;
}

function dbBorrarCategoria($db, $id){
	$query = "DELETE FROM lista_categorias WHERE id=$id";
	mysqli_query($db, $query);
	if (mysqli_affected_rows($db)==1){
		dbInsertLog($db, 'El usuario con email '.$_SESSION['email'].' ha borrado la categoría con id'.$id);
		return true;
	}
	else
		return false;
}


function dbInsertCategoria($db, $categoria){
	$query = "INSERT INTO lista_categorias (nombre)
														VALUES ('".mysqli_real_escape_string($db, $categoria)."')";
	$res = mysqli_query($db, $query);

	if (!$res){
		$info = " Error en la inserción de la categoría";
		echo "<p class = 'error'> Error en la inserción de la categoría </p>".mysqli_error($db);
	}else {
		dbInsertLog($db, 'El usuario con email '.$_SESSION['email'].' ha insertado una categoría');
	}
}


function dbInsertCategoriaReceta($db, $id, $categoria){
	$query = "INSERT INTO categorias (receta_id, categorias_id)
							VALUES($id, (SELECT id FROM lista_categorias WHERE '$categoria' = nombre))";
	$res = mysqli_query($db, $query);

	if (!$res){
		$info = " Error en la inserción de la categoría";
		echo "<p class = 'error'> Error en la inserción de la categoría </p>".mysqli_error($db);
	}else {
		dbInsertLog($db, 'El usuario con email '.$_SESSION['email'].' ha insertado una categoría');
	}
}

function dbUpdateCategoriaReceta($db, $id, $categoria){
	# Primero obtenemos el id que queremos actualizar
	$query = "UPDATE categorias set categorias_id =
					(SELECT id FROM lista_categorias WHERE lista_categorias.nombre = '$categoria')
					where receta_id = $id";
	$res = mysqli_query($db, $query);

	if (!$res){
		$info = " Error en la inserción de la categoría";
		echo "<p class = 'error'> Error en la inserción de la categoría </p>".mysqli_error($db);
	}else {
		dbInsertLog($db, 'El usuario con email '.$_SESSION['email'].' ha actualizado una categoría');
	}
}
?>
