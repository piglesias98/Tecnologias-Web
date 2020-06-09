<?php
require_once('database/database.php');

function formEditable($titulo, $receta, $accion, $editable){
  echo "<div class='contenido'>";
  echo "<h3>".$titulo."</h3>";
  showFormReceta($receta, $accion, $editable);
  echo "</div>";
  echo "</div>";
}


function getParams($p, $f){
  if(isset($p['id'])){
    $result['id'] = $p['id'];
  }
  if(isset($p['accion'])){
    $result['accion']=$p['accion'];
  }
  //confirmar
  if(isset($p['confirmar'])){
    $result['confirmar'] = true;
  }
  if (isset($p['form'])){

    // FORMULARIO RECETA
    if ($p['form'] == 'receta'){
      $result['form'] = 'receta';
      // Validación de resultados
      // -> titulo
      $result['err_titulo'] = '';
      if (empty($p['titulo'])){
        $result['err_titulo'] = 'El título no puede estar vacío';
      }else{
        $result['titulo'] = $p['titulo'];
      }
      // -> categoría
      if (isset($p['categoria']) and !empty($p['categoria'])){
        $result['categoria'] = $p['categoria'] ;
      }

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


    // FORMULARIO FOTOS
    }else if($p['form']=='fotos'){
      $result['form'] = 'fotos';
      $result['err_fotografia']='';
      if (!isset($f['fotografia']) or empty($f['fotografia']['name'])){
        $result['err_fotografia']='No ha incluido ninguna fotografía';
      }else{
        echo "fotografia isset";
        $name = uniqid();
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($f['fotografia']['name']); ;
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
          $result['fotografia'] = $name;
          move_uploaded_file($f['fotografia']['tmp_name'], $target_dir.$name);
          // Upload to the database
          $db = dbConnection();
          dbInsertPicture($db, $result['id'], $name);
        }
      }
    }
  }else {
    //El formulario aún no ha sido enviado
    $result['form'] = 'nada';
  }
  return $result;
}


//accion = Enviar
function showFormReceta($params, $accion, $editable){
  if ($editable == false){
    $disabled = 'readonly="readonly"';
    $disabledPic = 'disabled="disabled"';
  }else{
    $disabled = '';
    $disabledPic = '';
  }
  if (isset($params['categoria'])){
    $categorias = $params['categoria'];
    if (is_string($params['categoria'])){
      #convert to array
      $categorias = explode(',',$params['categoria']);
    }
  }
  ?>
  <form class="login_form" action="<?php $_SERVER['PHP_SELF']?>" enctype="multipart/form-data" method="post">
    <label for="titulo">Título de la receta:
      <input type="text" name="titulo" <?php echo $disabled ?>
      <?php if (isset($params['titulo'])) echo " value='".$params['titulo']."'";?>><br>
      <?php if (isset($params['err_titulo'])) echo "<p class = 'error'>".$params['err_titulo']."</p>";?><br>
    </label>
    <label for="descripcion">Descripción:
      <textarea name="descripcion" <?php echo $disabled ?>>
      <?php if (isset($params['descripcion'])) echo $params['descripcion'];?>
      </textarea><br>
      <?php if (isset($params['err_descripcion'])) echo "<p class = 'error'>".$params['err_descripcion']."</p>";?>
    </label>
    <label for="ingredientes">Ingredientes:
      <textarea  name="ingredientes" <?php echo $disabled ?>>
      <?php if (isset($params['ingredientes'])) echo $params['ingredientes'];?>
      </textarea><br>
      <?php if (isset($params['err_ingredientes'])) echo "<p class = 'error'>".$params['err_ingredientes']."</p>";?>
    </label>
    <label for="preparacion">Preparación:
      <textarea name="preparacion" <?php echo $disabled ?>>
      <?php if (isset($params['preparacion'])) echo $params['preparacion'];?>
      </textarea><br>
      <?php if (isset($params['err_preparacion'])) echo "<p class = 'error'>".$params['err_preparacion']."</p>";?>
    </label>
    <label for="categorias">Categorías:<br>
      Tipo de comida:
      <input type="checkbox" name="categoria[]" value="carnes"
      <?php if (isset($params['categoria']) && in_array('carnes',$categorias))
              echo ' checked';?>/>Carnes
      <input type="checkbox" name="categoria[]" value="verduras"
      <?php if (isset($params['categoria']) && in_array('verduras',$categorias))
              echo ' checked';?>/>Verduras
      <input type="checkbox" name="categoria[]" value="pescado"
      <?php if (isset($params['categoria']) && in_array('pescado',$categorias))
              echo ' checked';?>/>Pescado
      <input type="checkbox" name="categoria[]" value="arroz"
      <?php if (isset($params['categoria']) && in_array('arroz',$categorias))
              echo ' checked';?>/>Arroz
      <input type="checkbox" name="categoria[]" value="sopa"
      <?php if (isset($params['categoria']) && in_array('sopa',$categorias))
              echo ' checked';?>/>Sopa
      <br>
      Dificultad:
      <input type="checkbox" name="categoria[]" value="facil"
      <?php if (isset($params['categoria']) && in_array('facil',$categorias))
              echo ' checked';?>/>Fácil
      <input type="checkbox" name="categoria[]" value="dificil"
      <?php if (isset($params['categoria']) && in_array('dificil',$categorias))
              echo ' checked';?>/>Difícil
    </label><br>
    <?php if (isset($params['id'])) echo "<input type='hidden' name='id' value='".$params['id']."'/>";?>
    <input type="hidden" name = 'form' value='receta'/>
    <input type="submit" name = 'accion' value='<?php echo $accion ?>' >
  </form>

<?php
}


function enviarFormulario($params){
  ?>
  <p>Muchas gracias, <?php echo $_SESSION['nombre'] ?></p>
  <p>Tu receta <?php echo $params['titulo']?> ya está en nuestra base de datos
      y pronto podrás verla en la página web :)</p>
  <?php
  $db = dbConnection();
  dbCrearReceta($db, $params);
  dbDisconnection($db);
}


function showReceta($receta, $id){
  setcookie('ultima_receta', strval($id),time()+ 9999999999);
  //Obtenemos el usuario porque necesitaremos el autor
  $autor = 'Anonimo';
  if (isset($receta['idautor'])){
    $db = dbConnection();
    $usuario = dbGetUsuario($db, $receta['idautor']);
    $autor = $usuario['nombre']." ".$usuario['apellidos'];
  }
  $receta['autor']=$autor;
  ?>
  <div class="contenido">
    <div class="superior">
      <div class="nombre_receta">
        <h1><?php echo $receta['titulo'] ?></h1>
        <img src="images/estrellas.png" alt="estrellas">
      </div>
      <div class="detalles">
        <p>Autor: <?php echo $receta['autor'] ?></p>
      </div>
    </div>
    <section class="descripcion">
      <div class="texto">
        <?php echo $receta['descripcion'] ?>
      </div>
      <!-- <img src="uploads/<?php echo $receta['fotografia_src'] ?>"> -->
    </section>
    <section class="ingredientes">
      <p><?php echo $receta['ingredientes'] ?></p>
    </section>
    <section class="preparacion">
      <p><?php echo $receta['preparacion'] ?></p>
    </section>
    <section class='pasos'>
      <?php
      $db = dbConnection();
      echo $id;
      $fotos = dbGetPictures($db, $id);
      ?>
      <?php if(is_array($fotos)){
        foreach ($fotos as $value) {
          echo "<img src='uploads/".$value['ubicacion']."'/><br>";
        }
      }
      ?>
    </section>
    <section class="navegacion_inferior">
      <?php
      if (isset($_SESSION['identificado'])){
        echo "<form action='index?p=crud' method='POST'>
              <input type='hidden' name='id' value='{$id}' />";
        echo "<input type='submit'  name = 'accion' value='Editar' />
              <input type='submit' name = 'accion' value='Borrar'/>";
      }
      ?>
    </section>
  </div>
  </div>
  <?php
}

function formBuscarReceta($titulo, $datos=false){
  $bTitulo = isset($datos['bTitulo']) ? " value='{$datos['bTitulo']}'":'bTitulo';
  $bCampo = isset($datos['bCampo']) ? " value='{$datos['bCampo']}'":'bCampo';
  if (isset($datos['bAscDesc'])){
    $bAsc = 'bAsc' === $datos['bAscDesc'] ? " checked ":'bAsc';
    $bDesc = 'bDesc' === $datos['bAscDesc'] ? " checked ":'bDesc';
  }else{
    $bAsc = '';
    $bDesc = '';
  }
  $accion =  basename($_SERVER['REQUEST_URI']);
  if (!strpos($accion, '?')) $accion = $_SERVER['SCRIPT_NAME'];

?>
  <form class="" action= <?php echo $accion;?> method="post">
    <label for="bTitulo">Buscar en título:
      <input type="text" name="bTitulo" $bTitulo>
    </label>
    <label for="bTitulo">Buscar en receta:
      <input type="text" name="bCampo" $bTitulo>
    </label>
    <label for="bAsc">Ascendente
      <input type="radio" name="bAscDesc" value = "bAsc" $bAsc>
    </label>
    <label for="bDesc">Descendente
      <input type="radio" name="bAscDesc" value = "bDesc" $bDesc>
    </label>
    <label for="categorias">Categorías:<br>
      Tipo de comida:
      <input type="checkbox" name="bcategoria[]" value="carnes"
      <?php if (isset($datos['bCategoria']) && in_array('carnes',$datos['bCategoria']))
              echo ' checked';?>/>Carnes
      <input type="checkbox" name="bCategoria[]" value="verduras"
      <?php if (isset($datos['bCategoria']) && in_array('verduras',$datos['bCategoria']))
              echo ' checked';?>/>Verduras
      <input type="checkbox" name="bCategoria[]" value="pescado"
      <?php if (isset($datos['bCategoria']) && in_array('pescado',$datos['bCategoria']))
              echo ' checked';?>/>Pescado
      <input type="checkbox" name="bCategoria[]" value="arroz"
      <?php if (isset($datos['bCategoria']) && in_array('arroz',$datos['bCategoria']))
              echo ' checked';?>/>Arroz
      <input type="checkbox" name="bCategoria[]" value="sopa"
      <?php if (isset($datos['bCategoria']) && in_array('sopa',$datos['bCategoria']))
              echo ' checked';?>/>Sopa
      <br>
      Dificultad:
      <input type="checkbox" name="bcategoria[]" value="facil"
      <?php if (isset($datos['bCategoria']) && array_key_exists('facil',$datos['bCategoria']))
              echo ' checked';?>/>Fácil
      <input type="checkbox" name="bcategoria[]" value="dificil"
      <?php if (isset($datos['bCategoria']) && array_key_exists('dificil',$datos['bCategoria']))
              echo ' checked';?>/>Difícil
    </label><br>
    <input type="submit" name="accion" value="Buscar">
  </form>

<?php
}


function formFotos($params, $accion){
  $id = $params['id'];
  $db = dbConnection();
  $fotos = dbGetPictures($db, $id);
  ?>
  <h3>Fotografías adjuntas</h3>
  <?php if(is_array($fotos)){
    echo print_r($fotos);
    foreach ((array) $fotos as $value) {
      echo "<img src='uploads/".$value['ubicacion']."'/><br>";
      echo "hola";
    }

  }
  ?>
  <form class="login_form" action="<?php $_SERVER['PHP_SELF']?>" enctype="multipart/form-data" method="post">
    <label for="fotografia">Añade una imagen:
      <input type="file" name="fotografia">
      <?php if (isset($params['err_fotografia'])) echo "<p class = 'error'>".$params['err_fotografia']."</p>";?>
    </label>
    <?php if (isset($params['id'])) echo "<input type='hidden' name='id' value='".$params['id']."'/>";?>
    <input type="hidden" name = 'form' value='fotos'/>
    <input type="hidden" name="accion" value='Editar'>
    <input type="submit" name = 'enviar' value= 'Añadir fotografía' >
  </form>
<?php
}

?>
