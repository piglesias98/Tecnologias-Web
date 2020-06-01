<?php
require_once('database.php');
require_once('htmlForms.php');
if (isset($_POST['accion']) && isset($_POST['id'])){
  $accion = $_POST['accion'];
  $id = $_POST['id'];
}

if (isset($id)){
  if (!is_string($db = dbConnection())){
    switch($accion){
      case 'Borrar':
        $receta = dbGetReceta($db, $id);
        formEditable('Borrado de la receta', $receta, 'Confirmar borrado', false);
        break;
      case 'BorrarOK':
        if (dbBorrarReceta($db, $id)){
          $info[] = 'La receta '.$_POST['titulo'].' ha sido borrada.';
        }else {
          $info[] = 'No se ha podido borrar la receta '.$_POST['titulo'];
        }
      break;
      case 'Editar':
        $receta = dbGetReceta($db, $id);
        formEditable('Edite los datos de la receta', $receta, 'Editar', true);
        break;
      case 'Modificar':
        $params = getParams($_POST, $_FILES);
        $msg = dbModificarReceta($db, $id, $params);
        if ($msg == true){
          $info[] = "La receta".$params['titulo']." ha sido actualizada";
        }else{
          $info[]= "No se ha podido actualizar la receta ".$params['titulo'];
        }
        break;
      case 'Mostrar':
        $receta = dbGetReceta($db, $id);
        showReceta($receta, $id);
        break;
        }
    }
  }else{
    //Si los parámetros no son correctos volver al listado
  }

?>
