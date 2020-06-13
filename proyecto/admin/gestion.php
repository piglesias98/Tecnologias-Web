<div class="contenido">
<h3>Gestión</h3>
<?php
require_once('database/database.php');
require_once('usuarios/formulario_usuario.php');
require('database/dbbackup.php');


// ".$_SERVER['REQUEST_URI'],"
echo "<h4>Gestión de la base de datos</h4>";
echo "<a href='backup.php?download'>Pulse aquí</a> para
descargar un fichero con los datos de la copia de seguidad";


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
