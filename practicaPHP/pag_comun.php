<?php
function HTMLnav($activo){
echo <<<HTML
  <nav>
HTML;

$items = ['Inicio', 'Listado de recetas', 'Página de contacto'];
foreach ($items as $key => $value)
  echo "<a".($key==$activo?" class='activo'":"").
  " href=index.php?p=".($key).">".$value."</a>";
echo <<< HTML
</nav>
HTML;
}
?>
