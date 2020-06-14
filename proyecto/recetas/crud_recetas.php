<?php
require_once('database/database.php');
require_once('recetas/database_receta.php');
require_once('recetas/formulario_receta.php');

//Obtener y validar parámetros
$params = getParams($_POST, $_FILES);
// Conexión con DB
$db = dbConnection();

if(isset($_GET['p']) and $_GET['p']=='crear'){
  if (!isset($params['accion']))
    $params['accion']='Crear';
}else{
  $params['id'] = $_GET['id'];
  $receta = dbGetReceta($db, $params['id']);
}

  // Según el formulario que estemos editando
  if (isset($params['accion'])){
    switch ($params['accion']) {

      // Confirmar edición
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
        showReceta($receta, $params['id']);
        break;

          //Si le hemos pulsado el botón de editar recuperamos los datos de $usuario
          //y ponemos un form editable
      case 'Editar':
          switch ($params['form']) {
            case 'receta':
            // si no hay errores
              if ($params['err_titulo']=='' && $params['err_descripcion']==''
                  && $params['err_ingredientes']=='' && $params['err_preparacion'] ==''){
                //Pedir confirmación
                $params['editable']=false;
                formEditable('Confirma la edición', $params, 'Confirmar', false);
              // si hemos editado pero hay algún error
              }else{
                formEditable('Edita la receta',$params, 'Editar', true);
              }
              break;
            case 'fotos':
              formEditable('Edita la receta',$receta, 'Editar', true);
              formFotos($params, 'Añadir fotografía');
              break;
            default:
              formEditable('Edita la receta',$receta, 'Editar', true);
              formFotos($receta, 'Editar');
            break;
          }
        break;
      case 'Borrar':
        formEditable('Borrar receta',$receta, 'Confirmar borrado', false);
        break;
      case 'Borrar foto':
      case 'Borrar comentario':
      case 'Confirmar borrado':
        switch ($params['form']) {
          case 'receta':
            $msg = dbBorrarReceta($db, $params['id']);
            if ($msg == true){
              $message = "<p>La receta ".$params['titulo']." ha sido borrada</p>";
            }else{
              $message= "<p class='error'> La receta no se ha podido borrar </p>";
            }
            showMessage($message);
          break;
          case 'comentario':
            $msg = dbBorrarComentario($db, $params['id_comentario']);
            showReceta($receta, $params['id']);
          break;
          case 'fotos':
            $msg = dbBorrarFoto($db, $params['id_foto']);
            // Solo se puede borrar foto cuando se está editando
            formEditable('Edita la receta',$receta, 'Editar', true);
            formFotos($receta, 'Editar');
          break;
        }

        break;
      case 'Crear':
        switch ($params['form']) {
          case 'receta':
            // si no hay errores
            if ($params['err_titulo']=='' && $params['err_descripcion']==''
                && $params['err_ingredientes']=='' && $params['err_preparacion'] ==''){
              //Pedir confirmación
              $params['editable']=false;
              formEditable('Confirmar creación',$params, 'Confirmar creación', false);
            // si hemos editado pero hay algún error
            }else{
              formEditable('Crea una receta',$params, 'Crear', true);
            }
            break;
            default:
              formEditable('Crea una receta',$params, 'Crear', true);
            break;
        }
      break;
      case 'Confirmar creación':
        enviarFormulario($params);
      break;
      case 'Mostrar':
      case 'Comenta':
      case 'Califica':
        showReceta($receta, $params['id']);
      break;
    }
  }else{
    showReceta($receta, $params['id']);
  }


function showMessage($message){
  echo "<div class='contenido'>";
  echo "<h3>".$message."</h3>";
  echo "</div>";
  echo "</div>";
}


?>
