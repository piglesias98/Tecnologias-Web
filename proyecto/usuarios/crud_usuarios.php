<?php
require_once('database/database.php');
require_once('usuarios/database_usuario.php');
require_once('usuarios/formulario_usuario.php');

//Obtener y validar parámetros
$params = getParams($_POST, $_FILES);
if (isset($_GET['id']))
  $params['id'] = $_GET['id'];

$db = dbConnection();
echo "hola";


if (isset($_SESSION['identificado']) and $_SESSION['identificado'] == true){
  if (isset($_SESSION['admin']) and $_SESSION['admin'] == true and $_GET['p']!='perfil'){
      // Obtención de usuario y id
      $usuario = dbGetUsuario($db, $params['id']);
  }else{
    $params['id'] = $_SESSION['id'];
    $email = $_SESSION['email'];
    $db = dbConnection();
    $usuario = dbGetUsuario($db, $params['id']);
  }
    // Según el formulario que estemos editando
    if (isset($params['accion'])){
      switch ($params['accion']) {
        case 'Confirmar':
          //Si ya tenemos la confirmación modificar usuario y mostrar final
          $msg = dbModificarUsuario($db, $params['id'], $params);
          if ($msg == true){
            echo "<p>El usuario ".$params['nombre']." ha sido atualizado</p>";
          }else{
            echo "<p class='error'> El usuario no se ha podido actualizar </p>";
          }
          // hay que obtener de nuevo la receta porque ha sido actualizada
          $usuario = dbGetUsuario($db, $params['id']);
          showUsuario($usuario, $params['id']);
          break;

            //Si le hemos pulsado el botón de editar recuperamos los datos de $usuario
            //y ponemos un form editable
        case 'Editar':
          switch ($params['form']) {
            case 'usuario':
              // si no hay errores
              if ($params['err_nombre']=='' && $params['err_apellidos']==''
                    && $params['err_email']=='' && $params['err_clave1'] ==''
                    && $params['err_clave2']=='' && $params['err_foto']==''){
                //Pedir confirmación
                $params['editable']=false;
                showFormUsuario($params, 'Confirmar', false);
              // si hemos editado pero hay algún error
              }else{
                formEditable('Edita el usuario',$params, 'Editar', true);
              }
            break;
            default:
              echo "entra en default";
              formEditable('Edita el usuario',$usuario, 'Editar', true);
            break;
          }
          break;
        case 'Mostrar':
          showUsuario($usuario, $params['id']);
        break;
      }
    }else{
      echo "showUsuario";
      showUsuario($usuario, $params['id']);
    }
}else{
  if (isset($params['accion'])){
    switch ($params['accion']) {
      case 'Confirmar':
        enviarFormulario($params);
      break;
      case 'Registro':
        switch ($params['form']) {
          case 'usuario':
            // si no hay errores
            if ($params['err_nombre']=='' && $params['err_apellidos']==''
                  && $params['err_email']=='' && $params['err_clave1'] ==''
                  && $params['err_clave2']=='' && $params['err_foto']==''){
              //Pedir confirmación
              $params['editable']=false;
              formEditable('¿Es todo correcto?',$params, 'Confirmar', false);
            // si hemos editado pero hay algún error
            }else{
              formEditable('Registro',$params, 'Registro', true);
            }
          break;
        }
      }
    }else{
      echo "else";
      formEditable('Registro', $params, 'Registro', true);
    }
}



?>
