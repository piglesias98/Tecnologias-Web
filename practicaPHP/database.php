<?php
require_once('dbcredenciales.php');
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
	$res = mysqli_query($db, "SELECT id, titulo FROM receta");
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

function dbGetReceta($db, $id){

}

function dbCrearReceta($db, $params){
	// Comprobar que no hay una receta con el mismo nombre
	$res = mysqli_query($db, "SELECT id, titulo FROM receta WHERE titulo='{$params['titulo']}'");
	$num = mysqli_fetch_assoc($res);
	mysqli_free_result($res);
	if ($num>0)
		$info[] = 'Ya existe una receta con ese título';
	else{
		$res = mysqli_query($db, "INSERT INTO receta (titulo, autor, categoria, descripcion,
																									ingredientes, preparacion, fotografia)
															VALUES ('{$params['titulo']}',
															'{$params['autor']}',
															'{$params['categoria']}',
															'{$params['descripcion']}',
															'{$params['ingredientes']}',
															'{$params['preparacion']}',
															'{$params['fotografia_src_completa']}')");
		if (!$res){
			$info[] = "error en la creación de la receta";
			$info[] = mysqli_error($db);
		}
	}
	if (isset($info))
		return $info;
	else
		return true;
}


function dbBorrarReceta($db, $id){
	mysqli_query($db, "DELETE FROM receta WHERE id='$id'");
	if (mysqli_affected_rows($db)==1)
		return true;
	else
		return false;
}

function dbModificarReceta($db, $id, $params){
	// Comprobar que no hay una receta con el mismo nombre
	$res = mysqli_query($db, "SELECT id, titulo FROM receta WHERE titulo='{$params['tutulo']}'");
	$receta = mysqli_fetch_assoc($res);
	mysqli_free_result($res);
	if($receta['titulo'] == $params['titulo'] and $receta['id'] != $params['id']){
		$info[]= 'Ya hay una receta con ese mismo título';
	}else{
		$res = mysqli_query($db, "UPDATE receta
															SET titulo = '{$params['titulo']}',
															SET autor = '{$params['autor']}',
															SET categoria = '{$params['categoria']}',
															SET descripcion = '{$params['descripcion']}',
															SET ingredientes = '{$params['ingredientes']}',
															SET preparacion = '{$params['preparacion']}',
															SET fotografia = '{$params['fotografia']}'
															");
		if (!$res){
			$info[]= "Error al actualizar";
			$info[] = mysqli_error($db);
		}
	}
	if (isset($info))
		return $info;
	else
		return true;
}

?>
