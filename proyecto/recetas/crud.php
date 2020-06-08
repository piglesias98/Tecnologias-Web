<?php
require_once('database/database.php');
require_once('recetas/database_receta.php');
require_once('recetas/formulario_receta.php');

//Obtener y validar parámetros
$params = getParams($_POST, $_FILES);


if (isset($params['form'])){

  // Conexión con DB y obtención de receta y id
  $db = dbConnection();
  $receta = dbGetReceta($db, $params['id']);
  //Obtenemos el usuario porque necesitaremos el autor
  $autor = 'Anonimo';
  if (isset($receta['idautor'])){
    $usuario = dbGetUsuario($db, $receta['idautor']);
    $autor = $usuario['nombre']." ".$usuario['apellidos'];
  }
  $receta['autor']=$autor;

  // Según el formulario que estemos editando
  echo $params['accion'];
  switch ($params['accion']) {
    case 'Confirmar':
      //Si ya tenemos la confirmación modificar usuario y mostrar final
      $msg = dbModificarReceta($db, $params['id'], $params);
      if ($msg == true){
        echo "<p>La receta ".$params['titulo']." ha sido atualizada</p>";
      }else{
        echo "<p class='error'> La receta no se ha podido actualizar </p>";
      }
      // hay que obtener de nuevo la receta porque ha sido actualizada
      $receta = dbGetReceta($db, $params['id']);
      // El autor no cambia
      $receta['autor']=$autor;
      showReceta($receta, $params['id']);
      break;

        //Si le hemos pulsado el botón de editar recuperamos los datos de $usuario
        //y ponemos un form editable
    case 'Editar':
      echo $params['form'];
        switch ($params['form']) {
          case 'receta':
          // si no hay errores
            if ($params['err_titulo']=='' && $params['err_descripcion']==''
                && $params['err_ingredientes']=='' && $params['err_preparacion'] ==''){
              //Pedir confirmación
              $params['editable']=false;
              showFormReceta($params, 'Confirmar', false);
            // si hemos editado pero hay algún error
            }else{
              showFormReceta($params, 'Editar', true);
            }
            break;
          case 'fotos':
            formFotos($params, 'Añadir fotografía');
            break;
          default:
            formEditable('Edita la receta',$receta, 'Editar', true);
            formFotos($receta, 'Editar');
          break;
        }
      break;
    default:
      showReceta($receta, $params['id']);
      break;
  }
}else{
  echo "<p class='error'> No se ha enviado ningún formulario</p>";
}


?>
