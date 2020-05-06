<?php

function HTMLnav($activo){
echo <<<HTML
  <div class="menu">
  <nav>
HTML;

if (isset($_SESSION['identificado']))
  $items = ['index'=>'Inicio', 'listado'=>'Listado de recetas',
            'contacto'=> 'Página de contacto', 'crear'=>'Receta nueva'];
else
  $items = ['index'=>'Inicio', 'listado'=>'Listado de recetas','contacto'=> 'Página de contacto'];
foreach ($items as $key => $value)
  echo "<a".($key==$activo?" class='activo'":"").
  " href=index.php?p=".($key).">".$value."</a>";
echo <<< HTML
</nav>
</div>
HTML;
}

function HTMLwidgets(){
echo <<<HTML
  <div class="pagina">
    <div class="lateral">
HTML;
include 'login.php';
include 'fixed_widgets.html';

echo <<<HTML
  </div>
HTML;
}

?>
