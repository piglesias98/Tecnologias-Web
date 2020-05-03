<div class="contenido">
<h3>Crear una receta</h3>
<?php

//Obtener y validar parámetros
$params = getParams($_POST, $_FILES);

//Si ya tenemos la confirmación
if (isset($params['confirmar'])){
  enviarFormulario($params);
//Si se han recibido los datos y son correctos
}else if ($params['enviado']==true && $params['err_titulo']=='' && $params['err_autor']==''
    && $params['err_categoria']=='' && $params['err_descripcion'] ==''
    && $params['err_ingredientes']=='' && $params['err_preparacion']==''
    && $params['err_preparacion']=='' && $params['err_fotografia'] == ''){
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
      $result['titulo'] = $p['titulo'];
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
    $result['err_fotografia']='';
    if(empty($f['fotografia'])){
      $result['err_fotografia'] = 'Debe incluir una fotografía';
    }else{
      $target_dir = "uploads/";
      $target_file = $target_dir . basename($f['fotografia']['name']);
      $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
      // Comprobar que se trata de verdad de una imagen
      if (!getimagesize($f['fotografia']['tmp_name'])){
        $result['err_fotografia'] = "El archivo no es una fotografía";
      // Comprobar el tamaño del archivo
      }else if ($f['fotografia']["size"] > 500000){
        $result['err_fotografia'] = 'Lo siento, el archivo es demasiado grande';
      // Sólo permitimos JPG, JPEG y PNG
      }else if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"){
        $result['err_fotografia'] = 'Lo siento, solo se permiten archivos PNG, JPG o JPEG';
      }else{
        $result['fotografia'] = $f['fotografia']['tmp_name'];
        $result['fotografia_temp'] = file_get_contents($f['fotografia']['tmp_name']);
      }
    }
    if(isset($p['confirmar'])){
      $result['confirmar'] = true;
    }
  } else {
    //El formulario aún no ha sido enviado
    $result['enviado'] = false;
  }
  return $result;
}



function showForm($params){
  ?>
  <form class="login_form" action="index.php?p=3" enctype="multipart/form-data" method="post">
    <label for="nombre">Título de la receta:
      <input type="text" name="titulo"
      <?php if (isset($params['titulo'])) echo " value='".$params['titulo']."'";?>/>
      <?php if (isset($params['err_titulo'])) echo "<p class = 'error'>".$params['err_titulo'];?></p>
    </label>
    <label for="autor">Autor:
      <input type="text" name="autor"
      <?php if (isset($params['autor'])) echo " value='".$params['autor']."'";?>/>
      <?php if (isset($params['err_autor'])) echo "<p class = 'error'>".$params['err_autor'];?></p>
    </label>
    <label for="categoria">Categoría:
      <input type="text" name="categoria"
      <?php if (isset($params['categoria'])) echo " value='".$params['categoria']."'";?>/>
      <?php if (isset($params['err_categoria'])) echo "<p class = 'error'>".$params['err_categoria'];?></p>
    </label>
    <label for="descripcion">Descripción:
      <input type="text" name="descripcion"
      <?php if (isset($params['descripcion'])) echo " value='".$params['descripcion']."'";?>/>
      <?php if (isset($params['err_descripcion'])) echo "<p class = 'error'>".$params['err_descripcion'];?></p>
    </label>
    <label for="ingredientes">Ingredientes:
      <input type="text" name="ingredientes"
      <?php if (isset($params['ingredientes'])) echo " value='".$params['ingredientes']."'";?>/>
      <?php if (isset($params['err_ingredientes'])) echo "<p class = 'error'>".$params['err_ingredientes'];?></p>
    </label>
    <label for="preparacion">Preparación:
      <input type="text" name="preparacion"
      <?php if (isset($params['preparacion'])) echo " value='".$params['preparacion']."'";?>/>
      <?php if (isset($params['err_preparacion'])) echo "<p class = 'error'>".$params['err_preparacion'];?></p>
    </label>
    <label for="imagen">Selecciona una imagen:
      <input type="file" name="fotografia"
      <?php if (isset($params['fotografia'])) echo "<img src='".$params['fotografia']['tmp_name']."'";?>/>
      <?php if (isset($params['err_fotografia'])) echo "<p class = 'error'>".$params['err_fotografia'];?></p>
    </label>
    <input type="submit" value="Enviar">
  </form>
<?php
}


function enviarFormulario($params){
  ?>
  <p>Muchas gracias, <?php echo $params['autor'] ?></p>
  <p>Tu receta <?php echo $params['titulo']?> ya está en nuestra base de dato
      y pronto podrás ver la en la página web :)</p>
  <?php
  if (move_uploaded_file($params["fotografia"]["tmp_name"], $target_file)) {
    $result['err_fotografia'] = 'La foto ha sido subida';
    $result['fotografia'] = $f['fotografia'];
  }else{
    $result['err_fotografia'] = 'Ha habido un problema subiendo la foto';
  }
}

function solicitarConfirmacion($params){
?>
<form class="login_form" action="index.php?p=3" enctype="multipart/form-data" method="post">
  <label for="nombre">Título de la receta:
    <input type="text" name="titulo"
    <?php echo " value='".$params['titulo']."'";?> readonly/><br>
  </label>
  <label for="autor">Autor:
    <input type="text" name="autor"
    <?php echo " value='".$params['autor']."'";?> readonly/><br>
  </label>
  <label for="categoria">Categoría:
    <input type="text" name="categoria"
    <?php echo " value='".$params['categoria']."'";?> readonly/><br>
  </label>
  <label for="descripcion">Descripción:
    <input type="text" name="descripcion"
    <?php echo " value='".$params['descripcion']."'";?> readonly/><br>
  </label>
  <label for="ingredientes">Ingredientes:
    <input type="text" name="ingredientes"
    <?php echo " value='".$params['ingredientes']."'";?> readonly/><br>
  </label>
  <label for="preparacion">Preparación:
    <input type="text" name="preparacion"
    <?php echo " value='".$params['preparacion']."'";?> readonly/><br>
  </label>
  <label for="imagen">Selecciona una imagen:
    <input type="file" name="fotografia"
    <?php echo " value='".$params['fotografia']."'";?> readonly/><br>
    <?php echo "<img src='data:image/png;base64,".base64_encode($params['fotografia_temp'])."' ";?>/>
  </label>
  <input type="submit" value="Confirmar" name="confirmar">
</form>
<?php
}

?>
</div>
</div>
