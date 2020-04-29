<?php
function HTMLnav($activo, $identificado){
  echo <<< HTML
  <nav>
    <h1>Índice</h1>
    <ul>
    HTML;
    if $identificado{
      $items = ['Inicio', 'Listado de recetas', 'Añadir receta nueva','Página de contacto'];
      $links = ['inicio', 'listado', 'receta_nueva', 'contacto'];
    }else{
      $items = ['Inicio', 'Listado de recetas', 'Página de contacto'];
      $links = ['inicio', 'listado', 'contacto.'];
    }
    foreach ($items as $key => $value)
      echo "<li".($key==$activo?" class='activo'":"").">".
      "<a href=index.php?p=".$links[$k].">".$v."</a></li>";
    echo <<< HTML
  </ul>
</nav>
HTML;
}
?>
