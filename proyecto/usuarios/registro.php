<div class="contenido">
<h3>Tu perfil</h3>
<?php

require_once('formulario_usuario.php');

//Obtener y validar parámetros
$params = getParams($_POST, $_FILES);

//Si ya tenemos la confirmación
if (isset($params['confirmar'])){
  enviarFormulario($params);
//Si se han recibido los datos y son correctos
}else if ($params['enviado']==true && $params['err_nombre']=='' && $params['err_apellidos']=='' && $params['err_email']==''
    && $params['err_clave1']=='' && $params['err_clave2'] ==''
    && $params['err_foto']==''){
  //Pedir confirmación
  $params['editable']=false;
  $accion = 'Confirmar';
  showFormUsuario($params, $accion, false);
}else{
  //Si no se han recibido parámetros o son incorrectos
  showFormUsuario($params, 'Registrarse', true);
}



?>
</div>
</div>
