<?php
function HTMLinicio($titulo){
  echo <<< HTML
  <!DOCTYPE html>
  <html lang="en" dir="ltr">
    <head>
      <meta charset="utf-8">
      <meta name="author" content="Paula Iglesias Ahualli">
      <meta name="viewport" content="width=device-width">
      <link rel="stylesheet" href="style.css">
      <title>$titulo</title>
    </head>
    <body>
  HTML;
}

function HTMLfin(){
  echo <<< HTML
</body>
</html>
HTML;
}

function HTMLfooter(){
  echo <<< HTML
  <footer>
    <p>  © 2020 Tecnologías Web</p>
    <p class="divisor">|</p>
    <p>Mapa del sitio</p>
    <p class="divisor">|</p>
    <p>Contacto</p>
  </footer>
  HTML;
}

function HTMLnav($activo, $identificado){
  echo <<< HTML
  <nav>
    <h1>Índice</h1>
    <ul>
    HTML;
    if $identificado{
      $items = ['Inicio', 'Listado de recetas', 'Añadir receta nueva','Página de contacto'];
      // $links = ['inicio', 'listado', 'receta_nueva', 'contacto'];
    }else{
      $items = ['Inicio', 'Listado de recetas', 'Página de contacto'];
      // $links = ['inicio', 'listado', 'contacto.'];
    }
    foreach ($items as $key => $value)
      echo "<li".($key==$activo?" class='activo'":"").">".
      "<a href=index.php?p=".($k).">".$value."</a></li>";
    echo <<< HTML
  </ul>
</nav>
HTML;
}
?>
