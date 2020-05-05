<?php

function formEditable($titulo, $receta, $accion, $editable){
  echo "<div class='contenido'>";
  echo "<h3>".$titulo."</h3>";
  showForm($params, $accion, $editable);
  echo "</div>";
  echo "</div>";
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
    if(empty($f['fotografia']['tmp_name'])){
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
    //confirmar
    if(isset($p['confirmar'])){
      $result['confirmar'] = true;
    }
  } else {
    //El formulario aún no ha sido enviado
    $result['enviado'] = false;
  }
  return $result;
}


//accion = Enviar
function showForm($params, $accion, $editable){
  if (isset($editable)){
    $disabled = 'readonly="readonly"';
    echo "<p>Disabled activado</p>";
  }else
    $disabled = '';
  ?>
  <form class="login_form" action="<?php $_SERVER['PHP_SELF']?>" enctype="multipart/form-data" method="post">
    <label for="nombre">Título de la receta:
      <input type="text" name="titulo" <?php echo $disabled ?>
      <?php if (isset($params['titulo'])) echo " value='".$params['titulo']."'";?>/>
      <?php if (isset($params['err_titulo'])) echo "<p class = 'error'>".$params['err_titulo']."</p>";?><br>
    </label>
    <label for="autor">Autor:
      <input type="text" name="autor" <?php echo $disabled ?>
      <?php if (isset($params['autor'])) echo " value='".$params['autor']."'";?>/>
      <?php if (isset($params['err_autor'])) echo "<p class = 'error'>".$params['err_autor']."</p>";?><br>
    </label>
    <label for="categoria">Categoría:
      <input type="text" name="categoria" <?php echo $disabled ?>
      <?php if (isset($params['categoria'])) echo " value='".$params['categoria']."'";?>/><br>
      <?php if (isset($params['err_categoria'])) echo "<p class = 'error'>".$params['err_categoria']."</p>";?>
    </label>
    <label for="descripcion">Descripción:
      <input type="text" name="descripcion" <?php echo $disabled ?>
      <?php if (isset($params['descripcion'])) echo " value='".$params['descripcion']."'";?>/><br>
      <?php if (isset($params['err_descripcion'])) echo "<p class = 'error'>".$params['err_descripcion']."</p>";?>
    </label>
    <label for="ingredientes">Ingredientes:
      <input type="text" name="ingredientes" <?php echo $disabled ?>
      <?php if (isset($params['ingredientes'])) echo " value='".$params['ingredientes']."'";?>/><br>
      <?php if (isset($params['err_ingredientes'])) echo "<p class = 'error'>".$params['err_ingredientes']."</p>";?>
    </label>
    <label for="preparacion">Preparación:
      <input type="text" name="preparacion" <?php echo $disabled ?>
      <?php if (isset($params['preparacion'])) echo " value='".$params['preparacion']."'";?>/><br>
      <?php if (isset($params['err_preparacion'])) echo "<p class = 'error'>".$params['err_preparacion']."</p>";?>
    </label>
    <label for="imagen">Selecciona una imagen:
      <input type="file" name="fotografia" <?php echo $disabled ?>
      <?php if (isset($params['fotografia'])){
        echo " value='".$params['fotografia']."'/><br>";
        echo "<img src='data:image/png;base64,".base64_encode($params['fotografia_temp'])."' /><br>";?>
      <?php }
      if (isset($params['err_fotografia'])) echo "<p class = 'error'>".$params['err_fotografia']."</p><br>";?>
    </label>
    <?php if (isset($params['id'])) echo "<input type='hidden' name='id' value='".$params['id']."'/>";?>
    <input type="submit" name = <?php echo $accion ?> value=<?php echo $accion ?> >
  </form>
<?php
}


function enviarFormulario($params){
  ?>
  <p>Muchas gracias, <?php echo $params['autor'] ?></p>
  <p>Tu receta <?php echo $params['titulo']?> ya está en nuestra base de datos
      y pronto podrás verla en la página web :)</p>
  <?php
}
?>
