<div class="contenido">
<h3>Listado de recetas</h3>
<?php
require_once('database.php');
require_once('formulario_receta.php');


$results['accion'] = '';
// Argumentos POST
if (isset($_POST['accion'])){
  if (isset($_POST['bTitulo']) and $_POST['bTitulo']!='')
    $results['bTitulo'] = $_POST['bTitulo'];
  if (isset($_POST['bAscDesc']) and $_POST['bAscDesc']!='')
    $results['bAscDesc']= $_POST['bAscDesc'];
  if (isset($results) and count($results) > 0){
    $results['accion'] = 'Buscar';
  }
//Argumentos GET de la página
}else{
  $results = [];
  if (isset($_GET['bTitulo']))
    $results['bTitulo'] = $_GET['bTitulo'];
  if (isset($_GET['bAscDesc']))
    $results['bAscDesc']= $_GET['bTAscDesc'];
  if (count($results)>0)
    $results['accion'] = 'Buscar';
}

if (!is_string($db=dbConnection())){
  if (isset($results)){
    formBuscarReceta('Datos de la búsqueda', $results);
  }else
    formBuscarReceta('Datos de la búsqueda');
  if (isset($results['accion']) and $results['accion'] == 'Buscar'){
    echo implode(" ",$results);
    $busc = dbArray2SQL($results);
    $num_recetas = dbGetNumRecetas($db, $busc);
    if ($num_recetas>0){
      if (isset($results['bAscDesc'])){
        $orden =  strpos($results['bAscDesc'], 'Asc') !== false ? 'asc' : 'desc';
      }else {
        $orden = '';
      }
      $recetas = dbGetRecetas($db, $busc, $orden);
      if ($recetas!==false){
        ver_listado($recetas, 'index.php?p=crud');
      }else {
        echo "<p class='error'> Ha habido un error en la consulta a la BD</p>";
      }
    }else{
      echo "<p class = 'error'> No hay ninguna receta con ese título</p>";
    }
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
        <th>Receta</th>
      </tr>
HTML;

// se queja en void
foreach ((array) $datos as $v){
    echo '<tr>';
    echo "<td>{$v['titulo']}</td>";
    echo "<td><form action='$accion' method='POST'>
          <input type='hidden' name='id' value='{$v['id']}' />
          <input type='submit' name = 'accion' value='Mostrar'/>";
    if (isset($_SESSION['identificado'])){
      echo "<input type='submit'  name = 'accion' value='Editar' />
            <input type='submit' name = 'accion' value='Borrar'/>";
    }
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
