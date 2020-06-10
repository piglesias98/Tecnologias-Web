<div class="contenido">
<h3>Listado de usuarios</h3>
<?php
require_once('database/database.php');
require_once('usuarios/formulario_usuario.php');


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
  <div class="listado">
    <table>
      <tr>
        <th>Usuario</th>
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
</div>
HTML;
}
?>
</div>
</div>
