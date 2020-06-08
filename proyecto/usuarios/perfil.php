<?php
require_once('database/database.php');
require_once('usuarios/database_usuario.php');
require_once('usuarios/formulario_usuario.php');
if (isset($_SESSION['identificado']) and $_SESSION['identificado'] == true){
  $id = $_SESSION['id'];
  $email = $_SESSION['email'];
  $db = dbConnection();
  $usuario = dbGetUsuario($db, $id);
  //Obtener y validar parámetros
  $params = getParams($_POST, $_FILES);

  //Si ya tenemos la confirmación modificar usuario y mostrar final
  if (isset($params['confirmar'])){
    $msg = dbModificarUsuario($db, $id, $params);
    if ($msg == true){
      echo "<p>".$params['nombre'].", tus datos han sido actualizados</p>";
    }else{
      echo "<p class='error'>Tus datos no se han podido actualizar ".$params['nombre']."</p>";
    }
    $usuario = dbGetUsuario($db, $id);
    showUsuario($usuario, $id);
  //Si le hemos dado al botón de editar recuperamos los datos de $usuario
  //y ponemos un form editable
  }else if(isset($params['accion']) && $params['accion']=='Editar'){
    showFormUsuario($usuario, 'enviar', true);
  //Si ya hemos editado y todos los valores son correctos
  }else if (isset($params['enviado']) && $params['enviado']==true && $params['err_nombre']=='' && $params['err_apellidos']==''
        && $params['err_email']=='' && $params['err_clave1'] ==''
        && $params['err_clave2']=='' && $params['err_foto']==''){
      //Pedir confirmación
      $params['editable']=false;
      $accion = 'confirmar';
      showFormUsuario($params, $accion, false);
  // Si hemos editado pero hay algunos errores
  }else if(isset($params['enviado']) && $params['enviado']==true){
    showFormUsuario($params, 'enviar', true);
  }else{
    //Si no se han recibido parámetros
    showUsuario($usuario, $id);
  }
}else{
  echo "<p class='error'> No estás identificado</p>";
}


?>
