<?php
require_once('database/database.php');

function formEditable($titulo, $receta, $accion, $editable){
  echo $accion;
  echo "<div class='contenido'>";
  echo "<h3>".$titulo."</h3>";
  showFormReceta($receta, $accion, $editable);
  echo "</div>";
  echo "</div>";
}


function getParams($p, $f){
  if(isset($p['id'])){
    $result['id'] = strip_tags($p['id']);
  }
  if(isset($p['accion'])){
    $result['accion']=strip_tags($p['accion']);
  }
  if (isset($p['form'])){

    // FORMULARIO RECETA
    if ($p['form'] == 'receta'){
      $result['form'] = strip_tags('receta');
      // Validación de resultados
      // -> titulo
      $result['err_titulo'] = '';
      if (empty($p['titulo'])){
        $result['err_titulo'] = 'El título no puede estar vacío';
      }else{
        $result['titulo'] = strip_tags($p['titulo']);
      }
      // -> categoría
      if (isset($p['categoria']) and !empty($p['categoria'])){
        $result['categoria'] = $p['categoria'] ;
      }

      $result['err_descripcion'] = '';
      if (empty($p['descripcion'])){
        $result['err_descripcion'] = 'La descripción no puede estar vacía';
      }else{
        $result['descripcion'] = strip_tags($p['descripcion']);
      }
      // -> ingredientes
      $result['err_ingredientes'] = '';
      if (empty($p['ingredientes'])){
        $result['err_ingredientes'] = 'Los ingredientes no pueden estar vacíos';
      }else{
        $result['ingredientes'] = strip_tags($p['ingredientes']);
      }
      // -> preparacion
      $result['err_preparacion'] = '';
      if (empty($p['preparacion'])){
        $result['err_preparacion'] = 'La preparación no puede estar vacía';
      }else{
        $result['preparacion'] = strip_tags($p['preparacion']);
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
      if (isset($p['id_foto'])){
        $result['id_foto'] = strip_tags($p['id_foto']);
      }


      // FORMULARIO COMENTARIO
    }else if($p['form']=='comentario'){
      $result['form']='comentario';
      $result['err_comentario']='';
      if (!isset($p['comentario']) or empty($p['comentario'])){
        $result['err_comentario']='No ha escrito ningún comentario';
      }else{
        $result['comentario']=strip_tags($p['comentario']);
        $db = dbConnection();
        dbInsertComment($db, $result['id'], $result['comentario']);
      }
      if (isset($p['id_comentario'])){
        $result['id_comentario'] = strip_tags($p['id_comentario']);
      }

    // FORMULARIO VALORACIÓN
  }else if($p['form']=='valoracion'){
    $result['form']='valoracion';
    $result['err_valoracion']='';
    if (!isset($p['valoracion']) or empty($p['valoracion'])){
      $result['err_valoracion']='Debe seleccionar una puntuación';
    }else{
      $result['valoracion']=strip_tags($p['valoracion']);
      echo "VALORACION";
      echo $result['valoracion'];
      $db = dbConnection();
      dbInsertValoracion($db, $result['id'], $result['valoracion']);
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
      <?php
      $db = dbConnection();
      // Vemos si la receta ya tiene categorías
      $comprobar = false;
      if (isset($params['id'])){
        $categorias_receta = dbGetCategorias($db, $params['id']);
        if ($categorias_receta != 0){
          $comprobar = true;
        }
      }
      $lista_categorias = dbGetListaCategorias($db);
      dbDisconnection($db);
      foreach ($lista_categorias as $categoria) {
        echo '<input type="checkbox" name="categoria[]"';
        echo "value='{$categoria['nombre']}'";
        if (isset($params['categoria']) && in_array($categoria['nombre'],$categorias)
            or $comprobar and in_array($categoria['nombre'],$categorias_receta))
               echo ' checked';
        echo ">{$categoria['nombre']}";
      }
      ?>
    </label>
      <br>
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
    <!-- // CATEGORÍAS  -->
    <section class='categorias'>
      <?php
      $db = dbConnection();
      $categorias = dbGetCategorias($db, $id);
      foreach ($categorias as $categoria) {
        echo "<p>{$categoria['nombre']}'</p>";
      }
      ?>
    </section>


    <!-- COMENTARIOS- -->
    <section class="comentarios">
      <?php
      $db = dbConnection();
      echo $id;
      $comentarios = dbGetComments($db, $id);
      ?>
      <?php if(is_array($comentarios)){
        foreach ($comentarios as $value) {
          $autor = 'Anonimo';
          if (isset($value['id_usuario'])){
            $db = dbConnection();
            $usuario = dbGetUsuario($db, $value['id_usuario']);
            $autor = $usuario['nombre']." ".$usuario['apellidos'];
          }
          echo "<p>".$value['fecha']."</p><br>";
          echo "<p>".$autor."</p><br>";
          echo "<p>".$value['comentario']."</p><br>";
          if (isset($_SESSION['admin']) and $_SESSION['admin']==true){
            echo "<form action='index.php?p=crud_recetas&id=$id' method='POST'>";
            echo "<input type='hidden' name='id_comentario' value='{$value['id']}'/>";
            echo "<input type='hidden' name = 'form' value='comentario'/>";
            echo "<input type='submit' name = 'accion' value='Borrar comentario'/>";
            echo "</form>";
          }
        }
      }
      ?>
      <h4>Añade un comentario</h4>
      <?php
      echo "<form action='index.php?p=crud_recetas&id=$id' method='POST'>";
      echo "<input type='hidden' name='id' value='{$id}' />";
        ?>
        <textarea name="comentario" rows="2" cols="70" placeholder="Mmm... qué rica!"></textarea>
        <input type="hidden" name="form" value="comentario">
        <input type="submit" name="accion" value="Comenta">
      </form>
    </section>

    <!-- // PUNTUACIÓN  -->
    <section>
      <?php
      $db = dbConnection();
      echo $id;
      $valoraciones = dbGetValoracion($db, $id);
      if ($valoraciones != 0){
        echo $valoraciones[0];
      }
      echo "<form action='index.php?p=crud_recetas&id=$id' method='POST'>";
      echo "<input type='hidden' name='id' value='{$id}' />";
      ?>
        <p class="clasificacion">
          <input id="radio1" type="radio" name="valoracion" value="5">
            <label for="radio1">★</label>
            <input id="radio2" type="radio" name="valoracion" value="4">
            <label for="radio2">★</label>
            <input id="radio3" type="radio" name="valoracion" value="3">
            <label for="radio3">★</label>
            <input id="radio4" type="radio" name="valoracion" value="2">
            <label for="radio4">★</label>
            <input id="radio5" type="radio" name="valoracion" value="1">
            <label for="radio5">★</label>
        </p>
        <input type="hidden" name="form" value="valoracion">
        <input type="submit" name="accion" value="Califica">
  </form>
    </section>


    <section class="navegacion_inferior">
      <?php
      if (isset($_SESSION['identificado'])){
        echo "<form action='index.php?p=crud_recetas&id=$id' method='POST'>
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
  if (isset($datos['bOrdenar'])){
    $bAlfabetico = 'bAlfabetico' === $datos['bOrdenar'] ? " checked ":'bAlfabetico';
    echo $bAlfabetico;
    $bComentadas = 'bComentadas' === $datos['bOrdenar'] ? " checked ":'bComentadas';
    $bPuntuacion = 'bPuntuacion' === $datos['bOrdenar'] ? " checked ":'bPuntuacion';
  }else{
    $bAlfabetico = '';
    $bComentadas = '';
    $bPuntuacion = '';
  }
  $accion =  basename($_SERVER['REQUEST_URI']);
  if (!strpos($accion, '?')) $accion = $_SERVER['SCRIPT_NAME'];

?>
  <form class="" action= <?php echo $accion;?> method="post">
    <label for="bTitulo">
      <p>Buscar en título:</p>
      <input type="text" name="bTitulo" <?php echo $bTitulo ?>>
    </label>
    <label for="bTitulo">
      <p>Buscar en receta:</p>
      <input type="text" name="bCampo" <?php echo $bCampo ?>>
    </label>
      <p>Ordenar por...</p>
    <label for="bAlfabetico">Orden alfabético
      <input type="radio" name="bOrdenar" value = "bAlfabetico" <?php echo $bAlfabetico ?>>
    </label>
    <label for="bComentadas">Más comentadas
      <input type="radio" name="bOrdenar" value = "bComentadas" <?php echo $bComentadas ?>>
    </label>
    <label for="bPuntuacion">Mayor puntuación
      <input type="radio" name="bOrdenar" value = "bPuntuacion" <?php echo $bPuntuacion ?>>
    </label>
    <label for="categorias">
      <p>Categorías:</p>
      <p>Tipo de comida:</p>
      <input type='checkbox' name='bCategoria[]' value='carnes'
      <?php if (isset($datos['bCategoria']) && in_array('carnes',$datos['bCategoria']))
              echo ' checked';?>> Carnes
      <input type="checkbox" name="bCategoria[]" value="verduras"
      <?php if (isset($datos['bCategoria']) && in_array('verduras',$datos['bCategoria']))
              echo ' checked';?>>Verduras
      <input type="checkbox" name="bCategoria[]" value="pescado"
      <?php if (isset($datos['bCategoria']) && in_array('pescado',$datos['bCategoria']))
              echo ' checked';?>>Pescado
      <input type="checkbox" name="bCategoria[]" value="arroz"
      <?php if (isset($datos['bCategoria']) && in_array('arroz',$datos['bCategoria']))
              echo ' checked';?>>Arroz
      <input type="checkbox" name="bCategoria[]" value="sopa"
      <?php if (isset($datos['bCategoria']) && in_array('sopa',$datos['bCategoria']))
              echo ' checked';?>>Sopa
      <p>Dificultad:</p>
      <input type="checkbox" name="bCategoria[]" value="facil"
      <?php if (isset($datos['bCategoria']) && in_array('facil',$datos['bCategoria']))
              echo ' checked';?>>Fácil
      <input type="checkbox" name="bCategoria[]" value="dificil"
      <?php if (isset($datos['bCategoria']) && in_array('dificil',$datos['bCategoria']))
              echo ' checked';?>>Difícil
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
      echo "<form action='index.php?p=crud_recetas&id=$id' method='POST'>";
      echo "<input type='hidden' name='id_foto' value='{$value['id']}'/>";
      echo "<input type='hidden' name = 'form' value='fotos'/>";
      echo "<input type='submit' name = 'accion' value='Borrar foto'/>";
      echo "</form>";
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
