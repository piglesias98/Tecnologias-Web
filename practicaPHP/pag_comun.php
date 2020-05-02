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

function HTMLwidgets($identificado){
echo <<<HTML
  <div class="pagina">
    <div class="lateral">
HTML;
if($identificado){
  include 'login.html';
}
include 'fixed_widgets.html';

echo <<<HTML
  </div>
HTML;
}

?>
