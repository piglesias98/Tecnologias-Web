<?php
require_once('dbcredenciales.php');

function dbConnection(){
	$db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWD, DB_NAME);
	if ($db) {
		mysqli_set_charset($db, "utf8");
		return $db;
	}else{
		return "Error de conexión en la base de datos (".mysqli_connect_errno().") : ".mysqli_connect_error();
	}
}


function dbDisconnection($db){
	mysqli_close($db);
}

function dbGetRecetas($db, $cadena='', $orden=''){
	$query = "SELECT id, titulo FROM receta";
	$query = $cadena=='' ? $query : $query." WHERE ".$cadena;
	$query = $orden=='' ? $query : $query." ORDER BY titulo ".$orden;
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

function dbGetReceta($db, $id){
	$res = mysqli_query($db, "SELECT id, titulo, autor, categoria, descripcion,
														ingredientes, preparacion, fotografia_src
														FROM receta WHERE id='".mysqli_real_escape_string($db,$id)."'");
	if ($res && mysqli_num_rows($res)==1){
		$receta = mysqli_fetch_assoc($res);
	}
	else{
		$receta = false;
	}
	mysqli_free_result($res);
	return $receta;
}

function dbCrearReceta($db, $params){
	// Comprobar que no hay una receta con el mismo nombre
	$res = mysqli_query($db, "SELECT id, titulo FROM receta WHERE titulo='{$params['titulo']}'");
	$num = mysqli_fetch_assoc($res);
	mysqli_free_result($res);
	if ($num>0){
		echo "<p class = 'error'> Error en la creacion d el areceta </p>";
		$info = 'Ya existe una receta con ese título';
	}
	else{
		$res = mysqli_query($db, "INSERT INTO receta (titulo, autor, categoria, descripcion,
																									ingredientes, preparacion, fotografia_src)
															VALUES ('{$params['titulo']}',
															'{$params['autor']}',
															'{$params['categoria']}',
															'{$params['descripcion']}',
															'{$params['ingredientes']}',
															'{$params['preparacion']}',
															'{$params['fotografia_src']}')");
		if (!$res){
			$info = "Error en la creacion d el areceta";
			echo "<p class = 'error'> Error en la creacion d el areceta </p>".mysqli_error($db);
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
		$info =  'Ya hay una receta con ese mismo título';
		echo "<p class='error'>Ya hay una receta con ese mismo título</p>";
	}else{
		$res = mysqli_query($db, "UPDATE receta
															SET titulo = '{$params['titulo']}',
															SET autor = '{$params['autor']}',
															SET categoria = '{$params['categoria']}',
															SET descripcion = '{$params['descripcion']}',
															SET ingredientes = '{$params['ingredientes']}',
															SET preparacion = '{$params['preparacion']}',
															SET fotografia_src = '{$params['fotografia_src']}'
															");
		if (!$res){
			$info =  'Error al actualizar'.mysqli_error($db);
			echo "<p class='error'>Error al actualizar".mysqli_error($db)."</p>";
		}
	}
	if (isset($info))
		return $info;
	else
		return true;
}

function dbArray2SQL($query){
	$cadena = '';
	if (array_key_exists('bTitulo', $query)){
		$cadena .= "titulo LIKE '%{$query['bTitulo']}%'";
	}
	return $cadena;
}

function dbGetNumRecetas($db, $cadena=''){
	$res = mysqli_query($db, "SELECT COUNT(*) FROM receta WHERE $cadena");
	$num = mysqli_fetch_assoc($res);
	mysqli_free_result($res);
	return $num;
}

?>
