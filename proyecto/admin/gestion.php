<div class="contenido_gestion">
<h3>Gestión</h3>
<?php
require_once('database/database.php');
require_once('usuarios/formulario_usuario.php');
require('database/dbbackup.php');


// ".$_SERVER['REQUEST_URI'],"
echo "<h4>Gestión de la base de datos</h4>";
// Copia de seguridad
echo "<div class='agrupar_col'>";
echo "<h5>Copia de seguridad</h5>";
echo "<p>";
echo "<a href='backup.php?download'>Pulse aquí</a> para
descargar un fichero con los datos de la <strong>copia de seguidad</strong>";
echo "</p>";
echo "</div>";
echo "<div class='agrupar_fil'>";
echo "<div class='agrupar_col'>";
echo "<h5>Restauración de la base de datos</h5>";

if(isset($_POST['accion']) and $_POST['accion']=='Restaurar'){
  // Comprobar se ha subido algún fichero
  if (sizeof($_FILES)==0 or !array_key_exists('bbdd', $_FILES)){
    $error ='No se ha podido subir el fichero';
  }else if (!is_uploaded_file($_FILES['bbdd']['tmp_name'])){
    $error ='Fichero no subido, código de error: '.$_FILES['bbdd']['error'];
  }else if (strtolower(pathinfo($_FILES['bbdd']['name'],PATHINFO_EXTENSION)) != 'sql'){
    $error = 'El archivo debe tener la extensión .sql';
  }else{
    $db = dbConnection();
    $error = dbRestore($db, $_FILES['bbdd']['tmp_name']);
    dbDisconnection($db);
  }
  if (isset($error)){
    echo "<p class='error'>".$error."</p>";
  }else{
    echo "<p>Base de datos restaurada correctamente</p>";
  }
}
//formulario RESTAURAR
// Restaurar BD
echo "<form class='agrupar_col' action=".$_SERVER['REQUEST_URI']." enctype='multipart/form-data' method='post'>";
echo "Adjunta el fichero sql";
echo "<input type='file' name='bbdd'>";
echo "<input type='submit' name = 'accion' value= 'Restaurar' >";
echo "</form>";
echo "</div>";

echo "<div class='agrupar_col'>";
echo "<h5>Borrado completo de la base de datos</h5>";

if(isset($_POST['accion']) and $_POST['accion']=='Borrado completo'){
  $db = dbConnection();
  $error = dbBorradoCompleto($db);
  dbDisconnection($db);
  if (isset($error)){
    echo "<p class='error'>".$error."</p>";
  }else{
    echo "<p>Base de datos borrada correctamente</p>";
  }
}
//formulario BORRAR
// Borrar BD
echo "<form class='agrupar_col' action=".$_SERVER['REQUEST_URI']." method='post'>";
echo "<input type='submit' name = 'accion' value= 'Borrado completo' >";
echo "</form>";
echo "</div>";
echo "</div>";


echo "<h4>Gestión de categorías</h4>";

if(isset($_POST['form']) and $_POST['form']=='categoria'){
  if (isset($_POST['accion'])){
    switch ($_POST['accion']) {
      case 'Editar':
        if (!isset($_POST['categoria']) or empty($_POST['categoria'])){
          $error_categoria = "No ha editado la categoria";
        }else{
          $db = dbConnection();
          dbEditarCategoria($db, $_POST['id'], strip_tags($_POST['categoria']));
          dbDisconnection($db);
        }
      break;
      case 'Borrar':
        $db = dbConnection();
        dbBorrarCategoria($db, $_POST['id']);
        dbDisconnection($db);
      break;
      case 'Añadir categoría':
        if (!isset($_POST['nueva_categoria']) or empty($_POST['nueva_categoria'])){
          $error_categoria = "No ha escrito ninguna categoria";
        }else{
          $db = dbConnection();
          dbInsertCategoria($db, strip_tags($_POST['nueva_categoria']));
          dbDisconnection($db);
        }
      break;
    }
  }

}
$db = dbConnection();
$lista_categorias = dbGetListaCategorias($db);
if(isset($error_categoria))
  echo "<p class='error'>".$error_categoria."</p>";
echo "<form class='agrupar_col' action=".$_SERVER['REQUEST_URI']." method='post'>";
echo "<input type='hidden' name='form' value='categoria'>";
foreach ($lista_categorias as $categoria) {
  echo '<tr>';
  echo "<div class='agrupar_fil'>";
  echo "<td><input type='text' name='categoria' value='{$categoria['nombre']}'></td>";
  echo "<td><input type='hidden' name='id' value='{$categoria['id']}'></td>";
  echo "<input type='submit'  name = 'accion' value='Editar' >
        <input type='submit' name = 'accion' value='Borrar'>";
  echo "</div>";
}
echo "<div class='agrupar_fil'>";
echo "<input type='text' name='nueva_categoria'>";
echo "<input type='submit' name='accion' value='Añadir categoría'>";
echo "</div>";
echo "</form></td>";
echo "</tr>";


echo "<h4>Gestión de los usuarios</h4>";
echo "<div class='agrupar_fil'>";
echo "<h5>Crear un usuario</h5>";
echo "<form action='index.php?p=crud_usuarios' method='POST'>";
echo "<input type='submit' name = 'accion' value='Registro'/>";
echo "</div>";

if (!is_string($db=dbConnection())){
  $busc='';
  $orden='';
  $num_usuarios = dbGetNumUsuarios($db, $busc);
  if ($num_usuarios>0){
    $usuarios = dbGetUsuarios($db, $busc, $orden);
    if ($usuarios!==false){
      ver_listado($usuarios, 'index.php?p=crud_usuarios');
    }else {
      echo "<p class='error'> Ha habido un error en la consulta a la BD</p>";
    }
  }else{
    echo "<p class='error'> No hay ninguna receta que cumpla estas condiciones</p>";
  }
  dbDisconnection($db);
}else{
  echo "<p> Error en la conexión con la db<p>";
}



function ver_listado($datos, $accion){
echo <<<HTML
    <h5>Listado de usuarios</h5>
    <table>
      <tr>
        <th>Nombre</th>
        <th>Apellidos</th>
      </tr>
HTML;

// se queja en void
foreach ((array) $datos as $v){
    echo '<tr>';
    echo "<td>{$v['nombre']}</td>";
    echo "<td>{$v['apellidos']}</td>";
    echo "<td><form action='".$accion.'&id='.$v['id']."' method='POST'>
          <input type='submit' name = 'accion' value='Mostrar'/>";
      echo "<input type='submit'  name = 'accion' value='Editar' />
            <input type='submit' name = 'accion' value='Borrar'/>";
    echo "</form></td>";
    echo "</tr>";
}
echo <<<HTML
</table>
HTML;
}
?>
</div>
</div>
