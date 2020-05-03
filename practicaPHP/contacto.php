<div class="contenido">
<h3>Formulario de contacto</h3>
<?php
//Obtener y validar parámetros
$params = getParams($_POST);
//Si se han recibido los datos y son correctos
if ($params['enviado']==true && $params['err_nombre']=='' && $params['err_email']==''
    && $params['err_tel']=='' && $params['err_com'] ==''){
  showResults($params);
}else{
  //Si no se han recibido parámetros o son incorrectos
  showForm($params);
}

function getParams($p){
  if (isset($p['nombre']) or isset($p['email']) or isset($p['telefono']) or isset($p['email'])){
    $result['enviado'] = true;
    // Validación de resultados
    // -> nombre
    $result['err_nombre'] = '';
    if (empty($p['nombre'])){
      $result['err_nombre'] = 'El nombre no puede estar vacío';
    }else{
      $result['nombre'] = $p['nombre'];
    }
    // -> email
    $result['err_email'] = '';
    if (empty($p['email'])){
      $result['err_email'] = 'El email no puede estar vacío';
    }
    else if (!filter_var($p['email'], FILTER_VALIDATE_EMAIL)){
      $result['err_email'] = 'Debe ser un email válido';
    }else{
      $result['email'] = $p['email'];
    }
    // -> teléfono
    $result['err_tel'] = '';
    if (!empty($p['telefono'])){
      if (!preg_match('/^\+?(?:[0-9] ?){6,14}[0-9]$/', filter_var($p['telefono'], FILTER_SANITIZE_NUMBER_INT))){
        $result['err_tel'] = 'Debe ser un teléfono válido';
      }else{
        $result['telefono'] = $p['telefono'];
      }
    }
    // -> comentario
    $result['err_com'] = '';
    if (empty($p['comentario'])){
      $result['err_com'] = 'El comentario no puede estar vacío';
    }else{
      $result['comentario'] = $p['comentario'];
    }
  }else{
    //El formulario aún no ha sido enviado
    $result['enviado'] = false;
  }
  return $result;
}

function showForm($params){
  ?>
  <form class="login_form" action="<?php echo $_SERVER['SCRIPT_NAME']?>" method="post">
    <label for="nombre">Nombre:
      <input type="text" name="nombre"
      <?php if (isset($params['nombre'])) echo "value='".$params['nombre']."'";?>/>
      <?php if (isset($params['err_nombre'])) echo "<p class = 'error'>".$params['err_nombre'];?>"</p>"
    </label>
    <label for="email">Correo Electrónico:
      <input type="text" name="email"
      <?php if (isset($params['email'])) echo "value='".$params['email']."'";?>/>
      <?php if (isset($params['err_email'])) echo "<p class = 'error'>".$params['err_email'];?>"</p>"
    </label>
    <label for="email">Teléfono:
      <input type="text" name="telefono"
      <?php if (isset($params['telefono'])) echo "value='".$params['telefono']."'";?>/>
      <?php if (isset($params['err_tel'])) echo "<p class = 'error'>".$params['err_tel'];?>"</p>"
    </label>
    <label for="comentario">Comentario:
      <input type="text" name="comentario"
      <?php if (isset($params['comentario'])) echo "value='".$params['comentario']."'";?>/>
      <?php if (isset($params['err_com'])) echo "<p class = 'error'>".$params['err_com'];?>"</p>"
    </label>
    <input type="submit" value="Enviar">
  </form>
<?php
}
function showResults($params){
  ?>
  <p>Muchas gracias, <?php echo $params['nombre'] ?></p>
  <p>Contactaremos contigo con la mayor brevedad</p>
<?php
}

?>
</div>
</div>
