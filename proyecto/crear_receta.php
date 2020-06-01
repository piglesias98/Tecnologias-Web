<div class="contenido">
<h3>Crear una receta</h3>
<?php

require_once('htmlForms.php');

//Obtener y validar parámetros
$params = getParams($_POST, $_FILES);

//Si ya tenemos la confirmación
if (isset($params['confirmar'])){
  enviarFormulario($params);
//Si se han recibido los datos y son correctos
}else if ($params['enviado']==true && $params['err_titulo']=='' && $params['err_autor']==''
    && $params['err_categoria']=='' && $params['err_descripcion'] ==''
    && $params['err_ingredientes']=='' && $params['err_preparacion']==''
    && $params['err_preparacion']=='' && $params['err_fotografia'] == ''){
  //Pedir confirmación
  $params['editable']=false;
  $accion = 'confirmar';
  showFormReceta($params, $accion, false);
}else{
  //Si no se han recibido parámetros o son incorrectos
  showFormReceta($params, 'enviar', true);
}



?>
</div>
</div>
