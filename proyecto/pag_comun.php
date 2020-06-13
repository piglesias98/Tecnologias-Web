<?php

function HTMLnav($activo){
echo <<<HTML
  <div class="menu">
  <nav>
HTML;

if (isset($_SESSION['admin'])){
  $items = ['index'=>'Inicio', 'listado'=>'Listado de recetas',
            'contacto'=> 'Página de contacto', 'crear'=>'Receta nueva',
            'perfil'=>'Mi perfil', 'mis_recetas' =>'Mis recetas',
            'gestion'=>'Gestión', 'log'=>'Log'];
}else if (isset($_SESSION['identificado'])){
  $items = ['index'=>'Inicio', 'listado'=>'Listado de recetas',
            'contacto'=> 'Página de contacto', 'crear'=>'Receta nueva',
            'perfil'=>'Mi perfil', 'mis_recetas' =>'Mis recetas'];
}else{
  $items = ['index'=>'Inicio', 'listado'=>'Listado de recetas',
            'contacto'=> 'Página de contacto', 'registro' => 'Regístrate'];
}

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
include 'count.php';
include 'valoradas.php';

echo <<<HTML
  </div>
HTML;
}

?>
