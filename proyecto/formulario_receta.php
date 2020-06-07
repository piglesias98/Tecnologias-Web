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
    // -> categoría
    $result['categoria'] = $p['categoria'];
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
    $disabledPic = 'disabled="disabled"';
  }else{
    $disabled = '';
    $disabledPic = '';
  }
  ?>
  <form class="form" action="<?php $_SERVER['PHP_SELF']?>" enctype="multipart/form-data" method="post">
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
      <?php if (isset($params['categoria']) && in_array('carnes',$params['categoria']))
              echo ' checked';?>/>Carnes
      <input type="checkbox" name="categoria[]" value="verduras"
      <?php if (isset($params['categoria']) && in_array('verduras',$params['categoria']))
              echo ' checked';?>/>Verduras
      <input type="checkbox" name="categoria[]" value="pescado"
      <?php if (isset($params['categoria']) && in_array('pescado',$params['categoria']))
              echo ' checked';?>/>Pescado
      <input type="checkbox" name="categoria[]" value="arroz"
      <?php if (isset($params['categoria']) && in_array('arroz',$params['categoria']))
              echo ' checked';?>/>Arroz
      <input type="checkbox" name="categoria[]" value="sopa"
      <?php if (isset($params['categoria']) && in_array('sopa',$params['categoria']))
              echo ' checked';?>/>Sopa
      <br>
      Dificultad:
      <input type="checkbox" name="categoria[]" value="facil"
      <?php if (isset($params['categoria']) && array_key_exists('facil',$params['categoria']))
              echo ' checked';?>/>Fácil
      <input type="checkbox" name="categoria[]" value="dificil"
      <?php if (isset($params['categoria']) && array_key_exists('dificil',$params['categoria']))
              echo ' checked';?>/>Difícil
    </label><br>
    <?php if (isset($params['id'])) echo "<input type='hidden' name='id' value='".$params['id']."'/>";?>
    <input type="submit" name = <?php echo $accion ?> value=<?php echo $accion ?> >
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
      <img src="uploads/<?php echo $receta['fotografia_src'] ?>">
    </section>
    <section class="ingredientes">
      <p><?php echo $receta['ingredientes'] ?></p>
    </section>
    <section class="preparacion">
      <p><?php echo $receta['preparacion'] ?></p>
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
  if (isset($datos['bAscDesc'])){
    $bAsc = 'bAsc' === $datos['bAscDesc'] ? " checked ":'bAsc';
    $bDesc = 'bDesc' === $datos['bAscDesc'] ? " checked ":'bDesc';
  }else{
    $bAsc = '';
    $bDesc = '';
  }

  $accion =  basename($_SERVER['REQUEST_URI']);
  if (!strpos($accion, '?')) $accion = $_SERVER['SCRIPT_NAME'];

echo <<< HTML
  <form class="" action= $accion method="post">
    <label for="bTitulo">Título:
      <input type="text" name="bTitulo" $bTitulo>
    </label>
    <label for="bAsc">Ascendente
      <input type="radio" name="bAscDesc" value = "bAsc" $bAsc>
    </label>
    <label for="bDesc">Descendente
      <input type="radio" name="bAscDesc" value = "bDesc" $bDesc>
    </label>
    <input type="submit" name="accion" value="Buscar">
  </form>
HTML;

}

?>
