<?php
function HTMLnav($activo, $identificado){
  echo <<< HTML
  <nav> <h1>Índice</h1> <ul>
    HTML;
    if $identificado{
      $items = ['Inicio', 'Listado de recetas', 'Añadir receta nueva','Página de contacto'];
      $links = ['inicio.php', 'listado.php', 'receta_nueva.php', 'contacto.php'];
    }else{
      $items = ['Inicio', 'Listado de recetas', 'Página de contacto'];
      $links = ['inicio.php', 'listado.php', 'contacto.php'];
    }
    foreach ($items as $key => $value)
      echo "<li".($k==$activo?" class='activo'":"")."<a href=".$links[$k].">".$v."</a></li>";
    echo <<< HTML
  </ul></nav>
  HTML;
}
?>
