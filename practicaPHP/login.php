<?php
// El formulario ha sido enviado si exist
// alguna de las dos variables usuario o contraseña
$error = false;
if (isset($_POST['usuario']) or isset($_POST['clave'])){
  // Comprobar el valor de usuario
  if ($_POST['usuario'] == 'admin' and $_POST['clave'] == 'clave'){
    $GLOBALS['identificado'] = true;
  }else{
    $error = true;
  }
}
// Si el forumlario enviado y datos correctos
if ($GLOBALS['identificado']){
  echo "<p> Ya estás identificado en el sistema</p>";
// Hay errores o no se ha enviado el formulario
}else {
?>
  <aside class="login">
    <h3>Login</h3>
    <form class="login_form" action="<?php echo $_SERVER['SCRIPT_NAME']?>" method="post">
      <div class="field">
        <label for="usuario">Usuario:</label>
        <input type="text" name="usuario" id="usuario" placeholder="Escribe tu usuario">
      </div>
      <div class="field">
        <label for="clave">Clave:</label>
        <input type="text" name="clave" id="clave" placeholder="Escribe tu constraseña">
      </div>
      <?php if ($error){
        echo "<p class='error'> Nombre de usuario o clave no son correctos, prueba otra vez</p>";
      } ?>
      <input type="submit" value="Login">
    </form>
  </aside>
<?php
}
?>
