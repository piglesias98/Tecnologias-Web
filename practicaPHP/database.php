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
	$query = "SELECT id, titulo, autor, categoria, descripcion,
														ingredientes, preparacion, fotografia_src
														FROM receta WHERE id='".mysqli_real_escape_string($db,$id)."'";
	echo $query;
	$res = mysqli_query($db, $query);

	if ($res && mysqli_num_rows($res)==1){
		$receta = mysqli_fetch_assoc($res);
		echo "receta rodo bien";
	}else{
		$receta = -1;
		echo "todo mal";
		echo $receta;
	}
	echo $receta;
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
															VALUES ('".mysqli_real_escape_string($db, $params['titulo'])."','"
															.mysqli_real_escape_string($db, $params['autor'])."','"
															.mysqli_real_escape_string($db, $params['categoria'])."','"
															.mysqli_real_escape_string($db, $params['descripcion'])."','"
															.mysqli_real_escape_string($db, $params['ingredientes'])."','"
															.mysqli_real_escape_string($db, $params['preparacion'])."','"
															.mysqli_real_escape_string($db, $params['fotografia_src'])."')");
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
		$query = "UPDATE receta SET titulo = '".mysqli_real_escape_string($db, $params['titulo'])."'
													SET autor = '".mysqli_real_escape_string($db, $params['autor'])."',
													SET categoria = '".mysqli_real_escape_string($db, $params['categoria'])."',
					                SET descripcion = '".mysqli_real_escape_string($db, $params['descripcion'])."',
					                SET ingredientes = '".mysqli_real_escape_string($db, $params['ingredientes'])."',
					                SET preparacion = '".mysqli_real_escape_string($db, $params['preparacion'])."',
													SET fotografia_src = '".mysqli_real_escape_string($db, $params['fotografia_src'])."'

		";

		$res = mysqli_query($db, $query );
		if (!$res){
			$info =  'Error al actualizar'.mysqli_error($db);
			echo "<p class='error'> Error al actualizar.mysqli_error($db)</p>";

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

// function dbGetNumRecetas($db, $cadena=''){
// 	if ($cadena==''){
// 		echo '<p>CADENA ES VACIA</p>';
// 		$query = "SELECT COUNT(*) FROM receta";
// 	}else{
// 		$query = "SELECT COUNT(*) FROM receta WHERE $cadena";
// 	}
	// $res = mysqli_query($db, $query);
	// $num = mysqli_fetch_row($res)[0];
	// mysqli_free_result($res);
// 	// return $num;
// }

?>
