<?php
require_once('database.php');
require_once('formulario_usuario.php');
if (isset($_POST['accion']) && isset($_POST['id'])){
  $accion = $_POST['accion'];
  $id = $_POST['id'];
}

if (isset($id)){
  if (!is_string($db = dbConnection())){
    switch($accion){
      case 'Editar':
        $usuario = dbGetUsuario($db, $id);
        formEditable('Edite sus datos ', $usuario, 'Editar', true);
        break;
      case 'Modificar':
        $params = getParams($_POST, $_FILES);
        $msg = dbModificarUsuario($db, $id, $params);
        if ($msg == true){
          $info[] = "".$params['nombre'].", tus datos han sido actualizados";
        }else{
          $info[]= "Tus datos no se han podido actualizar ".$params['nombre'];
        }
        break;
      case 'Mostrar':
        $usuario = dbGetUsuario($db, $id);
        showUsuario($usuario, $id);
        break;
        }
    }
  }else{
    //Si los parámetros no son correctos volver al listado
  }

?>
