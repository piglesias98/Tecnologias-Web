<?php
require_once('database.php');

function formEditable($titulo, $receta, $accion, $editable){
  echo "<div class='contenido'>";
  echo "<h3>".$titulo."</h3>";
  showFormReceta($receta, $accion, $editable);
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
    if(empty($f['fotografia']['name'])){
      $result['err_fotografia'] = 'Debe incluir una fotografía';
    }else{
      $name = $f['fotografia']['name'];
      $target_dir = "uploads/";
      $target_file = $target_dir . basename($f['fotografia']['name']);
      // Tipo del archivo
      $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
      $validExtensions = array('jpg', 'jpeg', 'png', 'gif');
      // Comprobar que se trata de verdad de una imagen
      if (!getimagesize($f['fotografia']['tmp_name'])){
        $result['err_fotografia'] = "El archivo no es una fotografía";
      // Comprobar el tamaño del archivo
      }else if ($f['fotografia']["size"] > 500000){
        $result['err_fotografia'] = 'Lo siento, el archivo es demasiado grande';
      // Sólo permitimos JPG, JPEG y PNG
      }else if (!in_array($imageFileType, $validExtensions)){
        $result['err_fotografia'] = 'Lo siento, solo se permiten archivos PNG, JPG o JPEG';
      }else{
        // Guardamos la imagen
        $result['fotografia'] = $f['fotografia']['tmp_name'];
        $result['fotografia_src'] = $name;
        move_uploaded_file($f['fotografia']['tmp_name'], $target_dir.$name);
      }
    }
    // Si ya hemos subido la imagen
    if (isset($p['fotografia_src'])){
      $result['fotografia_src'] = $p['fotografia_src'];
    }
    //confirmar
    if(isset($p['confirmar'])){
      $result['confirmar'] = true;
    }
 }else {
    //El formulario aún no ha sido enviado
    $result['enviado'] = false;
  }
  return $result;
}


//accion = Enviar
function showFormReceta($params, $accion, $editable){
  if ($editable == false){
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
        echo "<input type='hidden' name='fotografia_src' value='".$params['fotografia_src']."'/>";
        echo "<img src='uploads/".$params['fotografia_src']."' /><br>";?>
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
  $db = dbConnection();
  dbCrearReceta($db, $params);
  dbDisconnection($db);
}


function showReceta($receta){
  ?>
  <div class="contenido">
    <div class="superior">
      <div class="nombre_receta">
        <h1><?php echo $receta['titulo'] ?></h1>
        <img src="images/estrellas.png" alt="estrellas">
      </div>
      <div class="detalles">
        <p>Autor: <?php echo $receta['autor'] ?> El cocinillas</p>
      </div>
    </div>
    <section class="descripcion">
      <div class="texto">
        <?php echo $receta['descripcion'] ?>
      </div>
      <img src="uploads/<?php echo $receta['fotografia_src'] ?>">
    </section>
    <section class="ingredientes">
        <?php echo $receta['ingredientes'] ?>
    </section>
    <section class="preparacion">
      <?php echo $receta['preparacion'] ?>
    </section>
    <section class="navegacion_inferior">
      <img src="images/edit.png">
      <img src="images/comment.png">
      <img src="images/x.jpg">
    </section>
  </div>
  </div>
  <?php
}

?>
