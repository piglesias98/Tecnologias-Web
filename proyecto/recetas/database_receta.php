<?php

function dbGetRecetas($db, $cadena='', $orden=''){
	$query_start = "SELECT id, titulo, idautor FROM recetas";
	$query_end = '';
	if ($orden=='bComentadas'){
		$query_start = "SELECT r.id, r.titulo, r.idautor, count(c.id)
							from recetas r join comentarios c on c.id_receta = r.id";
		$query_end = " GROUP BY r.id ORDER BY count(c.id) DESC";
	} else if ($orden=='bPuntuacion'){
		$query_start = "SELECT r.id, r.titulo, r.idautor, avg(v.valoracion)
							from recetas r join valoraciones v on v.id_receta = r.id";
		$query_end = " GROUP BY r.id ORDER BY avg(v.valoracion) DESC";
	} else if ($orden=='bAlfabetico'){
		$query_end = " ORDER BY titulo";
	}
	$query = $cadena=='' ? $query_start : $query_start." WHERE ".$cadena;
	$query = $query_end=='' ? $query : $query.$query_end;
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
	$res = mysqli_query($db, "SELECT id, titulo, idautor, descripcion,
														ingredientes, preparacion
														FROM recetas WHERE id='".mysqli_real_escape_string($db,$id)."'");
	if ($res && mysqli_num_rows($res)==1){
		$receta = mysqli_fetch_assoc($res);
	}
	else{
		$receta = -1;
	}
	mysqli_free_result($res);
	return $receta;
}

function dbCrearReceta($db, $params){
	// Comprobar que no hay una receta con el mismo nombre
	$res = mysqli_query($db, "SELECT id, titulo FROM recetas WHERE titulo='{$params['titulo']}'");
	$num = mysqli_fetch_assoc($res);
	mysqli_free_result($res);
	if ($num>0){
		$info = "Ya existe una receta con ese título";
		echo "<p class = 'error'> Ya existe una receta con ese título </p>";
	}else{
		$idautor = $_SESSION['id'];
		$res = mysqli_query($db, "INSERT INTO recetas (titulo, idautor, descripcion,
																									ingredientes, preparacion)
															VALUES ('".mysqli_real_escape_string($db, $params['titulo'])."','"
															.mysqli_real_escape_string($db, $idautor)."','"
															.mysqli_real_escape_string($db, $params['descripcion'])."','"
															.mysqli_real_escape_string($db, $params['ingredientes'])."','"
															.mysqli_real_escape_string($db, $params['preparacion'])."')");
		if (!$res){
			$info = " Error en la creacion de la receta";
			echo "<p class = 'error'> Error en la creacion de la receta </p>".mysqli_error($db);
		}
	}

	if (isset($info))
		return $info;
	else{
		// Insertar categorías
		if (isset($params['categoria'])){
			$id_receta = mysqli_insert_id($db);
			foreach ($params['categoria'] as $value) {
				dbInsertCategoriaReceta($db, $id_receta, $value);
			}
		dbInsertLog($db, 'El usuario con email '.$_SESSION['email'].' ha creado la receta'.$params['titulo']);
		return true;
	}
}

}




function dbBorrarReceta($db, $id){
	$query = "DELETE FROM recetas WHERE id=$id";
	mysqli_query($db, $query);
	if (mysqli_affected_rows($db)==1){
		dbInsertLog($db, 'El usuario con email '.$_SESSION['email'].' ha borrado la receta con id'.$id);
		return true;
	}
	else
		return false;
}

function dbModificarReceta($db, $id, $params){
	// Comprobar que no hay una receta con el mismo nombre
	$res = mysqli_query($db, "SELECT id, titulo FROM recetas WHERE titulo='{$params['titulo']}'");
	$receta = mysqli_fetch_assoc($res);
	mysqli_free_result($res);
	if($receta['titulo'] == $params['titulo'] and $receta['id'] != $params['id']){
		$info =  'Ya hay una receta con ese mismo título';
		echo "<p class='error'>Ya hay una receta con ese mismo título</p>";
	}else{
		$query = "UPDATE recetas SET titulo = '".mysqli_real_escape_string($db, $params['titulo'])."',
					                descripcion = '".mysqli_real_escape_string($db, $params['descripcion'])."',
					                ingredientes = '".mysqli_real_escape_string($db, $params['ingredientes'])."',
					                preparacion = '".mysqli_real_escape_string($db, $params['preparacion'])."'
							WHERE id = '".mysqli_real_escape_string($db, $id)."'";
		$res = mysqli_query($db, $query );
		if (!$res){
			$info =  'Error al actualizar'.mysqli_error($db);
			echo "<p class='error'> Error al actualizar".mysqli_error($db)."</p>";
		}
	}
	if (isset($info))
		return $info;
	else{
		// Insertar categorías
		if (isset($params['categoria'])){
			foreach ($params['categoria'] as $value) {
				dbUpdateCategoriaReceta($db, $id, $value);
			}
		dbInsertLog($db, 'El usuario con email '.$_SESSION['email'].' ha modificado la receta con título '.$params['titulo']);
		return true;
	}
}
}

function dbArray2SQL($query){
	$cadena = '';
	if (array_key_exists('bTitulo', $query)){
		$cadena .= "titulo LIKE '%{$query['bTitulo']}%'";
	}
	if (array_key_exists('autor_id', $query)){
		if ($cadena != ''){
			$cadena .= " AND ";
		}
		$cadena .= "idautor LIKE '%{$query['autor_id']}%'";
	}
	if (array_key_exists('bCampo', $query)){
		if ($cadena != ''){
			$cadena .= " AND ";
		}
		$cadena .= " (titulo LIKE '%{$query['bCampo']}%'
								OR descripcion LIKE '%{$query['bCampo']}%'
								OR ingredientes LIKE '%{$query['bCampo']}%'
								OR preparacion LIKE '%{$query['bCampo']}%') ";
	}
	if (array_key_exists('bCategoria', $query)){
		if ($cadena != ''){
			$cadena .= " AND ";
		}
		foreach ($query['bCategoria'] as $value) {
			$cadena .= " categoria LIKE '%{$value}%' AND ";
		}
		$cadena = substr($cadena, 0, -4);
	}
	return $cadena;
}

function dbGetNumRecetas($db, $cadena=''){
	if ($cadena==''){
		$query = "SELECT COUNT(*) FROM recetas";
	}else{
		$query = "SELECT COUNT(*) FROM recetas WHERE $cadena";
	}
	$res = mysqli_query($db, $query);
	$num = mysqli_fetch_row($res)[0];
	$num = (int)$num;
	echo $num;
	mysqli_free_result($res);
	return $num;
}

function dbGetIdsReceta ($db){
	$query = "SELECT id FROM recetas";
	$res = mysqli_query($db, $query);
	if ($res){
		$id_array = mysqli_fetch_all($res, MYSQLI_ASSOC);
		foreach ($id_array as $key => $value) {
			$ids[] = $value['id'];
		}
	}
	else{
		$ids = -1;
	}
	mysqli_free_result($res);
	return $ids;
}

function dbInsertPicture($db, $id, $nombre){
	$query = "INSERT INTO fotos (id_receta, ubicacion)
														VALUES ('".mysqli_real_escape_string($db, $id)."','"
														.mysqli_real_escape_string($db, $nombre)."')";
	$res = mysqli_query($db, $query);

	if (!$res){
		$info = " Error en la subida de imagen";
		echo "<p class = 'error'> Error en la subida de imagen </p>".mysqli_error($db);
	}else {
		dbInsertLog($db, 'El usuario con email '.$_SESSION['email'].' ha insertado una foto a la receta con id '.$id);
	}
}

function dbGetPictures($db, $id){
	$query =   "SELECT ubicacion, id
		 					FROM fotos
							WHERE id_receta = ".mysqli_real_escape_string($db, $id);
	$res = mysqli_query($db, $query);
	if ($res && mysqli_num_rows($res)>0){
		$fotos = mysqli_fetch_all($res, MYSQLI_ASSOC);
	}
	else{
		$fotos = false;
	}
	mysqli_free_result($res);
	return $fotos;
}

function dbBorrarFoto($db, $id){
	$query = "DELETE FROM fotos WHERE id=$id";
	mysqli_query($db, $query);
	if (mysqli_affected_rows($db)==1){
		dbInsertLog($db, 'El usuario con email '.$_SESSION['email'].' ha borrado la foto con id'.$id);
		return true;
	}
	else
		return false;
}

function dbGetComments($db, $id){
	$query =   "SELECT id, comentario, fecha, id_usuario
		 					FROM comentarios
							WHERE id_receta = ".mysqli_real_escape_string($db, $id);
	$res = mysqli_query($db, $query);
	if ($res && mysqli_num_rows($res)>0){
		$comentarios = mysqli_fetch_all($res, MYSQLI_ASSOC);
	}
	else{
		$comentarios = false;
	}
	mysqli_free_result($res);
	return $comentarios;
}

function dbBorrarComentario($db, $id){
	$query = "DELETE FROM comentarios WHERE id=$id";
	mysqli_query($db, $query);
	if (mysqli_affected_rows($db)==1){
		dbInsertLog($db, 'El usuario con email '.$_SESSION['email'].' ha borrado el comentario con id'.$id);
		return true;
	}
	else
		return false;
}

function dbInsertComment($db, $id, $comentario){
	$date = date('Y-m-d');
	$query = "INSERT INTO comentarios (id_receta, comentario, fecha)
														VALUES ($id, '$comentario', '$date')";
	$res = mysqli_query($db, $query);


	if (!$res){
		$info = " Error en la subida del comentario";
		echo "<p class = 'error'> Error en la subida del comentario </p>".mysqli_error($db);


	}else if (isset($_SESSION['identificado'])){
		$id_comentario = mysqli_insert_id($db);
		$idautor = $_SESSION['id'];
		$query = "UPDATE comentarios SET id_usuario=$idautor WHERE id = $id_comentario ";
		$res = mysqli_query($db, $query);

		if (!$res){
			$info = " Error en la asignación del autor";
			echo "<p class = 'error'> Error en la asignación del autor </p>".mysqli_error($db);
		}else{
			dbInsertLog($db, 'El usuario con email '.$_SESSION['email'].' ha insertado un comentario a la receta con id '.$id);
		}
	}
	return $res;

}


function dbInsertValoracion($db, $id, $valoracion){
	$query = "INSERT INTO valoraciones (id_receta, valoracion)
														VALUES ($id, $valoracion)";
	$res = mysqli_query($db, $query);


	if (!$res){
		$info = " Error en la subida de la valoracion";
		echo "<p class = 'error'> Error en la subida de la valoracion </p>".mysqli_error($db);


	}else if (isset($_SESSION['identificado'])){
		$id_valoracion = mysqli_insert_id($db);
		$idautor = $_SESSION['id'];
		$query = "UPDATE valoraciones SET id_usuario=$idautor WHERE id = $id_valoracion ";
		$res = mysqli_query($db, $query);

		if (!$res){
			$info = " Error en la asignación del autor";
			echo "<p class = 'error'> Error en la asignación del autor </p>".mysqli_error($db);
		}else{
			dbInsertLog($db, 'El usuario con email '.$_SESSION['email'].' ha hecho una valoración a la receta con id '.$id);
		}
	}

	return $res;

}

function dbGetValoracion($db, $id){
	$query =   "SELECT ROUND(AVG(valoracion),2)
		 					FROM valoraciones
							WHERE id_receta = ".mysqli_real_escape_string($db, $id);
	$res = mysqli_query($db, $query);
	if ($res && mysqli_num_rows($res)>0){
		$valoraciones = mysqli_fetch_row($res);
	}
	else{
		$valoraciones = 0;
	}
	mysqli_free_result($res);
	return $valoraciones;
}

function dbGetValoradas($db){
	$query =   "SELECT r.titulo as 'titulo', round(avg(v.valoracion),2) as 'media'
							from recetas r join valoraciones v on v.id_receta = r.id
							GROUP BY r.id ORDER BY avg(v.valoracion) DESC limit 3";
	$res = mysqli_query($db, $query);
	if ($res && mysqli_num_rows($res)>0){
		$valoraciones = mysqli_fetch_all($res, MYSQLI_ASSOC);
	}
	else{
		$valoraciones = 0;
	}
	mysqli_free_result($res);
	return $valoraciones;
}

function dbGetCategorias($db, $id){
	$query =   "SELECT l.nombre as 'nombre'
							FROM categorias c join lista_categorias l on c.categorias_id = l.id
							WHERE c.receta_id = $id";
	$res = mysqli_query($db, $query);
	if ($res && mysqli_num_rows($res)>0){
		$valoraciones = mysqli_fetch_all($res, MYSQLI_ASSOC);
	}
	else{
		$valoraciones = 0;
	}
	mysqli_free_result($res);
	return $valoraciones;
}

 ?>
