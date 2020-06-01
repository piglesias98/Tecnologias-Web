<?php
// El formulario ha sido enviado si exist
// alguna de las dos variables usuario o contraseña

$url =  basename($_SERVER['REQUEST_URI']);
if (!strpos($url, '?')) $url = $_SERVER['SCRIPT_NAME'];
$error = false;
if (isset($_POST['usuario']) or isset($_POST['clave'])){
  // Comprobar el valor de usuario
  if ($_POST['usuario'] == 'admin' and $_POST['clave'] == 'clave'){
    $_SESSION['identificado'] = true;
  }
  else {
    $error = true;
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
    <p>!Ya estás logeado!</p>
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
        <label for="usuario">Usuario:</label>
        <input type="text" name="usuario" id="usuario" placeholder="Escribe tu usuario">
      </div>
      <div class="field">
        <label for="clave">Clave:</label>
        <input type="password" name="clave" id="clave" placeholder="Escribe tu constraseña">
      </div>
      <?php if ($error){
        echo "<p class='error'> Nombre de usuario o clave no son correctos, prueba otra vez</p>";
      } ?>
      <input type="submit" value="Login">
    </form>
  </aside>
<?php
}

function acabarSesion(){
  //La sesion debe estar iniciada
  if (session_status()==PHP_SESSION_NONE){
    session_start();
  }
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
