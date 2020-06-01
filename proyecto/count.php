<?php
require_once 'database.php';
$db = dbConnection();
$num_recetas = dbGetNumRecetas($db)
?>
<aside class="n_recetas">
  <h3>Nº de recetas</h3>
  <p>El sitio contiene <?php echo $num_recetas; ?> recetas diferentes</p>
</aside>
