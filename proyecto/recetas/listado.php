<div class="contenido">
<h3>Listado de recetas</h3>
<?php
require_once('database/database.php');
require_once('recetas/formulario_receta.php');


$results['accion'] = '';
// Argumentos POST
if (isset($_POST['accion'])){
  if (isset($_POST['bTitulo']) and $_POST['bTitulo']!='')
    $results['bTitulo'] = $_POST['bTitulo'];
  if (isset($_POST['bCampo']) and $_POST['bCampo']!='')
    $results['bCampo'] = $_POST['bCampo'];
  if (isset($_POST['bCategoria']) and $_POST['bCategoria']!='')
    $results['bCategoria'] = $_POST['bCategoria'];
  if (isset($_POST['bOrdenar']) and $_POST['bOrdenar']!='')
    $results['bOrdenar']= $_POST['bOrdenar'];
  if (isset($results) and count($results) > 0){
    $results['accion'] = 'Buscar';
  }
}

if (isset($_GET['p']) and $_GET['p'] == 'mis_recetas'){
  $results['autor_id']=$_SESSION['id'];
  echo $results['autor_id'];
}

if (isset($_GET['id'])){
  $results['id']=$_GET['id'];
  echo $results['id'];
}


if (!is_string($db=dbConnection())){
  $busc = '';
  $orden = '';
  formBuscarReceta('Datos de la búsqueda', $results);
  $busc = dbArray2SQL($results);
  if(isset($results['bOrdenar'])){
    $orden = $results['bOrdenar'];
  }
  $num_recetas = dbGetNumRecetas($db, $busc);
  if ($num_recetas>0){
    $recetas = dbGetRecetas($db, $busc, $orden);
    if ($recetas!==false){
      ver_listado($recetas, 'index.php?p=crud_usuarios');
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
        <th>Receta</th>
      </tr>
HTML;

// se queja en void
foreach ((array) $datos as $v){
    echo '<tr>';
    echo "<td>{$v['titulo']}</td>";
    echo "<td><form action='".$accion.'&id='.$v['id']."' method='POST'>
          <input type='submit' name = 'accion' value='Mostrar'/>";
    if (isset($_SESSION['identificado']) and
        (isset($_SESSION['admin']) or $v['idautor']==$_SESSION['id'])){
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
