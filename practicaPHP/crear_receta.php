<div class="contenido">
<h3>Crear una receta</h3>
<?php
//Obtener y validar parámetros
$params = getParams($_POST, $FILES);
//Si se han recibido los datos y son correctos
if ($params['enviado']==true && $params['err_titulo']=='' && $params['err_autor']==''
    && $params['err_categoria']=='' && $params['err_descripcion'] ==''
    && $params['err_ingredientes']=='' && $params['err_preparacion']==''){
  solicitarConfirmacion($params);
}else{
  //Si no se han recibido parámetros o son incorrectos
  showForm($params);
}

function getParams($p, $f){
  if (isset($p['titulo']) or isset($p['autor']) or isset($p['categoria']) or isset($p['descripcion'])
      or isset($p['ingredientes']) or isset($p['preparacion']) or isset($f['fotografia'])){
    $result['enviado'] = true;
    // Validación de resultados
    // -> titulo
    $result['err_titulo'] = '';
    if (empty($p['titulo'])){
      $result['err_titulo'] = 'El título no puede estar vacío';
    }else{
      $result['nombre'] = $p['nombre'];
    }
    // -> autor
    $result['err_autor'] = '';
    if (empty($p['autor'])){
      $result['err_autor'] = 'El autor no puede estar vacío';
    }else{
      $result['autor'] = $p['autor'];
    }
    // -> categoría
    $result['err_categoria'] = '';
    if (empty($p['categoria'])){
      $result['err_categoria'] = 'La categoría no puede estar vacía';
    }else{
      $result['categoria'] = $p['categoria'];
    }
    // -> descripcion
    $result['err_descripcion'] = '';
    if (empty($p['descripcion'])){
      $result['err_descripcion'] = 'La descripción no puede estar vacía';
    }else{
      $result['descripcion'] = $p['descripcion'];
    }
    // -> ingredientes
    $result['err_ingredientes'] = '';
    if (empty($p['ingredientes'])){
      $result['err_ingredientes'] = 'Los ingredientes no pueden estar vacíos';
    }else{
      $result['ingredientes'] = $p['ingredientes'];
    }
    // -> preparacion
    $result['err_preparacion'] = '';
    if (empty($p['preparacion'])){
      $result['err_preparacion'] = 'La preparación no puede estar vacía';
    }else{
      $result['preparacion'] = $p['preparacion'];
    }
    // -> fotografía
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($f["fotografia"]["nombre_foto"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $result['err_fotografia']='';
    // Comprobar que se trata de verdad de una imagen
    if (!getimagesize($f["fotografia"]["nombre_foto"])){
      $result['err_fotografia'] = "El archivo no es una fotografía";
    // Comprobar el tamaño del archivo
    }else if ($f["fileToUpload"]["size"] > 500000){
      $result['err_fotografia'] = 'Lo siento, el archivo es demasiado grande';
    // Sólo permitimos JPG, JPEG y PNG
    }else if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"){
      $result['err_fotografia'] = 'Lo siento, solo se permiten archivos PNG, JPG o JPEG';
    }
  } else {
    //El formulario aún no ha sido enviado
    $result['enviado'] = false;
  }
  return $result;
}



function showForm($params){
  ?>
  <form class="login_form" action="index.php?p=2" method="post">
    <label for="nombre">Nombre:
      <input type="text" name="nombre"
      <?php if (isset($params['nombre'])) echo " value='".$params['nombre']."'";?>/>
      <?php if (isset($params['err_nombre'])) echo "<p class = 'error'>".$params['err_nombre'];?></p>
    </label>
    <label for="email">Correo Electrónico:
      <input type="text" name="email"
      <?php if (isset($params['email'])) echo " value='".$params['email']."'";?>/>
      <?php if (isset($params['err_email'])) echo "<p class = 'error'>".$params['err_email'];?></p>
    </label>
    <label for="email">Teléfono:
      <input type="text" name="telefono"
      <?php if (isset($params['telefono'])) echo " value='".$params['telefono']."'";?>/>
      <?php if (isset($params['err_tel'])) echo "<p class = 'error'>".$params['err_tel'];?></p>
    </label>
    <label for="comentario">Comentario:
      <input type="text" name="comentario"
      <?php if (isset($params['comentario'])) echo " value='".$params['comentario']."'";?>/>
      <?php if (isset($params['err_com'])) echo "<p class = 'error'>".$params['err_com'];?></p>
    </label>
    <input type="submit" value="Enviar">
  </form>
<?php
}


function showResults($params){
  ?>
  <p>Muchas gracias, <?php echo $params['nombre'] ?></p>
  <p>Contactaremos contigo a la mayor brevedad</p>
  <p>Estos son los datos que hemos recibido:</p>
  <ul>
    <li>Nombre: <?php echo $params['nombre'] ?></li>
    <li>Correo electrónico: <?php echo $params['email'] ?></li>
    <li>Teléfono: <?php echo $params['telefono'] ?></li>
    <li>Comentario: <?php echo $params['comentario'] ?></li>
  </ul>
<?php
}

?>
</div>
</div>
