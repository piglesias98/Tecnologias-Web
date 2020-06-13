<div class="contenido">
<h3>Gestión</h3>
<?php
require_once('database/database.php');
require_once('usuarios/formulario_usuario.php');
require('database/dbbackup.php');


// ".$_SERVER['REQUEST_URI'],"
echo "<h4>Gestión de la base de datos</h4>";
// Copia de seguridad
echo "<a href='backup.php?download'>Pulse aquí</a> para
descargar un fichero con los datos de la <strong>copia de seguidad</strong>";


if(isset($_POST['accion']) and $_POST['accion']=='Restaurar'){
  // Comprobar se ha subido algún fichero
  if (sizeof($_FILES)==0 or array_key_exists('bbdd', $_FILES)){
    $result['err_bbdd']='No se ha podido subir el fichero';
  }else if (!is_uploaded_file($_FILES['bbdd']['tmp_name'])){
    $result['err_bbdd']='Fichero no subido, código de error: '.$_FILES['bbdd']['error'];
  }else if (strtolower(pathinfo($_FILES['bbdd']['name'],PATHINFO_EXTENSION)) != 'sql'){
    $result['err_bbdd'] = 'El archivo debe tener la extensión .sql';
  }else{
    $db = dbConnection();
    $error = dbRestore($db, $_FILES['bbdd']['tmp_name']);
    dbDisconnection();
  }
  if (isset($error)){
    echo "<p class='error'>".$error."</p>";
  }
}else{
  //formulario restaurarrrrrrrrrrr
  // Restaurar BD
  echo "<form action=".$_SERVER['PHP_SELF']." enctype='multipart/form-data' method='post'>";
  echo "Adjunta el fichero sql<input type='file' name='bbdd'>";
  echo "<input type='submit' name = 'accion' value= 'Restaurar' >";
  echo "</form>";
}




echo "<h4>Gestión de los usuarios</h4>";
echo "<h5>Crear un usuario</h5>";
echo "<form action='index.php?p=crud_usuarios' method='POST'>";
echo "<input type='submit' name = 'accion' value='Registro'/>";

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
