<?php
ini_set("display_errors", "1");
ini_set("error_reporting", E_ALL);
require "pag_comun.php";
include "header.html";

// Obtener el query string

if ((!isset($_GET["p"])) || ($_GET["p"]<0 || $_GET["p"]>3)){
  $_GET['p'] = 0;
}
$opc = $_GET['p'];

$identificado = False;

//Según el query string resaltamos el menú
HTMLnav($opc);

//Identificación de los usuarios

HTMLwidgets($identificado);

//Según el query string insertamos el código HTML correspondiente
if ($identificado){
  switch($opc) {
    case 0: include "inicio.html"; break;
    case 1: include "listado.html"; break;
    case 2: include "contacto.html"; break;
    case 3: include "pag_nueva.html"; break;
  }
}else{
  switch($opc) {
    case 0: include "inicio.html"; break;
    case 1: include "listado.html"; break;
    case 2: include "contacto.html"; break;
  }
}

include "footer.html";
 ?>
