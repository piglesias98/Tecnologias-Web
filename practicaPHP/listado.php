<div class="contenido">
<p>Holaa soy listado</p>
<?php
require('database.php');
if (!is_String($db = dbConnection())){
  $recetas = dbGetRecetas($db);
  ver_listado($ciudades);
  dbDisconnection($db);
}else{
  echo "<p class='error'>No se ha podido conectar con la base de datos</p>";
}


function ver_listado($datos){
  ?>
  <div class="listado">
    <table>
      <tr>
        <th>Receta</th>
      </tr>
      <?php foreach ($datos as $v): ?>
        <tr>
          <td><?php $v['titulo'] ?></td>;
          <td><a href = 'crud.php?show=<?php echo $v['id']; ?>'><img src="images/show.png"></a></td>
          <?php if (isset($_SESSION['identificado'])){
                  echo "<td><a href = 'crud.php?edit="; echo $v['id'];"'><img src='images/edit.png'></a></td>";
                  echo "<td><a href = 'crud.php?del="; echo $v['id'];"'><img src='images/show.png'></a></td>";}?>
        </tr>
      <?php endforeach; ?>
    </table>
  </div>
  <?php
}
?>
</div>
</div>
