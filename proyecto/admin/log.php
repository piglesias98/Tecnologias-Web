<div class="contenido_formulario">
<h3>Log de la aplicación</h3>
<?php

/*
En log.php se encuentra el log del sistema ordenado de manera cronológica inversa
*/

require_once('database/database.php');

//Paginación de resultados
if(!isset($_GET['items'])){
  $num_items = 10; //Valor por defecto
}else if (!is_numeric($_GET['items']) or $_GET['items']<1){
  $num_items = 0;
}else{
  $num_items = $_GET['items'];
}

if ($num_items==0){
  $primero = 0;//VER TODOS LOS ITEMS
}else{
  $primero = isset($_GET['primero']) ? $_GET['primero'] : 0;
  if (!is_numeric($primero) or $primero<0){
    $primero = 0;
  }
}


if (isset($_SESSION['admin'])){
  if (!is_string($db=dbConnection())){
    $num_log = dbGetNumLog($db);
    if ($num_log>0){
      $log = dbGetLogs($db, $primero, $num_items);
      if ($log!==false){
        ver_listado($log);
        paginacion($num_log, $primero, $num_items);
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

function paginacion($num_log, $primero, $num_items){
  if ($num_items>0){
    $ultima = $num_log - ($num_log%$num_items);
    $anterior = $num_items>$primero ? 0 : ($primero-$num_items);
    $siguiente = ($primero + $num_items)>$num_log? $ultima : ($primero + $num_items);
    navegador('paginador',[
      ['texto'=>'Primera', 'url'=>$_SERVER['REQUEST_URI'].'&primero=0&items='. $num_items],
      ['texto'=>'Anterior', 'url'=>$_SERVER['REQUEST_URI'].'&primero='. $anterior.'&items='. $num_items],
      ['texto'=>'Siguiente', 'url'=>$_SERVER['REQUEST_URI'].'&primero='. $siguiente.'&items='. $num_items],
      ['texto'=>'Última', 'url'=>$_SERVER['REQUEST_URI'].'&primero='. $ultima.'&items='. $num_items]]);
  }
}

function navegador($clase,$menu,$activo='') {
echo "<nav class='$clase'>";
foreach ($menu as $elem)
echo "<a ".($activo==$elem['texto']?"class='activo' ":'').
"href='{$elem['url']}'>{$elem['texto']}</a>";
echo '</nav>';
}


?>
</div>
</div>
