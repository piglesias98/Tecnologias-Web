<?php
require_once('database/database.php');
require_once('usuarios/database_usuario.php');
require_once('usuarios/formulario_usuario.php');


/*
En crud_usuarios.php se gestionan todas las operaciones CRUD de los usuarios dependiendo
de con que parámetros(tanto de post y get) se llame a este fichero.
*/



//Obtener y validar parámetros
$params = getParams($_POST, $_FILES);
$db = dbConnection();

# Si estamos viendo nuestro perfil, obtenemos el id de la variable de sesión (el nuestro)
# Debemos estar identificados
if (isset($_GET['p']) and $_GET['p'] == 'perfil' and
    isset($_SESSION['identificado']) and $_SESSION['identificado'] == true){
  $params['id'] = $_SESSION['id'];
  $id = $params['id'];
  $email = $_SESSION['email'];
  $usuario = dbGetUsuario($db, $params['id']);
# Si por el contrario el id se manda en la URL, obtenemos el usuario de ese id
# Debemos ser administradores, porque puede tratarse del id de cualquier usuario
}else if (isset($_GET['id']) and
          isset($_SESSION['admin']) and $_SESSION['admin'] == true){
  $params['id'] = $_GET['id'];
  $id = $params['id'];
  $usuario = dbGetUsuario($db, $params['id']);
}



// Si se quiere verificar el Email
if (isset($_GET['p']) and $_GET['p']=='confirmacion' and isset($_GET['vkey'])){
  $vkey = $_GET['vkey'];
  // Comprobamos la clave de verificación
  $id = dbCheckVerificacion($db, $vkey);
  if ($id != -1){
    // La clave de verificación está bien
    // Verificamos el usuario
    dbSetVerificado($db, $id, 1);
    echo "<div class='mensaje_simple'>";
    echo "<p>Enhorabuena! Ahora puedes logearte en el sistema</p>";
    echo "</div>";
  }else{
    include "error.html";
  }
}
# Si hemos obtenido un usuario (para lo que teníamos que estar identificados)
# podemos realizar operaciones CRUD sobre este
else if (isset($id) and isset($usuario)){
    // Según el formulario que estemos editando
    if (isset($params['accion'])){
      switch ($params['accion']) {
        case 'Confirmar':
          //Si ya tenemos la confirmación modificar usuario y mostrar final
          $msg = dbModificarUsuario($db, $params['id'], $params);
          if ($msg == true){
            echo "<div class='mensaje_simple'>";
            echo "<p>El usuario ".$params['nombre']." ha sido atualizado</p>";
            echo "</div>";
          }else{
            echo "<p class='error'> El usuario no se ha podido actualizar </p>";
          }
          // hay que obtener de nuevo la receta porque ha sido actualizada
          $usuario = dbGetUsuario($db, $params['id']);
          showUsuario($usuario, $params['id']);
          break;

            //Si hemos pulsado el botón de editar recuperamos los datos de $usuario
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
                formEditable('Confirma la edición',$params, 'Confirmar', false);
              // si hemos editado pero hay algún error
              }else{
                formEditable('Edita el usuario',$params, 'Editar', true);
              }
            break;
            default:
              formEditable('Edita el usuario',$usuario, 'Editar', true);
            break;
          }
          break;

        // Si queremos tan solo ver el usuario
        case 'Mostrar':
          showUsuario($usuario, $params['id']);
        break;
      }
    // Si no hemos especificado ninguna acción, visualizaremos el usuario
    }else{
      showUsuario($usuario, $params['id']);
    }
# Si por el contrario el id no está definido, se procederá al Registro
# Tendremos que distinguir entre el registro de un visitante (que habrá de Confirmar
# su email para que quede registrado) y el del administrador, que registra directamente
}else{
  if (isset($params['accion'])){
    switch ($params['accion']) {
      case 'Confirmar':
        if (isset($_SESSION['admin']) and $_SESSION['admin'] == true){
          enviarFormulario($params);
        }else{
          verificacionEmail($params);
        }
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
              formEditable('Registra el usuario',$params, 'Registro', true);
            }
          break;
          default:
            formEditable('Registra el usuario',$params, 'Registro', true);
          break;
        }
      }
    # Si no se ha enviado ninguna acción (esto ocurre cuando accede el visitante
    # por primera vez), le presentaremos el formulario de registro
    }else{
      formEditable('Registro', $params, 'Registro', true);
    }
}


?>
