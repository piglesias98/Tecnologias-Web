<div class="contenido">
<h3>Listado de recetas</h3>
<?php
require_once('database.php');
require_once('htmlForms.php');


$accion = '';
// Argumentos POST
if (isset($_POST['accion'])){
  if (isset($_POST['bTitulo']) and $_POST['bTitulo']!='')
    $results['bTitulo'] = $_POST['bTitulo'];
  if (isset($_POST['bAscDesc']) and $_POST['bAscDesc']!='')
    $results['bAscDesc']= $_POST['bAscDesc'];
  if (isset($results) and count($results) > 0){
    $accion = 'Buscar';
  }
//Argumentos GET de la página
}else{
  $results = [];
  if (isset($_GET['bTitulo']))
    $results['bTitulo'] = $_GET['bTitulo'];
  if (isset($_GET['bAscDesc']))
    $results['bAscDesc']= $_GET['bTAscDesc'];
  if (count($results)>0)
    $accion = 'Buscar';
}

if (!is_string($db=dbConnection())){
  if (isset($results))
    formBuscarReceta('Datos de la búsqueda', $results);
  else
    formBuscarReceta('Datos de la búsqueda');
  if ($accion == 'Buscar'){
    $busc = dbArray2SQL($results);
    echo "BUSC".$busc;
    $num_recetas = dbGetNumRecetas($db, $busc);
    if ($num_recetas>0){
      $recetas = dbGetRecetas($db, $busc);
      if ($recetas!==false){
        echo "<p> Ver listado</p>";
        ver_listado($recetas, 'index?p=crud');
      }else {
        echo "<p> Ha habido un error en la consulta a la BD</p>";
      }
    }else{
      echo "<p> No hay ninguna receta con ese título</p>";
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
