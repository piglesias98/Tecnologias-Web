<div class="contenido">
<h3>Listado de recetas</h3>
<?php
require('database.php');
if (!is_String($db = dbConnection())){
  $recetas = dbGetRecetas($db);
  if ($recetas == false)
    echo "<p>es false</p>";
  ver_listado($recetas, 'crud.php');
  dbDisconnection($db);
}else{
  echo "<p class='error'>No se ha podido conectar con la base de datos</p>";
}


function ver_listado($datos, $accion){
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
