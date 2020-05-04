<div class="contenido">
<h3>Listado de recetas</h3>
<?php
require('database.php');
if (!is_String($db = dbConnection())){
  $recetas = dbGetRecetas($db);
  if ($recetas == false)
    echo "<p>es false</p>";
  ver_listado($recetas);
  dbDisconnection($db);
}else{
  echo "<p class='error'>No se ha podido conectar con la base de datos</p>";
}


function ver_listado($datos){
echo <<<HTML
  <div class="listado">
    <table>
      <tr>
        <th>Receta</th>
      </tr>
HTML;

foreach ($datos as $v){
    echo '<tr>';
    echo "<td>{$v['titulo']}</td>";
    echo "<td><a href = 'crud.php?show={$v['id']}'><img class = 'boton' style ='width:15px;' src='images/show.png'></a></td>";
    if (isset($_SESSION['identificado'])){
      echo "<td><form action='action.php' method='POST'>
              <input type='hidden' name='id' value='{$v['id']}' />
              <input type='image' src = 'images/edit.png' width=15px name='editar' value='Editar'/>
              <input type='image' src = 'images/delete.png' width=15px name='borrar' value='Borrar'/>
            </form></td>";    }
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
