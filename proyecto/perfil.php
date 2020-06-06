<?php
require_once('database.php');
require_once('formulario_usuario.php');
if (isset($_SESSION['identificado']) and $_SESSION['identificado'] == true){
  $id = $_SESSION['id'];
  $email = $_SESSION['email'];
  $db = dbConnection();
  $usuario = dbGetUsuario($db, $id);
}else{
  echo "<p class='error'> No estás identificado</p>";
}




if (isset($id) and isset($_POST['accion'])){
  $accion = $_POST['accion'];
  //Obtener y validar parámetros
  $params = getParams($_POST, $_FILES);
  if (!is_string($db = dbConnection())){
    switch($accion){
      case 'Editar':
        $usuario = dbGetUsuario($db, $id);
        formEditable('Edite sus datos ', $usuario, 'Editar', true);
        break;
      case 'Modificar':
        $params = getParams($_POST, $_FILES);
        //Si ya tenemos la confirmación
        if (isset($params['confirmar'])){
          $msg = dbModificarUsuario($db, $id, $params);
          if ($msg == true){
            $info[] = "".$params['nombre'].", tus datos han sido actualizados";
          }else{
            $info[]= "Tus datos no se han podido actualizar ".$params['nombre'];
          }
          // enviarFormulario($params);
        //Si se han recibido los datos y son correctos
        }else if ($params['enviado']==true && $params['err_titulo']=='' && $params['err_autor']==''
            && $params['err_categoria']=='' && $params['err_descripcion'] ==''
            && $params['err_ingredientes']=='' && $params['err_preparacion']==''
            && $params['err_preparacion']=='' && $params['err_fotografia'] == ''){
          //Pedir confirmación
          $params['editable']=false;
          $accion = 'confirmar';
          showFormUsuario($params, $accion, false);
        }else{
          //Si no se han recibido parámetros o son incorrectos
          showFormUsuario($params, 'enviar', true);
        }
        break;
        }
    }
  }else if (isset($id)){
    showUsuario($usuario, $id);
  }

?>
