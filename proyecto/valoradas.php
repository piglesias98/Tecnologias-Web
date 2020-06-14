<?php
require_once 'database/database.php';
require_once 'recetas/database_receta.php';
$db = dbConnection();
$valoradas = dbGetValoradas($db)
?>
<aside class="valoradas">
  <h3>Las mejor valoradas</h3>
  <ol>
    <?php
    foreach ((array) $valoradas as $v){
        echo "<li>";
        echo "<div class='agrupar_fil'>";
        echo "<p>".$v['titulo']."</p>";
        echo "<p>".$v['media']."</p>";
        echo "</div>";
        echo "</li>";
    }
    ?>
  </ol>
</aside>
