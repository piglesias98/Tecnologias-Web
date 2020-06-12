<?php
// El formulario ha sido enviado si exist
// alguna de las dos variables usuario o contraseña
require_once('database/database.php');
require_once('usuarios/database_usuario.php');

$url =  basename($_SERVER['REQUEST_URI']);
if (!strpos($url, '?')) $url = $_SERVER['SCRIPT_NAME'];
$error = false;
if (isset($_POST['email_login']) or isset($_POST['clave_login'])){
  // Comprobar que exista el usuario
  $email = $_POST['email_login'];
  $db = dbConnection();
  $res = dbCheckUsuario($db, $email);
  if ($res === false){
    $error = true;
  }else{
    $id = $res['id'];
    $nombre = $res['nombre'];
    $rol = $res['tipo'];
    // Verificamos la contraseña
    $clave = $_POST['clave_login'];
    if (dbPasswordVerify($db, $clave, $id)){
      // La contraseña es correcta así que comenzamos una nueva sesión
      $_SESSION['identificado'] = true;
      $_SESSION['id'] = $id;
      $_SESSION['email'] = $email;
      $_SESSION['nombre'] = $nombre;
      dbInsertLog($db, 'El usuario con  email '.$email.' ha comenzado una nueva sesión');
      if($rol == 'admin'){
        echo 'es admin';
        $_SESSION['admin'] = true;
        dbInsertLog($db, 'El usuario con email '.$email.' ha comenzado una sesión de administrador');
      }
    }else {
      $error = true;
    }
  }
}else if (isset($_POST['logout'])) {
    // Acceso desde formulario de logout
    acabarSesion();
}
// Si el forumlario enviado y datos correctos
if (isset($_SESSION['identificado'])){
?>
  <aside class="login">
    <h3>Login</h3>
    <p><?php echo $_SESSION['nombre'] ?>, estás logead@</p>
    <form class="login_form" action="<?php echo $url?>" method="post">
      <div class="field">
        <input type="submit" name="logout" value="logout">
      </div>
    </form>
  </aside>
<?php
// Hay errores o no se ha enviado el formulario
}else {
?>
  <aside class="login">
    <h3>Login</h3>
    <form class="login_form" action="<?php echo $url?>" method="post">
      <div class="field">
        <label for="email">Email:</label>
        <input type="text" name="email_login" id="email_login" placeholder="Escribe tu usuario">
      </div>
      <div class="field">
        <label for="clave">Clave:</label>
        <input type="password" name="clave_login" id="clave_login" placeholder="Escribe tu constraseña">
      </div>
      <?php if ($error){
        echo "<p class='error'> Nombre de usuario o clave no son correctos, prueba otra vez</p>";
      } ?>
      <input type="submit" value="Login">
    </form>
    <?php $registro_url = strval($_SERVER['PHP_SELF']) . "?p=registro";?>
    <p>¿No tienes cuenta aún? <a href=<?php echo $registro_url?>>Regístrate</a></p>
  </aside>
<?php
}

function acabarSesion(){
  //La sesion debe estar iniciada
  if (session_status()==PHP_SESSION_NONE){
    session_start();
  }
  dbInsertLog($db, 'El usuario con email '.$email.' ha finalizado su sesión');
  //Borrar variables de sesión
  //$_SESSION = array()
  session_unset();

  //Obtener parámetros cookies de sesión
  $param = session_get_cookie_params();

  //Borrar cookie de sesión
  setcookie(session_name(), $_COOKIE[session_name()], time()-2592000,
            $param['path'], $param['domain'], $param['secure'], $param['httponly']);

  //Destruir sesión
  session_destroy();
}


?>
