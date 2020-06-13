<div class="contenido">
<h3>Log de la aplicación</h3>
<?php
require_once('database/database.php');

if (isset($_SESSION['admin'])){
  if (!is_string($db=dbConnection())){
    $num_log = dbGetNumLog($db);
    if ($num_log>0){
      $log = dbGetLogs($db);
      if ($log!==false){
        ver_listado($log);
      }else {
        echo "<p class='error'> Ha habido un error en la consulta a la BD</p>";
      }
    }else{
      echo "<p class='error'> No hay ningún log almacenado en el sistema</p>";
    }
    dbDisconnection($db);
  }else{
    echo "<p> Error en la conexión con la db<p>";
  }
}else{
  echo "<p class='error'> Debe ser administrador para visualizar esta página</p>";
}


function ver_listado($datos){
echo <<<HTML
  <div class="listado">
    <table>
      <tr>
        <th>Fecha</th>
        <th>Descripción</th>
      </tr>
HTML;

// se queja en void
foreach ((array) $datos as $v){
    echo '<tr>';
    echo "<td>{$v['fecha']}</td>";
    echo "<td>{$v['descripcion']}</td>";
}
echo <<<HTML
</table>
</div>
HTML;
}
?>
</div>
</div>
