<?php
require_once('database/database.php');
require_once('usuarios/database_usuario.php');
require_once('usuarios/formulario_usuario.php');

if (isset($_SESSION['admin']) and $_SESSION['admin'] == true){
  //Obtener y validar parámetros
  $params = getParams($_POST, $_FILES);

    // Conexión con DB y obtención de receta y id
    $db = dbConnection();
    $usuario = dbGetUsuario($db, $params['id']);

    // Según el formulario que estemos editando
    if (isset($params['accion'])){
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
                formEditable('Edita la receta',$receta, 'Editar', true);
                formFotos($params, 'Añadir fotografía');
                break;
              default:
                echo "entra en default";
                formEditable('Edita la receta',$receta, 'Editar', true);
                formFotos($receta, 'Editar');
              break;
            }
          break;
        case 'Mostrar':
          showReceta($receta, $params['id']);
        break;
        case 'Comenta':
          showReceta($receta, $params['id']);
        break;
        case 'Califica':
          echo "califica";
          // echo $receta['valoracion'];
          showReceta($receta, $params['id']);
        break;
      }
    }else{
      echo "showReceta";
      showReceta($receta, $params['id']);
    }
}



?>
