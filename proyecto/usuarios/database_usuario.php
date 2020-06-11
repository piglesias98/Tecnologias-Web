<?php

function dbCrearUsuario($db, $params){
	echo $params['foto_perfil_src'];
	$rol = 'colaborador';
	$query = "INSERT INTO usuarios (nombre, apellidos, email, clave1, tipo,
																								foto_perfil_src)
														VALUES ('".mysqli_real_escape_string($db, $params['nombre'])."','"
														.mysqli_real_escape_string($db, $params['apellidos'])."','"
														.mysqli_real_escape_string($db, $params['email'])."','"
														.mysqli_real_escape_string($db, password_hash($params['clave1'], PASSWORD_DEFAULT))."','"
														.$rol."','"
														.mysqli_real_escape_string($db, $params['foto_perfil_src'])."')";
	$res = mysqli_query($db, $query);
	if (!$res){
		$info = "Error en la creacion del usuario";
		echo "<p class = 'error'> Error en la creacion d el areceta </p>".mysqli_error($db);
	}
	if (isset($info))
		return $info;
	else
		return true;
}


function dbModificarUsuario($db, $id, $params){
	$query = "UPDATE usuarios SET nombre = '".mysqli_real_escape_string($db, $params['nombre'])."',
												apellidos = '".mysqli_real_escape_string($db, $params['apellidos'])."',
												email = '".mysqli_real_escape_string($db, $params['email'])."',";
	if (isset($params['clave1'])){
		$query = $query."clave1 = '".mysqli_real_escape_string($db, password_hash($params['clave1'], PASSWORD_DEFAULT))."',";
	}
	$query = $query."foto_perfil_src = '".mysqli_real_escape_string($db, $params['foto_perfil_src'])."'
									WHERE id = '".mysqli_real_escape_string($db, $id)."'";
	$res = mysqli_query($db, $query );
	if (!$res){
		$info =  'Error al actualizar'.mysqli_error($db);
		echo "<p class='error'> Error al actualizar".mysqli_error($db)."</p>";
	}
	if (isset($info))
		return $info;
	else
		return true;
}

function dbCheckUsuario($db, $email){
	$res = mysqli_query($db, "SELECT id, nombre, tipo
														FROM usuarios WHERE email='".mysqli_real_escape_string($db,$email)."'");
	if ($res and mysqli_num_rows($res)==1)
		return mysqli_fetch_assoc($res);
	else
		return False;
}

function dbPasswordVerify($db, $clave, $id){
	$res = mysqli_query($db, "SELECT clave1
														FROM usuarios WHERE id='".mysqli_real_escape_string($db,$id)."'");
	if ($res and mysqli_num_rows($res)==1){
		$pwd = mysqli_fetch_row($res);
		if (password_verify($clave, ($pwd[0])))
			return true;
	}
	else
		return false;
}

function dbGetUsuario($db, $id){
	$res = mysqli_query($db, "SELECT id, nombre, apellidos, email,
														foto_perfil_src
														FROM usuarios WHERE id='".mysqli_real_escape_string($db,$id)."'");
	if ($res && mysqli_num_rows($res)==1){
		$usuario = mysqli_fetch_assoc($res);
	}
	else{
		$usuario = false;
	}
	mysqli_free_result($res);
	return $usuario;
}


function dbGetNumUsuarios($db, $cadena=''){
	if ($cadena==''){
		$query = "SELECT COUNT(*) FROM usuarios";
	}else{
		$query = "SELECT COUNT(*) FROM usuarios WHERE $cadena";
	}
	$res = mysqli_query($db, $query);
	$num = mysqli_fetch_row($res)[0];
	$num = (int)$num;
	mysqli_free_result($res);
	return $num;
}

function dbGetUsuarios($db, $cadena='', $orden=''){
	$query = "SELECT id, nombre, apellidos FROM usuarios";
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

 ?>
