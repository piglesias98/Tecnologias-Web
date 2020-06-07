<?php

function dbCrearUsuario($db, $params){
	echo $params['foto_perfil_src'];
	$res = mysqli_query($db, "INSERT INTO usuarios (nombre, apellidos, email, clave1,
																								foto_perfil_src)
														VALUES ('".mysqli_real_escape_string($db, $params['nombre'])."','"
														.mysqli_real_escape_string($db, $params['apellidos'])."','"
														.mysqli_real_escape_string($db, $params['email'])."','"
														.mysqli_real_escape_string($db, $params['clave1'])."','"
														.mysqli_real_escape_string($db, $params['foto_perfil_src'])."')");
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
												email = '".mysqli_real_escape_string($db, $params['email'])."',
				                clave1 = '".mysqli_real_escape_string($db, $params['clave1'])."',
												foto_perfil_src = '".mysqli_real_escape_string($db, $params['foto_perfil_src'])."'
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
	$res = mysqli_query($db, "SELECT id, nombre
														FROM usuarios WHERE email='".mysqli_real_escape_string($db,$email)."'");
	if ($res and mysqli_num_rows($res)==1)
		return mysqli_fetch_assoc($res);
	else
		return False;
}

function dbPasswordVerify($db, $clave, $id){
	$res = mysqli_query($db, "SELECT id
														FROM usuarios WHERE clave1='".mysqli_real_escape_string($db,$clave)."'");
	if ($res and mysqli_num_rows($res)==1)
		return true;
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


 ?>
